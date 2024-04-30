<?php

namespace Kami\Restaurant\Infrastructure\Sql;

final class SqlConnectionManager {
	
   private static self|null $instance = null;
	
   private ?PDO $connection = null;
	
   private function __construct(
	private ?string $dbhost = 'localhost';
	private ?string $dbname = 'restaurant_test';
	private ?string $username = 'root';
	private ?string $password  = '';
   ){		
	try {
	    $this->connection = new PDO("mysql:host={$this->dbhost};dbname={$this->dbname};",$this->username,$this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);			
        }   catch (\PDOException $e) {
              echo $e->getMessage();   
        }		
   }
	
    private static function getInstance(): static
    {
        if (static::$instance === null) {
            static::$instance = new static;
        }
        return static::$instance;
    }
		
    private function createMealProductTable(): void 
    {				
       try {
	  $statement = $this->connection->prepare('DROP TABLE IF EXISTS `MealProductTable`'); 
	  $statement->execute();
				
	} catch (\PDOException $e) {
	  echo $e->getMessage();
	}
		
	try {					
	   $statement = $this->connection->prepare('CREATE TABLE IF NOT EXISTS `MealProductTable` (
	                                                                           `id` varchar(255) NOT NULL UNIQUE,
										   `name` varchar(255) NOT NULL,
										   `price` int(11) unsigned NOT NULL UNSIGNED)'
						   );
	   $statement->execute(); 
	 } catch (\PDOException $e) {
		echo $e->getMessage();
	}	
     }

     private function createDrinkProductTable(): void 
     {
        try {
	  $statement = $this->connection->prepare('DROP TABLE IF EXISTS `DrinkProductTable`'); 
	  $statement->execute();			
	} catch (\PDOException $e) {
	  echo $e->getMessage();
	}
	
	try {					
	    $statement = $this->connection->prepare('CREATE TABLE IF NOT EXISTS `DrinkProductTable` (
											`id` varchar(255) NOT NULL UNIQUE,
											`name` varchar(255) NOT NULL,
											`price` int(11) unsigned NOT NULL UNSIGNED)'
						   );
	    $statement->execute(); 

	} catch (\PDOException $e) {
	  echo $e->getMessage();
	}	
     }

     private function executeStatement(string $statement,array $parameters): void
     {
        try {			
          $stmt = $this->connection->prepare($statement);
          $stmt->execute($parameters);			
        }  catch (\PDOException $e)  {
           echo $e->getMessage();   
        }		
    }	
}	
