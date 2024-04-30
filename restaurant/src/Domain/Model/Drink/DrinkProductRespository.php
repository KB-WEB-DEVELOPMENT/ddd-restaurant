<?php

namespace Kami\Restaurant\Domain\Model\Drink;

use Kami\Restaurant\Domain\DomainException\InvalidDrinkProductIdException;
use Kami\Restaurant\Domain\DomainException\InvalidProductNameException;
use Kami\Restaurant\Domain\DomainException\InvalidPriceException;

interface DrinkProductRepository
{ 
    /**
    * @throws InvalidProductNameException
    * @throws InvalidPriceException
    */	
     public function store(string $name,int|float $price): void 
	
     public static function all(): array
	
    /**
    * @throws InvalidDrinkProductIdException
    */
    public function byId(string $drinkProductId): ?DrinkProduct

    /**
    * @throws InvalidDrinkProductIdException
    */	
    public function destroy(string $drinkProductId): void
	
    /**
    * @throws InvalidDrinkProductIdException
    * @throws InvalidProductNameException
    */
     public function updateName(string $drinkProductId,string $name): void
	
    /**
    * @throws InvalidDrinkProductIdException
    * @throws InvalidPriceException
    */
    public function updatePrice(string $drinkProductId,int|float $price): void		
}
