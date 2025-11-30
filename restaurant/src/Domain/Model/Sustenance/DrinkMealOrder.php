<?php

namespace Kami\Restaurant\Domain\Model\Sustenance;

use Kami\Restaurant\Domain\DomainException\InvalidMealProductIdException;
use Kami\Restaurant\Domain\DomainException\InvalidDrinkProductIdException;

use Kami\Restaurant\Domain\DomainException\InvalidMealItemIdException;
use Kami\Restaurant\Domain\DomainException\InvalidDrinkItemIdException; 

use Brick\Money\Money;
use Brick\Math\RoundingMode;

use Kami\Restaurant\Domain\Prices\Price;
use Kami\Restaurant\Domain\Prices\VarRate;

use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

use Kami\Restaurant\Domain\Model\Meal\MealItem;
use Kami\Restaurant\Domain\Model\Meal\MealProductRepository;

use Kami\Restaurant\Domain\Model\Drink\DrinkItem;
use Kami\Restaurant\Domain\Model\Drink\DrinkProductRepository;

final class DrinkMealOrder
{ 
    private readonly UuidInterface $id;
    private array $mealItems = [];
    private array $drinkItems = [];
		
    public function __construct(
    	private MealProductRepository $mealProductRepository,
		private DrinkProductRepository $drinkProductRepository
    ) {		 	
		$this->id = Uuid::uuid4();	
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }
	
    public function toString(): string
    {
        return $this->id->toString();
    }
	
    public function addMealItem(MealItem $mealItem): void
    {
       $mealProductId = $mealItem->mealProductId;
	   	   
       $mealProducts  = ($this->mealProductRepository)::all();
	   
       $mealProductIds = [];
	   
       $mealProductIds = array_map(fn($mealProduct) => $mealProduct->id(),$mealProducts);
	   
       if (!in_array($mealProductId,$mealProductIds)) {
       	 throw new InvalidMealProductIdException(); 
       }	
	    
       $this->mealItems[] = $mealItem;
	   
    }
	
    public function addDrinkItem(DrinkItem $drinkItem): void
    {
      $drinkProductId = $drinkItem->drinkProductId;
	   	   
      $drinkProducts = [];   
	    
      $drinkProducts  = ($this->drinkProductRepository)::all();
	   
      $drinkProductIds = [];
	    
      $drinkProductIds = array_map(fn($drinkProduct) => $drinkProduct->id(),$drinkProducts);
	   
      if (!in_array($drinkProductId,$drinkProductIds)) {
	     throw new InvalidDrinkProductIdException(); 
      }	
		
      $this->drinkItems[] = $drinkItem;    
    }
	
    public function removeMealItem(string $mealItemId): void
    {
        $mealItemsIds = [];
		
		$mealItemsIds = array_map(fn($mealItem) => $mealItem->toString(),$this->mealItems);
	   
		if (!in_array($mealItemId,$mealItemsIds)) {
	  		throw new InvalidMealItemIdException();
		}	
				
		if (($key = array_search($mealItemId,$mealItemsIds)) !== false) {
	  		unset($this->mealItems[$key]);
		}	   
    }
	
    public function removeDrinkItem(string $drinkItemId): void
    {
        $drinkItemsIds = [];
		
		$drinkItemsIds = array_map(fn($drinkItem) => $drinkItem->toString(),$this->drinkItems);
	   
		if (!in_array($drinkItemId,$drinkItemsIds)) {
	   		throw new InvalidDrinkItemIdException(); 
		}	
				
		if (($key = array_search($drinkItemId,$drinkItemsIds)) !== false) {
	  		unset($this->drinkItems[$key]);
		}
    }
	
    public function removeAllMealItems(): void
    {	
    	if (count($this->mealItems) > 0) {
	   		unset($this->mealItems);
		}	
    }
	
    public function removeAllDrinkItems(): void
    {	
        if (count($this->drinkItems) > 0) {
	  		unset($this->drinkItems);
		}
    }
	
    public function removeAllSustenanceItems(): void
    {		
	   	$this->removeAllMealItems();
		$this->removeAllDrinkItems(); 
    }	
	
    public function mealItemsCostNoVat(): int
    {
        $foundMealProductIds = [];
		$quantities = [];
		$mealProducts = [];
		$allMealProductIds = [];
		$prices = [];
		$totalCostArray = [];
			
		if (count($this->mealItems) == 0){
	   		return 0;
		} 	
	
		$foundMealProductIds = array_map(fn($mealItem) => $mealItem->mealProductId,$this->mealItems);
		
		$quantities = array_map(fn($mealItem) => $mealItem->quantity,$this->mealItems);
		
		$mealProducts =  ($this->mealProductRepository)::all();	
		
		$allMealProductIds = array_map(fn($mealProduct) => $mealProduct->id(),$mealProducts);
		
		$prices =   array_filter(
						$foundMealProductIds,
						function ($foundMealProductId) {
							foreach ($mealProducts as $mealProduct) {	
								if ($foundMealProductId == $mealProduct->id())
								{
					    			return $mealProduct->priceInt();
								} 
							}								
						}
					); 

		$totalCostArray  =  array_map(function($price,$quantity) {
			     						return $price * $quantity;
			    					},$prices,$quantities);
					
		$totalCost = array_sum($totalCostArray);			
			
		return $totalCost;

    }		
  	
    public function mealItemsCostWithVat(): int
    {
        $costNoVat =  $this->mealItemsCostNoVat();
		
		$costVat = (int)($costNoVat*((float)VarRate::Food));
		
		$sum = $costNoVat + $costVat;
		
		return $sum; 
    }
	
    public function drinkItemsCostNoVat(): int
    {
     	$foundDrinkProductIds = [];
		$quantities = [];
		$drinkProducts = [];
		$allDrinkProductIds = [];
		$prices = [];
		$totalCostArray = [];
		
		if (count($this->drinkItems) == 0){
	   		return 0;
		} 	
	
		$foundDrinkProductIds = array_map(fn($drinkItem) => $drinkItem->drinkProductId,$this->drinkItems);
			
		$quantities = array_map(fn($drinkItem) => $drinkItem->quantity,$this->drinkItems);
				
		$drinkProducts =  ($this->drinkProductRepository)::all();
	
		$allDrinkProductIds = array_map(fn($drinkProduct) => $drinkProduct->id(),$drinkProducts);		
		
		$prices =  array_filter(
							$foundDrinkProductIds,
							function ($foundDrinkProductId) {
								foreach ($drinkProducts as $drinkProduct) {	
									if ($foundDrinkProductId == $drinkProduct->id())
									{
					  					return $drinkProduct->priceInt();
									} 
								}								
							}
		    	); 

		$totalCostArray  =  array_map(function($price,$quantity) {
										return $price * $quantity;
			    			},$prices,$quantities);
					
		$totalCost = array_sum($totalCostArray);			
			
		return $totalCost;
    }		
  	
    public function drinkItemsCostWithVat(): int
    {
        $costNoVat =  $this->drinkItemsCostNoVat();
		
		$costVat = (int)($costNoVat*((float)VarRate::Drink));
		
		$sum = $costNoVat + $costVat;
		
		return $sum; 
    }
	
    public function totalCostInt(): int
    {
        $totalCostInt = $this->mealItemsCostWithVat() + $this->drinkItemsCostWithVat();
		
		return $totalCostInt; 
    }

    public function totalCostFormatted(): string
    {
       $totalCostInt = $this->totalCostInt();
	   
       $totalCost = new Price($totalCostInt);

       return $totalCost->formatted();
    }		
}
