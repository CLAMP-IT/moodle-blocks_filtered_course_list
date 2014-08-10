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

global $CFG;

require_once($CFG->dirroot . '/blocks/moodleblock.class.php');
require_once(dirname(__FILE__) . '/../block_filtered_course_list.php');

class block_filtered_course_list_block_testcase extends advanced_testcase {

    private $adminid;
    private $user1;
    private $user2;
    private $courses = array();
    private $categories = array();

    protected function setUp() {

        global $CFG;
        unset ( $CFG->maxcategorydepth );
        $this->resetAfterTest(true);
        $this->_setupusers();

    }

    public function test_default_settings() {

        global $CFG;

         // Confirm expected defaults.

        $settings = array (
            'hideallcourseslink' => 0,
            'hidefromguests'     => 0,
            'hideothercourses'   => 0,
            'maxallcourse'       => 10,
            'collapsible'        => 1,
            'adminview'          => 'all',
            'filtertype'         => 'shortname',
            'useregex'           => 0,
            'currentshortname'   => '',
            'futureshortname'    => '',
            'labelscount'        => 2,
            'customlabel1'       => '',
            'customshortname1'   => '',
            'customlabel2'       => '',
            'customshortname2'   => '',
            'categories'         => 1
        );

        foreach ($settings as $setting => $value) {
            $scoped = 'block_filtered_course_list_' . $setting;
            $this->assertEquals($CFG->$scoped, $value, "$scoped should be set to $value." );
        }

    }

    public function test_site_with_no_courses() {

        global $CFG;

        // On a site with no courses, no users should see a block.
        $this->_noblock ( array (
            'none'  => true,
            'guest' => true,
            'user1' => true,
            'admin' => true
        ));

    }

    public function test_single_category_site_with_no_enrollments() {

        // Create 8 courses in the default category: Miscellaneous.
        $this->_create_misc_courses( 1, 8 );

        // Anonymous, Guest and Admin should see all courses.
        $this->_courselistincludes ( array (
            'none'  => array ( 'Course 1' , 'Course 8' ),
            'guest' => array ( 'Course 1' , 'Course 8' ),
            'admin' => array ( 'Course 1' , 'Course 8' )
        ));

        // Regular users should not see a block (because there are as yet no enrollments).
        $this->_noblock ( array (
            'user1' => true
        ));

    }

    public function test_small_single_category_site_with_enrollments() {

        // Create 8 courses in the default category: Miscellaneous.
        $this->_create_misc_courses( 1, 8 );

        // Enroll user1 as a teacher in course 1 and a student in courses 3 and 5.
        $this->getDataGenerator()->enrol_user( $this->user1->id , $this->courses['c_1']->id , 3 );
        $this->getDataGenerator()->enrol_user( $this->user1->id , $this->courses['c_3']->id );
        $this->getDataGenerator()->enrol_user( $this->user1->id , $this->courses['c_5']->id );

        // Anonymous, Guest and Admin should see all courses.
        $this->_courselistincludes ( array (
            'none'  => array ( 'Course 1' , 'Course 8' ),
            'guest' => array ( 'Course 1' , 'Course 8' ),
            'admin' => array ( 'Course 1' , 'Course 8' )
        ));

        // User1 should see courses 1, 3 and 5.
        $this->_courselistincludes ( array (
            'user1' => array ( 'Course 1' , 'Course 3' , 'Course 5' )
        ));

        // User1 should not see course 2 or course 6.
        $this->_courselistexcludes ( array (
            'user1' => array ( 'Course 2' , 'Course 6' )
        ));

    }

    public function test_larger_single_category_site_with_enrollments() {

        // Create 12 courses in the default category: Miscellaneous.
        $this->_create_misc_courses( 1, 12 );

        // Enroll user1 as a teacher in course 1 and a student in courses 3 and 5.
        $this->getDataGenerator()->enrol_user( $this->user1->id , $this->courses['c_1']->id , 3 );
        $this->getDataGenerator()->enrol_user( $this->user1->id , $this->courses['c_3']->id );
        $this->getDataGenerator()->enrol_user( $this->user1->id , $this->courses['c_5']->id );

        // The block should not display individual courses to anonymous, guest, or admin.
        $this->_courselistexcludes ( array (
            'none'  => array ( 'Course 1' ),
            'guest' => array ( 'Course 1' ),
            'admin' => array ( 'Course 1' )
        ));

        // The block should offer a category link to anonymous, guest, and admin.
        $this->_courselistincludes ( array (
            'none'  => array ( 'Miscellaneous' ),
            'guest' => array ( 'Miscellaneous' ),
            'admin' => array ( 'Miscellaneous' )
        ));

        // User1 should still see courses 1, 3 and 5.
        $this->_courselistincludes ( array (
            'user1' => array ( 'Course 1' , 'Course 3' , 'Course 5' )
        ));

        // User1 should not see course 2 or course 6.
        $this->_courselistexcludes ( array (
            'user1' => array ( 'Course 2' , 'Course 6' )
        ));

    }

