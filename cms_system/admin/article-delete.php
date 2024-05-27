<?php
require "../includes/functions.php";
require "../includes/db-connect.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? null;
$sql = "SELECT a.title, a.images_id, i.filename, i.id AS imageId
        FROM articles AS a
        LEFT JOIN images AS i ON a.images_id = i.id
        WHERE a.id = :id";
if (isset($pdo)) {
  $article = pdo_execute($pdo, $sql, ['id' => $id])->fetch();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $art_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? null;

  try {
    $pdo->beginTransaction();

    if ($article["images_id"]) {
      $image_path = get_file_path($article["filename"], "", true);
      $sql = "UPDATE articles SET images_id = NULL WHERE id = :id";
      $stmt = pdo_execute($pdo, $sql, ['id' => $art_id]);
      unlink($image_path);
    }

    $sql = "DELETE FROM images WHERE id = :id";
    pdo_execute($pdo, $sql, ["id" => $article["imageId"]]);

    $sql = "DELETE FROM articles WHERE id = :id";
    pdo_execute($pdo, $sql, ["id" => $art_id]);

    $pdo->commit();
    redirect("articles.php", ["success" => "Article successfully deleted"]);

  } catch (PDOException $e) {
    $pdo->rollBack();
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
