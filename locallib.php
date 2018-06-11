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
 * A class to structure rubrics regardless of their config type
 *
 * @package    block_filtered_course_list
 * @copyright  2016 CLAMP
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_filtered_course_list_rubric {
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
        // If we have limits defined, apply them.
        static::apply_template_limits($replacements, $tpl);
        $displaytext = str_replace(array_keys($replacements), $replacements, $tpl);
        return strip_tags($displaytext);
    }

    /**
     * Apply length limits to a template string. TOKEN{#} in the template string
     * is replaced by TOKEN, and the replacement value for TOKEN is truncated to
     * # characters.
     *
     * @param object $replacements an array of pattern => replacement
     * @param string $tpl the template string (coursename or category)
     */
    public static function apply_template_limits(&$replacements, &$tpl) {
        $limitpattern = "{(\d+)}";
        foreach ($replacements as $pattern => $replace) {
            $limit = array();
            if (preg_match("/$pattern$limitpattern/", $tpl, $limit)) {
                $replacements[$pattern] = static::truncate($replace, (int) $limit[1]);
            }
        }
        $tpl = preg_replace("/$limitpattern/", "", $tpl);
    }

    /**
     * Ellipsis truncate the given string to $length characters.
     *
     * @param string $string the string to be truncated
     * @param int $length the number of characters to truncate to
     * @return $string the truncated string
     */
    public static function truncate($string, $length) {
        if ($length > 0 && \core_text::strlen($string) > $length) {
            $string = \core_text::substr($string, 0, $length);
            $string = trim($string);
            $string .= "…";
        }
        return $string;
    }
}
