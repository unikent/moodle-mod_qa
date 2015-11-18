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
        // TODO - display replies too.
        $title = $item->title;
        $votes = \html_writer::tag('span', $item->count_votes(), array('class' => 'badge'));

        $contents = \html_writer::tag('h4', "{$title} {$votes}", array('class' => 'list-group-item-heading'));
        $contents .= \html_writer::tag('p', 'Posted by ' . $item->get_user(), array('class' => 'list-group-item-text'));

        $link = $item->get_view_link();
        return "<a href=\"{$link}\" class=\"list-group-item\">{$contents}</span></a>";
    }
}