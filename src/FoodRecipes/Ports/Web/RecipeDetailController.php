<?php

namespace Przper\Tribe\FoodRecipes\Ports\Web;

use Przper\Tribe\FoodRecipes\Application\Query\GetRecipeDetail;
use Przper\Tribe\FoodRecipes\Application\Query\Result\RecipeDetail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RecipeDetailController extends AbstractController
{
    public function __construct(
        private readonly GetRecipeDetail $getRecipeDetailQuery,
        #[Autowire(service: 'tribe.internal_http_client')]
        private readonly HttpClientInterface $httpClient,
    ) {}

    #[Route('/recipe/{id}', name: 'recipe_detail', methods: ['GET'])]
    public function __invoke(string $id): Response
    {
        $recipe = $this->getRecipeDetailQuery->execute($id);

        if (!$recipe instanceof RecipeDetail) {
            throw $this->createNotFoundException();
        }

        $groceryLists = $this->httpClient->request('GET', '/api/v1/groceries');

        return $this->render('food_recipes/detail.html.twig', [
            'recipe' => $recipe,
            'groceryLists' => json_decode($groceryLists->getContent(), true),
        ]);
    }
}
