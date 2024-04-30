<?php

namespace Kami\Restaurant\Domain\Model\Meal;

use Kami\Restaurant\Domain\DomainException\InvalidMealProductIdException;
use Kami\Restaurant\Domain\DomainException\InvalidProductNameException;
use Kami\Restaurant\Domain\DomainException\InvalidPriceException;

interface MealProductRepository
{ 

	/**
    * @throws InvalidProductNameException
 	* @throws InvalidPriceException
    */	
	public function store(string $name,int|float $price): void 
	
	public static function all(): array

    /**
    * @throws InvalidMealProductIdException
    */
    public function byId(string $mealProductId): ?MealProduct

    /**
    * @throws InvalidMealProductIdException
    */
	public function destroy(string $mealProductId): void
	
	/**
    * @throws InvalidMealProductIdException
 	* @throws InvalidProductNameException
    */	
	public function updateName(string $mealProductId,string $name): void
	
	/**
    * @throws InvalidMealProductIdException
 	* @throws InvalidPriceException
    */		
	public function updatePrice(string $mealProductId,int|float $price): void
		
}