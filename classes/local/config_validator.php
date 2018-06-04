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
 * A class to validate data parsed from filter config text
 * Each filter wishing to read config beyond the default should extend this
 * class and reference it in the $configvalidator property of the main
 * filter class.
 * Most classes need only add a name to $names, and a validate method (see below).
 *
 * @package    block_filtered_course_list
 * @copyright  2016 CLAMP
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_filtered_course_list\local;

defined('MOODLE_INTERNAL') || die();

class config_validator {
    /** @var string The very first config element, indicating the type of filter */
    public $filtertype;
    /** @var array Elements parsed from the rubric config line */
    public $values;
    /** @var array Names to identify each config value, in order */
    public $names = array('expanded');
    /** @var array To store name => value */
    public $config = array();

    /**
     * Constructor
     *
     * @param array $line An array of config from the line of text (see config_parser)
     */
    public function __construct($line) {
        $this->filtertype = $line[0];
        $this->values = array_slice($line, 1);
    }

    /**
     * Using names defined in this class, and values parsed and passed,
     * validate each value and store it in the config array.
     *
     * @return $array An array of config name => validated value
     */
    public function validate() {
        foreach ($this->names as $index => $name) {
            $f = "validate_$name";
            $v = array_key_exists($index, $this->values) ? $this->values[$index] : false;
            $this->config[$name] = $this->$f($v);
        }
        return $this->config;
    }

    /**
     * Validate expanded value
     *
     * @param string $value the value to validate
     * @return string|boolean the validated value, or false if the value is not valid
     */
    public function validate_expanded($value) {
        if (empty($value)) {
            return 'collapsed';
        }
        return (\core_text::strpos($value, 'e') === 0) ? 'expanded' : 'collapsed';
    }
}
