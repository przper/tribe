<?php

namespace Przper\Tribe\FoodRecipes\Ports\Controller;

use Przper\Tribe\FoodRecipes\Application\Command\UpdateRecipe\UpdateRecipeCommand;
use Przper\Tribe\FoodRecipes\Application\Command\UpdateRecipe\UpdateRecipeHandler;
use Przper\Tribe\FoodRecipes\Application\Query\GetRecipeEdit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeUpdateController extends AbstractController
{
    public function __construct(
        private readonly UpdateRecipeHandler $updateRecipeHandler,
        private readonly GetRecipeEdit $getRecipeQuery,
    ) {}

    #[Route('/recipe/{id}/update', name: 'recipe_update', methods: ['GET', 'POST'])]
    public function __invoke(string $id, Request $request): Response
    {
        $recipe = $this->getRecipeQuery->execute($id);

        if ($request->getMethod() === 'POST') {
            call_user_func($this->updateRecipeHandler, new UpdateRecipeCommand(
                $id,
                $request->get('name'),
                $request->get('ingredients'),
            ));

            return $this->redirectToRoute('recipe_detail', ['id' => $recipe->id]);
        }

        return $this->render(
            'food_recipes/edit.html.twig',
            [
                'recipe' => $recipe,
            ],
        );
    }
}
