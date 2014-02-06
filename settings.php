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

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    $settings->add(new admin_setting_configcheckbox('block_filtered_course_list_hideallcourseslink',
        get_string('hideallcourseslink', 'block_filtered_course_list'),
        get_string('confighideallcourseslink', 'block_filtered_course_list'), 0));

    $adminviews = array(
        'all' => get_string('allcourses', 'block_filtered_course_list'),
        'own' => get_string('owncourses', 'block_filtered_course_list')
    );
    $settings->add(new admin_setting_configselect('block_filtered_course_list_adminview',
        get_string('adminview', 'block_filtered_course_list'),
        get_string('configadminview', 'block_filtered_course_list'), 'all', $adminviews));

    $filters = array(
        'term' => get_string('filterterm', 'block_filtered_course_list'),
        'categories' => get_string('filtercategories', 'block_filtered_course_list')
        /* we haven't setup custom filters yet
        2 => get_string('filtercustom','block_filtered_course_list')
        */
    );
    $settings->add(new admin_setting_configselect('block_filtered_course_list_filtertype',
        get_string('filtertype', 'block_filtered_course_list'),
        get_string('configfiltertype', 'block_filtered_course_list'), 'term', $filters));

    $settings->add(new admin_setting_configtext('block_filtered_course_list_termcurrent',
        get_string('termcurrent', 'block_filtered_course_list'),
        get_string('configtermcurrent', 'block_filtered_course_list'), ''));

    $settings->add(new admin_setting_configtext('block_filtered_course_list_termfuture',
        get_string('termfuture', 'block_filtered_course_list'),
        get_string('configtermfuture', 'block_filtered_course_list'), ''));

    $categories = coursecat::make_categories_list();
    $settings->add(new admin_setting_configselect('block_filtered_course_list_categories',
        get_string('categories', 'block_filtered_course_list'),
        get_string('configcategories', 'block_filtered_course_list'), 1, $categories));

    $settings->add(new admin_setting_configcheckbox('block_filtered_course_list_hideothercourses',
        get_string('hideothercourses', 'block_filtered_course_list'),
        get_string('confighideothercourses', 'block_filtered_course_list'), 0));

    $settings->add(new admin_setting_configtext('block_filtered_course_list_maxallcourse',
        get_string('maxallcourse', 'block_filtered_course_list'),
        get_string('configmaxallcourse', 'block_filtered_course_list'), 10, PARAM_INT, 3));
}
