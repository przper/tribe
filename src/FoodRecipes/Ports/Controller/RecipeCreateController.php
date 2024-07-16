<?php

namespace Przper\Tribe\FoodRecipes\Ports\Controller;

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
            call_user_func($this->createRecipeHandler, new CreateRecipeCommand($request->get('name'), []));

            return new RedirectResponse("/recipe");
        }

        return new Response(<<<HTML
                <html>
                    <head></head>
                    <body>
                        <div>
                            <h1>New Recipe</h1>
                            
                            <form method="POST">
                                <input name="name">
                                
                                <button type="submit">Save</button>
                            </form>
                        </div>
                    </body>
                </html>
            HTML);
    }
}
