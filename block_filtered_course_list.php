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

require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->dirroot . '/course/externallib.php');
require_once($CFG->dirroot . '/lib/coursecatlib.php');
require_once(dirname(__FILE__) . '/locallib.php');

class block_filtered_course_list extends block_base {
    public function init() {
        $this->title   = get_string('blockname', 'block_filtered_course_list');
    }

    public function has_config() {
        return true;
    }

    public function specialization() {
        $this->title = isset($this->config->title) ? $this->config->title : get_string('blockname', 'block_filtered_course_list');
    }

    public function get_content() {
        global $CFG, $USER, $DB, $OUTPUT;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content         = new stdClass;
        $this->content->text   = '';
        $this->content->footer = '';
        $context = context_system::instance();

        // Obtain values from our config settings.
        $filtertype = 'shortname';
        if (isset($CFG->block_filtered_course_list_filtertype)) {
            $filtertype = $CFG->block_filtered_course_list_filtertype;
        }

        $hidefromguests = 0;
        if (isset($CFG->block_filtered_course_list_hidefromguests)) {
            $hidefromguests = $CFG->block_filtered_course_list_hidefromguests;
        }

        $useregex = 0;
        if (isset($CFG->block_filtered_course_list_useregex)) {
            $useregex = $CFG->block_filtered_course_list_useregex;
        }

        $currentshortname = ' ';
        if (isset($CFG->block_filtered_course_list_currentshortname)) {
            $currentshortname = $CFG->block_filtered_course_list_currentshortname;
        }

        $futureshortname = ' ';
        if (isset($CFG->block_filtered_course_list_futureshortname)) {
            $futureshortname = $CFG->block_filtered_course_list_futureshortname;
        }

        $labelscount = BLOCK_FILTERED_COURSE_LIST_DEFAULT_LABELSCOUNT;
        if (isset($CFG->block_filtered_course_list_labelscount)) {
            $labelscount = $CFG->block_filtered_course_list_labelscount;
        }

        $customlabels = array();
        $customshortnames = array();

        for ($i = 1; $i <= $labelscount; $i++) {
            $property = 'block_filtered_course_list_customlabel'.$i;
            if (isset($CFG->$property) && $CFG->$property != '') {
                $customlabels[$i] = $CFG->$property;
            }
            $customshortnames[$i] = '';
            $property = 'block_filtered_course_list_customshortname'.$i;
            if (isset($CFG->$property) && $CFG->$property != '') {
                $customshortnames[$i] = $CFG->$property;
            }
        }

        $categoryids = ' ';
        if (isset($CFG->block_filtered_course_list_categories)) {
            $categoryids = $CFG->block_filtered_course_list_categories;
        }

        $adminview = BLOCK_FILTERED_COURSE_LIST_ADMIN_VIEW_ALL;
        if (isset($CFG->block_filtered_course_list_adminview)) {
            if ($CFG->block_filtered_course_list_adminview == BLOCK_FILTERED_COURSE_LIST_ADMIN_VIEW_OWN) {
                $adminview = BLOCK_FILTERED_COURSE_LIST_ADMIN_VIEW_OWN;
            }
        }

        $maxallcourse = 10;
        if (isset($CFG->block_filtered_course_list_maxallcourse)) {
            $maxallcourse = $CFG->block_filtered_course_list_maxallcourse;
        }

        $collapsible = 1;
        if (isset($CFG->block_filtered_course_list_collapsible)) {
            $collapsible = $CFG->block_filtered_course_list_collapsible;
        }

        $collapsibleclass = ($collapsible == 1) ? 'collapsible ' : '';

        /* Call accordion YUI module */
        if ($collapsible == 1 && $this->page) {
            $this->page->requires->yui_module('moodle-block_filtered_course_list-accordion',
                'M.block_filtered_course_list.accordion.init', array());
        }

        /* Given that 'my courses' has not been disabled in the config,
         * these are the two types of user who should get to see 'my courses':
         * 1. A logged in user who is neither an admin nor a guest
         * 2. An admin, in the case that $adminview is set to 'own'
         */

        if (empty($CFG->disablemycourses) &&
            (!empty($USER->id) &&
            !has_capability('moodle/course:view', $context) &&
            !isguestuser()) ||
            (has_capability('moodle/course:view', $context) and $adminview == BLOCK_FILTERED_COURSE_LIST_ADMIN_VIEW_OWN)) {

            $allcourses = enrol_get_my_courses(null, 'visible DESC, fullname ASC');

            if ($allcourses) {
                switch ($filtertype) {
                    case 'shortname':
                        $filteredcourses = $this->_filter_by_shortname($allcourses,
                                                                   $currentshortname,
                                                                   $futureshortname,
                                                                   $labelscount,
                                                                   $customlabels,
                                                                   $customshortnames,
                                                                   $useregex);
                        break;

                    case 'categories':
                        $filteredcourses = $this->_filter_by_category($allcourses,
                                                                       $categoryids);
                        break;

                    case 'custom':
                        // We do not yet have a handler for custom filter types.

                        break;

                    default:
                        // This is unexpected.
                        break;
                }

                foreach ($filteredcourses as $section => $courslist) {
                    if (count($courslist) == 0) {
                        continue;
                    }
                    $this->content->text .= html_writer::tag('div', $section, array('class' => 'course-section'));
                    $this->content->text .= '<ul class="' . $collapsibleclass . 'list">';

                    foreach ($courslist as $course) {
                        $this->content->text .= $this->_print_single_course($course);
                    }
                    $this->content->text .= '</ul>';
                    // If we can update any course of the view all isn't hidden.
                    // Show the view all courses link.
                    if (has_capability('moodle/course:update', $context) ||
                        empty($CFG->block_filtered_course_list_hideallcourseslink)) {
                        $this->content->footer = "<a href=\"$CFG->wwwroot/course/index.php\">" .
                                                 get_string('fulllistofcourses') .
                                                 "</a> ...";
                    }
                }
            }
        } else {

            if ($hidefromguests == true && !has_capability('moodle/course:update', $context)) {
                $this->content = null;
                return $this->content;;
            }

            // Parent = 0   ie top-level categories only.
            $categories = coursecat::get(0)->get_children();

            // Check we have categories.
            if ($categories) {
                // Just print top level category links.
                if (count($categories) > 1 ||
                   (count($categories) == 1 &&
                    current($categories)->coursecount > $maxallcourse)) {
                    $this->content->text .= '<ul class="' . $collapsibleclass . 'list">';
                    foreach ($categories as $category) {
                        $linkcss = $category->visible ? "" : "dimmed";
                        $this->content->text .= html_writer::tag('li',
                            html_writer::tag('a', format_string($category->name),
                            array(
                                'href' => $CFG->wwwroot . '/course/index.php?categoryid=' . $category->id,
                                'class' => $linkcss))
                            );
                    }
                    $this->content->text .= '</ul>';
                    $this->content->footer .= "<br><a href=\"$CFG->wwwroot/course/index.php\">" .
                                              get_string('searchcourses') .
                                              '</a> ...<br />';

                    // If we can update any course of the view all isn't hidden.
                    // Show the view all courses link.
                    if (has_capability('moodle/course:update', $context) ||
                        empty($CFG->block_filtered_course_list_hideallcourseslink)) {
                        $this->content->footer .= "<a href=\"$CFG->wwwroot/course/index.php\">" .
                                                  get_string('fulllistofcourses') .
                                                  '</a> ...<br>';
                    }

                } else {
                    // Just print course names of single category.
                    $category = array_shift($categories);
                    $courses = get_courses($category->id);

                    if ($courses) {
                        $this->content->text .= '<ul class="' . $collapsibleclass . 'list">';
                        foreach ($courses as $course) {
                            $this->content->text .= $this->_print_single_course($course);
                        }
                        $this->content->text .= '</ul>';

                        // If we can update any course of the view all isn't hidden.
                        // Show the view all courses link.
                        if (has_capability('moodle/course:update', $context) ||
                            empty($CFG->block_filtered_course_list_hideallcourseslink)) {
                            $this->content->footer .= "<a href=\"$CFG->wwwroot/course/index.php\">" .
                                                      get_string('fulllistofcourses') .
                                                      '</a> ...';
                        }
                    }
                }
            }
        }
        return $this->content;
    }

