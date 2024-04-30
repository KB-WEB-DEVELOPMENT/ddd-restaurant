<?php

declare(strict_types=1);

namespace Kami\Restaurant\Tests\Infrastructure;

use Kami\Restaurant\Domain\DomainException\InvalidProductNameException;
use Kami\Restaurant\Domain\DomainException\InvalidPriceException;
use Kami\Restaurant\Domain\DomainException\InvalidMealProductIdException;

use Kami\Restaurant\Domain\Model\Meal\MealProduct;
use Kami\Restaurant\Infrastructure\DoctrineMealProductRepository;

use PHPUnit\Framework\TestCase;

final class DoctrineMealProductRepositoryTest extends TestCase
{
		
	/* 
	
	1. File specific implementation of all the methods contained in InMemoryMealProductRepositoryTest.php
    2. Those methods make use of all methods contained in DoctrineMealProductRepository.php
	3. Working connection to a MySQL database assumed for testing (see file DoctrineEntityManager.php file)
	
	*/
}

