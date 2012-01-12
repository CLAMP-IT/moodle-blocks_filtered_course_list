<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_configcheckbox('block_filtered_course_list_hideallcourseslink', get_string('hideallcourseslink', 'block_filtered_course_list'),
	get_string('confighideallcourseslink', 'block_filtered_course_list'), 0));
    $filters = array(
    	'term' => get_string('filterterm','block_filtered_course_list'),
    	'categories' => get_string('filtercategories','block_filtered_course_list')
	/* we haven't setup custom filters yet
    	2 => get_string('filtercustom','block_filtered_course_list')
	*/
    );

    $settings->add(new admin_setting_configselect('block_filtered_course_list_filtertype', get_string('filtertype', 'block_filtered_course_list'),
	get_string('configfiltertype', 'block_filtered_course_list'), 0, $filters));

    $settings->add(new admin_setting_configtext('block_filtered_course_list_termcurrent', get_string('termcurrent', 'block_filtered_course_list'),
	get_string('configtermcurrent', 'block_filtered_course_list'), ''));

    $settings->add(new admin_setting_configtext('block_filtered_course_list_termfuture', get_string('termfuture', 'block_filtered_course_list'),
	get_string('configtermfuture', 'block_filtered_course_list'), ''));

    $cat_list = array();
    $categories = get_categories("0");
    if($categories) {
	foreach($categories as $cat) {
	    $cat_list[$cat->id] = $cat->name;
	}
    }
    $settings->add(new admin_setting_configselect('block_filtered_course_list_categories', get_string('categories', 'block_filtered_course_list'),
	get_string('configcategories', 'block_filtered_course_list'), '', $cat_list));
}
