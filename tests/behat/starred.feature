@block @block_filtered_course_list @block_filtered_course_list_starred
Feature: The starred courses filter will display courses the user has starred, and allow the user to star/unstar the current course
  In order to filter courses by whether or not they are starred
  As a moodle administrator
  I need to add the starred courses filter to the block configuration

  @javascript
  Scenario: testing the starred courses filter
    Given the following "courses" exist:
      | fullname | shortname | id |
      | Course 1 | course1   | 2  |
      | Course 2 | course2   | 5  |
      | Test     | test      | 7  |
    And the following "users" exist:
      | username | id |
      | userone  | 11 |
      | usertwo  | 22 |
    And the following "course enrolments" exist:
      | user     | course  | role    |
      | userone  | course1 | student |
      | userone  | course2 | student |
      | userone  | test    | student |
      | usertwo  | course1 | student |
      | usertwo  | course2 | student |
      | usertwo  | test    | student |
    And the following "blocks" exist:
      | blockname            | contextlevel | reference |
      | filtered_course_list | System       | 1         |
    And I set the multiline "block_filtered_course_list" "filters" setting as admin to:
    """
    starred | expanded | Starred courses
    """

    When I log in as "userone"
    And I am on site homepage
    Then I should see "Filtered course list"
    When I follow "Course 1"
    Then I should see "Filtered course list"
    And I should not see "Course 1" in the ".block_filtered_course_list" "css_element"
    And I should not see "Course 2" in the ".block_filtered_course_list" "css_element"
    And I should not see "Test" in the ".block_filtered_course_list" "css_element"
    And I click on "a.block-fcl__starlink" "css_element"
    When I reload the page
    Then I should see "Course 1" in the ".block_filtered_course_list" "css_element"
    And I should not see "Course 2" in the ".block_filtered_course_list" "css_element"
    And I should not see "Test" in the ".block_filtered_course_list" "css_element"
    And I log out

    When I log in as "usertwo"
    And I am on site homepage
    Then I should see "Filtered course list"
    When I follow "Test"
    Then I should see "Filtered course list"
    And I should not see "Course 1" in the ".block_filtered_course_list" "css_element"
    And I should not see "Course 2" in the ".block_filtered_course_list" "css_element"
    And I should not see "Test" in the ".block_filtered_course_list" "css_element"
    And I click on "a.block-fcl__starlink" "css_element"
    When I reload the page
    Then I should not see "Course 1" in the ".block_filtered_course_list" "css_element"
    And I should not see "Course 2" in the ".block_filtered_course_list" "css_element"
    And I should see "Test" in the ".block_filtered_course_list" "css_element"
