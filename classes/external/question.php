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
 * Defines a Q&A activity.
 *
 * @package    mod_qa
 * @copyright  2015 Skylar Kelty <S.Kelty@kent.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_qa\external;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/externallib.php");

use external_api;
use external_value;
use external_single_structure;
use external_multiple_structure;
use external_function_parameters;

/**
 * External methods for mod_qa.
 *
 * @package    mod_qa
 * @copyright  2015 Skylar Kelty <S.Kelty@kent.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class question extends external_api
{
    /**
     * Returns description of method parameters.
     *
     * @return external_function_parameters
     */
    public static function vote_parameters() {
        return new external_function_parameters(array(
            'qaqid' => new external_value(
                PARAM_INT,
                'Question ID',
                VALUE_REQUIRED
            )
        ));
    }

    /**
     * Expose to AJAX?
     *
     * @todo Kent Moodle 3.0.
     * @return boolean
     */
    public static function vote_is_allowed_from_ajax() {
        return true;
    }

    /**
     * Toggle user vote for a question.
     *
     * @param $qid
     * @return array [string]
     * @throws \invalid_parameter_exception
     */
    public static function vote($qaqid) {
        global $DB;

        $params = self::validate_parameters(self::vote_parameters(), array(
            'qaqid' => $qaqid
        ));
        $qaqid = $params['qaqid'];

        $question = \mod_qa\question::from_id($qaqid);
        return $question->toggle_vote(); // TODO - access check.
    }

    /**
     * Returns description of vote() result value.
     *
     * @return external_description
     */
    public static function vote_returns() {
        return new external_multiple_structure(new external_value(PARAM_BOOL, 'True if we have a vote now, or false if not.'));
    }
}