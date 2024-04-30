<?php

namespace Kami\Restaurant\Infrastructure;

use Kami\Restaurant\Domain\DomainException\InvalidProductNameException;
use Kami\Restaurant\Domain\DomainException\InvalidPriceException;
use Kami\Restaurant\Domain\DomainException\InvalidMealProductIdException;

use Kami\Restaurant\Domain\Prices\Price;

use Kami\Restaurant\Domain\Model\Meal\MealProduct;

use Kami\Restaurant\Domain\Model\Meal\MealProductRepository;

use Kami\Restaurant\Infrastructure\Doctrine\DoctrineEntityManager;

final class DoctrineMealProductRepository implements MealProductRepository
{ 		
	public function __construct(		
		
	){}
		
    /**
    * @throws InvalidProductNameException
 	* @throws InvalidPriceException
    */	
	public function store(string $name,int|float $price): void
    {			
		try {
			if (strlen(trim($name)) == 0) {
				throw new InvalidProductNameException();
			}
			if ($price <= 0) {
				throw new InvalidPriceException();  
			}	
		}
		
		$cost = is_int($price) ? new Price($cost) : new Price((string)$cost);
			
		$costInt = $cost->toInt(); 	
			
		$mealProduct = new MealProduct($name,$costInt);
		
		$doctrine = DoctrineEntityManager::getInstance();
		
		$entityManager = $doctrine->mealEntityManagerInstance();
								
		$entityManager->persist($mealProduct);
		
		$entityManager->flush();
			
	}		
	
	public static function all(): array
    {
		$mealProducts = [];
		
		$doctrine = DoctrineEntityManager::getInstance();
		
		$entityManager = $doctrine->mealEntityManagerInstance();
		
		$mealProducts = $entityManager->findAll();
				
		return is_array($mealProducts) ? $mealProducts : [];
	}

    /**
    * @throws InvalidMealProductIdException
    */
    public function byId(string $mealProductId): ?MealProduct
    {	
		try {
			if (strlen(trim($mealProductId)) == 0) {
				throw new InvalidMealProductIdException();	
			}
		}
		
		$doctrine = DoctrineEntityManager::getInstance();
		
		$entityManager = $doctrine->mealEntityManagerInstance();
		
		$mealProduct = $entityManager->find('MealProduct',$mealProductId);
			
		return (count($mealProduct) == 1) ? $mealProduct : null;
	}

    /**
    * @throws InvalidMealProductIdException
    */
	public function destroy(string $mealProductId): void
	{	

		try {
			if (strlen(trim($mealProductId)) == 0) {
				throw new InvalidMealProductIdException();	
			}
		}
		
		$doctrine = DoctrineEntityManager::getInstance();
		
		$entityManager = $doctrine->mealEntityManagerInstance();
		
		$mealProduct = $entityManager->find('MealProduct',$mealProductId);
		
		if (count($mealProduct) == 1) {
			$entityManager->remove($mealProduct);
			$entityManager->flush();	
		}		
	}
	
	/**
    * @throws InvalidMealProductIdException
 	* @throws InvalidProductNameException
    */	
	public function updateName(string $mealProductId,string $name): void
	{	
		try {
			if (strlen(trim($mealProductId)) == 0) {
				throw new InvalidMealProductIdException();	
			}
			if (strlen(trim($name)) == 0) {
				throw new InvalidProductNameException();
			}			
		}
			
		$mealProduct = $this->byId($mealProductId);

		if (count($mealProduct) == 1) {
			$mealProduct->setName($name);
		}		
	}
	
	/**
    * @throws InvalidMealProductIdException
 	* @throws InvalidPriceException
    */
	public function updatePrice(string $mealProductId,int|float $price): void
    {
		try {
			if (strlen(trim($mealProductId)) == 0) {
				throw new InvalidMealProductIdException();	
			}
			if ($price <= 0) {
				throw new InvalidPriceException();  
			}			
		}
		
		$mealProduct = $this->byId($mealProductId);
		
		if (count($mealProduct) == 1) {
			
			$cost = is_int($price) ? new Price($price) : new Price((string)$price);

			$costInt =  $cost->toInt();
			
			$mealProduct->setCost($costInt);
		}	
		
	}
}


