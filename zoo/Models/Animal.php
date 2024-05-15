<?php

abstract class Animal
{
  public function __construct(
    private string $name,
    private float  $weight,
    private float  $maxWeight,
    private float  $minWeight,
    private bool   $isFemale,
    private float  $foodPortion
  )
  {
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function getMinWeight(): float
  {
    return $this->minWeight;
  }

  public function getSex(): string
  {
    return $this->isFemale ? 'female' : 'male';
  }

  abstract public function pet(): void;

  public function feed(): void
  {
    if (($this->getWeight() + $this->getFoodPortion()) <= $this->getMaxWeight()) {
      $this->setWeight(($this->getWeight() + $this->getFoodPortion()));
    }
  }

  public function getWeight(): float
  {
    return $this->weight;
  }

  public function getFoodPortion(): float
  {
    return $this->foodPortion;
  }

  public function getMaxWeight(): float
  {
    return $this->maxWeight;
  }

  public function setWeight(float $newWeight): void
  {
    $this->weight = $newWeight;
  }
}