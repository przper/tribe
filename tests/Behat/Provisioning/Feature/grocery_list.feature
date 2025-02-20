Feature: Grocery list
  As a tribesman
  In order to buy products
  I need to be able to put needed products on the grocery list

  Scenario: Add item to grocery list
    When I add the 1.0 kilogram of "Meat" to the grocery list
    Then I should see that item "Meat" is listed with 1.0 kilogram amount in the grocery list