    public function test_rich_site_with_defaults() {

        $this->_create_rich_site();

        // With no special settings, the behavior should be as for a larger single-category site.

        // The block should not display individual courses to anonymous, guest, or admin.
        // The block should not display links to categories below the top level.
        $this->_courselistexcludes ( array (
            'none'  => array ( 'Course', 'Child', 'Grandchild' ),
            'guest' => array ( 'Course', 'Child', 'Grandchild' ),
            'admin' => array ( 'Course', 'Child', 'Grandchild' )
        ));

        // The block should offer top-level category links to anonymous, guest, and admin.
        $this->_courselistincludes ( array (
            'none'  => array ( 'Miscellaneous', 'Sibling' ),
            'guest' => array ( 'Miscellaneous', 'Sibling' ),
            'admin' => array ( 'Miscellaneous', 'Sibling' )
        ));

        // Regular users should see links to visible courses in visible categories under 'Other courses'.
        // Teachers should see links to all their courses, visible or hidden, and under hidden categories.
        $this->_courseunderrubric( array(
            'user1' => array(
                'c_1'   => 'Other courses',
                'cc1_2' => 'Other courses',
                'gc1_1' => 'Other courses',
                'sc_2'  => 'Other courses',
            ),
            'user2' => array(
                'c_1'   => 'Other courses',
                'cc1_3' => 'Other courses',
                'gc1_1' => 'Other courses',
                'hc_1'  => 'Other courses',
                'hcc_3' => 'Other courses',
                'sc_2'  => 'Other courses',
            )
        ));

        // Students should not see links to hidden courses or visible courses under hidden categories.
        $this->_courselistexcludes( array(
            'user1' => array( 'cc1_3', 'gc1_3', 'hc_1', 'hcc_2' )
        ));

        // The block should not appear for a user who is not enrolled in any visible courses.
        $this->_noblock( array(
            'user3' => true
        ));

    }

    public function test_rich_site_filtered_by_category() {

        global $CFG;
        $this->_create_rich_site();

        // Change setting to filter by categories.
        $CFG->block_filtered_course_list_filtertype = 'categories';

        // The block should not display individual courses to anonymous, guest, or admin.
        // The block should not display links to categories below the top level.
        $this->_courselistexcludes ( array (
            'none'  => array ( 'Course', 'Child', 'Grandchild' ),
            'guest' => array ( 'Course', 'Child', 'Grandchild' ),
            'admin' => array ( 'Course', 'Child', 'Grandchild' )
        ));

        // The block should offer top-level category links to anonymous, guest, and admin.
        $this->_courselistincludes ( array (
            'none'  => array ( 'Miscellaneous', 'Sibling' ),
            'guest' => array ( 'Miscellaneous', 'Sibling' ),
            'admin' => array ( 'Miscellaneous', 'Sibling' )
        ));

        // Regular users should see links to visible courses under corresponding visible categories.
        // Teachers should see links to all their courses, visible or hidden, and under hidden categories.
        $this->_courseunderrubric( array(
            'user1' => array(
                'c_1'   => 'Miscellaneous',
                'cc1_2' => 'Child category 1',
                'gc1_1' => 'Grandchild category 1',
                'sc_2'  => 'Other courses',
            ),
            'user2' => array(
                'c_1'   => 'Miscellaneous',
                'cc1_3' => 'Child category 1',
                'gc1_1' => 'Grandchild category 1',
                'hc_1'  => 'Other courses',
                'hcc_3' => 'Other courses',
                'sc_2'  => 'Other courses',
            )
        ));

        // Courses should appear only under immediate parents.
        $this->_courseunderrubric ( array (
            'user1' => array ( 'gc1_1' => 'Miscellaneous' ),
            'user2' => array ( 'gc1_1' => 'Child category 1' )
        ) , 'not' );

        // Students should not see links to hidden courses or visible courses under hidden categories.
        $this->_courselistexcludes( array(
            'user1' => array( 'cc1_3', 'gc1_3', 'hc_1', 'hcc_2' )
        ));

        // The block should not appear for a user who is not enrolled in any visible courses.
        $this->_noblock( array(
            'user3' => true
        ));

        // Now try switching the root category setting.
        $CFG->block_filtered_course_list_categories = $this->categories['cc2']->id;

        // There should be no rubric for Miscellaneous.
        $this->_courselistexcludes( array (
            'user1' => array ( 'Miscellaneous' )
        ));

        // Courses directly under Miscellaneous should continue to appear in 'Other courses'.
        $this->_courseunderrubric( array(
            'user1' => array(
                'c_1'   => 'Other courses',
                'cc1_2' => 'Other courses',
                'cc2_1' => 'Child category 2',
                'gc1_1' => 'Grandchild category 1',
                'sc_2'  => 'Other courses',
            )
        ));
    }

