<?php

namespace Kami\Restaurant\Infrastructure;

use Kami\Restaurant\Domain\DomainException\InvalidProductNameException;
use Kami\Restaurant\Domain\DomainException\InvalidPriceException;
use Kami\Restaurant\Domain\DomainException\InvalidMealProductIdException;

use Kami\Restaurant\Domain\Prices\Price;

use Kami\Restaurant\Domain\Model\Meal\MealProduct;
use Kami\Restaurant\Domain\Model\Meal\MealProductRepository;

use Kami\Restaurant\src\Infrastructure\Sql\SqlConnectionManager;

final class SqlMealProductRepository implements MealProductRepository
{ 		
	public function __construct(		
		private SqlConnectionManager $sqlConnectionManager; 
	){
		$sqlConnectionManager = SqlConnectionManager::getInstance();
		
		$this->sqlConnectionManager = $sqlConnectionManager
		
		($this->sqlConnectionManager)->createMealProductTable();
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
		
		$sql = "INSERT INTO MealProductTable (name,price) VALUES (?,?)";
		
		$cost = is_int($price) ? new Price($price) : new Price((string)$price);
		
		$costInt = $cost->priceInt();
		
		$params = [];
		
		$params = [$name,$costInt];
		
		try {
			$statement = ($this->sqlConnectionManager)->executeStatement($sql,$params);
		
		}   catch (\PDOException $e)  {
                echo $e->getMessage();   
            }				
	}		
	
	public static function all(): array
    {
		$mealProductsArray = [];
		
		try {
		
			$mealProductsArray = ($this->sqlConnectionManager)->query("SELECT * FROM MealProductTable")->fetchAll();
		
		}   catch (\PDOException $e)  {
                echo $e->getMessage();   
            }
			
		return $mealProductsArray;
	}

    /**
    * @throws InvalidMealProductIdException
    */
    public function byId(string $mealProductId): ?MealProduct
    {
		$mealProduct = null;
		
		try {
			if (strlen(trim($mealProductId)) == 0) {
				throw new InvalidMealProductIdException();	
			}
		}
		
		$sql = "SELECT * FROM MealProductTable WHERE id=?";
				
		$params = [];
		
		$params = [$mealProductId];
		
		try {
			$statement = ($this->sqlConnectionManager)->executeStatement($sql,$params);

			$mealProduct = $statement->fetchObject('MealProduct');
		
		}   catch (\PDOException $e)  {
                echo $e->getMessage();   
            }

		return ($mealProduct instanceof MealProduct) ? $mealProduct : null;		
	}

    /**
    * @throws InvalidMealProductIdException
    */
	public function destroy(string $mealProductId): void
	{	
		try {
			if (strlen(trim($mealProductId)) == 0) {
				throw new InvalidMealProductIdException();	
			}
		}
		
		$sql = "DELETE FROM MealProductTable WHERE id=?";
				
		$params = [];
		
		$params = [$mealProductId];
		
		try {
			$statement = ($this->sqlConnectionManager)->executeStatement($sql,$params);
		
		}   catch (\PDOException $e)  {
                echo $e->getMessage();   
            }	
	}
	
	/**
    * @throws InvalidMealProductIdException
 	* @throws InvalidProductNameException
    */	
	public function updateName(string $mealProductId,string $name): void
	{	
		try {
			if (strlen(trim($mealProductId)) == 0) {
				throw new InvalidMealProductIdException();
			}
			if (strlen(trim($name)) == 0) {
				throw new InvalidProductNameException();
			}	
		}
		
		$sql =  "UPDATE MealProductTable SET name=? WHERE id=?";
				
		$params = [];
		
		$params = [$name,$mealProductId];
		
		try {
			$statement = ($this->sqlConnectionManager)->executeStatement($sql,$params);
		
		}   catch (\PDOException $e)  {
                echo $e->getMessage();   
            }		
	}
	
	/**
    * @throws InvalidMealProductIdException
 	* @throws InvalidPriceException
    */
	public function updatePrice(string $mealProductId,int|float $price): void
    {
		try {
			if (strlen(trim($mealProductId)) == 0) {
				throw new InvalidMealProductIdException();
			}
			if ($price <= 0) {
				throw new InvalidPriceException();  
			}	
		}
		
		$sql =  "UPDATE MealProductTable SET price=? WHERE id=?";
				
		$cost = is_int($price) ? new Price($price) : new Price((string)$price);
		
		$costInt = $cost->priceInt();
		
		$params = [];
		
		$params = [$costInt,$mealProductId];
		
		try {
			$statement = ($this->sqlConnectionManager)->executeStatement($sql,$params);
		
		}   catch (\PDOException $e)  {
                echo $e->getMessage();   
            }
	}

}