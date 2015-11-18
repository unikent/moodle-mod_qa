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

namespace mod_qa\traits;

defined('MOODLE_INTERNAL') || die();

/**
 * User abstraction trait.
 *
 * @package    mod_qa
 * @copyright  2015 Skylar Kelty <S.Kelty@kent.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
trait user
{
    protected $user;

    /**
     * Returns a pretty print of the user's name.
     */
    public function get_user() {
        global $DB;

        if (!isset($this->user)) {
            $this->user = $DB->get_record('user', array(
                'id' => $this->userid
            ));
        }

        return $this->user;
    }

    /**
     * Is this anonymously posted?
     */
    public function is_anonymous() {
        return isset($this->anonymous) && $this->anonymous > 0;
    }

    /**
     * Returns a pretty print of the user's name.
     */
    public function get_username() {
        if (method_exists($this, 'is_anonymous') && $this->is_anonymous()) {
            return 'Anonymous';
        }

        $user = $this->get_user();
        return fullname($user);
    }
}