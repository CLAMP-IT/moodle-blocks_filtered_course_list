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

namespace block_filtered_course_list;

defined('MOODLE_INTERNAL') || die();

/**
 * A class to structure rubrics regardless of their config type
 *
 * @package    block_filtered_course_list
 * @copyright  2016 CLAMP
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class rubric {
    /** @var string The rubric's title */
    public $title;
    /** @var array The subset of enrolled courses that match the filter criteria */
    public $courses = array();
    /** @var string Indicates whether the rubric is expanded or collapsed by default */
    public $expanded;
    /** @var array Config settings */
    public $config;

    /**
     * Constructor
     *
     * @param string $title The display title of the rubric
     * @param array $courses Courses the user is enrolled in that match the Filtered
     * @param array $config Block configuration
     * @param string $expanded Indicates the rubrics initial state: expanded or collapsed
     */
    public function __construct($title, $courses, $config, $expanded = false) {
        $this->title = $title;
        $this->courses = $courses;
        $this->config = $config;
        $this->expanded = $expanded;
    }
}
