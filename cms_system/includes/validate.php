<?php

function isNumber($number, $min = 0, $max = 100): bool
{
  return ($number >= $min && $number <= $max);
}

function isText($text, $min = 1, $max = 100): bool
{
  return (strlen($text) >= $min && strlen($text) <= $max);
}

function is_user_id($id, $users): bool
{
  foreach ($users as $user) {
    if ($user["id"] == $id) {
      return true;
    }
  }
  return false;
}

function is_category($id, $categories): bool
{
  foreach ($categories as $category) {
    if ($category["id"] == $id) {
      return true;
    }
  }
  return false;
}