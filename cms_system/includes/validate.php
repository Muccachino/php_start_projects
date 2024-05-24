<?php

function isNumber($number, $min = 0, $max = 100): bool
{
  return ($number >= $min && $number <= $max);
}

function isText($text, $min = 1, $max = 100): bool
{
  return (strlen($text) >= $min && strlen($text) <= $max);
}