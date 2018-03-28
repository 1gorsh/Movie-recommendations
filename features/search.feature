Feature: Search
  In order to find movie recommendations
  As a website user
  I need to be able to search for movies

  Scenario: Search for a movie recommendations
    Given I am on "/recommendations"
    When I select "Animation" from "genre-select"
    And I fill in "showing-time" with "12:00"
    And I press "search-submit"
    Then I should see "Zootopia"
    And I should see "Shaun The Sheep"
