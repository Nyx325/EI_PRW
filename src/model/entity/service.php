<?php
require_once("entity.php");

class Service implements IEntity
{
  public readonly int $id;
  public string $description;
  public float $price;

  public function __construct(int $id, string $description, float $price)
  {
    $this->id = $id;
    $this->description = $description;
    $this->price = $price;
  }

  public function getId(): int|string
  {
    return $this->id;
  }
}

class ServiceCriteria implements IEntityCriteria
{
  public ?string $description;
  public ?float $price;
  public ?float $minPrice;
  public ?float $maxPrice;

  public function __construct(?string $description, ?float $price, ?float $minPrice, ?float $maxPrice)
  {
    $this->description = $description;
    $this->price = $price;
    $this->minPrice = $minPrice;
    $this->maxPrice = $maxPrice;
  }
}