    public function test_rich_site_current_and_future_shortnames() {

        global $CFG;
        $this->_create_rich_site();

        // Set a current and future shortname.
        $CFG->block_filtered_course_list_currentshortname = '_1';
        $CFG->block_filtered_course_list_futureshortname  = '_2';

        // The block should not display individual courses to anonymous, guest, or admin.
        // The block should not display links to categories below the top level.
        $this->_courselistexcludes ( array (
            'none'  => array ( 'Course', 'Child', 'Grandchild' ),
            'guest' => array ( 'Course', 'Child', 'Grandchild' ),
            'admin' => array ( 'Course', 'Child', 'Grandchild' )
        ));

        // The block should offer top-level category links to anonymous, guest, and admin.
        $this->_courselistincludes ( array (
            'none'  => array ( 'Miscellaneous', 'Sibling' ),
            'guest' => array ( 'Miscellaneous', 'Sibling' ),
            'admin' => array ( 'Miscellaneous', 'Sibling' )
        ));

        // The block should list'Current', 'Future', and 'Other courses'.
        $this->_courseunderrubric( array(
            'user1' => array(
                'c_1'   => 'Current courses',
                'cc1_1' => 'Current courses',
                'cc2_1' => 'Current courses',
                'gc1_1' => 'Current courses',
                'sc_1'  => 'Current courses',
            ),
            'user2' => array(
                'c_2'   => 'Future courses',
                'cc1_2' => 'Future courses',
                'cc2_2' => 'Future courses',
                'gc1_2' => 'Future courses',
                'sc_2'  => 'Future courses',
                'hc_2'  => 'Future courses',
                'c_3'   => 'Other courses',
                'gc1_3' => 'Other courses'
            )
        ));

    }

    public function test_rich_site_custom_shortnames() {

        global $CFG;
        $this->_create_rich_site();

        // Set two custom shortnames and rubric labels.
        $CFG->block_filtered_course_list_customlabel1     = 'Child courses';
        $CFG->block_filtered_course_list_customshortname1 = 'cc';
        $CFG->block_filtered_course_list_customlabel2     = 'Unnumbered categories';
        $CFG->block_filtered_course_list_customshortname2 = 'c_';

        // Test against expectations.
        $this->_courseunderrubric ( array (
            'user1' => array (
                'cc1_2' => 'Child courses',
                'cc2_1' => 'Child courses',
                'sc_2'  => 'Unnumbered categories',
                'gc1_2' => 'Other courses',
            ),
            'user2' => array (
                'hc_3'  => 'Unnumbered categories',
            ),
        ));

        // Set a third custom shortname and label.
        $CFG->block_filtered_course_list_customlabel3     = 'Threes';
        $CFG->block_filtered_course_list_customshortname3 = '3';

        // This setting should not apply because labelscount is still set at 2.
        $this->_courselistexcludes ( array (
            'user2' => array ( 'Threes' ),
        ));

        // Increase the number of custom labels.
        $CFG->block_filtered_course_list_labelscount = 4;

        // The 'Threes' should appear now.
        // Courses should appear under all applicable matches.
        $this->_courseunderrubric ( array (
            'user2' => array (
                'c_3'   => 'Unnumbered categories',
                'c_3'   => 'Threes',
                'cc2_3' => 'Child courses',
                'cc2_3' => 'Threes',
            ),
        ));

        // TODO: Validate custom labels.
        // Unfortunately setting a value directly does not submit it to the PARAM validation.
        // So this may be a job for behat testing instead.

        // Use regex for shortname matches.
        $CFG->block_filtered_course_list_useregex = 1;
        $CFG->block_filtered_course_list_customlabel4 = 'All but default';
        $CFG->block_filtered_course_list_customshortname4 = '[a-z]{2}';

        // This new rubric should exclude courses with a shortname like 'c_1'.
        // It does not begin with two lowercase letters.
        $this->_courseunderrubric ( array (
            'user1' => array (
                'c_1' => 'All but default',
            )
        ), 'not');

        // Courses under any other category should be listed.
        $this->_courseunderrubric ( array (
            'user1' => array (
                'cc1_1' => 'All but default',
                'cc2_1' => 'All but default',
                'gc1_1' => 'All but default',
                'sc_1'  => 'All but default',
            ),
        ));

    }

