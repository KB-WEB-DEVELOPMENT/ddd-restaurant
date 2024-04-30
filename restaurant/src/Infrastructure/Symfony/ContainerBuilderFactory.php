<?php
declare(strict_types=1);

namespace Kami\Restaurant\Infrastructure\Symfony

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;

use Kami\Restaurant\Infrastructure\Doctrine\DoctrineEntityManager;

use Kami\Restaurant\Infrastructure\Sql\SqlConnectionManager;

use Kami\Restaurant\Infrastructure\DoctrineDrinkProductRepository;
use Kami\Restaurant\Infrastructure\DoctrineMealProductRepository;
use Kami\Restaurant\Infrastructure\InMemoryDrinkProductRepository;
use Kami\Restaurant\Infrastructure\InMemoryMealProductRepository;
use Kami\Restaurant\Infrastructure\SqlDrinkProductRepository;
use Kami\Restaurant\Infrastructure\SqlMealProductRepository;

use Kami\Restaurant\Application\UseCaseApplication;

use Kami\Restaurant\Domain\Model\Drink\DrinkProductRepository;
use Kami\Restaurant\Domain\Model\Meal\MealProductRepository;

final class ContainerBuilderFactory
{
    public static function create(): ContainerBuilder 
	{
        $containerBuilder = new ContainerBuilder();

        $containerBuilder
            ->register(SqlConnectionManager::class,SqlConnectionManager::class)
			->setFactory([SqlConnectionManager::class,'createMealProductTable']);
            ->setFactory([SqlConnectionManager::class,'createMealProductTable']);
			
        $containerBuilder
            ->register(DoctrineEntityManager::class,DoctrineEntityManager::class)
            ->setFactory([DoctrineEntityManager::class,'mealEntityManagerInstance'])
            ->setAutowired(true);
			
		$containerBuilder
            ->register(DoctrineEntityManager::class,DoctrineEntityManager::class)
            ->setFactory([DoctrineEntityManager::class,'drinklEntityManagerInstance'])
            ->setAutowired(true);

        $containerBuilder
            ->register(MealProductRepository::class,InMemoryMealProductRepository::class)
            ->setAutowired(true);
			
		/* 
		
		We could also register the following repositories in the same way: 	

			InMemoryMealProductRepository, InMemoryDrinkProductRepository,SqlMealProductRepository,SqlDrinkProductRepository,
			DoctrineMealProductRepository,DoctrineDrinkProductRepository		

		*/
		
        $containerBuilder
            ->register(UseCaseApplication::class,UseCaseApplication::class)
            ->setAutowired(true)
            ->setPublic(true);

        $containerBuilder->compile();

        return $containerBuilder;
    }

    public static function createInMemory(): ContainerBuilder
	{
        $containerBuilder = new ContainerBuilder();

        $containerBuilder->register(MealProductRepository::class,InMemoryMealProductRepository::class);
		// after testing above line works, also needed : $containerBuilder->register(DrinkProductRepository::class,InMemoryDrinkProductRepository::class);
		
        $containerBuilder->compile();

        return $containerBuilder;
    }
}
