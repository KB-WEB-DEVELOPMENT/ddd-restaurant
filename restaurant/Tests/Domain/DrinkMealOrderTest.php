<?php
declare(strict_types=1);

namespace Kami\Restaurant\Tests\Domain;

use Kami\Restaurant\Domain\DomainException\InvalidMealItemIdException;
use Kami\Restaurant\Domain\DomainException\InvalidMealProductIdException

use Kami\Restaurant\Domain\Model\Meal\MealItem;
use Kami\Restaurant\Infrastructure\InMemoryMealProductRepository;

use Kami\Restaurant\Domain\Model\Drink\DrinkItem;
use Kami\Restaurant\Infrastructure\InMemoryDrinkProductRepository;

use Kami\Restaurant\Domain\Model\Sustenance\DrinkMealOrder;

use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

use PHPUnit\Framework\TestCase;

final class DrinkMealOrderTest extends TestCase
{
  private ?DrinkMealOrder $drinkMealOrder = null;
  private ?InMemoryMealProductRepository $inMemoryMealRepo = null;
  private ?InMemoryDrinkProductRepository $inMemoryDrinklRepo = null;
	
  protected function setUp(): void
  {
    $this->inMemoryMealRepo = new InMemoryMealProductRepository();
		
    $this->inMemoryDrinkRepo = new InMemoryDrinkProductRepository();

   ($this->inMemoryMealRepo)->store('Meal1',9.15);
   ($this->inMemoryMealRepo)->store('Meal2',19.75);
   ($this->inMemoryMealRepo)->store('Meal3',29.99);
		
   ($this->inMemoryDrinkRepo)->store('Drink1',7.55);
   ($this->inMemoryDrinkRepo)->store('Drink2',14.65);
   ($this->inMemoryDrinkRepo)->store('Drink3',20.10);
		
   $this->drinkMealOrder = new DrinkMealOrder(
                                  $this->inMemoryMealRepo,
				  $this->inMemoryDrinkRepo
			   );		
 }
		
