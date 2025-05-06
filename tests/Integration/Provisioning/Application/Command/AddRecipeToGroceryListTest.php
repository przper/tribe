<?php

namespace Integration\Provisioning\Application\Command;

use PHPUnit\Framework\Attributes\Test;
use Przper\Tribe\Provisioning\AntiCorruption\FoodRecipes\Recipe;
use Przper\Tribe\Provisioning\AntiCorruption\FoodRecipes\RecipeIngredient;
use Przper\Tribe\Provisioning\AntiCorruption\FoodRecipes\RecipeTranslatorInterface;
use Przper\Tribe\Provisioning\Application\Command\AddRecipeToGroceryList\AddRecipeToGroceryListCommand;
use Przper\Tribe\Provisioning\Application\Command\AddRecipeToGroceryList\AddRecipeToGroceryListHandler;
use Przper\Tribe\Provisioning\Domain\GroceryList;
use Przper\Tribe\Provisioning\Domain\GroceryListId;
use Przper\Tribe\Provisioning\Domain\GroceryListRepositoryInterface;
use Przper\Tribe\Shared\Domain\Name;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Doubles\InMemoryInfrastructure\Provisioning\InMemoryGroceryListRepository;
use Tests\Doubles\InMemoryInfrastructure\Provisioning\InMemoryRecipeTranslator;
use Tests\Doubles\MotherObjects\Provisioning\GroceryListItemMother;
use Tests\Doubles\MotherObjects\Provisioning\GroceryListMother;
use Tests\Doubles\MotherObjects\Shared\AmountMother;

class AddRecipeToGroceryListTest extends KernelTestCase
{
    private InMemoryGroceryListRepository $groceryListRepository;
    private AddRecipeToGroceryListHandler $sut;

    protected function setUp(): void
    {
        self::bootKernel();

        $recipeTranslator = self::getContainer()->get(RecipeTranslatorInterface::class);
        $this->assertInstanceOf(InMemoryRecipeTranslator::class, $recipeTranslator);
        $recipeTranslator->recipes = [
            'recipe_1' => new Recipe([
                new RecipeIngredient('Ser feta', 1.0, 'sztuka'),
                new RecipeIngredient('Pomidory', 2.0, 'sztuka'),
                new RecipeIngredient('Rukola', 1.0, 'paczka'),
                new RecipeIngredient('Ogórek', 1.0, 'sztuka'),
            ]),
        ];

        $this->groceryListRepository = self::getContainer()->get(GroceryListRepositoryInterface::class);
        $this->assertInstanceOf(InMemoryGroceryListRepository::class, $this->groceryListRepository);
        $this->groceryListRepository->groceryLists = [
            'list_1' => GroceryListMother::new()
                ->id('list_1')
                ->addItem(GroceryListItemMother::new()->name('Kabanos')->unit('paczka')->build())
                ->addItem(GroceryListItemMother::new()->name('Ogórek')->unit('sztuka')->build())
                ->build(),
        ];

        $this->sut = self::getContainer()->get(AddRecipeToGroceryListHandler::class);
    }

    #[Test]
    public function it_adds_recipe_to_grocery_list(): void
    {
        $groceryList = $this->groceryListRepository->get(new GroceryListId('list_1'));

        $this->assertInstanceOf(GroceryList::class, $groceryList);
        $this->assertCount(2, iterator_to_array($groceryList->getItems()));

        ($this->sut)(new AddRecipeToGroceryListCommand('list_1', 'recipe_1'));

        $groceryList = $this->groceryListRepository->get(new GroceryListId('list_1'));
        $this->assertCount(5, iterator_to_array($groceryList->getItems()));
        $this->assertTrue(AmountMother::new()->quantity(2)->unit('sztuka')->build()->isEqual($groceryList->getItemByName(Name::fromString('Ogórek'))->getAmount()));
    }
}
