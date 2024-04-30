<?php
declare(strict_types=1);

namespace Kami\Restaurant\Tests\Domain;

use Kami\Restaurant\Domain\DomainException\InvalidDrinkProductIdException;
use Kami\Restaurant\Domain\DomainException\InvalidQuantityException;

use Kami\Restaurant\Domain\Model\Drink\DrinkItem;

use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

use PHPUnit\Framework\TestCase;

final class DrinkItemTest extends TestCase
{
	// The tests are identical to the ones in MealItemTest.php
}
