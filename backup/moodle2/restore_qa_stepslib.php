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
 * Define all the restore steps that will be used by the restore_qa_activity_task
 *
 * @package   mod_qa
 * @category  backup
 * @copyright 2015 Skylar Kelty <S.Kelty@kent.ac.uk>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Structure step to restore one qa activity
 *
 * @package   mod_qa
 * @category  backup
 * @copyright 2015 Skylar Kelty <S.Kelty@kent.ac.uk>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_qa_activity_structure_step extends restore_activity_structure_step {

    /**
     * Defines structure of path elements to be processed during the restore
     *
     * @return array of {@link restore_path_element}
     */
    protected function define_structure() {

        $paths = array();
        $paths[] = new restore_path_element('qa', '/activity/qa');
        $paths[] = new restore_path_element('qa_question', '/activity/qa/questions/question');
        $paths[] = new restore_path_element('qa_reply', '/activity/qa/questions/question/replies/reply');
        $paths[] = new restore_path_element('qa_vote', '/activity/qa/questions/question/votes/vote');

        // Return the paths wrapped into standard activity structure.
        return $this->prepare_activity_structure($paths);
    }

    /**
     * Process the given restore path element data
     *
     * @param array $data parsed element data
     */
    protected function process_qa($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();

        if (empty($data->timecreated)) {
            $data->timecreated = time();
        }

        if (empty($data->timemodified)) {
            $data->timemodified = time();
        }

        // Create the qa instance.
        $newitemid = $DB->insert_record('qa', $data);
        $this->apply_activity_instance($newitemid);
    }

    /**
     * Process the given restore path element data
     *
     * @param array $data parsed element data
     */
    protected function process_qa_question($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        $data->qaid = $this->get_new_parentid('qa');
        $data->userid = $this->get_mappingid('user', $data->userid);

        if (empty($data->timecreated)) {
            $data->timecreated = time();
        }

        if (empty($data->timemodified)) {
            $data->timemodified = time();
        }

        $newitemid = $DB->insert_record('qa_questions', $data);
        $this->set_mapping('qa_questions', $oldid, $newitemid);
    }

    /**
     * Process the given restore path element data
     *
     * @param array $data parsed element data
     */
    protected function process_qa_reply($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        $data->qaqid = $this->get_new_parentid('qa_questions');
        $data->userid = $this->get_mappingid('user', $data->userid);

        if (empty($data->timecreated)) {
            $data->timecreated = time();
        }

        if (empty($data->timemodified)) {
            $data->timemodified = time();
        }

        $newitemid = $DB->insert_record('qa_replies', $data);
        $this->set_mapping('qa_replies', $oldid, $newitemid);
    }

    /**
     * Process the given restore path element data
     *
     * @param array $data parsed element data
     */
    protected function process_qa_vote($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        $data->qaqid = $this->get_new_parentid('qa_questions');
        $data->userid = $this->get_mappingid('user', $data->userid);

        if (empty($data->timecreated)) {
            $data->timecreated = time();
        }

        $newitemid = $DB->insert_record('qa_votes', $data);
        $this->set_mapping('qa_votes', $oldid, $newitemid);
    }


    /**
     * Post-execution actions
     */
    protected function after_execute() {
        // Add qa related files, no need to match by itemname (just internally handled context).
        $this->add_related_files('mod_qa', 'intro', null);
        $this->add_related_files('mod_qa', 'description', null);
        $this->add_related_files('mod_qa', 'content', null);
    }
}
