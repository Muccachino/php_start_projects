<?php
$css_path = "../pico.classless.min.css";
include "../header.php";
include_once dirname(__DIR__) . "/inc/objects.php";

if (isset($_GET["name"]) && isset($theZoo)) {
  foreach ($theZoo->getAnimals() as $currentAnimal) {
    if (str_replace(" ", "", $currentAnimal->getName()) === $_GET["name"]) {
      $animal = $currentAnimal;
    }
  }
} else {
  $animal = null;
}
?>

    <main style="padding: 0">
        <h2><?= $animal->getName() ?></h2>
        <p>Sex: <?= $animal->getSex() ?></p>
        <p>Current Weight: <?= $animal->getWeight() ?> kg</p>
        <a href="../index.php"><-- Back to the Zoo</a>
    </main>

<?php include "../footer.php"; ?>