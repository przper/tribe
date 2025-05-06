<?php

namespace Przper\Tribe\Provisioning\Ports\Web;

use Przper\Tribe\Provisioning\Application\Query\GetGroceryLists;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GroceryListIndexController extends AbstractController
{
    public function __construct(
        private GetGroceryLists $query,
    ) {}

    #[Route('/groceries', name: 'provisioning.groceries.index', methods: ['GET'])]
    public function __invoke(): Response
    {
        $lists = $this->query->execute();

        return $this->render('provisioning/index.html.twig', [
            'groceryLists' => $lists,
        ]);
    }
}
