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

/**
 * A class to construct rubrics based on shortname matches
 *
 * @package    block_filtered_course_list
 * @copyright  2016 CLAMP
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class starred_filter extends \block_filtered_course_list\filter {

    /**
     * Validate the line
     *
     * @param array $line The array of line elements that has been passed to the constructor
     * @return array A fixed-up line array
     */
    public function validate_line($line) {
        $keys = array('expanded', 'label');
        $values = array_map(function($item) {
            return trim($item);
        }, explode('|', $line[1], count($keys)));
        $this->validate_expanded(0, $values);
        if (!array_key_exists(1, $values)) {
            $values[1] = get_string('starreddefaultlabel', 'block_filtered_course_list');
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

        $courselist = $this->get_starred_courses($USER->id);

        if (empty($courselist)) {
            return null;
        }

        if ($this->in_course()) {
            $this->line['label'] .= '&nbsp;' . $this->get_starlink();
        }

        $this->rubrics[] = new \block_filtered_course_list_rubric(
                                $this->line['label'],
                                $courselist,
                                $this->config,
                                $this->line['expanded']
                            );
        return $this->rubrics;
    }

    /**
     * Get a list of all courses starred by a user.
     *
     * @param int $userid The id of the user
     * @return array The list of course records
     */
    public function get_starred_courses($userid) {
        global $DB;

        $starred_courses = array();
        if ($starred_ids = $this->get_starred_course_ids($userid)) {
            foreach ($starred_ids as $courseid) {
                $course = $DB->get_record('course', array('id' => $courseid));
                $starred_courses[] = $course;
            }
        }
        return $starred_courses;
    }

    /**
     * Get the ids of all courses starred by a user.
     *
     * @param int $userid The id of the user
     * @return array The list of course ids
     */
    public function get_starred_course_ids($userid) {
        $starred = get_user_preferences('starred_courses', false, $userid);
        if (!empty($starred) && $stararr = explode(',', $starred)) {
            return $stararr;
        }
        return array();
    }

    /**
     * Determine whether we are currently in a non-frontpage course.
     */
    public function in_course() {
        global $COURSE;

        return isset($COURSE) && $COURSE->id > 1;
    }

    /**
     * Get markup for correctly displaying the link to star or unstar a course.
     *
     * @param int $courseid the id of the course to use as reference.
     * @return array HTML describing the star link.
     */
    public function get_starlink() {
        global $COURSE;

        $aclass = "block-fcl__starlink";
        $iclass = "fa fa-star"; // Filled star.
        if ($this->course_is_starred($COURSE->id)) {
            $aclass .= " starred"; // Mark as starred.
        } else {
            $iclass .= "-o"; // Make it outline.
        }
        return '<a class="' . $aclass . '"><i class="' . $iclass . '"></i></a>';
    }

    public function course_is_starred($courseid) {
        global $USER;

        $starred = $this->get_starred_course_ids($USER->id);
        return in_array($courseid, $starred);
    }
}
