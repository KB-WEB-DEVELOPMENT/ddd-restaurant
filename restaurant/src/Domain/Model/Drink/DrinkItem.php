<?php

namespace Kami\Restaurant\Domain\Model\Drink;

use Kami\Restaurant\Domain\DomainException\InvalidDrinkProductIdException;
use Kami\Restaurant\Domain\DomainException\InvalidQuantityException;

use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

final class DrinkItem
{ 
    private readonly UuidInterface $id;
	
    public function __construct( 	
		private readonly string $drinkProductId,
		private int $quantity
	)
	
    {
		try {
			if (strlen(trim($drinkProductId)) == 0) {
				throw new InvalidDrinkProductIdException();
			}
			if ($quantity <1) {
				throw new InvalidQuantityException();  
			}
		}	
		
		$this->drinkProductId = $drinkProductId;
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