 public function newEmptyDrinkMealOrder(): void
 {
    $mealItemsArr = [];
		
    $mealItemsArr = ($this->drinkMealOrder)->mealItems;
		
    $this->assertEmpty($mealItemsArr);	
 }
		
// same for addDrinkProductItem(): void { ... }
public function addMealProductItem(): void
{
   $allMealProductsArr = [];
		
   $allMealProductIdsArr = [];
		
   $mealItemsArr = [];
		
   $allMealProductsArr = ($this->inMemoryMealRepo)::all();
		
   $allMealProductIdsArr = array_map(fn($mealProduct) => $mealProduct->id(),$allMealProductsArr); 		
	
   $randomMealProductId = $allMealProductIdsArr[array_rand($allMealProductIdsArr,1)];
				
   $randomQuantity = rand(1,5);
		
   $newMealItem = new MealItem($randomMealProductId,$randomQuantity);
		
   ($this->drinkMealOrder)->addMealItem($newMealItem);
	
   $mealItemsArr = ($this->drinkMealOrder)->mealItems;
		
   $newestMealItem = end($mealItemsArr);
		
   $this->assertContainsOnlyInstancesOf(MealItem::class,[$newestMealItem]);
	
}
	
// same for addNonExistingDrinkProductItem(): void { ... }
public function addNonExistingMealProductItem(): void
{
    $this->expectException(InvalidMealProductIdException::class);

    $nonExistingMealProductId =  Uuid::uuid4()->toString();
		
    $randomQuantity = rand(1,5);
		
    $newMealItem = new MealItem($nonExistingMealProductId,$randomQuantity);
		
    ($this->drinkMealOrder)->addMealItem($newMealItem);
}
	
// same for removeDrinkProductItem(): void { ... }		
public function removeMealProductItem(): void
{		
    $allMealProductsArr = [];
		
    $allMealProductIdsArr = [];
		
    $mealItemsArr = [];
		
    $allMealProductsArr = ($this->inMemoryMealRepo)::all();
		
    $allMealProductIdsArr = array_map(fn($mealProduct) => $mealProduct->id(),$allMealProductsArr); 		
	
    $randomMealProductId = $allMealProductIdsArr[array_rand($allMealProductIdsArr,1)];
				
    $randomQuantity = rand(1,5);
		
    $newMealItem = new MealItem($randomMealProductId,$randomQuantity);
		
    ($this->drinkMealOrder)->addMealItem($newMealItem);
		
    $mealItemId = $newMealItem->toString(); 
		
    ($this->drinkMealOrder)->removeMealItem($mealItemId);
		
    $mealItemsArr = ($this->drinkMealOrder)->mealItems;
		
    $this->assertEmpty($mealItemsArr);						
}
	
// same for removeNonExistingDrinkProductItem(): void { ... }	
public function removeNonExistingMealProductItem(): void
{
   $this->expectException(InvalidMealItemIdException::class);
		 
   $mealItemId = 'invalid';
		
   ($this->drinkMealOrder)->removeMealItem($mealItemId);
}
	
// same for emptyAllDrinkProductItems(): void { ... }	
public function emptyAllMealProductItems(): void
{
   $mealItems = [];
			
   ($this->drinkMealOrder)->removeAllMealItems();
			
   $mealItems = ($this->drinkMealOrder)->mealItems;

   $this->assertEmpty($mealItems);		   
}	
	
public function emptyItems(): void
{
  $mealItems = [];
  $drinkItems = [];
    
  ($this->drinkMealOrder)->removeAllSustenanceItems();
		
  $mealItems = ($this->drinkMealOrder)->mealItems;
		
  $drinkItems = ($this->drinkMealOrder)->drinkItems;
		
  $merged = array_merge($mealItems,$drinkItems);
	
  $this->assertEmpty($merged);
}
		
// same for calculateDrinkProductItemsCostNoVat(): void { ... }
public function calculateMealProductItemsCostNoVat(): void
{
  $allMealProductsArr = [];
		
  $allMealProductIdsArr = [];
								
  $allMealProductsArr = ($this->inMemoryMealRepo)::all();
		
  $allMealProductIdsArr = array_map(fn($mealProduct) => $mealProduct->id(),$allMealProductsArr); 		
				
  foreach ($allMealProductIdsArr as $key => $mealProductId) {			
      ($this->drinkMealOrder)->mealItems[] = new MealItem($mealProductId,1);
  }

  $costInt = ($this->drinkMealOrder)->mealItemsCostNoVat();
		
  $this->assertSame(5889,$costInt);		
}

// same for calculateDrinkProductItemsCostWithVat(): void { ... }
public function calculateMealProductItemsCostWithVat(): void
{
   $allMealProductsArr = [];
		
   $allMealProductIdsArr = [];
								
   $allMealProductsArr = ($this->inMemoryMealRepo)::all();
		
   $allMealProductIdsArr = array_map(fn($mealProduct) => $mealProduct->id(),$allMealProductsArr); 		
				
   foreach ($allMealProductIdsArr as $key => $mealProductId) {			
        ($this->drinkMealOrder)->mealItems[] = new MealItem($mealProductId,1);
    }

    $costInt = ($this->drinkMealOrder)->mealItemsCostWithVat();
		
    $this->assertSame(7007,$costInt);		
  }

public function calculateTotalCostInt(): void
{
  $allMealProductsArr = [];
  $allDrinkProductsArr = [];
		
  $allMealProductIdsArr = [];
  $allDrinkProductIdsArr = [];
								
  $allMealProductsArr =  ($this->inMemoryMealRepo)::all();
  $allDrinkProductsArr = ($this->inMemoryDrinkRepo)::all();
		
  $allMealProductIdsArr = array_map(fn($mealProduct) => $mealProduct->id(),$allMealProductsArr);
  $allDrinkProductIdsArr = array_map(fn($drinkProduct) => $drinkProduct->id(),$allDrinkProductsArr);	
				
  foreach ($allMealProductIdsArr as $key => $mealProductId) {			
	($this->drinkMealOrder)->mealItems[] = new MealItem($mealProductId,1);
  }
		
  foreach ($allDrinkProductIdsArr as $key => $drinkProductId) {			
  	($this->drinkMealOrder)->drinkItems[] = new DrinkItem($drinkProductId,1);
  }

  $costInt = ($this->drinkMealOrder)->totalCostInt();

  $this->assertSame(12040,$costInt);				
}

public function calculateTotalCostFormatted(): void
{		
  $allMealProductsArr = [];
  $allDrinkProductsArr = [];
		
  $allMealProductIdsArr = [];
  $allDrinkProductIdsArr = [];
								
  $allMealProductsArr =  ($this->inMemoryMealRepo)::all();
  $allDrinkProductsArr = ($this->inMemoryDrinkRepo)::all();
		
  $allMealProductIdsArr = array_map(fn($mealProduct) => $mealProduct->id(),$allMealProductsArr);
  $allDrinkProductIdsArr = array_map(fn($drinkProduct) => $drinkProduct->id(),$allDrinkProductsArr);	
				
  foreach ($allMealProductIdsArr as $key => $mealProductId) { 			
     ($this->drinkMealOrder)->mealItems[] = new MealItem($mealProductId,1);
  }
		
  foreach ($allDrinkProductIdsArr as $key => $drinkProductId) {			
	($this->drinkMealOrder)->drinkItems[] = new DrinkItem($drinkProductId,1);
  }

 $costFormatted = ($this->drinkMealOrder)->totalCostFormatted();

 $this->assertSame('EUR 120.40',$costFormatted); 
}

 protected function tearDown(): void
 {
   unset($this->inMemoryMealRepo);
   unset($this->inMemoryDrinkRepo);
   unset($this->drinkMealOrder);		
 }
	
}
