<?php

namespace Przper\Tribe\FoodRecipes\Ports\Controller;

use Przper\Tribe\FoodRecipes\Application\Query\GetRecipes;
use Przper\Tribe\FoodRecipes\Application\Query\Result\RecipeIndex;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeIndexController extends AbstractController
{
    public function __construct(
        private readonly GetRecipes $getRecipesQuery,
    ) {}

    #[Route('/recipe', name: 'recipe_index')]
    public function __invoke(): Response
    {
        $recipes = $this->getRecipesQuery->execute();

        $recipesHtml = $recipes !== []
            ? implode("\n", array_map(fn(RecipeIndex $r) => "<li>{$r->name} <a href='/recipe/{$r->id}'>Show</a></li>", $recipes))
            : "List is empty :( . Add something!"
        ;

        $html = <<<HTML
                <html>
                    <head></head>
                    <body>
                        <div>
                            <h1>Our tasty recipes:</h1>
                            
                            <div><a href="/recipe/new">Add new</a></div>
                            
                            <div>
                                <ol>
                                    $recipesHtml
                                </ol>
                            </div>
                        </div>
                    </body>
                </html>
            HTML;

        return new Response($html);
    }
}
