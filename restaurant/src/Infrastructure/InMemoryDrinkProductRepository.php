<?php

namespace Kami\Restaurant\Infrastructure;

use Kami\Restaurant\Domain\DomainException\InvalidProductNameException;
use Kami\Restaurant\Domain\DomainException\InvalidPriceException;
use Kami\Restaurant\Domain\DomainException\InvalidDrinkProductIdException;

use Kami\Restaurant\Domain\Prices\Price;

use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

use Kami\Restaurant\Domain\Model\Drink\DrinkProduct;
use Kami\Restaurant\Domain\Model\Drink\DrinkProductRepository;

final class InMemoryDrinkProductRepository implements DrinkProductRepository
{ 
    private array $drinkProducts = [];
		
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
	
       $cost = is_int($price) ? new Price($price) : new Price((string)$price);
		
       $drinkProduct = new DrinkProduct($name,$cost);
		
       $drinkProduct->id =  Uuid::uuid4();
		
       $this->drinkProducts[] = $drinkProduct; 		
    }		
	
    public static function all(): array
    {
      return $this->drinkProducts;
    }

    /**
    * @throws InvalidDrinkProductIdException
    */
    public function byId(string $drinkProductId): ?DrinkProduct
    {		
      $drinkProductIds = [];
		
      $drinkProductIds = array_map(fn($drinkProduct) => $drinkProduct->id(),$this->drinkProducts);
	
      try {
	if (!in_array($drinkProductId,$drinkProductIds)) {			
           throw new InvalidDrinkProductIdException(); 
	}	
      }
		
      if (($key = array_search($drinkProductId,$drinkProductIds)) !== false) {
	   return ($this->drinkProducts[$key] instanceof DrinkProduct) ? $this->drinkProducts[$key] : null;	
      } 				
   }

    /**
    * @throws InvalidDrinkProductIdException
    */
    public function destroy(string $drinkProductId): void
    {	
	 $drinkProductIds = [];
				
	 $drinkProductIds = array_map(fn($drinkProduct) => $drinkProduct->id(),$this->drinkProducts);
	
	 try {
	   if (!in_array($drinkProductId,$drinkProductIds)) {			
	     throw new InvalidDrinkProductIdException(); 
	   }	
	 }
		
	if (($key = array_search($drinkProductId,$drinkProductIds)) !== false) {		
	   unset($this->drinkProducts[$key]); 
	}			
    }
	
    /**
    * @throws InvalidDrinkProductIdException
    * @throws InvalidProductNameException
    */	
    public function updateName(string $drinkProductId,string $name): void
    {
       $drinkProductIds = [];
		
       $drinkProductIds = array_map(fn($drinkProduct) => $drinkProduct->id(),$this->drinkProducts);
	
       try {
	  if (!in_array($drinkProductId,$drinkProductIds)) {				
	       throw new InvalidDrinkProductIdException(); 
	   }
       	  if (strlen(trim($name)) == 0) {				
	       throw new InvalidProductNameException();
       	  }		
       }
		
       if (($key = array_search($drinkProductId,$drinkProductIds)) !== false) {
		
		$drinkProduct = $this->drinkProducts[$key];
			
		$drinkProduct->name = $name;
			
		$this->drinkProducts[$key] = $drinkProduct; 		
	}		
     }
	
    /**
    * @throws InvalidDrinkProductIdException
    * @throws InvalidPriceException
    */
    public function updatePrice(string $drinkProductId,int|float $price): void
    {		
      $drinkProductIds = [];
		
      $drinkProductIds = array_map(fn($drinkProduct) => $drinkProduct->id(),$this->drinkProducts);
	
      try {
	 if (!in_array($drinkProductId,$drinkProductIds)) {				
	    throw new InvalidDrinkProductIdException(); 
         }
	 if ($price <= 0) {
	    throw new InvalidPriceException();  		
	 }		
      }
		
      if (($key = array_search($dealProductId,$drinkProductIds)) !== false) {	
         
	   $drinkProduct = $this->drinkProducts[$key];
			
	   $drinkProduct->price = is_int($price) ? new Price($price) : new Price((string)$price);;
			
	   $this->drinkProducts[$key] = $drinkProduct; 		
       }
    }
}

