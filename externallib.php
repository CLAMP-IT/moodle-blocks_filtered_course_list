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
 * This file contains external web service methods.
 *
 * @package    block_filtered_course_list
 * @copyright  2018 CLAMP
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->libdir . "/externallib.php");

class block_filtered_course_list_external extends external_api {

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function toggle_starred_parameters() {
        return new external_function_parameters(
            array(
                // 'assignmentid' => new external_value(PARAM_INT, 'The assignment id to operate on'),
                // 'userid' => new external_value(PARAM_INT, 'The user id the submission belongs to'),
                // 'jsonformdata' => new external_value(PARAM_RAW, 'The data from the grading form, encoded as a json array')
            )
        );
    }

    /**
     * The function itself
     * @return string welcome message
     */
    public static function toggle_starred() {
        return true;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function toggle_starred_returns() {
        return new external_value(PARAM_BOOL, PARAM_REQUIRED);
    }
}
