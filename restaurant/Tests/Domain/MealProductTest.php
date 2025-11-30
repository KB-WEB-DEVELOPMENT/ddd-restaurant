<?php
declare(strict_types=1);

namespace Kami\Restaurant\Tests\Domain;

use Kami\Restaurant\Domain\DomainException\InvalidProductNameException;
use Kami\Restaurant\Domain\DomainException\InvalidPriceException;

use Kami\Restaurant\Domain\Prices\Price;
use Kami\Restaurant\Domain\Meal\MealProduct;

use PHPUnit\Framework\TestCase;

final class MealProductTest extends TestCase
{
	
  public function newProduct(): void
  {
    	$mealProduct = new MealProduct('Meal1',9.99);
		
    	$this->assertContainsOnlyInstancesOf(MealProduct::class,[$mealProduct]);
  }
	
  public function newWrongNameProduct(): void
  {
       $this->expectException(InvalidProductNameException::class);
        
	   $mealProduct = new MealProduct('',9.99);		
  }
	
  public function newWrongPriceProduct(): void
  {
    	$this->expectException(InvalidPriceException::class);
        
    	$mealProduct = new MealProduct('Meal3',-9.99);
  }
		
  public function updatePrice(): void
  {
    $mealProduct = new MealProduct('Meal1',9.99);
			
    $mealProduct->reprice(19.99);
		
    $newPriceInt = $mealProduct->priceInt();
    
    $this->assertSame(1999,$newPriceInt);
  }
	
  public function updateWrongPrice(): void
  {
     $this->expectException(InvalidPriceException::class);
		
     $mealProduct = new MealProduct('Meal1',9.99);
			
     $mealProduct->reprice(-19.99);	
  }

  public function updateName(): void
  {
    $mealProduct = new MealProduct('Meal1',6.99);
			
    $mealProduct->changeName('Meal2');
		
    $newName = $mealProduct->name();
		
    $this->assertSame('Meal2',$newName);
  }
	
  public function updateWrongName(): void
  {
    $this->expectException(InvalidProductNameException::class);
		
    $mealProduct = new MealProduct('Meal1',7.99);
			
    $mealProduct->changeName('');
  }	 
}