    public function test_setting_hideallcourseslink() {

        global $CFG;
        $this->_create_rich_site();

        // Any user who sees the should also see the "All courses" link.
        $this->_allcourseslink ( array (
            'none'  => true,
            'guest' => true,
            'user1' => true,
            'admin' => true,
            'user3' => false
        ));

        // Hide the All-courses link from all but admins.
        $CFG->block_filtered_course_list_hideallcourseslink = 1;

        // Only an admin should see the "All courses" link.
        $this->_allcourseslink ( array (
            'none'  => false,
            'guest' => false,
            'user1' => false,
            'admin' => true
        ));

    }

    public function test_setting_hidefromguests() {

        global $CFG;
        $this->_create_rich_site();

        // All users (except a regular user enrolled in no courses) should see the block.
        $this->_noblock ( array (
            'none'  => false,
            'guest' => false,
            'user1' => false,
            'admin' => false,
            'user3' => true
        ));

        // Change the setting to hide the block from guests and anonymous visitors.
        $CFG->block_filtered_course_list_hidefromguests = 1;

        // Now only admins and regular enrolled users should see the block.
        $this->_noblock ( array (
            'none'  => true,
            'guest' => true,
            'user1' => false,
            'admin' => false,
            'user3' => true
        ));

    }

    public function test_setting_hideothercourses() {

        global $CFG;
        $this->_create_rich_site();

        // Set a shortname match.
        $CFG->block_filtered_course_list_currentshortname = 'gc';

        // Enrollments that do not match appear under 'Other courses'.
        $this->_courseunderrubric ( array (
            'user1' => array (
                'sc_1' => 'Other courses',
            ),
        ));

        // Hide the catch-all 'Other courses' rubric.
        $CFG->block_filtered_course_list_hideothercourses = 1;

        // No other courses are listed.
        $this->_courselistexcludes ( array (
            'user1' => array( 'Other courses' ),
        ));

    }

    public function test_setting_collapsible_sections() {

        global $CFG;
        $this->_create_rich_site();

        // For users enrolled in courses the various rubrics are collapsible.
        $this->_courselistincludes ( array (
            'user1' => array ( 'collapsible' ),
        ));

        // Change the collapsibility setting.
        $CFG->block_filtered_course_list_collapsible = 0;

        // The rubrics are no longer collapsible.
        $this->_courselistexcludes ( array (
            'user1' => array ( 'collapsible' ),
        ));
    }

    public function test_setting_adminview() {

        global $CFG;
        $this->_create_rich_site();

        // The block should not display links to categories below the top level.
        $this->_courselistexcludes ( array (
            'admin' => array ( 'Course', 'Child', 'Grandchild' )
        ));

        // The block should offer top-level category links to anonymous, guest, and admin.
        $this->_courselistincludes ( array (
            'admin' => array ( 'Miscellaneous', 'Sibling' )
        ));

        // Change the adminview setting to 'own'.
        $CFG->block_filtered_course_list_adminview = 'own';

        // An admin enrolled in no courses will not see the block.
        $this->_noblock ( array (
            'admin' => true,
        ));

        // Put the admin in a course.
        $this->getDataGenerator()->enrol_user( $this->adminid, $this->courses['gc1_1']->id );

        // Admin should see the course listing as a regular user would.
        $this->_courselistexcludes ( array (
            'admin' => array ( 'Miscellaneous', 'Sibling' )
        ));

        $this->_courseunderrubric ( array (
            'admin' => array ( 'gc1_1' => 'Other courses' )
        ));

    }

