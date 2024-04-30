<?php

namespace Kami\Restaurant\Domain\Prices;

use Brick\Money\Money;
use Brick\Math\RoundingMode;

final class Price
{
    private Money $money;
	
	public function __construct(
		private readonly string|int $amount
	)
	
    {
        $this->money = Money::of($amount,'EUR',roundingMode:RoundingMode::UP); 
    }
		
	public function add(string|int $amount): Money
    {
       return $this->money->plus($amount);
    }

    public function subtract(string|int $amount): Money
    {
       return $this->money->minus($amount);
    }	
	
    public function multiplyBy(string|int $amount): Money
    {
       return $this->money->multipliedBy($amount);
    } 	
	
	public function withFoodVat(): Money
    {
       return $this->money->multipliedBy(VatRate::Food);
    }
	
    public function withDrinkVat(): Money
    {
       return $this->money->multipliedBy(VatRate::Drink);
    }

	public static function toInt(): int
    {
      return $this->money->getMinorAmount()->toInt();   
	}
	
	public static function formatted(): string
    {
       return $this->money->to($this->money->getContext(),RoundingMode::UP);
    }
   	
}
