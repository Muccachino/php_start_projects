<?php
require_once "BigCat.php";

class Lion extends BigCat
{
  public function __construct(string $name, float $weight, float $maxWeight, float $minWeight, bool $isFemale, float $foodPortion, int $danger)
  {
    parent::__construct($name, $weight, $maxWeight, $minWeight, $isFemale, $foodPortion, $danger);
  }

  public function roar(): void
  {
    echo "Lion says: Rooooaaaaar!";
  }

  public function pet(): void
  {
    echo "Lion " . $this->getName() . " is purring";
  }
}