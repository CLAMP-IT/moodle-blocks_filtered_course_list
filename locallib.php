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

defined('MOODLE_INTERNAL') || die();

define('BLOCK_FILTERED_COURSE_LIST_ADMIN_VIEW_ALL', 'all');
define('BLOCK_FILTERED_COURSE_LIST_ADMIN_VIEW_OWN', 'own');
define('BLOCK_FILTERED_COURSE_LIST_DEFAULT_LABELSCOUNT', 2);
define('BLOCK_FILTERED_COURSE_LIST_DEFAULT_CATEGORY', 0);
define('BLOCK_FILTERED_COURSE_LIST_EMPTY', '');
define('BLOCK_FILTERED_COURSE_LIST_FALSE', 0);
define('BLOCK_FILTERED_COURSE_LIST_TRUE', 1);

/**
 * Utility functions
 *
 * @package    block_filtered_course_list
 * @copyright  2017 CLAMP
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_filtered_course_list_lib {
    /**
     * Display a coursename according to the template
     *
     * @param object $course An object with all of the course attributes
     * @param string $tpl The coursename display template
     */
    public static function coursedisplaytext($course, $tpl) {
        if ($tpl == '') {
            $tpl = 'FULLNAME';
        }
        $cat = coursecat::get($course->category, IGNORE_MISSING);
        $catname = (is_object($cat)) ? $cat->name : '';
        $replacements = array(
            'FULLNAME'  => $course->fullname,
            'SHORTNAME' => $course->shortname,
            'IDNUMBER'  => $course->idnumber,
            'CATEGORY'  => $catname,
        );
        $displaytext = str_replace(array_keys($replacements), $replacements, $tpl);
        return strip_tags($displaytext);
    }
}
