@block @block_filtered_course_list @block_filtered_course_list_app_smoke @app
Feature: The block renders in the mobile app
  In order to use the Filtered Course List block in the mobile app
  As a user
  I need to visit the installation in the mobile app

  @javascript
  Scenario: Access the block in the mobile app
    Given the following "categories" exist:
      | name  | category | idnumber |
      | Test  | 0        | test     |
      | Cat 1 | test     | cat1     |
      | Cat 2 | test     | cat2     |
    And the following "courses" exist:
      | fullname    | shortname | category |
      | Course 11   | course11  | cat1     |
      | Course 12   | course12  | cat1     |
      | Course 13   | course13  | cat1     |
      | Course 21   | course21  | cat2     |
      | Course 22   | course22  | cat2     |
      | Course 23   | course23  | cat2     |
      | Test Course | test      | test     |
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
      | testuser | test     | student |
    And the following "blocks" exist:
      | blockname            | contextlevel | reference |
      | filtered_course_list | Course       | test      |
    And the following config values are set as admin:
      | persistentexpansion | 0 | block_filtered_course_list |
    And I enter the app
    When I log in as "testuser"
    And I press "Test Course" near "Timeline" in the app
    Then I should see "Filtered course list"
    And I should see "Cat 1"
    And I should see "Cat 2"
    And I should not see "Course 11"
    When I press "Cat 1" in the app
    And I press "Cat 2" in the app
    Then I should see "Course 11"
    And I should see "Course 22"
