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

$id = required_param('id', PARAM_INT);
list($course, $cm) = get_course_and_cm_from_cmid($id, 'qa');
$qa = \mod_qa\qa::from_id($cm->instance);

require_login($course, true, $cm);

$event = \mod_qa\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot('qa', $qa->get_data());
$event->trigger();

// Print the page header.

$PAGE->set_url('/mod/qa/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($qa->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->requires->css('/mod/qa/styles.css');
$PAGE->requires->js_call_amd('mod_qa/view', 'init', array());

// Output starts here.
echo $OUTPUT->header();
echo $OUTPUT->heading($qa->name);

// Conditions to show the intro can change to look for own settings or whatever.
if ($qa->intro) {
    echo $OUTPUT->box(format_module_intro('qa', $qa, $cm->id), 'generalbox mod_introbox', 'qaintro');
}

$renderer = $PAGE->get_renderer('mod_qa');

// Output list of questions.
$questions = $qa->get_questions();
if (!empty($questions)) {
    // Order by votes.
    usort($questions, function($a, $b) {
        $va = $a->count_votes();
        $vb = $b->count_votes();
        return $va == $vb ? 0 : ($va < $vb ? 1 : -1);
    });

    echo $renderer->render_list($questions);
} else {
    echo \html_writer::tag('p', get_string('noquestions', 'mod_qa'));
}

// New question link.
$url = new \moodle_url('/mod/qa/ask.php', array('qaid' => $qa->id));
echo \html_writer::tag('p', \html_writer::link($url, get_string('askquestion', 'mod_qa')));

// Finish the page.
echo $OUTPUT->footer();
