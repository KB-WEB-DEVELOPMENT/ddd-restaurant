<?php
declare(strict_types=1);

namespace Kami\Restaurant\Tests\Domain;

use Kami\Restaurant\Domain\DomainException\InvalidMealProductIdException;
use Kami\Restaurant\Domain\DomainException\InvalidQuantityException;

use Kami\Restaurant\Domain\Model\Meal\MealItem;

use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

use PHPUnit\Framework\TestCase;

final class MealItemTest extends TestCase
{
	public function newMealItem(): void
    {
		$mealItem = new MealItem('111111',2);
		
		$this->assertContainsOnlyInstancesOf(MealItem::class,[$mealItem]);

    }
	
	public function wrongNewMealItemProductId(): void
    {
		$this->expectException(InvalidMealProductIdException::class);
		
		$mealItem = new MealItem('',2);
    }
	public function wrongNewMealItemQuantity(): void
    {
		$this->expectException(InvalidQuantityException::class);
		
		$mealItem = new MealItem('111111',-3);	
    }
	
	public function testUuidObject(): void
    {
		$mealItem = new MealItem('111111',2);
		$uuidObject = $mealItem->id();
		$this->assertTrue($uuidObject instanceof TestInterface);
    }
	
	public function testUuidString(): void
    {
		$mealItem = new MealItem('333333',1);
		
		$fromMethod = $mealItem->toString();
		
		$id = $mealItem->id();
		
		$fromUuid = $id->toString(); 

		$this->assertSame($fromMethod,$fromUuid);			
	
	}
	
	public function testQuantity(): void
    {
		$mealItem = new MealItem('101010',2);
		
		$quantity = $mealItem->quantity();
		
		$this->assertSame(2,$quantity);
    }
}