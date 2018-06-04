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

namespace block_filtered_course_list\local\filter_category;

defined('MOODLE_INTERNAL') || die();

/**
 * A class to construct rubrics based on category structure
 *
 * @package    block_filtered_course_list
 * @copyright  2016 CLAMP
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class filter extends \block_filtered_course_list\local\filter_base {
    public $configvalidator = "\\block_filtered_course_list\\local\\filter_category\\config_validator";

    /**
     * Populate the array of rubrics for this filter type
     *
     * @return array The list of rubric objects corresponding to the filter
     */
    public function get_rubrics() {

        // We only need this for Moodle < 3.4.
        global $CFG;
        $moodleversion = $CFG->version;

        $categories = $this->_get_cat_and_descendants($this->config['catid'], $this->config['depth']);
        foreach ($categories as $category) {
            $rubricname = $category->name;
            if (isset($this->blockconfig->catrubrictpl) && $this->blockconfig->catrubrictpl != '') {
                $parent = \coursecat::get($category->parent)->get_formatted_name();
                $separator = ' / ';
                if (isset($this->blockconfig->catseparator) && $this->blockconfig->catseparator != '') {
                    $separator = strip_tags($this->blockconfig->catseparator);
                }
                // Simplify the logic below when we drop support for Moodle 3.3.
                if ($moodleversion >= 2017111300) { // For Moodle >= 3.4.
                    $ancestry = $category->get_nested_name(false, $separator);
                } else { // For Moodle < 3.4.
                    $ancestors = \coursecat::make_categories_list('', 0, $separator);
                    $ancestry = $ancestors[$category->id];
                }
                $replacements = array(
                    'NAME'     => $category->name,
                    'IDNUMBER' => $category->idnumber,
                    'PARENT'   => $parent,
                    'ANCESTRY' => $ancestry,
                );
                $rubricname = str_replace(array_keys($replacements), $replacements, $this->blockconfig->catrubrictpl);
                $rubricname = strip_tags($rubricname);
            }
            $courselist = array_filter($this->courselist, function($course) use($category) {
                return ($course->category == $category->id);
            });
            if (empty($courselist)) {
                continue;
            }
            $this->rubrics[] = new \block_filtered_course_list\local\rubric($rubricname, $courselist,
                                                                $this->blockconfig, $this->config['expanded']);
        }

        return $this->rubrics;
    }

    /**
     * Fetch a category and all descendants visible to current usertype
     *
     * @param int $catid The id number of the category to fetch
     * @param int $depth How many generations of categories to show
     * @param array $accumulator An accumulator passed by reference to store the recursive results
     * @return array of \coursecat objects
     */
    protected function _get_cat_and_descendants($catid=0, $depth=0, &$accumulator=array()) {

        if (!\coursecat::get($catid, IGNORE_MISSING)) {
            return array();
        }

        // If $catid is 0, we have a special case. We will need to get all the top-level categories.
        // In the meantime, we don't start adding anything.
        if ($catid != 0) {
            $accumulator[$catid] = \coursecat::get($catid);
        }

        // We do, however, need to pad any non-zero depth, since the first iteration is just prep.
        if ($catid == 0 && $depth > 0) {
            $depth++;
        }

        // If depth was zero then we will keep iterating until there are no more children..
        // Otherwise we bottom out when depth is 1.
        if ($depth != 1) {
            $children = \coursecat::get($catid)->get_children();
            foreach ($children as $child) {
                $this->_get_cat_and_descendants($child->id, $depth - 1, $accumulator);
            }
        }

        return $accumulator;
    }
}
