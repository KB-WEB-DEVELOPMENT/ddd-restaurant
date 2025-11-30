<?php

namespace Kami\Restaurant\Domain\Model\Meal;

use Kami\Restaurant\Domain\DomainException\InvalidProductNameException;
use Kami\Restaurant\Domain\DomainException\InvalidPriceException;

use Kami\Restaurant\Domain\Prices\Price;

use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

final class MealProduct
{ 
    private readonly string $id;
    private Price $cost;
 
    public function __construct( 
		private string $name,
		private int|float $cost 
    ) {	 
		if (strlen(trim($name)) == 0) {
	   	throw new InvalidProductNameException();
		}
        
		if ($cost <= 0) {
	   		throw new InvalidPriceException();  
		}	
		
		$this->name = $name;
		$this->cost = is_int($cost) ? new Price($cost) : new Price((string)$cost);
		$uuid = Uuid::uuid4();
		$this->id = $uuid->toString();
    }

    public function id(): string
    {
        return $this->id;
    }
		
    public function name(): string
    {
        return $this->name;
    }
	
    public function priceInt(): int
    {
        return ($this->cost)->toInt();
    }

    public function priceFormatted(): string
    {
        return ($this->cost)->formatted();
    }
	
    public function changeName(string $name): void
    {
		if (strlen(trim($name)) == 0) {
	   		throw new InvalidProductNameException();
		}	
		
		$this->name = $name;		
    }

    public function reprice(int|float $cost): void
    {
       if ($cost <= 0) {
	   		throw new InvalidPriceException(); 
       }	
       	
       unset($this->cost);
		
       $this->cost = is_int($cost) ? new Price($cost) : new Price((string)$cost);	
    }
}

