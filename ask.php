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
 * Prints a particular instance of qa.
 *
 * @package    mod_qa
 * @copyright  2015 Skylar Kelty <S.Kelty@kent.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Replace qa with the name of your module and remove this line.

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(__FILE__) . '/lib.php');

$qaid = required_param('qaid', PARAM_INT);
list($course, $cm) = get_course_and_cm_from_instance($qaid, 'qa');
$qa = \mod_qa\qa::from_id($qaid);

require_login($course, true, $cm);
require_capability('mod/qa:submit', $PAGE->context);

// Print the page header.

$PAGE->set_url('/mod/qa/ask.php', array('qaid' => $qaid));
$PAGE->navbar->add(get_string('askquestion', 'mod_qa'));
$PAGE->set_title(format_string($qa->name));
$PAGE->set_heading(format_string($course->fullname));

// Form handling.
$form = new \mod_qa\forms\post_question($qa);

if ($form->is_cancelled()) {
    redirect(new \moodle_url('/mod/qa/view.php', array(
        'id' => $cm->id
    )));
}

if ($data = $form->get_data()) {
    $question = $qa->post_question($data->name, $data->desc, isset($data->anon) ? $data->anon : 0);

    $data = $question->get_data();
    $data->desc = file_save_draft_area_files($draftitemid, $context->id, 'mod_page', 'content', 0, $form->get_editor_options($PAGE->context), $data->desc);

    redirect(new \moodle_url('/mod/qa/question.php', array(
        'id' => $question->id
    )));
}

// Output starts here.
echo $OUTPUT->header();
echo $OUTPUT->heading($qa->name);

$form->display();

// Finish the page.
echo $OUTPUT->footer();
