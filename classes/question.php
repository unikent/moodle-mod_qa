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
class question
{
    use traits\protecteddata;
    use traits\user;

    private $cmid;
    private $context;
    public $qa;
    public $votes;
    public $replies;

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

        $data = $DB->get_record('qa_questions', array(
            'id' => $id
        ), '*', \MUST_EXIST);

        return new static($data);
    }

    /**
     * Returns our parent qa object.
     */
    public function get_qa() {
        if (!isset($this->qa)) {
            $this->qa = qa::from_id($this->qaid);
        }

        return $this->qa;
    }

    /**
     * Returns our CMID.
     */
    public function get_cmid() {
        if (!isset($this->cmid)) {
            list($course, $cm) = get_course_and_cm_from_instance($this->id, 'qa');
            $this->cmid = $cm->id;
        }

        return $this->cmid;
    }

    /**
     * Returns our context.
     */
    public function get_context() {
        if (!isset($this->context)) {
            $cmid = $this->get_cmid();
            $this->context = \context_module::instance($cmid);
        }

        return $this->context;
    }

    /**
     * Returns all votes.
     */
    public function get_votes() {
        global $DB;

        if (!isset($this->votes)) {
            $this->votes = $DB->get_records('qa_votes', array(
                'qaqid' => $this->id
            ));
        }

        return $this->votes;
    }

    /**
     * Returns a vote count.
     */
    public function count_votes() {
        return count($this->get_votes());
    }

    /**
     * Returns all replies.
     */
    public function get_replies() {
        global $DB;

        if (!isset($this->replies)) {
            $replies = $DB->get_records('qa_replies', array(
                'qaqid' => $this->id
            ));

            $this->replies = array();
            foreach ($replies as $reply) {
                $this->replies[$reply->id] = reply::from_db($reply);
            }
        }

        return $this->replies;
    }

    /**
     * Returns a reply count.
     */
    public function count_replies() {
        return count($this->get_replies());
    }

    /**
     * Return a view link.
     */
    public function get_view_url() {
        return new \moodle_url('/mod/qa/question.php', array(
            'id' => $this->id
        ));
    }

    /**
     * Returns true if we are able to vote.
     */
    public function can_vote() {
        global $USER;

        if ($USER->id == $this->userid) {
            return false;
        }

        return has_capability('mod/qa:globalview', $this->get_context());
    }

    /**
     * Can the current user view this question?
     */
    public function can_view() {
        global $USER;

        if ($USER->id == $this->userid) {
            return true;
        }

        $qa = $this->get_qa();
        return $qa->has_global_view() || has_capability('mod/qa:globalview', $this->get_context());
    }

    /**
     * Can the current user reply to this question?
     */
    public function can_reply() {
        global $USER;

        if ($USER->id == $this->userid) {
            return true;
        }

        $qa = $this->get_qa();
        return $qa->has_global_reply() || has_capability('mod/qa:globalreply', $this->get_context());
    }

    /**
     * Have we voted on this?
     */
    public function has_voted() {
        global $DB, $USER;

        return $DB->record_exists('qa_votes', array(
            'qaqid' => $this->id,
            'userid' => $USER->id
        ));
    }

    /**
     * Toggle user vote.
     */
    public function toggle_vote() {
        global $DB, $USER;

        if ($this->has_voted()) {
            // Delete.
            $DB->delete_records('qa_votes', array(
                'qaqid' => $this->id,
                'userid' => $USER->id
            ));

            return false;
        }

        // Create.
        $DB->insert_record('qa_votes', array(
            'qaqid' => $this->id,
            'userid' => $USER->id,
            'timecreated' => time()
        ));

        return true;
    }

    /**
     * Post a new reply.
     */
    public function post_reply($contents, $anonymous = 0) {
        global $DB, $USER, $PAGE;

        $reply = new \stdClass();
        $reply->qaqid = $this->id;
        $reply->userid = $USER->id;
        $reply->anonymous = $anonymous;
        $reply->content = $contents;
        $reply->timecreated = time();
        $reply->timemodified = time();

        $id = $DB->insert_record('qa_replies', $reply);
        $reply->id = $id;

        $event = \mod_qa\event\reply_posted::create(array(
            'objectid' => $reply->id,
            'context' => $PAGE->context,
            'userid' => $anonymous ? 0 : $USER->id,
            'other' => array(
                'questionid' => $this->id,
                'questiontitle' => $this->title
            )
        ));
        $event->add_record_snapshot('qa_replies', $reply);
        $event->trigger();

        return reply::from_db($reply);
    }
}
