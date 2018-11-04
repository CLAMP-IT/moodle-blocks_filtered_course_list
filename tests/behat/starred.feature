@block @block_filtered_course_list @block_filtered_course_list_starred
Feature: The starred courses filter displays a user's starred courses
  In order to add a starred courses filter
  As a Moodle administrator
  I need to add a line to the filter configuration field

  @javascript
  Scenario: Viewing a starred courses rubric
    Given the following "courses" exist:
      | fullname | shortname |
      | Course 1 | course1   |
      | Course 2 | course2   |
      | Course 3 | course3   |
      | Test     | test      |
    And the following "users" exist:
      | username |
      | student1 |
      | student2 |
    And the following "course enrolments" exist:
      | user     | course   | role    |
      | student1 | course1  | student |
      | student1 | course2  | student |
      | student1 | test     | student |
      | student2 | course2  | student |
    And the following "blocks" exist:
      | blockname            | contextlevel | reference |
      | filtered_course_list | Course       | test      |
    And I set the multiline "block_filtered_course_list" "filters" setting as admin to:
    """
    starred | expanded | My starred courses
    """
    When I log in as "student2"
    And I click on ".coursemenubtn" "css_element" in the "//div[@class='card dashboard-card' and contains(.,'Course 2')]" "xpath_element"
    And I click on "Star this course" "link" in the "//div[@class='card dashboard-card' and contains(.,'Course 2')]" "xpath_element"
    And I log out
    And I log in as "student1"
    And I click on ".coursemenubtn" "css_element" in the "//div[@class='card dashboard-card' and contains(.,'Course 1')]" "xpath_element"
    And I click on "Star this course" "link" in the "//div[@class='card dashboard-card' and contains(.,'Course 1')]" "xpath_element"
    And I follow "Test"
    Then I should see "Filtered course list"
    And I should see "My starred courses"
    And I should see "Course 1"
    And I should not see "Course 2"
    And I should not see "Course 3"
