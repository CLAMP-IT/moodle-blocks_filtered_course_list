@block @block_filtered_course_list @settings
Feature: Select category to filter by
    In order to select a category to filter by
    As admin
    I need to select an option from the "Categories" dropdown

    @javascript
    Scenario: Selecting a category to filter by from the settings page
        Given the following "categories" exist:
            | name  | category | idnumber |
            | Cat 1 | 0        | cat1     |
            | Cat 2 | 0        | cat2     |
        And the following "courses" exist:
            | fullname  | shortname | category |
            | Course 11 | course11  | cat1     |
            | Course 21 | course21  | cat2     |
        And the following "course enrolments" exist:
            | user  | course   | role    |
            | admin | course11 | student |
            | admin | course21 | student |
        And I log in as "admin"
        And I set the following administration settings values:
            | block_filtered_course_list_filtertype | categories |
            | block_filtered_course_list_adminview  | own        |
        And I navigate to "Filtered course list" node in "Site administration>Plugins>Blocks"
        And I press "Blocks editing on"
        And I add the "filtered_course_list" block
        When I navigate to "Filtered course list" node in "Site administration>Plugins>Blocks"
        Then I should see "Top" in the "#id_s__block_filtered_course_list_categories" "css_element"
        And "Cat 1" "link" should be visible
        And "Cat 2" "link" should be visible
        When I set the field "s__block_filtered_course_list_categories" to "Cat 1"
        Then "Cat 1" "link" should be visible
        And "Cat 2" "link" should not be visible
