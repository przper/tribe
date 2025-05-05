<?php

namespace Przper\Tribe\Provisioning\Domain;

interface GroceryListRepositoryInterface
{
    public function persist(GroceryList $groceryList): void;

    public function get(GroceryListId $id): ?GroceryList;
}
