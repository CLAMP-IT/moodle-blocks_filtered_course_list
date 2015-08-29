@block @block_filtered_course_list
Feature: Course rubrics are collapsible
    In order to see the courses listed under a rubric
    As a user
    I need to click on the rubric to expand it

    @javascript
    Scenario: Viewing the courses under a rubric
        Given the following "categories" exist:
            | name  | category | idnumber |
            | Test  | 0        | test     |
            | Cat 1 | test     | cat1     |
            | Cat 2 | test     | cat2     |
        And the following "courses" exist:
            | fullname  | shortname | category |
            | Course 11 | course11  | cat1     |
            | Course 12 | course12  | cat1     |
            | Course 13 | course13  | cat1     |
            | Course 21 | course21  | cat2     |
            | Course 22 | course22  | cat2     |
            | Course 23 | course23  | cat2     |
        And the following "users" exist:
            | username |
            | testuser |
        And the following "course enrolments" exist:
            | user     | course   | role    |
            | testuser | course11 | student |
            | testuser | course12 | student |
            | testuser | course13 | student |
            | testuser | course21 | student |
            | testuser | course22 | student |
            | testuser | course23 | student |
        And I log in as "admin"
        And I am on site homepage
        And I follow "Turn editing on"
        And I add the "filtered_course_list" block
        And I set the following administration settings values:
            | block_filtered_course_list_filtertype | categories |
            | block_filtered_course_list_categories | Test       |
        And I log out
        When I log in as "testuser"
        And I am on site homepage
        Then I should see "Filtered Course List"
        And "Cat 1" "link" in the ".block_filtered_course_list" "css_element" should be visible
        And "Cat 2" "link" in the ".block_filtered_course_list" "css_element" should be visible
        And "Course 11" "link" in the ".block_filtered_course_list" "css_element" should not be visible
        When I follow "Cat 1"
        Then "Course 11" "link" in the ".block_filtered_course_list" "css_element" should be visible
        When I log out
        And I log in as "admin"
        And I set the following administration settings values:
            | block_filtered_course_list_filtertype       | shortname |
            | block_filtered_course_list_currentshortname | 3         |
            | block_filtered_course_list_currentexpanded  | 1         |
            | block_filtered_course_list_futureshortname  | 2         |
            | block_filtered_course_list_customlabel1     | Ones      |
            | block_filtered_course_list_customshortname1 | 1         |
            | block_filtered_course_list_labelexpanded1   | 1         |
            | block_filtered_course_list_customlabel2     | Twos      |
            | block_filtered_course_list_customshortname2 | 22        |
        And I log out
        And I log in as "testuser"
        And I am on site homepage
        Then "Course 23" "link" in the ".block_filtered_course_list" "css_element" should be visible
        And "Course 11" "link" in the ".block_filtered_course_list" "css_element" should be visible
        And "Course 22" "link" in the ".block_filtered_course_list" "css_element" should not be visible
