Feature: Use Grocery List during shopping to map progress
  As a tribesman
  In order to buy products
  I need to be able to mark items from the grocery list as picked up during shopping

  Scenario: Mark item as gathered during shopping time
    Given there is "Meat" with amount of 1.0 kilogram on the grocery list
    And there is "Tomato" with amount of 6.0 pieces on the grocery list
    When I mark "Meat" as PickedUp
    Then I should see "Meat" as PickedUp
    And I should see "Tomato" as ToBuy
