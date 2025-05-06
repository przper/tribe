<?php

namespace Przper\Tribe\FoodRecipes\Ports\Web;

use Przper\Tribe\FoodRecipes\Application\Command\CreateRecipe\CreateRecipeCommand;
use Przper\Tribe\FoodRecipes\Application\Command\CreateRecipe\CreateRecipeHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeCreateController extends AbstractController
{
    public function __construct(
        private readonly CreateRecipeHandler $createRecipeHandler,
    ) {}

    #[Route('/recipe/new', name: 'recipe_create', methods: ['GET', 'POST'])]
    public function __invoke(Request $request): Response
    {
        if ($request->getMethod() === 'POST') {
            call_user_func($this->createRecipeHandler, new CreateRecipeCommand(
                $request->get('name'),
                $request->get('ingredients'),
            ));

            return new RedirectResponse("/recipe");
        }

        return $this->render(
            'food_recipes/create.html.twig',
        );
    }
}
