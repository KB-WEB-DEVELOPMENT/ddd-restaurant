<?php
namespace Kami\Restaurant\Infrastructure;

use Kami\Restaurant\Domain\DomainException\InvalidProductNameException;
use Kami\Restaurant\Domain\DomainException\InvalidPriceException;
use Kami\Restaurant\Domain\DomainException\InvalidDrinkProductIdException;

use Kami\Restaurant\Domain\Prices\Price;

use Kami\Restaurant\Domain\Model\Drink\DrinkProduct;

use Kami\Restaurant\Domain\Model\Drink\DrinkProductRepository;

use Kami\Restaurant\Infrastructure\Doctrine\DoctrineEntityManager;


final class DoctrineDrinkProductRepository implements DrinkProductRepository
{ 		
   public function __construct(){	   
   }
		
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
			
	$drinkProduct = new DrinkProduct($name,$costInt);
				
	$doctrine = DoctrineEntityManager::getInstance();
		
	$entityManager = $doctrine->drinkEntityManagerInstance();
		
	$entityManager->persist($drinkProduct);
		
	$entityManager->flush();		 
      }		
	
    public static function all(): array
    {
       $drinkProducts = [];
		
       $doctrine = DoctrineEntityManager::getInstance();
		
       $entityManager = $doctrine->drinkEntityManagerInstance();
		
       $drinkProducts = $entityManager->findAll();
				
       return is_array($drinkProducts) ? $drinkProducts : [];
		
    }

    /**
    * @throws InvalidDrinkProductIdException
    */
    public function byId(string $drinkProductId): ?DrinkProduct
    {
      try {
	 if (strlen(trim($drinkProductId)) == 0) {
	    throw new InvalidDrinkProductIdException();	
	}
      }
		
      $doctrine = DoctrineEntityManager::getInstance();
		
      $entityManager = $doctrine->drinkEntityManagerInstance();
		
      $drinkProduct = $entityManager->find('DrinkProduct',$drinkProductId);
			
      return (count($drinkProduct) == 1) ? $drinkProduct : null;
    }

    /**
    * @throws InvalidDrinkProductIdException
    */
     public function destroy(string $drinkProductId): void
     {	
	try {
		if (strlen(trim($drinkProductId)) == 0) {
			throw new InvalidDrinkProductIdException();	
		}
	}
		
	$doctrine = DoctrineEntityManager::getInstance();
		
	$entityManager = $doctrine->drinkEntityManagerInstance();
		
	$drinkProduct = $entityManager->find('DrinkProduct',$drinkProductId);
		
	if (count($drinkProduct) == 1) {
		$entityManager->remove($drinkProduct);
		$entityManager->flush();	
	}			
      }
	
    /**
    * @throws InvalidDrinkProductIdException
    * @throws InvalidProductNameException
    */	
     public function updateName(string $drinkProductId,string $name): void
     {
	  try {
	    if (strlen(trim($drinkProductId)) == 0) {
	      throw new InvalidDrinkProductIdException();	
	     }
	     if (strlen(trim($name)) == 0) {
	        throw new InvalidProductNameException();
	     }			
	  }
			
	  $drinkProduct = $this->byId($drinkProductId);

	  if (count($drinkProduct) == 1) {
	 	$drinkProduct->setName($name);
	  }				
     }
	
    /**
    * @throws InvalidDrinkProductIdException
    * @throws InvalidPriceException
    */
    public function updatePrice(string $drinkProductId,int|float $price): void
    {
      try {
         if (strlen(trim($drinkProductId)) == 0) {
	   throw new InvalidDrinkProductIdException();	
         }
         if ($price <= 0) {
	   throw new InvalidPriceException();  
	 }			
      }
		
      $drinkProduct = $this->byId($drinkProductId);
		
      if (count($drinkProduct) == 1) {
			
	   $cost = is_int($price) ? new Price($price) : new Price((string)$price);

	   $costInt =  $cost->toInt();
			
	   $drinkProduct->setCost($costInt);
       }			
  }
}


