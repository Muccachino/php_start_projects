<?php
require_once __DIR__ . "/inc/all.php";

if (isset($pdo)) {
  $products = new ProductListCaller($pdo);
}
if (isset($user_login)) {
  if ($user_login->logged_in) {
    header("Location: /php_start_projects/up_down_voting/user_index.php");
    exit();
  }
}

render(__DIR__ . "/views/index.view.php", [
  "products" => $products->fetchALl(),
]);