<?php

namespace Kami\Restaurant\Infrastructure\Doctrine;

use Kami\Restaurant\Infrastructure\DoctrineMapping\MealProduct;
use Kami\Restaurant\Infrastructure\DoctrineMapping\DrinkProduct;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

final class DoctrineEntityManager {
		
   private static self|null $instance = null;
	
   private EntityManager $mealEntityManager;
   private EntityManager $drinkEntityManager;
   private array $path = array(__DIR__ . '/../DoctrineMapping');
   private bool $isDevMode = true;
   private string $driver = 'pdo_mysql';
   private string $user ='root';
   private string $password='';
   private string $dbname='restaurant_test';
			
    private function __construct(
    ){		
       $dbParams = [
	  'driver' => $this->driver,
	  'user' => $this->user,
	  'password' => $this->password,
	  'dbname'   => $this->dbname,
       ];
				
	try {			
	   $config = ORMSetup::createAttributeMetadataConfiguration($this->path,$this->isDevMode);
	   $connection = DriverManager::getConnection($dbParams,$config);
	   $this->mealEntityManager = new EntityManager($connection,$config);
	   $this->drinkEntityManager = new EntityManager($connection,$config);			
        }  catch (Exception $e) {
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
	
    private function mealEntityManagerInstance(): EntityManager
    {
        try {
           $entityManager = ($this->mealEntityManager)->getRepository(MealProduct::class);	
	   return $entityManager;	
	} catch (Exception $e) {
          echo $e->getMessage();   
        }	   
    }
	
    private function drinkEntityManagerInstance(): EntityManager
    {
      try {
	 $entityManager = ($this->drinkEntityManager)->getRepository(DrinkProduct::class);	
	 return $entityManager;	
      } catch (Exception $e) {
         echo $e->getMessage();   
      }	   
    }	
}
