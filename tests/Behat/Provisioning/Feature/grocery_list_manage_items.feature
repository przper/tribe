Feature: Manage items on Grocery List
  As a tribesman
  In order to buy products
  I need to be able to put needed products on the grocery list

  Scenario: Add item to grocery list
    When I add the 1.0 kilogram of "Meat" to the grocery list
    Then I should see that item "Meat" is listed with 1.0 kilogram amount in the grocery list

  Scenario: Remove item from grocery list
    Given there is "Meat" with amount of 1.0 kilogram on the grocery list
    When I want to remove "Meat" item from the grocery list
    Then I should not see "Meat" on the grocery list