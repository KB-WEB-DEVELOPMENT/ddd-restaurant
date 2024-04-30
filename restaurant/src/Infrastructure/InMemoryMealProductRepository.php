<?php

namespace Kami\Restaurant\Infrastructure;

use Kami\Restaurant\Domain\DomainException\InvalidProductNameException;
use Kami\Restaurant\Domain\DomainException\InvalidPriceException;
use Kami\Restaurant\Domain\DomainException\InvalidMealProductIdException;

use Kami\Restaurant\Domain\Prices\Price;

use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

use Kami\Restaurant\Domain\Model\Meal\MealProduct;
use Kami\Restaurant\Domain\Model\Meal\MealProductRepository;

final class InMemoryMealProductRepository implements MealProductRepository
{ 
    private array $mealProducts = [];
		
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
		
	$mealProduct = new MealProduct($name,$cost);
		
	$this->mealProducts[] = $mealProduct; 	
     }		
	
    public static function all(): array
    {
	return $this->mealProducts;
    }

    /**
    * @throws InvalidMealProductIdException
    */
    public function byId(string $mealProductId): ?MealProduct
    {		
        $mealProductIds = [];
		
	$mealProductIds = array_map(fn($mealProduct) => $mealProduct->id(),$this->mealProducts);
	
	try {
	   if (!in_array($mealProductId,$mealProductIds)) {			
	      throw new InvalidMealProductIdException(); 
	    }	
        }
		
	if (($key = array_search($mealProductId,$mealProductIds)) !== false) {		
		return ($this->mealProducts[$key] instanceof MealProduct) ? $this->mealProducts[$key] : null;
	} 	
      }

    /**
    * @throws InvalidMealProductIdException
    */
    public function destroy(string $mealProductId): void
    {			
       $mealProductIds = [];
		
       $mealProductIds = array_map(fn($mealProduct) => $mealProduct->id(),$this->mealProducts);
	
       try {
          if (!in_array($mealProductId,$mealProductIds)) {				
	     throw new InvalidMealProductIdException(); 
	   }	
       }
		
       if (($key = array_search($mealProductId,$mealProductIds)) !== false) {		
	  unset($this->mealProducts[$key]);
       }			
     }
	
    /**
    * @throws InvalidMealProductIdException
    * @throws InvalidProductNameException
    */	
    public function updateName(string $mealProductId,string $name): void
    {
       $mealProductIds = [];
		
       $mealProductIds = array_map(fn($mealProduct) => $mealProduct->id(),$this->mealProducts);
	
	try {
	   if (!in_array($mealProductId,$mealProductIds)) {				
	      throw new InvalidMealProductIdException(); 
	   }

	   if (strlen(trim($name)) == 0) {				
	     throw new InvalidProductNameException();
	   }		
        }
		
	if (($key = array_search($mealProductId,$mealProductIds)) !== false) {
		
		$mealProduct = $this->mealProducts[$key];
			
		$mealProduct->name = $name;
			
		$this->mealProducts[$key] = $mealProduct; 		
	}		
     }
	
    /**
    * @throws InvalidMealProductIdException
    * @throws InvalidPriceException
    */
    public function updatePrice(string $mealProductId,int|float $price): void
    {
	$mealProductIds = [];
		
	$mealProductIds = array_map(fn($mealProduct) => $mealProduct->id(),$this->mealProducts);
	
	try {
	   if (!in_array($mealProductId,$mealProductIds)) {				
	      throw new InvalidMealProductIdException(); 
	   }
           if ($price <= 0) {				
	      throw new InvalidPriceException();  
	   }		
	}
		
	if (($key = array_search($mealProductId,$mealProductIds)) !== false) {
		
		$mealProduct = $this->mealProducts[$key];
			
		$mealProduct->price = is_int($price) ? new Price($price) : new Price((string)$price);;
			
		$this->mealProducts[$key] = $mealProduct; 		
	}
     }
}
