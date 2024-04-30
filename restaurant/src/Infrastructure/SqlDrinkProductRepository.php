<?php
namespace Kami\Restaurant\Infrastructure;

use Kami\Restaurant\Domain\DomainException\InvalidProductNameException;
use Kami\Restaurant\Domain\DomainException\InvalidPriceException;
use Kami\Restaurant\Domain\DomainException\InvalidDrinkProductIdException;

use Kami\Restaurant\Domain\Prices\Price;

use Kami\Restaurant\Domain\Model\Drink\DrinkProduct;
use Kami\Restaurant\Domain\Model\Drink\DrinkProductRepository;

use Kami\Restaurant\src\Infrastructure\Sql\SqlConnectionManager;

final class SqlDrinkProductRepository implements DrinkProductRepository
{ 		
	public function __construct(
			private SqlConnectionManager $sqlConnectionManager; 	
	){
		
		$sqlConnectionManager = SqlConnectionManager::getInstance();
		
		$this->sqlConnectionManager = $sqlConnectionManager
		
		($this->sqlConnectionManager)->createDrinkProductTable();
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
		
		$sql = "INSERT INTO DrinkProductTable (name,price) VALUES (?,?)";
		
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
		$drinkProductsArray = [];
		
		try {
		
			$drinkProductsArray = ($this->sqlConnectionManager)->query("SELECT * FROM DrinkProductTable")->fetchAll();
		
		}   catch (\PDOException $e)  {
                echo $e->getMessage();   
            }
			
		return $drinkProductsArray;
	}

    /**
    * @throws InvalidDrinkProductIdException
    */
    public function byId(string $drinkProductId): ?DrinkProduct
    {
		$drinkProduct = null;
		
		try {
			if (strlen(trim($drinkProductId)) == 0) {
				throw new InvalidDrinkProductIdException();	
			}
		}
		
		$sql = "SELECT * FROM DrinkProductTable WHERE id=?";
				
		$params = [];
		
		$params = [$drinkProductId];
		
		try {
			$statement = ($this->sqlConnectionManager)->executeStatement($sql,$params);

			$drinkProduct = $statement->fetchObject('DrinkProduct');
		
		}   catch (\PDOException $e)  {
                echo $e->getMessage();   
            }

		return ($drinkProduct instanceof DrinkProduct) ? $drinkProduct : null;		
	}

    /**
    * @throws InvalidDrinkProductIdException
    */
	public function destroy(string $drinkProductId): void
	{	
		try {
			if (strlen(trim($drinkProductId)) == 0) {
				throw new InvalidDrinkProductIdException();	
			}
		}
		
		$sql = "DELETE FROM DrinkProductTable WHERE id=?";
				
		$params = [];
		
		$params = [$drinkProductId];
		
		try {
			$statement = ($this->sqlConnectionManager)->executeStatement($sql,$params);
		
		}   catch (\PDOException $e)  {
                echo $e->getMessage();   
            }	
	}
	
	/**
    * @throws InvalidDrinkProductIdException
 	* @throws InvalidProductNameException
    */	
	public function updateName(string $drinkProductId,string $name): void
	{	
		try {
			if (strlen(trim($drinkProductId)) == 0) {
				throw new InvalidDrinkProductIdException();
			}
			if (strlen(trim($name)) == 0) {
				throw new InvalidProductNameException();
			}	
		}
		
		$sql =  "UPDATE DrinkProductTable SET name=? WHERE id=?";
				
		$params = [];
		
		$params = [$name,$drinkProductId];
		
		try {
			$statement = ($this->sqlConnectionManager)->executeStatement($sql,$params);
		
		}   catch (\PDOException $e)  {
                echo $e->getMessage();   
            }		
	}
	
	/**
    * @throws InvalidDrinkProductIdException
 	* @throws InvalidPriceException
    */
	public function updatePrice(string $drinkProductId,int|float $price): void
    {
		try {
			if (strlen(trim($drinkProductId)) == 0) {
				throw new InvalidDrinkProductIdException();
			}
			if ($price <= 0) {
				throw new InvalidPriceException();  
			}	
		}
		
		$sql =  "UPDATE DrinkProductTable SET price=? WHERE id=?";
				
		$cost = is_int($price) ? new Price($price) : new Price((string)$price);
		
		$costInt = $cost->priceInt();
		
		$params = [];
		
		$params = [$costInt,$drinkProductId];
		
		try {
			$statement = ($this->sqlConnectionManager)->executeStatement($sql,$params);
		
		}   catch (\PDOException $e)  {
                echo $e->getMessage();   
            }
	}

}