<?php

declare(strict_types=1);

namespace Kami\Restaurant\Tests\Infrastructure;

use Kami\Restaurant\Domain\DomainException\InvalidProductNameException;
use Kami\Restaurant\Domain\DomainException\InvalidPriceException;
use Kami\Restaurant\Domain\DomainException\InvalidDrinkProductIdException;

use Kami\Restaurant\Domain\Model\Drink\DrinkProduct;
use Kami\Restaurant\Infrastructure\SqlDrinkProductRepository;

use PHPUnit\Framework\TestCase;

final class SqlDrinkProductRepositoryTest extends TestCase
{
		
	/* 
	
	1. File specific implementation of all the methods contained in InMemoryDrinkProductRepositoryTest.php
    2. Those methods make use of all methods contained in SqlDrinkProductRepository.php
	3. Working connection to a MySQL database assumed for testing.
	
	*/
}


