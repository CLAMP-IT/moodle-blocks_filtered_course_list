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
 * A class to handle lines of filter config
 *
 * @package    block_filtered_course_list
 * @copyright  2016 CLAMP
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_filtered_course_list\local\filter_category;

defined('MOODLE_INTERNAL') || die();

/**
 * Class to validate config for shortname filter
 *
 * @package    block_filtered_course_list
 * @copyright  2016 CLAMP
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class config_validator extends \block_filtered_course_list\local\config_validator{
    /** @var array Names to identify each config value, in order */
    public $names = array('expanded', 'catid', 'depth');

    /**
     * Validate 'catid' value
     *
     * @param string $value the value to validate
     * @return string|boolean the validated value, or false if the value is not valid
     */
    public function validate_catid($value) {
        return is_numeric($value) ? $value : '0';
    }

    /**
     * Validate 'depth' value
     *
     * @param string $value the value to validate
     * @return string|boolean the validated value, or false if the value is not valid
     */
    public function validate_depth($value) {
        return is_numeric($value) ? $value : '0';
    }
}
