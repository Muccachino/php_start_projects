<?php

class Zoo
{
  private array $allAnimals = [];

  public function getAnimals(): array
  {
    return $this->allAnimals;
  }

  public function addAnimal(object $animal): void
  {
    $this->allAnimals[] = $animal;
  }

  public function feedAllAnimals(): void
  {
    foreach ($this->allAnimals as $animal) {
      if ($animal->weight < $animal->maxWeight) {
        $animal->feed();
      }
    }
  }

  public function petAllAnimals(): void
  {
    foreach ($this->allAnimals as $animal) {
      $animal->pet();
    }
  }

  public function allCatsRoar(): void
  {
    foreach ($this->allAnimals as $animal) {
      if (is_a($animal, "BigCat")) {
        $animal->roar();
      }
    }
  }
}