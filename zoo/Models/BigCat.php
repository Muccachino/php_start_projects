<?php

require_once "Animal.php";

abstract class BigCat extends Animal
{
  private int $danger;

  public function __construct(string $name, float $weight, float $maxWeight, float $minWeight, bool $isFemale, float $foodPortion, int $danger)
  {
    parent::__construct($name, $weight, $maxWeight, $minWeight, $isFemale, $foodPortion);
    $this->danger = $danger;
  }

  public function getDangerLevel(): int
  {
    return $this->danger;
  }


  abstract public function roar(): void;
}