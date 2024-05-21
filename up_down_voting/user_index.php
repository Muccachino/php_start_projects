<?php
require_once __DIR__ . "/inc/all.php";

if (isset($pdo)) {
  $products = new ProductListCaller($pdo);
}

render(__DIR__ . "/views/user_index.view.php", [
  "products" => $products->fetchALl(),
]);