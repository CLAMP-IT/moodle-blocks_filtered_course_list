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

namespace block_filtered_course_list;

use block_filtered_course_list\output\content;
use block_filtered_course_list_rubric;

require_once(dirname(__FILE__) . '/../locallib.php');

class blockcontent
{
    private $content;
    private $instanceid;
    private $mobile;
    /** @var array Admin settings for the FCL block */
    private $fclconfig;
    /** @var array A list of rubric objects for display */
    private $rubrics = array();
    /** @var arrray A list of courses for the current user */
    private $mycourses = array();
    /** @var string A type of user for purposes of list display, should be 'user', 'manager' or 'guest' */
    private $usertype;
    /** @var string Type of list: 'generic_list', 'filtered_list' or 'empty_block' */
    private $liststyle;


    public function __construct($instanceid, $mobile = false) {
        $this->instanceid = $instanceid;
        $this->mobile = $mobile;
        $this->fclconfig = get_config('block_filtered_course_list');
        $this->content = new \stdClass();
        $this->content->text = '';
        $this->content->footer = '';
    }

    /**
     * Return the block contents
     *
     * @return stdClass The block contents
     */
    public function get_content() {

        global $PAGE;

        $sortsettings = array(
            array(
                $this->fclconfig->primarysort,
                $this->fclconfig->primaryvector,
            ),
            array(
                $this->fclconfig->secondarysort,
                $this->fclconfig->secondaryvector,
            ),
        );

        $sortstring = "visible DESC";

        foreach ($sortsettings as $sortsetting) {
            if ($sortsetting[0] == 'none') {
                continue;
            } else {
                $sortstring .= ", " . $sortsetting[0] . " " . $sortsetting[1];
            }
        }

        $this->mycourses = enrol_get_my_courses(null, "$sortstring");

        $this->_calculate_usertype();

        $this->liststyle = $this->_set_liststyle();

        if ($this->liststyle != 'empty_block') {
            if ($this->liststyle == 'generic_list') {
                $this->fclconfig->filters = BLOCK_FILTERED_COURSE_LIST_GENERIC_CONFIG;
            }
            $this->_process_filtered_list();
        }
        if ($this->mobile) {
            return $this->rubrics;
        }

        $output = $PAGE->get_renderer('block_filtered_course_list');
        $params = array(
            'usertype' => $this->usertype,
            'liststyle' => $this->liststyle,
            'hideallcourseslink' => $this->fclconfig->hideallcourseslink,
        );
        $footer = new \block_filtered_course_list\output\footer($params);
        $this->content->footer = $output->render($footer);
        return $this->content;
    }

    /**
     * Set the usertype for purposes of the course list display
     */
    private function _calculate_usertype() {

        global $USER;

        if (empty($USER->id) || isguestuser()) {
            $this->usertype = 'guest';
        } else if (has_capability('moodle/course:view', \context_system::instance())) {
            $this->usertype = 'manager';
        } else {
            $this->usertype = 'user';
        }
    }

    /**
     * Set the list style
     */
    private function _set_liststyle() {
        global $CFG;

        // The default liststyle is 'filtered_list' but ...
        $liststyle = 'filtered_list';

        if (!empty($CFG->disablemycourses) && $this->usertype != 'manager') {
            $liststyle = 'generic_list';
        }

        if ($this->usertype == 'manager' &&
            $this->fclconfig->managerview != BLOCK_FILTERED_COURSE_LIST_ADMIN_VIEW_OWN) {
            $liststyle = "generic_list";
        }

        if ($this->fclconfig->hidefromguests == BLOCK_FILTERED_COURSE_LIST_TRUE && $this->usertype == 'guest') {
            $liststyle = "empty_block";
        }

        return $liststyle;
    }

    /**
     * Build a user-specific Filtered course list block
     */
    private function _process_filtered_list() {
        global $PAGE;
        $output = $PAGE->get_renderer('block_filtered_course_list');

        // Parse the textarea settings into an array of arrays.
        $filterconfigs = array_map(function ($line) {
            return array_map(function ($item) {
                return trim($item);
            }, explode('|', $line, 2));
        }, explode("\n", $this->fclconfig->filters));

        // Get the arrays of rubrics based on the config lines, filter out failures, and merge them into one array.
        $this->rubrics = array_reduce(
            array_filter(
                array_map(function ($config) {
                    $classname = get_filter($config[0], $this->fclconfig->externalfilters);
                    if (class_exists($classname)) {
                        $item = new $classname($config, $this->mycourses, $this->fclconfig);
                        return $item->get_rubrics();
                    }
                    return null;
                }, $filterconfigs), function ($item) {
                    return is_array($item);
                }
            ),
            'array_merge', array());

        if ($this->fclconfig->hideothercourses == BLOCK_FILTERED_COURSE_LIST_FALSE &&
            $this->liststyle != 'generic_list') {

            $mentionedcourses = array_unique(array_reduce(array_map(function ($item) {
                return $item->courses;
            }, $this->rubrics), 'array_merge', array()), SORT_REGULAR);

            $othercourses = array_udiff($this->mycourses, $mentionedcourses, function ($a, $b) {
                return $a->id - $b->id;
            });

            if (!empty($othercourses)) {
                $otherrubric = new block_filtered_course_list_rubric(get_string('othercourses',
                    'block_filtered_course_list'), $othercourses, $this->fclconfig);
                $this->rubrics[] = $otherrubric;
            }
        }

        if (count($this->rubrics) > 0) {
            if (!$this->mobile) {
                $content = new content($this->rubrics, $this->instanceid);
                $this->content->text = $output->render($content);
            }
        } else if ($this->fclconfig->filters != BLOCK_FILTERED_COURSE_LIST_GENERIC_CONFIG) {
            $this->liststyle = 'generic_list';
            $this->fclconfig->filters = 'generic|e';
            $this->_process_filtered_list();
        }
    }
}