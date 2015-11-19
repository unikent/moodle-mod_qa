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
 * Library of render functions and constants for module qa.
 *
 * @package    mod_qa
 * @copyright  2015 Skylar Kelty <S.Kelty@kent.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * A custom renderer class that extends the plugin_renderer_base and is used by the qa module.
 */
class mod_qa_renderer extends plugin_renderer_base
{
    /**
     * Render a list of items.
     */
    public function render_list($items) {
        $contents = '';
        foreach ($items as $item) {
            $contents .= $this->render_question_item($item);
        }

        return "<div class=\"list-group\">{$contents}</div>";
    }

    /**
     * Render a question.
     */
    public function render_question_item($item) {
        $title = s($item->title);

        $replies = \html_writer::tag('span', get_string('replies', 'mod_qa', $item->count_replies()), array('class' => 'badge'));

        $contents = \html_writer::tag('h4', "{$title} {$replies}", array('class' => 'list-group-item-heading'));
        $contents .= \html_writer::tag('p', get_string('postedby', 'mod_qa', $item->get_username()), array('class' => 'list-group-item-text'));

        if ($item->has_voted()) {
            $tools = \html_writer::tag('span', \html_writer::tag('i', '', array('class' => 'fakelink vote voted fa fa-heart', 'data-id' => $item->id)));
        } else {
            $tools = \html_writer::tag('span', \html_writer::tag('i', '', array('class' => 'fakelink vote fa fa-heart-o', 'data-id' => $item->id)));
        }

        $tools = \html_writer::div($tools);

        $link = \html_writer::link($item->get_view_url(), $contents, array('class' => 'item-link'));

        return \html_writer::div($link . $tools, 'qa-questions list-group-item');
    }

    /**
     * Renders the question part of question.php.
     */
    public function render_question_view($question) {
        global $OUTPUT;

        // Output the description too.
        $contents = '';
        if (!empty($question->description)) {
            $contents .= $OUTPUT->box(format_text($question->description, \FORMAT_HTML), 'generalbox', 'qapost');
        }

        $contents .= \html_writer::tag('p', get_string('postedby', 'mod_qa', $question->get_username()), array('class' => 'author'));

        return $contents . \html_writer::empty_tag('hr');
    }

    /**
     * Render question replies.
     */
    public function render_question_replies($items) {
        $contents = '';
        foreach ($items as $item) {
            $contents .= $this->render_reply($item);
        }

        return \html_writer::div($contents, 'list-group');
    }

    /**
     * Render a reply.
     */
    public function render_reply($item) {
        $link = $item->get_view_url();
        $contents = \html_writer::tag('h4', get_string('postedby', 'mod_qa', $item->get_username()), array('class' => 'list-group-item-heading'));
        $contents .= \html_writer::tag('p', format_text($item->content, \FORMAT_HTML), array('class' => 'list-group-item-text'));
        return \html_writer::div($contents, 'list-group-item');
    }
}