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
$string['catrubrictpl']             = 'Category rubric template';
$string['catseparator']             = 'Category separator';
$string['completedcourses']         = 'Completed courses';
$string['configcatrubrictpl']       = 'Use this setting to determine a pattern for displaying rubrics when filtering by category. The following replacement tokens are available: NAME, IDNUMBER, PARENT, ANCESTRY. <br /><br />Full details at <a href="https://github.com/CLAMP-IT/moodle-blocks_filtered_course_list/wiki">https://github.com/CLAMP-IT/moodle-blocks_filtered_course_list/wiki</a>.';
$string['configcatseparator']       = 'Separator string to use between category names when using category ANCESTRY in the category rubric template.';
$string['configcoursenametpl']      = 'Use this setting to determine a pattern for displaying a course name. The following replacement tokens are available: FULLNAME, SHORTNAME, IDNUMBER and CATEGORY. <br /><br />Full details at <a href="https://github.com/CLAMP-IT/moodle-blocks_filtered_course_list/wiki">https://github.com/CLAMP-IT/moodle-blocks_filtered_course_list/wiki</a>.';
$string['configmanagerview']        = 'What should a manager see in the course list block? Note that managers who are not enrolled in any courses will still see the generic list.';
$string['configfilters']            = 'Enter one filter per line using vertical bars to separate filter elements. Filter types are: <i>category</i>, <i>shortname</i>, <i>regex</i>, <i>completion</i> and <i>generic</i>. <br /><br />Full details at <a href="https://github.com/CLAMP-IT/moodle-blocks_filtered_course_list/wiki/Filter-syntax">https://github.com/CLAMP-IT/moodle-blocks_filtered_course_list/wiki/Filter-syntax</a>.';
$string['confighideallcourseslink'] = 'Hide "All courses" link at the bottom of the block. <br>Link hiding does not affect a manager\'s view';
$string['confighidefromguests']     = 'Hide the block from guests and anonymous visitors.';
$string['confighideothercourses']   = 'Hide the "Other courses" catch-all rubric in the block.';
$string['configmaxallcourse']       = 'On a site with only one category, managers and guests will see all courses, <br />but above this number they will see a category link instead. <br />[Choose an integer between 0 and 999.]';
$string['configprimarysort']        = 'Within a rubric courses will be sorted by this field. Choose "Sort order" to display courses in the same order as seen in the course management interface.';
$string['configsecondarysort']      = 'Within a rubric courses will secondarily be sorted by this field.';
$string['configtitle']              = 'Block title';
$string['coursenametpl']            = 'Course name template';
$string['courses']                  = 'Courses';
$string['defaultfilters']           = 'category | collapsed | 0 (top level) | 0 (all descendants)';
$string['filters']                  = 'Filter configuration';
$string['filters_help']             = 'Enter one filter per line using vertical bars to separate filter elements. Filter types are: <i>category</i>, <i>shortname</i>, <i>regex</i>, <i>completion</i> and <i>generic</i>. <br /><br />Full details at <a href="https://github.com/CLAMP-IT/moodle-blocks_filtered_course_list/wiki/Filter-syntax">https://github.com/CLAMP-IT/moodle-blocks_filtered_course_list/wiki/Filter-syntax</a>.';
$string['hideallcourseslink']       = 'Hide "All courses" link';
$string['hideothercourses']         = 'Hide other courses';
$string['hidefromguests']           = 'Hide from guests';
$string['maxallcourse']             = 'Max for single category';
$string['othercourses']             = 'Other courses';
$string['owncourses']               = 'A manager sees own courses';
$string['pluginname']               = 'Filtered course list';
$string['primarysort']              = 'Primary sort';
$string['primaryvector']            = 'Primary sort vector';
$string['privacy:metadata']         = 'The filtered course list block displays information about course enrolments, but does not effect or store any data itself. All enrolments are managed by other Moodle systems.';
$string['secondarysort']            = 'Secondary sort';
$string['secondaryvector']          = 'Secondary sort vector';
$string['top']                      = 'Top';