    private function _print_single_course($course) {
        global $CFG;
        $linkcss = $course->visible ? "fcl-course-link" : "fcl-course-link dimmed";
        $html = html_writer::tag('li',
            html_writer::tag('a', format_string($course->fullname),
            array('href' => $CFG->wwwroot . '/course/view.php?id=' . $course->id,
                'title' => format_string($course->shortname), 'class' => $linkcss))
        );
        return $html;
    }

    private function _filter_by_shortname($courses,
                                        $currentshortname,
                                        $futureshortname,
                                        $labelscount,
                                        $customlabels,
                                        $customshortnames,
                                        $useregex) {

        global $CFG;
        $results = array(get_string('currentcourses', 'block_filtered_course_list') => array(),
                         get_string('futurecourses', 'block_filtered_course_list')  => array());

        foreach ($customlabels as $label) {
            $results[$label] = array();
        }

        $other = $courses;

        foreach ($courses as $key => $course) {
            if ($course->id == SITEID) {
                unset($courses[$key]);
                unset($other[$key]);
                continue;
            }

            if (!empty($currentshortname) && $this->_satisfies_match($course->shortname, $currentshortname, $useregex)) {
                $results[get_string('currentcourses', 'block_filtered_course_list')][] = $course;
                unset($other[$key]);
            }
            if (!empty($futureshortname) && $this->_satisfies_match($course->shortname, $futureshortname, $useregex)) {
                $results[get_string('futurecourses', 'block_filtered_course_list')][] = $course;
                unset($other[$key]);
            }
            for ($i = 1; $i <= $labelscount; $i++) {
                if (isset($customlabels[$i])) {
                    if ($customshortnames[$i] && $this->_satisfies_match($course->shortname, $customshortnames[$i], $useregex)) {
                        $label = $customlabels[$i];
                        $results[$label][] = $course;
                        unset($other[$key]);
                    }
                }
            }
        }

        if (empty($CFG->block_filtered_course_list_hideothercourses) ||
            (!$CFG->block_filtered_course_list_hideothercourses)) {
            $results[get_string('othercourses', 'block_filtered_course_list')] = $other;
        }

        return $results;
    }

    private function _satisfies_match($coursename, $teststring, $useregex) {
        if ($useregex == 0) {
            $satisfies = stristr($coursename, $teststring);
        } else {
            $teststring = str_replace('`', '', $teststring);
            $satisfies = preg_match("`$teststring`", $coursename);
        }
        return $satisfies;
    }

    private function _filter_by_category($courses, $catids) {
        global $CFG;
        $mycats = core_course_external::get_categories(array(
            array('key' => 'id', 'value' => $catids)));
        $results = array();
        $other = array();

        foreach ($mycats as $cat) {
            foreach ($courses as $key => $course) {
                if ($course->id == SITEID) {
                    continue;
                }
                if ($course->category == $cat['id']) {
                    $results[$cat['name']][] = $course;
                    unset($courses[$key]);
                }
            }
        }

        if (empty($CFG->block_filtered_course_list_hideothercourses) ||
            (!$CFG->block_filtered_course_list_hideothercourses)) {
            foreach ($courses as $course) {
                $other[] = $course;
            }
            $results[get_string('othercourses', 'block_filtered_course_list')] = $other;
        }

        return $results;
    }
}
