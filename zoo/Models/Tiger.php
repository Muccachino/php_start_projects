<?php

require_once "BigCat.php";

class Tiger extends BigCat
{
  public function __construct(string $name, float $weight, float $maxWeight, float $minWeight, bool $isFemale, float $foodPortion, int $danger)
  {
    parent::__construct($name, $weight, $maxWeight, $minWeight, $isFemale, $foodPortion, $danger);
  }

  public function roar(): void
  {
    echo "Tiger says: Roar!";
  }

  public function pet(): void
  {
    echo "Tiger " . $this->getName() . " is purring";
  }
}