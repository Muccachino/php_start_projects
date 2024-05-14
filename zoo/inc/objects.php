<?php
require_once dirname(__DIR__) . "/Models/Zoo.php";
require_once dirname(__DIR__) . "/Models/Tiger.php";
require_once dirname(__DIR__) . "/Models/Lion.php";
require_once dirname(__DIR__) . "/Models/Rhino.php";

$theZoo = new Zoo();

$shirkan = new Tiger("Shirkan", 0, 220, 180, false, 10, 4);
$sora = new Tiger("Sora", 0, 140, 120, true, 7, 2);

$mufasa = new Lion("Mufasa", 0, 250, 150, false, 12, 3);
$namibi = new Lion("Namibi", 0, 180, 110, true, 8, 5);

$henrik = new Rhino("Henrik", 0, 2500, 1800, false, 120);
$amilia = new Rhino("Amilia", 0, 2000, 1700, true, 100);

$theZoo->addAnimal($shirkan);
$theZoo->addAnimal($sora);
$theZoo->addAnimal($mufasa);
$theZoo->addAnimal($namibi);
$theZoo->addAnimal($henrik);
$theZoo->addAnimal($amilia);

$currentlyfed = 0;
while ($currentlyfed != count($theZoo->getAnimals())) {

}