<?php

namespace Przper\Tribe\Provisioning\Ports\Api;

use Przper\Tribe\Provisioning\Application\Command\AddRecipeToGroceryList\AddRecipeToGroceryListCommand;
use Przper\Tribe\Provisioning\Application\Query\GetGroceryList;
use Przper\Tribe\Provisioning\Application\Query\Result\GroceryList;
use Przper\Tribe\Shared\Application\Command\Sync\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GroceryListActionController extends AbstractController
{
    final public const string ADD_RECIPE_TO_GROCERY_LIST_ACTION = 'add-recipe-to-grocery-list';

    public function __construct(
        private readonly GetGroceryList $getGroceryList,
        private readonly CommandBus $commandBus,
    ) {}

    #[Route('/groceries/{id}', name: 'action', methods: ['POST'])]
    public function __invoke(string $id, Request $request): JsonResponse
    {
        $groceryList = $this->getGroceryList->execute($id);

        if (!$groceryList instanceof GroceryList) {
            throw $this->createNotFoundException('Grocery list not found');
        }

        $request = $request->toArray();

        $action = $request['groceryListAction'] ?? null;

        if ($action === null) {
            return new JsonResponse(
                ["error" => "Missing 'action' parameter in request body"],
                Response::HTTP_UNPROCESSABLE_ENTITY,
            );
        }

        if ($action === self::ADD_RECIPE_TO_GROCERY_LIST_ACTION) {
            $this->commandBus->dispatch(new AddRecipeToGroceryListCommand($id, $request['recipeId']));

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(
            [
                'error' => "Unknown action: $action",
            ],
            Response::HTTP_FORBIDDEN,
        );
    }
}
