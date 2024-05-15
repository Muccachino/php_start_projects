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

        <br>
      <?php
      if ($animal instanceof BigCat):?>
          <p>How Dangerous is <?= $animal->getName() ?> ? (0-5)
              <progress value="<?= $animal->getDangerLevel() ?>" max="5"></progress>
          </p>
          <p><?php $animal->roar(); ?></p>
      <?php endif; ?>
      <?php
      if (!($animal instanceof BigCat)): ?>
          <p>No Danger Parameter for this kind of Animal</p>
          <p><?php $animal->pet() ?></p>
      <?php endif; ?>
        <br>
        <div>
            <p>Min. Weight: (<?= $animal->getMinWeight() ?> kg)
                <progress value="<?= $animal->getMinWeight() ?>" max="<?= $animal->getMaxWeight() ?>"></progress>
            </p>
            <p>Current Weight: (<?= $animal->getWeight() ?> kg)
                <progress value="<?= $animal->getWeight() ?>" max="<?= $animal->getMaxWeight() ?>"></progress>
            </p>
            <p>Max. Weight: (<?= $animal->getMaxWeight() ?> kg)
                <progress value="<?= $animal->getMaxWeight() ?>" max="<?= $animal->getMaxWeight() ?>"></progress>
            </p>
        </div>

        <a href="../index.php"><-- Back to the Zoo</a>
    </main>

<?php include "../footer.php"; ?>