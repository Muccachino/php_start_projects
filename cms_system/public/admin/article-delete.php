<?php
require "../../src/bootstrap.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? null;

if (isset($cms)) {
  $article = $cms->getArticle()->fetch($id);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $art_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? null;

  try {
    // Wenn ein Bild vorhanden ist, muss zunächst die Bild ID auf null gesetzt werden um es löschen zu können.
    if ($article["image_id"]) {
      // Prüfen, ob das Bild noch für andere Artikel verwendet wird
      $imageUsed = $cms->getArticle()->imageInUse($article["image_id"]);

      $image_path = get_file_path($article["image_file"], "", true);

      $stmt = $cms->getArticle()->setImageIdNull($art_id);

      // Wenn das Bild nicht öfter verwendet wird, kann es gelöscht werden.
      if (!$imageUsed) {
        // Löschen des Bildes aus dem Serverordner
        unlink($image_path);

        // Löschen des Bildes aus der Datenbank
        $cms->getImage()->deleteArticleImage($article["image_id"]);
      }
    }

    // Löschen des Artikels aus der Datenbank
    $cms->getArticle()->delete($art_id);

    redirect("articles.php", ["success" => "Article successfully deleted"]);

  } catch (PDOException $e) {
    redirect("articles.php", ["error" => "Article could not be removed"]);
  }

}

?>

<?php include "../includes/header-admin.php"; ?>

<main class="container w-auto mx-auto md:w-1/2 flex justify-center flex-col items-center p-5">
    <h1 class="text-4xl text-blue-500 mb-8">Are you sure you want to delete the article <?= $article["title"] ?>?</h1>
    <div class="flex justify-center items-center">
        <form action="article-delete.php?id=<?= $id ?>" method="post">
            <input type="hidden" name="id" id="id" value="<?= $id ?>">
            <button type="submit" class=" text-white bg-pink-600 p-3 m-2 rounded-md">Yes</button>
        </form>
        <form action="articles.php">
            <button type="submit" class="text-white bg-blue-500 p-3 m-2 rounded-md ">No</button>
        </form>
    </div>

</main>

<?php include "../includes/footer-admin.php" ?>
