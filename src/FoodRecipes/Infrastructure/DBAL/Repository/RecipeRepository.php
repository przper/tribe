<?php

namespace Przper\Tribe\FoodRecipes\Infrastructure\DBAL\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Przper\Tribe\FoodRecipes\Domain\Amount;
use Przper\Tribe\FoodRecipes\Domain\Ingredient;
use Przper\Tribe\FoodRecipes\Domain\Ingredients;
use Przper\Tribe\FoodRecipes\Domain\Name;
use Przper\Tribe\FoodRecipes\Domain\Quantity;
use Przper\Tribe\FoodRecipes\Domain\Recipe;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;
use Przper\Tribe\FoodRecipes\Domain\Unit;
use Przper\Tribe\Shared\Domain\DomainEvent;
use Przper\Tribe\Shared\Domain\DomainEventDispatcherInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
class RecipeRepository implements RecipeRepositoryInterface
{
    public function __construct(
        private Connection $connection,
        private DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function get(RecipeId $id): ?Recipe
    {
        $recipeStatement = $this->connection->prepare("SELECT * FROM recipe WHERE id = ?");
        $recipeStatement->bindValue(1, $id);
        $recipeData = $recipeStatement->executeQuery();

        if ($recipeData->rowCount()) {
            $recipeData = $recipeData->fetchAssociative();

            $ingredientsStatement = $this->connection->prepare("SELECT * FROM ingredient WHERE recipe_id = ?");
            $ingredientsStatement->bindValue(1, $id);
            $ingredientsData = $ingredientsStatement->executeQuery();

            $ingredients = new Ingredients();
            foreach ($ingredientsData->fetchAllAssociative() as $ingredientData) {
                $ingredients->add(Ingredient::create(
                    Name::fromString($ingredientData['name']),
                    Amount::create(
                        Quantity::fromFloat((float) $ingredientData['quantity']),
                        Unit::fromString($ingredientData['unit']),
                    ),
                ));
            }

            return Recipe::restore(
                $id,
                Name::fromString($recipeData['name']),
                $ingredients,
            );
        }

        return null;
    }

    /**
     * @throws Exception
     * @throws \Throwable
     */
    public function create(Recipe $recipe): void
    {
        $this->connection->beginTransaction();

        try {
            $this->connection->insert('recipe', [
                'id' => $recipe->getId(),
                'name' => $recipe->getName(),
            ]);

            foreach ($recipe->getIngredients() as $ingredient) {
                $this->connection->insert('ingredient', [
                    'recipe_id' => $recipe->getId(),
                    'name' => $ingredient->getName(),
                    'quantity' => $ingredient->getAmount()->getQuantity(),
                    'unit' => $ingredient->getAmount()->getUnit(),
                ]);
            }

            $this->dispatchDomainEvents($recipe->pullEvents());

            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }

    //    public function persist(Recipe $recipe): void
    //    {
    //        $sql = <<<SQL
    //                UPDATE recipe
    //                SET
    //                    id = ?,
    //                    name = ?
    //                WHERE
    //                    id = ?
    //            SQL;
    //        $statement = $this->connection->prepare($sql);
    //        $statement->bindValue(1, $recipe->getId());
    //        $statement->bindValue(2, $recipe->getName());
    //        $statement->bindValue(3, $recipe->getId());
    //        $statement->executeQuery();
    //    }

    /**
     * @param DomainEvent[] $events
     */
    private function dispatchDomainEvents(array $events): void
    {
        $this->eventDispatcher->dispatch(...$events);
    }
}
