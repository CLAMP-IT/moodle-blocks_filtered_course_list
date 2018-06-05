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

namespace block_filtered_course_list\local\filter_shortname;

defined('MOODLE_INTERNAL') || die();

/**
 * A class to construct rubrics based on shortname matches
 *
 * @package    block_filtered_course_list
 * @copyright  2016 CLAMP
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class filter extends \block_filtered_course_list\local\filter_base {

    /**
     * Populate the array of rubrics for this filter type
     *
     * @return array The list of rubric objects corresponding to the filter
     */
    public function get_rubrics() {
        $courselist = array_filter($this->courselist, function($course) {
            return (\core_text::strpos($course->shortname, $this->config['match']) !== false);
        });
        if (empty($courselist)) {
            return null;
        }
        $this->rubrics[] = new \block_filtered_course_list\local\rubric(
            $this->config['label'],
            $courselist,
            $this->blockconfig,
            $this->config['expanded']);
        return $this->rubrics;
    }
}
