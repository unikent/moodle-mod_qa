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
 * Prints a particular question.
 *
 * @package    mod_qa
 * @copyright  2015 Skylar Kelty <S.Kelty@kent.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Replace qa with the name of your module and remove this line.

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(__FILE__) . '/lib.php');

$qid = required_param('id', PARAM_INT);
$question = $DB->get_record('qa_questions', array('id' => $qid));
list($course, $cm) = get_course_and_cm_from_instance($question->qaid, 'qa');

require_login($course, true, $cm);

$PAGE->set_url('/mod/qa/question.php', array('id' => $qid));
$PAGE->navbar->add($question->title, $PAGE->url);
$PAGE->set_title(format_string($question->title));
$PAGE->set_heading(format_string($course->fullname));

// Output starts here.
echo $OUTPUT->header();
echo $OUTPUT->heading($question->title);

// Output the description too.
if (!empty($question->description)) {
    echo $OUTPUT->box(format_text($question->description, \FORMAT_HTML), 'generalbox', 'qapost');
}

// Finish the page.
echo $OUTPUT->footer();
