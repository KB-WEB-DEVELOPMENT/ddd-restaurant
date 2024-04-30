<?php
declare(strict_types=1);

namespace Kami\Restaurant\Tests\Infrastructure;

use Kami\Restaurant\Domain\DomainException\InvalidProductNameException;
use Kami\Restaurant\Domain\DomainException\InvalidPriceException;
use Kami\Restaurant\Domain\DomainException\InvalidDrinkProductIdException;

use Kami\Restaurant\Domain\Model\Drink\DrinkProduct;
use Kami\Restaurant\Infrastructure\InMemoryDrinkProductRepository;

use PHPUnit\Framework\TestCase;

final class InMemoryDrinkProductRepositoryTest extends TestCase
{
		// The tests are identical to the tests contained in InMemoryMealProductRepositoryTest.php
}

