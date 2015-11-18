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
 * Defines the QA forms.
 *
 * @package    mod_qa
 * @copyright  2015 Skylar Kelty <S.Kelty@kent.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_qa\forms;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir.'/formslib.php');

/**
 * The mod_qa question reply form.
 *
 * @package    mod_qa
 * @copyright  2015 Skylar Kelty <S.Kelty@kent.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class post_reply extends \moodleform
{
    private $question;

    public function __construct($question, $action=null, $customdata=null, $method='post', $target='', $attributes=null, $editable=true) {
        $this->question = $question;

        parent::__construct($action, $customdata, $method, $target, $attributes, $editable);
    }

    /**
     * Form definition.
     */
    protected function definition() {
        $mform = $this->_form;

        $mform->addElement('hidden', 'qaqid');
        $mform->setType('qaqid', PARAM_INT);

        $mform->addElement('textarea', 'contents', get_string('qrdesc', 'qa'));
        $mform->setType('contents', PARAM_TEXT);

        if ($this->question->get_qa()->can_post_anonymously()) {
            $mform->addElement('checkbox', 'anon', get_string('qanon', 'qa'));
        }

        $this->set_data(array('qaqid' => $this->question->id));

        $this->add_action_buttons();
    }
}