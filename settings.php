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

    $settings->add(new admin_setting_heading('block_filtered_course_list/general',
        get_string('generalsettings', 'block_filtered_course_list'), ''));

    $settings->add(new admin_setting_configcheckbox('block_filtered_course_list_hideallcourseslink',
        get_string('hideallcourseslink', 'block_filtered_course_list'),
        get_string('confighideallcourseslink', 'block_filtered_course_list'), 0));

    $settings->add(new admin_setting_configcheckbox('block_filtered_course_list_hideothercourses',
        get_string('hideothercourses', 'block_filtered_course_list'),
        get_string('confighideothercourses', 'block_filtered_course_list'), 0));

    $settings->add(new admin_setting_configtext('block_filtered_course_list_maxallcourse',
        get_string('maxallcourse', 'block_filtered_course_list'),
        get_string('configmaxallcourse', 'block_filtered_course_list'), 10, PARAM_INT, 3));

    $adminviews = array(
        'all' => get_string('allcourses', 'block_filtered_course_list'),
        'own' => get_string('owncourses', 'block_filtered_course_list')
    );
    $settings->add(new admin_setting_configselect('block_filtered_course_list_adminview',
        get_string('adminview', 'block_filtered_course_list'),
        get_string('configadminview', 'block_filtered_course_list'), 'all', $adminviews));

    $filters = array(
        'shortname' => get_string('filtershortname', 'block_filtered_course_list'),
        'categories' => get_string('filtercategories', 'block_filtered_course_list')
        /* we haven't setup custom filters yet
        2 => get_string('filtercustom','block_filtered_course_list')
        */
    );
    $settings->add(new admin_setting_configselect('block_filtered_course_list_filtertype',
        get_string('filtertype', 'block_filtered_course_list'),
        get_string('configfiltertype', 'block_filtered_course_list'), 'shortname', $filters));

    $settings->add(new admin_setting_heading('block_filtered_course_list/shortname',
        get_string('shortnamesettings', 'block_filtered_course_list'),
        get_string('shortnamesettingsinfo', 'block_filtered_course_list')));

    $settings->add(new admin_setting_configtext('block_filtered_course_list_currentshortname',
        get_string('currentshortname', 'block_filtered_course_list'),
        get_string('configcurrentshortname', 'block_filtered_course_list'), ''));

    $settings->add(new admin_setting_configtext('block_filtered_course_list_futureshortname',
        get_string('futureshortname', 'block_filtered_course_list'),
        get_string('configfutureshortname', 'block_filtered_course_list'), ''));

    $howmanylabels = array();
    for ($i = 0; $i <= 10; $i++) {
        $howmanylabels[] = $i;
    }
    $settings->add(new admin_setting_configselect('block_filtered_courselist_labelscount',
        get_string('labelscount', 'block_filtered_course_list'),
        get_string('configlabelscount', 'block_filtered_course_list'), '2', $howmanylabels));

    $labelscount = 2;
    if (isset($CFG->block_filtered_courselist_labelscount)) {
        $labelscount = $CFG->block_filtered_courselist_labelscount;
    }

    for ($i = 1; $i <= $labelscount; $i++) {

        $settings->add(new admin_setting_configtext("block_filtered_course_list_customlabel$i",
            get_string('customlabel', 'block_filtered_course_list') . " $i",
            get_string('configcustomlabel', 'block_filtered_course_list'), ''));

        $settings->add(new admin_setting_configtext("block_filtered_course_list_customshortname$i",
            get_string('customshortname', 'block_filtered_course_list') . " $i",
            get_string('configcustomshortname', 'block_filtered_course_list'), ''));
    }

    $settings->add(new admin_setting_heading('block_filtered_course_list/categories',
        get_string('categorysettings', 'block_filtered_course_list'),
        get_string('categorysettingsinfo', 'block_filtered_course_list')));

    $categories = coursecat::make_categories_list();
    $settings->add(new admin_setting_configselect('block_filtered_course_list_categories',
        get_string('categories', 'block_filtered_course_list'),
        get_string('configcategories', 'block_filtered_course_list'), 1, $categories));
}
