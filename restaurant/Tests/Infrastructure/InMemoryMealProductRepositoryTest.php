<?php

declare(strict_types=1);

namespace Kami\Restaurant\Tests\Infrastructure;

use Kami\Restaurant\Domain\DomainException\InvalidProductNameException;
use Kami\Restaurant\Domain\DomainException\InvalidPriceException;
use Kami\Restaurant\Domain\DomainException\InvalidMealProductIdException;

use Kami\Restaurant\Domain\Model\Meal\MealProduct;
use Kami\Restaurant\Infrastructure\InMemoryMealProductRepository;

use PHPUnit\Framework\TestCase;

final class InMemoryMealProductRepositoryTest extends TestCase
{
   private array $mealProducts = [];
   private ?InMemoryMealProductRepository $repo = null;
	
   protected function setUp(): void
   {
        $this->mealProducts[] = new MealProduct('Meal1',9.99);
        $this->mealProducts[] = new MealProduct('Meal2',15.99);		
		$this->mealProducts[] = new MealProduct('Meal3',17.99);
	
		$this->repo = new InMemoryMealProductRepository();
		
		($this->repo)->mealProducts = $this->mealProducts;
   }
		
  public function newMealProduct(): void
  {
     ($this->repo)->store('Meal4',24.99);
		
     $mealProductsArray = [];
     $mealProductsArray = ($this->repo)->mealProducts; 
	
     $this->assertContainsOnlyInstancesOf(MealProduct::class,$mealProductsArray);
  }
    
 public function newMealProductWrongName(): void
 {
    $this->expectException(InvalidProductNameException::class);
    ($this->repo)->store('',14.99);
 } 

 public function newMealProductWrongPrice(): void
 {
    $this->expectException(InvalidPriceException::class);
    ($this->repo)->store('Meal5',0);
 }
	
 public function allMealProducts(): void
 {
     $mealProductsArray = [];
     $mealProductsArray = ($this->repo)::all();
 		
     $this->assertCount(3,$mealProductsArray); 
 }
	
public function mealProductById(): void
{
    $mealProduct = $this->mealProducts[0];
    $uuidString = $mealProduct->id();
		
    $foundMealProduct = ($this->repo)->byId($uuidString);
		
    $exp = [];
    $exp = [$foundMealProduct->name(),$foundMealProduct->priceInt()];
		
    $this->assertSame(['Meal1',999],$exp);
		
}
    
public function mealProductByWrongId(): void
{
   $this->expectException(InvalidMealProductIdException::class);
		
   $randomUuidsStrings = [];
				
   $randomUuidsStrings = [
		'a8c3de3d-1fea-4d7c-b8b0-29f63c4c3451',
		'b8c3de3d-1fee-4d7c-b8b0-29f63c4c3452',
		'c8c3de3d-1fea-4d7c-b8b0-29f63c8c3453',
		'd8c3se3d-1fea-4d7c-b8b0-29f63c4c3454'
   ];
		
  $randomUuidString = $randomUuidsStrings[array_rand($randomUuidsStrings,1)];
		
  $foundMealProduct = ($this->repo)->byId($randomUuidString);
		
} 

public function removeMealProduct(): void
{
  $mealProduct = $this->mealProducts[0];
  
  $uuidString = $mealProduct->id();
		
  ($this->repo)->destroy($uuidString);
		
  $mealProductsArr = [];
		
  $mealProductsArr = ($this->repo)->mealProducts; 
		
  $count = count($mealProductsArr);
 		
  $this->assertSame(2,$count); 		
}
	
public function removeWrongMealProduct(): void
{		
   $this->expectException(InvalidMealProductIdException::class);
			
   $randomUuidsStrings = [];
			
   $randomUuidsStrings = [
    	'a8c3de3d-1fea-4d7c-b8b0-29f63c4c3451',
		'b8c3de3d-1fee-4d7c-b8b0-29f63c4c3452',
		'c8c3de3d-1fea-4d7c-b8b0-29f63c8c3453',
		'd8c3se3d-1fea-4d7c-b8b0-29f63c4c3454'
  ];
		
   $randomUuidString = $randomUuidsStrings[array_rand($randomUuidsStrings,1)];
		
   ($this->repo)->destroy($randomUuidString);
}
	
public function updateMealProductByPrice(): void
{
   $mealProduct = $this->mealProducts[2];
		
   $uuidString = $mealProduct->id();
		
   ($this->repo)->updatePrice($uuidString,39.99);
		
   $changedMealProduct = ($this->repo)->mealProducts[2];
		
   $updatedPriceInt = $changedMealProduct->priceInt(); 
				
   $this->assertSame(3999,$updatedPriceInt); 		
}
    
public function updateMealProductByWrongPrice(): void
{
   $this->expectException(InvalidPriceException::class);
		
   $mealProduct = $this->mealProducts[2];
		
   $uuidString = $mealProduct->id();
		
   ($this->repo)->updatePrice($uuidString,-5);
} 

public function updateMealProductByName(): void
{
   $mealProduct = $this->mealProducts[2];
		
   $uuidString = $mealProduct->id();
		
   ($this->repo)->updateName($uuidString,'Meal9');
		
   $changedMealProduct = ($this->repo)->mealProducts[2];
		
   $updatedName = $changedMealProduct->name(); 
				
   $this->assertSame('Meal9',$updatedName); 		
}
	
public function updateMealProductByWrongName(): void
{
	$this->expectException(InvalidProductNameException::class);
		
	$mealProduct = $this->mealProducts[2];
		
	$uuidString = $mealProduct->id();
		
	($this->repo)->updateName($uuidString,'');		
}
	
 protected function tearDown(): void
 {
   unset($this->mealProducts);
		
   $this->repo = null;
 }
}
