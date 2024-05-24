<?php
require "../includes/functions.php";
require "../includes/db-connect.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? null;
$sql = "SELECT name FROM category WHERE id = :id";
if (isset($pdo)) {
  $category = pdo_execute($pdo, $sql, ['id' => $id])->fetch();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $cat_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? null;
  $sql = "DELETE FROM category WHERE id = :id";

  try {
    pdo_execute($pdo, $sql, ['id' => $id]);
    redirect("categories.php", ["success" => "category successfully deleted"]);
  } catch (PDOException $e) {
    $error = $e->errorInfo[1];
    if ($error == "1451") {
      $categoryName = $category['name'];
      redirect("categories.php", ["error" => "Category $categoryName can not be removed, there are articles in the Category"]);
    }
  }
}

?>

<?php include "../includes/header-admin.php"; ?>

<main class="container w-auto mx-auto md:w-1/2 flex justify-center flex-col items-center p-5">
    <h1 class="text-4xl text-blue-500 mb-8">Are you sure you want to delete the category <?= $category["name"] ?>?</h1>
    <div class="flex justify-center items-center">
        <form action="category-delete.php?id=<?= $id ?>" method="post">
            <input type="hidden" name="id" id="id" value="<?= $id ?>">
            <button type="submit" class=" text-white bg-pink-600 p-3 m-2 rounded-md">Yes</button>
        </form>
        <form action="categories.php">
            <button type="submit" class="text-white bg-blue-500 p-3 m-2 rounded-md ">No</button>
        </form>
    </div>

</main>

<?php include "../includes/footer-admin.php" ?>




