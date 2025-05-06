<?php

namespace Przper\Tribe\Provisioning\Ports\Api;

use Przper\Tribe\Provisioning\Application\Query\GetGroceryLists;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetGroceryListsController extends AbstractController
{
    public function __construct(
        private GetGroceryLists $getGroceryListsQuery,
    ) {}

    #[Route('/groceries', name: 'index', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        return $this->json($this->getGroceryListsQuery->execute());
    }

}
