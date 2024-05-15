<main style="padding: 0">
    <div class="animals">
      <?php foreach ($theZoo->getAnimals() as $animal) : ?>
          <div class="animal-card">
              <h1>
                  <a href="views/animal.view.php?name=<?= str_replace(" ", "", $animal->getName()) ?>"><?= $animal->getName() ?></a>
              </h1>
              <p>Weight:
                  <progress value="<?= $animal->getWeight() ?>" max="<?= $animal->getMaxWeight() ?>"></progress>
              </p>
              <p><?= $animal->pet(); ?></p>
          </div>
      <?php endforeach; ?>
    </div>
</main>