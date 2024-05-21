<section class="gallery grid">
  <?php if (isset($products)) : ?>
    <?php foreach ($products as $product): ?>
          <div class="gallery item" style="width: 300px">
              <img src="<?= e($product->getSrc()) ?>" alt="<?= e($product->title) ?>">
              <h4><?= e($product->title) ?></h4>
              <p>Voting: <?= $product->getUpvotes() - $product->getDownvotes() ?></p>
              <button>Upvotes: <?= $product->getUpvotes() ?></button>
              <button>Downvotes: <?= $product->getDownvotes() ?></button>
          </div>
    <?php endforeach ?>
  <?php else: ?>
      <p>Es wurden bisher keine Produkte erfasst</p>
  <?php endif; ?>
</section>