    private function _setupusers() {

        global $CFG, $USER;

        // Get the admin id.
        $this->setAdminUser();
        $this->adminid = $USER->id;

        // Set up 3 regular users.
        $this->user1 = $this->getDataGenerator()->create_user(array(
            'username'  => 'user1',
            'firstname' => 'User',
            'lastname'  => 'One',
            'email'     => 'user1@unittest.com'
        ));
        $this->user2 = $this->getDataGenerator()->create_user(array(
            'username'  => 'user2',
            'firstname' => 'User',
            'lastname'  => 'Two',
            'email'     => 'user2@unittest.com'
        ));
        $this->user3 = $this->getDataGenerator()->create_user(array(
            'username'  => 'user3',
            'firstname' => 'User',
            'lastname'  => 'Three',
            'email'     => 'user3@unittest.com'
        ));

    }

    private function _create_misc_courses( $start=1, $end=8 ) {

        for ($i = $start; $i <= $end; $i++) {
            $this->courses["c_$i"] = $this->getDataGenerator()->create_course(array(
                'fullname' => "Course $i in Misc",
                'shortname' => "c_$i",
                'idnumber' => "c_$i"
            ));
        }
    }

    private function _create_rich_site() {

        /*
         * Category structure
         *
         * Miscellaneous
         *   Course 1, c_1
         *   ...
         *   Course 12, c_12
         *   Child category 1
         *     Course 1 in Child category 1, cc1_1
         *     ...
         *     Course 3 in Child category 1, cc1_3, hidden
         *   Child category 2
         *     Course 1 in Child category 2, cc2_1
         *     ...
         *     Course 3 in Child category 2, cc2_3, hidden
         *     Grandchild category 1
         *       Course 1 in Grandchild category, gc1_1
         *       ...
         *       Course 3 in Grandchild category, gc1_3, hidden
         *   Hidden category
         *     Course 1 in Hidden category, hc_1
         *     ...
         *     Course 3 in Hidden category, hc_3, hidden
         *     Hidden category child
         *       Course 1 in Hidden category child, hcc_1
         *       ...
         *       Course 3 in Hidden category child, hcc_3, hidden
         * Sibling category
         *   Course 1 in Sibling category, sc_1
         *   ...
         *   Course 3 in Sibling category, sc_3, hidden
         */

        // Add some courses under Miscellaneous.
        $this->_create_misc_courses ( 1, 3 );

        // Create categories.
        $this->categories['cc1'] = $this->getDataGenerator()->create_category( array(
            'name'     => 'Child category 1',
            'parent'    => 1,
            'idnumber' => 'cc1'
        ));
        $this->categories['cc2'] = $this->getDataGenerator()->create_category( array(
            'name'     => 'Child category 2',
            'parent'    => 1,
            'idnumber' => 'cc2'
        ));
        $cc2id = $this->categories['cc2']->id;
        $this->categories['gc1'] = $this->getDataGenerator()->create_category( array(
            'name'     => 'Grandchild category 1',
            'parent'   => $cc2id,
            'idnumber' => 'gc1'
        ));
        $this->categories['hc'] = $this->getDataGenerator()->create_category( array(
            'name'     => 'Hidden category',
            'parent'   => 1,
            'idnumber' => 'hc',
            'visible'  => 0
        ));
        $hcid = $this->categories['hc']->id;
        $this->categories['hcc'] = $this->getDataGenerator()->create_category( array(
            'name'     => 'Hidden category child',
            'parent'   => $hcid,
            'idnumber' => 'hcc'
        ));
        $this->categories['sc'] = $this->getDataGenerator()->create_category( array(
            'name'     => 'Sibling category',
            'idnumber' => 'sc'
        ));

        // Create three courses in each category, the third of which is hidden.
        foreach ($this->categories as $id => $category) {
            for ($i = 1; $i <= 3; $i++) {
                $shortname = "${id}_$i";
                $params = array (
                    'fullname' => "Course $i in $category->name",
                    'shortname' => $shortname,
                    'idnumber'  => $shortname,
                    'category'  => $category->id
                );
                if ( $i % 3 == 0 ) {
                    $params['visible'] = 0;
                }
                $this->courses[$shortname] = $this->getDataGenerator()->create_course( $params );
            }
        }

        // Enroll user1 as a student in all courses.
        foreach ($this->courses as $course) {
            $this->getDataGenerator()->enrol_user( $this->user1->id, $course->id );
        }

        // Enroll user2 as a teacher in all courses.
        foreach ($this->courses as $course) {
            $this->getDataGenerator()->enrol_user( $this->user2->id, $course->id, 3 );
        }
    }

