<?php
namespace Kami\Restaurant\Infrastructure\DoctrineMapping;

use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'mealProducts')]
class MealProduct
{
    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    private readonly string $id;

    #[ORM\Column(type: 'string')]
    private string $name;
	
    #[ORM\Column(type: 'int')]
    private int $cost;
		
    public function __construct()
    {
      $uuid = Uuid::uuid4();
      $this->id = $uuid->toString();  
    }

    public function getId(): string
    {
        return $this->id;
    }
	
    public function getName(): string
    {
        return $this->name;
    }
	
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCost(): int
    {
        return $this->cost;
    }
	
    public function setCost(int $cost): void
    {		
       $this->cost = $cost;
    }		
}
