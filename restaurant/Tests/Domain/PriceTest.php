<?php
declare(strict_types=1);

namespace Kami\Restaurant\Tests\Domain;

use Kami\Restaurant\Domain\Prices\Price;

use PHPUnit\Framework\TestCase;

final class PriceTest extends TestCase
{
   public function testIntPrice(): void
   {
     $price = new Price(12345); 
		
     $this->assertContainsOnlyInstancesOf(Price::class,[$price]);		
   }

   public function testFloatPrice(): void
   {
     $price = new Price('12.345');
		
     $this->assertContainsOnlyInstancesOf(Price::class,[$price]);
    }
	
   public function testWrongTypePrice(): void
   {
      $this->expectException(InvalidArgumentException::class);
		 
      $wrongType = []; 
		
      $wrongType = [1,2,3]; 
		
      $price = new Price($wrongType);
	 
    }	
	
    public function testToInt(): void
    {
        $price = new Price('12.345');
	$int = $price->toInt();
	$this->assertSame(12345,$int);
    }
	 
    public function testFormatted(): void
    {
        $price = new Price('12.346');
	$formatted = $price->formatted();
	$this->assertSame('EUR 12.35',$formatted);
    }
	
    public function testAdd(): void
    {
        $price = new Price('3.51');
	$res = $price->add('2.23');
	$int = $res->toInt(); 
	$this->assertSame(574,$int);
    }
	 
    public function testSubtract(): void
    {
	$price = new Price(12345);
	$res = $price->subtract(2);
	$int = $res->toInt(); 
	$this->assertSame(12343,$int);
    }
	 
    public function testMultiply(): void
    {
        $price = new Price('9.99');
	$res = $price->multiplyBy('2.85');
	$int = $res->toInt(); 
	$this->assertSame(284715,$int);
    }
	 
    public function testAddFoodVat(): void
    {
      $price = new Price(1.98);
      $res = $price->withFoodVat();
      $int = $res->toInt();
      $this->assertSame(3762,$int);		
    }
	 
    public function testAddDrinkVat(): void
    {
       $price = new Price(1.75);
       $res = $price->withDrinkVat();
       $int = $res->toInt();
       $this->assertSame(3325,$res);
    }	 
}
