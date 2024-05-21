<?php
try {
  $dsn = 'mysql:host=localhost:3306;dbname=up_down_voting';
  $user_name = "up_down_voting";
  $password = "";
  $options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  ];

  $pdo = new PDO($dsn, $user_name, $password, $options);
} catch (PDOException $e) {
  echo "Datenbank Verbindung fehlgeschlagen: " . $e->getMessage();
}