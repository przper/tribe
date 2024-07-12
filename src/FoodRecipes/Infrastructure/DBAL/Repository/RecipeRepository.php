<?php

namespace Przper\Tribe\FoodRecipes\Infrastructure\DBAL\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Przper\Tribe\FoodRecipes\Domain\Name;
use Przper\Tribe\FoodRecipes\Domain\Recipe;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;
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
        $sql = "SELECT * FROM recipe WHERE id = ?";
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(1, $id);
        $result = $statement->executeQuery();

        if ($result->rowCount()) {
            $data = $result->fetchAssociative();

            return Recipe::restore(
                new RecipeId($data['id']),
                Name::fromString($data['name']),
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
            $sql = "INSERT INTO `recipe` (`id`, `name`) VALUES(?, ?)";
            $statement = $this->connection->prepare($sql);
            $statement->bindValue(1, $recipe->getId());
            $statement->bindValue(2, $recipe->getName());
            $statement->executeQuery();

            $this->dispatchEvents($recipe->pullEvents());

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
    private function dispatchEvents(array $events): void
    {
        $this->eventDispatcher->dispatch(...$events);
    }
}
