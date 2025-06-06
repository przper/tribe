<?php

namespace Przper\Tribe\FoodRecipes\Ports\Web;

use Przper\Tribe\FoodRecipes\Application\Command\CreateRecipe\CreateRecipeCommand;
use Przper\Tribe\Shared\Application\Command\Sync\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeCreateController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
    ) {}

    #[Route('/recipe/new', name: 'recipe_create', methods: ['GET', 'POST'])]
    public function __invoke(Request $request): Response
    {
        if ($request->getMethod() === 'POST') {
            $this->commandBus->dispatch(new CreateRecipeCommand(
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
