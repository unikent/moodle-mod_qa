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
 * The mod_qa question post form.
 *
 * @package    mod_qa
 * @copyright  2015 Skylar Kelty <S.Kelty@kent.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class post_question extends \moodleform
{
    private $qa;

    public function __construct($qa, $action=null, $customdata=null, $method='post', $target='', $attributes=null, $editable=true) {
        $this->qa = $qa;

        parent::__construct($action, $customdata, $method, $target, $attributes, $editable);
    }

    /**
     * Form definition.
     */
    protected function definition() {
        $mform = $this->_form;

        $mform->addElement('hidden', 'qaid');
        $mform->setType('qaid', PARAM_INT);

        // Adding the standard "name" field.
        $mform->addElement('text', 'name', get_string('qtitle', 'qa'), array('size' => '64'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

        $mform->addElement('textarea', 'desc', get_string('qdesc', 'qa'));
        $mform->setType('desc', PARAM_TEXT);

        if ($this->qa->can_post_anonymously()) {
            $mform->addElement('checkbox', 'anon', get_string('qanon', 'qa'));
        }

        $this->set_data(array('qaid' => $this->qa->id));

        $this->add_action_buttons();
    }
}