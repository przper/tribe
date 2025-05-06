<?php

namespace Przper\Tribe\Provisioning\Application\Command\AddRecipeToGroceryList;

use Przper\Tribe\Provisioning\AntiCorruption\FoodRecipes\Recipe;
use Przper\Tribe\Provisioning\AntiCorruption\FoodRecipes\RecipeTranslatorInterface;
use Przper\Tribe\Provisioning\Domain\GroceryList;
use Przper\Tribe\Provisioning\Domain\GroceryListId;
use Przper\Tribe\Provisioning\Domain\GroceryListItem;
use Przper\Tribe\Provisioning\Domain\GroceryListRepositoryInterface;
use Przper\Tribe\Shared\Domain\Amount;
use Przper\Tribe\Shared\Domain\DomainEventDispatcherInterface;
use Przper\Tribe\Shared\Domain\Name;
use Przper\Tribe\Shared\Domain\Quantity;
use Przper\Tribe\Shared\Domain\Unit;

final readonly class AddRecipeToGroceryListHandler
{
    public function __construct(
        private GroceryListRepositoryInterface $groceryListRepository,
        private DomainEventDispatcherInterface $eventDispatcher,
        private RecipeTranslatorInterface $recipeTranslator,
    ) {}

    public function __invoke(AddRecipeToGroceryListCommand $command): void
    {
        $groceryList = $this->groceryListRepository->get(new GroceryListId($command->groceryListId));

        if (!$groceryList instanceof GroceryList) {
            return;
        }

        $recipe = $this->recipeTranslator->translate($command->recipeId);

        if (!$recipe instanceof Recipe) {
            return;
        }

        foreach ($recipe->ingredients as $ingredient) {
            $item = GroceryListItem::create(
                Name::fromString($ingredient->name),
                Amount::create(Quantity::fromFloat($ingredient->quantity), Unit::fromString($ingredient->unit)),
            );
            $groceryList->add($item);
        }

        $this->groceryListRepository->persist($groceryList);
        $this->eventDispatcher->dispatch(...$groceryList->pullEvents());
    }
}
