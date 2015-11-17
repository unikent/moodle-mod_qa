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
 * Define all the backup steps that will be used by the backup_qa_activity_task
 *
 * @package   mod_qa
 * @category  backup
 * @copyright 2015 Skylar Kelty <S.Kelty@kent.ac.uk>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Define the complete qa structure for backup, with file and id annotations
 *
 * @package   mod_qa
 * @category  backup
 * @copyright 2015 Skylar Kelty <S.Kelty@kent.ac.uk>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_qa_activity_structure_step extends backup_activity_structure_step
{

    /**
     * Defines the backup structure of the module
     *
     * @return backup_nested_element
     */
    protected function define_structure() {

        // Get know if we are including userinfo.
        $userinfo = $this->get_setting_value('userinfo');

        // Define the root element describing the qa instance.
        $qa = new backup_nested_element('qa', array('id'), array(
            'name',
            'intro',
            'introformat'
        ));

        $questions = new backup_nested_element('questions');
        $question = new backup_nested_element('question', array('id'), array(
            'qaid',
            'userid',
            'anonymous',
            'title',
            'description',
            'timecreated',
            'timemodified'
        ));

        $replies = new backup_nested_element('replies');
        $reply = new backup_nested_element('reply', array('id'), array(
            'qaqid',
            'userid',
            'content',
            'timecreated',
            'timemodified'
        ));

        $votes = new backup_nested_element('votes');
        $vote = new backup_nested_element('vote', array('id'), array(
            'qaqid',
            'userid',
            'timecreated'
        ));

        // Define structure.
        $question->add_child($votes);
        $votes->add_child($vote);

        $question->add_child($replies);
        $replies->add_child($reply);

        $qa->add_child($questions);
        $questions->add_child($question);

        // Should we include questions, etc?
        if ($userinfo) {
            $question->set_source_table('qa_questions', array('qaid' => backup::VAR_PARENTID));
            $reply->set_source_table('qa_replies', array('qaqid' => backup::VAR_PARENTID));
            $vote->set_source_table('qa_votes', array('qaqid' => backup::VAR_PARENTID));
        }

        // Define data sources.
        $qa->set_source_table('qa', array('id' => backup::VAR_ACTIVITYID));

        // Define ID relations.
        $question->annotate_ids('qa', 'qaid');
        $question->annotate_ids('user', 'userid');
        $reply->annotate_ids('qa_questions', 'qaqid');
        $reply->annotate_ids('user', 'userid');
        $vote->annotate_ids('qa_questions', 'qaqid');
        $vote->annotate_ids('user', 'userid');

        // Define file annotations (we do not use itemid in this example).
        $qa->annotate_files('mod_qa', 'intro', null);
        $question->annotate_files('mod_qa', 'description', 'qa_questions');
        $reply->annotate_files('mod_qa', 'content', 'qa_replies');

        // Return the root element (qa), wrapped into standard activity structure.
        return $this->prepare_activity_structure($qa);
    }
}
