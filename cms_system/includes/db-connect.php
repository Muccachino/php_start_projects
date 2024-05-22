<?php
try {
  $dsn = 'mysql:host=localhost:3306;dbname=cms_edvgraz';
  $user_name = "cms_edvgraz";
  $password = "";
  $options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  ];

  $pdo = new PDO($dsn, $user_name, $password, $options);
} catch (PDOException $e) {
  echo "Datenbank Verbindung fehlgeschlagen: " . $e->getMessage();
}