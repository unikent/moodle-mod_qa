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
class reply
{
    use traits\protecteddata;
    use traits\user;

    private $question;

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
     * Returns a QA question object from an ID.
     */
    public static function from_id($id) {
        global $DB;

        $data = $DB->get_record('qa_replies', array(
            'id' => $id
        ), '*', \MUST_EXIST);

        return new static($data);
    }

    /**
     * Return a view link.
     */
    public function get_view_link() {
        return new \moodle_url('/mod/qa/question.php', array(
            'id' => $this->qaqid
        ));
    }

    /**
     * Returns the question.
     */
    public function get_question() {
        if (!isset($this->question)) {
            $this->question = question::from_id($this->qaqid);
        }

        return $this->question;
    }

    /**
     * Is this an anonymously posted question?
     */
    public function is_anonymous() {
        $question = $this->get_question();
        return $this->userid == $question->userid;
    }
}