<?php

namespace Kami\Restaurant\Example

use Kami\Restaurant\Infrastructure\Symfony\ContainerBuilderFactory;

use Kami\Restaurant\Infrastructure\InMemoryMealProductRepository;

require_once __DIR__ . '/../vendor/autoload.php';

$containerBuilder = ContainerBuilderFactory::createInMemory();

$repo = $containerBuilder->get(InMemoryMealProductRepository::class);

$mealProducts  = $repo->mealProducts;

var_dump($mealProducts); // array(0) {}
