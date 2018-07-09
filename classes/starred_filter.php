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
 * This file contains the class used to handle shortname filters.
 *
 * @package    block_filtered_course_list
 * @copyright  2018 CLAMP
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_filtered_course_list;

defined('MOODLE_INTERNAL') || die();

$starred_courses_lib_path = $CFG->dirroot . "/local/starred_courses/lib.php";
if (file_exists($starred_courses_lib_path)) {
    require_once($starred_courses_lib_path);
} else {
    throw new Exception('The Starred Courses local plugin is not installed, but is required by the Starred filter in block_filtered_course_list');
    die;
}

/**
 * A class to construct rubrics based on shortname matches
 *
 * @package    block_filtered_course_list
 * @copyright  2016 CLAMP
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class starred_filter extends \block_filtered_course_list\filter {
    /**
     * Validate expanded value
     * This should be similar for all subclasses.
     *
     * @param int $index The index of the $line array that should contain the expanded value
     * @param array $arr The line array
     */
    public function validate_enrolledonly($index, &$arr) {
        if (!array_key_exists($index, $arr)) {
            $arr[$index] = 'yes';
        }
        $arr[$index] = (\core_text::strpos($arr[$index], 'n') === 0) ? 'no' : 'yes';
    }

    /**
     * Validate the line
     *
     * @param array $line The array of line elements that has been passed to the constructor
     * @return array A fixed-up line array
     */
    public function validate_line($line) {
        $keys = array('expanded', 'enrolledonly', 'label');
        $values = array_map(function($item) {
            return trim($item);
        }, explode('|', $line[1], 3));
        $this->validate_expanded(0, $values);
        $this->validate_enrolledonly(1, $values);
        if (!array_key_exists(2, $values)) {
            $values[2] = get_string('starred_default_label', 'block_filtered_course_list');
        }
        return array_combine($keys, $values);
    }

    /**
     * Populate the array of rubrics for this filter type
     *
     * @return array The list of rubric objects corresponding to the filter
     */
    public function get_rubrics() {
        global $USER;
        $courselist = get_starred_courses($USER->id);

        if ($this->line['enrolledonly'] === 'yes' && !empty($courselist)) {
            $courselist = array_uintersect($courselist, $this->courselist, function($a, $b) {
                return $a->id === $b->id;
            });
        }

        if (empty($courselist)) {
            return null;
        }

        $this->rubrics[] = new \block_filtered_course_list_rubric($this->line['label'],
                                        $courselist, $this->config, $this->line['expanded']);
        return $this->rubrics;
    }
}
