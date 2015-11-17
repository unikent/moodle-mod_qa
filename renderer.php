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
    public function render_list($items) {
        $ret = '<div class="list-group">';

        foreach ($items as $item) {
            $ret .= "<a href=\"{$item->get_view_link()}\" class=\"list-group-item\">{$item->title} <span class=\"badge\">{$item->get_vote_count()}</span></a>";
        }

        $ret .= '</div>';

        return $ret;
    }
}