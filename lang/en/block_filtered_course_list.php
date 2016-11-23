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
 * This file defines language strings for the Filtered course list block.
 *
 * @package    block_filtered_course_list
 * @copyright  2016 CLAMP
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['filtered_course_list:addinstance'] = 'Add a new Filtered course list block';
$string['filtered_course_list:myaddinstance'] = 'Add a new Filtered course list block to My home';
$string['managerview']              = 'Manager view';
$string['allcourses']               = 'A manager sees all courses';
$string['blockname']                = 'Filtered course list';
$string['configmanagerview']        = 'What should a manager see in the course list block? Note that managers who are not enrolled in any courses will still see the generic list.';
$string['configfilters']            = <<<EOF
Enter the details for one filter per line. Use pipes ("|") to separate the values. Whitespace will be stripped.
<ul>
<li>The first value in each line indicates the filter type: <i>shortname, regex</i> or <i>category</i>. Lines that start with any other expression will be ignored.</li>
<li>The second element of each line indicates whether the relevant rubric(s) should be <i>expanded</i> or <i>collapsed</i> by default.<br /><li>For shortname and regex filters the third element is the display title for the relevant rubric; for category filteres it is the internal category id. Use zero (0) to match all top-level categories.</li>
<li>The final element for shortname and regex filters is the string to match shortnames against. For regex filters, you can use regex notation; for shortname filters use simple character matches. For category filters the fourth value indicates a depth for recursion. Here you can use zero (0) to instruct the filter to find all descendants.</li></ul>
Additional details available at <a href='https://github.com/CLAMP-IT/moodle-blocks_filtered_course_list#user-content-configuration' target='_blank' title='Additional documentation'>https://github.com/CLAMP-IT/moodle-blocks_filtered_course_list#user-content-configuration</a>
EOF;
$string['confighideallcourseslink'] = 'Hide "All courses" link at the bottom of the block. <br>Link hiding does not affect a manager\'s view';
$string['confighidefromguests']     = 'Hide the block from guests and anonymous visitors.';
$string['confighideothercourses']   = 'Hide "Other courses" in the block.';
$string['configmaxallcourse']       = 'On a site with only one category, managers and guests will see all courses, <br />but above this number they will see a category link instead. <br />[Choose an integer between 0 and 999.]';
$string['configprimarysort']        = 'Within a rubric courses will be sorted by this field. Choose "Sort order" to display courses in the same order as seen in the course management interface.';
$string['configsecondarysort']      = 'Within a rubric courses will secondarily be sorted by this field.';
$string['configtitle']              = 'Block title';
$string['courses']                  = 'Courses';
$string['filters']                  = 'Filter configuration';
$string['hideallcourseslink']       = 'Hide "All courses" link';
$string['hideothercourses']         = 'Hide other courses';
$string['hidefromguests']           = 'Hide from guests';
$string['maxallcourse']             = 'Max for single category';
$string['othercourses']             = 'Other courses';
$string['owncourses']               = 'A manager sees own courses';
$string['pluginname']               = 'Filtered course list';
$string['primarysort']              = 'Primary sort';
$string['primaryvector']            = 'Primary sort vector';
$string['secondarysort']            = 'Secondary sort';
$string['secondaryvector']          = 'Secondary sort vector';
$string['top']                      = 'Top';
