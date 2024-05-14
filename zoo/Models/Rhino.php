<?php
require_once "Animal.php";

class Rhino extends Animal
{
  public function __construct(string $name, float $weight, float $maxWeight, float $minWeight, bool $isFemale, float $foodPortion)
  {
    parent::__construct($name, $weight, $maxWeight, $minWeight, $isFemale, $foodPortion);
  }

  public function pet(): void
  {
    echo "Rhino " . $this->getName() . " makes a loud noise";
  }
}