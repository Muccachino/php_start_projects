<?php

class Product
{
  readonly string $id;
  readonly string $file_name;
  public string $title;
  private int $upvotes;
  private int $downvotes;

  public function getSrc(): string
  {
    return "uploads/product_images/$this->file_name";
  }

  public function getUpvotes(): int
  {
    return $this->upvotes;
  }

  public function getDownvotes(): int
  {
    return $this->downvotes;
  }

  public function addToUpvotes(): void
  {
    $this->upvotes++;
  }

  public function addToDownvotes(): void
  {
    $this->downvotes++;
  }
}