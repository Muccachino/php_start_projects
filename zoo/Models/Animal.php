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

  public function getWeight(): float
  {
    return $this->weight;
  }

  public function setWeight(float $newWeight): void
  {
    $this->weight = $newWeight;
  }

  public function getMinWeight(): float
  {
    return $this->minWeight;
  }

  public function getMaxWeight(): float
  {
    return $this->maxWeight;
  }

  public function getIsFemale(): bool
  {
    return $this->isFemale;
  }

  public function getFoodPortion(): float
  {
    return $this->foodPortion;
  }

  abstract public function pet(): void;

  public function feed(): void
  {
    $this->weight += $this->foodPortion;
  }
}