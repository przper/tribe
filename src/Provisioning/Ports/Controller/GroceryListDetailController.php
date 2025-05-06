<?php

namespace Przper\Tribe\Provisioning\Ports\Controller;

use Przper\Tribe\Provisioning\Application\Query\GetGroceryList;
use Przper\Tribe\Provisioning\Application\Query\Result\GroceryList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroceryListDetailController extends AbstractController
{
    public function __construct(
        private readonly GetGroceryList $getGroceryList,
    ) {}

    #[Route('/groceries/{id}', name: 'provisioning.groceries.shopping', methods: ['GET'])]
    public function detail(string $id): Response
    {
        $groceryList = $this->getGroceryList->execute($id);

        if (!$groceryList instanceof GroceryList) {
            throw $this->createNotFoundException('Grocery list not found');
        }

        return $this->render('provisioning/shopping.html.twig', [
            'list' => $groceryList,
        ]);
    }
}
