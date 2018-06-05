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
 * This file defines constants and classes used by the Filtered course list block.
 *
 * @package    block_filtered_course_list
 * @copyright  2016 CLAMP
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_filtered_course_list\local\filter_generic;

defined('MOODLE_INTERNAL') || die();

/**
 * A class to construct a rubric for generic course and category lists
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

        // Parent = 0   ie top-level categories only.
        $categories = \coursecat::get(0)->get_children();

        if ($categories) {
            // Just print top level category links.
            if (count($categories) > 1 ||
                    (count($categories) == 1 &&
                    current($categories)->coursecount > $this->blockconfig->maxallcourse)) {
                $label = $this->config['catlistheading'];
                $list = $categories;
                $this->rubrics[] = new \block_filtered_course_list\local\rubric($label, $list, $this->blockconfig, $this->config['expanded']);
            } else {
                // Just print course names of single category.
                $category = array_shift($categories);
                $courses = get_courses($category->id);
                if ($courses) {
                    $label = $this->config['courselistheading'];
                    $list = $courses;
                    $this->rubrics[] = new \block_filtered_course_list\local\rubric($label, $list, $this->blockconfig, $this->config['expanded']);
                }
            }
        }

        return $this->rubrics;
    }
}
