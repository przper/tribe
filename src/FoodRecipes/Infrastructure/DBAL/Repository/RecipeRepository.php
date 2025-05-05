<?php

namespace Przper\Tribe\FoodRecipes\Infrastructure\DBAL\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Przper\Tribe\FoodRecipes\Domain\Ingredient;
use Przper\Tribe\FoodRecipes\Domain\Ingredients;
use Przper\Tribe\FoodRecipes\Domain\Recipe;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;
use Przper\Tribe\Shared\Domain\Amount;
use Przper\Tribe\Shared\Domain\Name;
use Przper\Tribe\Shared\Domain\Quantity;
use Przper\Tribe\Shared\Domain\Unit;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
class RecipeRepository implements RecipeRepositoryInterface
{
    public function __construct(
        private Connection $connection,
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
    public function persist(Recipe $recipe): void
    {
        $this->connection->beginTransaction();

        try {
            $statement = $this->connection->prepare(<<<SQL
                    INSERT INTO tribe.recipe
                        (id, name)
                    VALUES
                        (?, ?)
                    ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);
                SQL);
            $statement->bindValue(1, $recipe->getId());
            $statement->bindValue(2, $recipe->getName());
            $statement->executeStatement();

            $this->connection->delete('ingredient', ['recipe_id' => $recipe->getId()]);

            foreach ($recipe->getIngredients() as $ingredient) {
                $this->connection->insert('ingredient', [
                    'recipe_id' => $recipe->getId(),
                    'name' => $ingredient->getName(),
                    'quantity' => $ingredient->getAmount()->getQuantity(),
                    'unit' => $ingredient->getAmount()->getUnit(),
                ]);
            }

            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }
}
