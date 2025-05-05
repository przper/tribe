<?php

namespace Przper\Tribe\Provisioning\Application\Query;

use Przper\Tribe\Provisioning\Application\Query\Result\GroceryListIndex;

interface GetGroceryLists
{
    /** @return GroceryListIndex[] */
    public function execute(): array;
}
