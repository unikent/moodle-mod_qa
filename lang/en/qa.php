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
 * English strings for qa.
 *
 * @package    mod_qa
 * @copyright  2015 Skylar Kelty <S.Kelty@kent.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['modulename'] = 'Q&A';
$string['modulenameplural'] = 'Q&As';
$string['modulename_help'] = 'The Q&A module allows protected and anonymous question and answer pages for students.';

$string['qa'] = 'Q&A';
$string['pluginadministration'] = 'Q&A administration';
$string['pluginname'] = 'Q&A';

$string['qaname'] = 'Q&A name';

$string['qtitle'] = 'Title';
$string['qdesc'] = 'Description';
$string['qanon'] = 'Post anonymously';
$string['qrdesc'] = 'Contents';

$string['qa:addinstance'] = 'Add a new QA activity';
$string['qa:view'] = 'View a QA activity';
$string['qa:submit'] = 'Submit questions to a QA activity';
$string['qa:globalview'] = 'View any post in a QA activity';
$string['qa:globalreply'] = 'Reply to any post in a QA activity';

$string['postedby'] = 'Posted by {$a}';

$string['askquestion'] = 'Ask a question.';
$string['questionreply'] = 'Reply to this question.';
$string['replyto'] = 'Reply: {$a}';

$string['event:questionposted'] = 'Question posted';
$string['event:questionposted_desc_anon'] = 'An anonymous user posted a question \'{$a}\'.';
$string['event:questionposted_desc_user'] = 'The user with id \'{$a->userid}\' posted a question \'{$a->title}\'.';

$string['event:replyposted'] = 'Reply posted';
$string['event:replyposted_desc_anon'] = 'An anonymous user posted a reply to the question \'{$a}\'.';
$string['event:replyposted_desc_user'] = 'The user with id \'{$a->userid}\' posted a reply to the question \'{$a->title}\'.';

$string['noquestions'] = 'There are no questions here yet!';