<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Defines a Q&A activity.
 *
 * @package    mod_qa
 * @copyright  2015 Skylar Kelty <S.Kelty@kent.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_qa;

defined('MOODLE_INTERNAL') || die();

/**
 * Abstracts us from the DB a little.
 *
 * @package    mod_qa
 * @copyright  2015 Skylar Kelty <S.Kelty@kent.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qa
{
    private $data;
    private $questions;

    /**
     * Private constructor.
     */
    private function __construct($data) {
        $this->data = $data;
    }

    /**
     * Public instancer.
     */
    public static function from_db($data) {
        return new static($data);
    }

    /**
     * Returns a QA object from an ID.
     */
    public static function from_id($id) {
        global $DB;

        $data = $DB->get_record('qa', array(
            'id' => $id
        ), '*', \MUST_EXIST);

        return new static($data);
    }

    /**
     * Returns questions.
     */
    public function get_questions() {
        global $DB;

        if (!isset($this->questions)) {
            $questions = $DB->get_records('qa_questions', array(
                'qaid' => $this->data->id
            ));

            $this->questions = array();
            foreach ($questions as $question) {
                $this->questions[$question->id] = question::from_db($question);
            }
        }

        return $this->questions;
    }

    /**
     * Post a new question.
     */
    public function post_question($title, $contents, $anonymous = 0) {
        global $DB, $USER, $PAGE;

        $question = new \stdClass();
        $question->qaid = $this->data->id;
        $question->userid = $USER->id;
        $question->anonymous = $anonymous;
        $question->title = $title;
        $question->contents = $contents;
        $question->timecreated = time();
        $question->timemodified = time();

        $id = $DB->insert_record('qa_questions', $question);
        $question->id = $id;

        $event = \mod_qa\event\question_posted::create(array(
            'objectid' => $question->id,
            'context' => $PAGE->context,
            'userid' => $anonymous ? 0 : $USER->id
        ));
        $event->add_record_snapshot('qa_questions', $question);
        $event->trigger();

        return question::from_db($question);
    }
}