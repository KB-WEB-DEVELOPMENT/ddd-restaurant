<?php

namespace Kami\Restaurant\Domain\Model\Meal;

use Kami\Restaurant\Domain\DomainException\InvalidMealProductIdException;
use Kami\Restaurant\Domain\DomainException\InvalidQuantityException;

use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

final class MealItem
{ 
    private readonly UuidInterface $id;
	
    public function __construct( 	
	private readonly string $mealProductId,
	private int $quantity
    ) {
	if (strlen(trim($mealProductId)) == 0) {
	    throw new InvalidMealProductIdException();
	}
	if ($quantity <1) {
	    throw new InvalidQuantityException();
	}
			
	$this->mealProductId = $mealProductId;
	$this->quantity = $quantity; 	
	$this->id = Uuid::uuid4();
   }

    public function id(): UuidInterface
    {
        return $this->id;
    }
	
    public function toString(): string
    {
        return ($this->id)->toString();
    }
		
    public function quantity(): int
    {
        return $this->quantity;
    }
}