    private function _noblock ( $expectations=array() ) {
        foreach ($expectations as $user => $result) {
            $this->_switchuser ( $user );
            $bi     = new block_filtered_course_list;
            if ( $result === true ) {
                if ( isset ( $bi->get_content()->text ) ) {
                    // In some cases the text exists but is empty.
                    $this->assertEmpty ( $bi->get_content()->text , "$user should not see a block." );
                } else {
                    // In other cases the text will not have been set at all.
                    $this->assertFalse ( isset ( $bi->get_content()->text ) );
                }
            } else {
                $this->assertNotEmpty ( $bi->get_content()->text , "$user should see a block." );
            }
        }
    }

    private function _allcourseslink ( $expectations=array() ) {
        foreach ($expectations as $user => $result) {
            $this->_switchuser ( $user );
            $bi     = new block_filtered_course_list;
            $footer = $bi->get_content()->footer;
            if ( $result === true ) {
                $this->assertContains ( 'All courses' , $footer , "$user should see the All-courses link." );
            } else {
                $this->assertNotContains ( 'All courses' , $footer , "$user should not see the All-courses link." );
            }
        }
    }

    private function _courselistincludes ( $expectations=array() ) {
        foreach ($expectations as $user => $courses) {
            $this->_switchuser ( $user );
            $bi     = new block_filtered_course_list;
            foreach ($courses as $course) {
                $this->assertContains ( $course , $bi->get_content()->text , "$user should see $course." );
            }
        }
    }

    private function _courselistexcludes ( $expectations=array() ) {
        foreach ($expectations as $user => $courses) {
            $this->_switchuser ( $user );
            $bi     = new block_filtered_course_list;
            foreach ($courses as $course) {
                $this->assertNotContains ( $course , $bi->get_content()->text , "$user should not see $course." );
            }
        }
    }

    private function _switchuser ( $user ) {
        if ( $user == 'none' ) {
            $this->setUser( null );
        } else if ( $user == 'guest' ) {
            $this->setGuestUser();
        } else if ( $user == 'admin' ) {
            $this->setAdminUser();
        } else {
            $this->setUser( $this->$user );
        }
    }

    private function _courseunderrubric ( $expectations=array() , $relation='under' ) {
        foreach ($expectations as $user => $courses) {
            $this->_switchuser ( $user );
            $bi = new block_filtered_course_list;
            $html = new DOMDocument;
            $html->loadHTML( $bi->get_content()->text );
            $rubrics = $html->getElementsByTagName('div');
            foreach ($courses as $course => $rubricmatch) {
                $hits = 0;
                foreach ($rubrics as $rubric) {
                    $rubrictitle = $rubric->nodeValue;
                    if ( $rubrictitle != $rubricmatch ) {
                        continue;
                    }
                    $ul = $rubric->nextSibling;
                    $anchors = $ul->getElementsByTagName('a');
                    foreach ($anchors as $anchor) {
                        $anchorclass = $anchor->attributes->getNamedItem('class')->nodeValue;
                        if ( strpos( $anchorclass, 'fcl-course-link' ) !== false ) {
                            $anchortitle = $anchor->attributes->getNamedItem('title')->nodeValue;
                            if ( $anchortitle == $course ) {
                                $hits++;
                            }
                        }
                    }
                }
                if ( $relation == 'not' ) {
                    $this->assertEquals( 0, $hits, "$user should not see $course under $rubricmatch" );
                } else {
                    $this->assertGreaterThan( 0, $hits, "$user should see $course under $rubricmatch" );
                }
            }
        }
    }
}
