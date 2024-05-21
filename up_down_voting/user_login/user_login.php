<?php
require_once __DIR__ . "/../inc/all.php";

// Wenn jemand schon eingeloggt ist, wird er auf die Admin-Seite weitergeleitet.
if (isset($user_login)) {
  if ($user_login->logged_in) {
    header("Location: /php_start_projects/up_down_voting/user_index.php");
    exit();
  }
}
// Wenn jemand nicht eingeloggt ist, wird er auf die Login-Seite weitergeleitet
renderUserLogin(__DIR__ . "/views/user_login.view.php");

// Wenn jemand das Formular abschickt, wird geprüft, ob die eingegebenen Daten korrekt sind.
// Die Hardcoded Logindaten sind in der Datei LoginSession.php in der Eigenschaft login_data gespeichert (admin, password)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $user_name = $_POST["username"] ?? "";
  $password = $_POST["password"] ?? "";
  if ($user_name === $user_login->login_data["username"] && $password === $user_login->login_data["password"]) {
    //die Methode login() wird aufgerufen, die die Session startet
    $user_login->login();

    // Wenn die Daten korrekt sind, wird der Benutzer auf die Admin-Seite weitergeleitet.
    header("Location: /php_start_projects/up_down_voting/user_index.php");
    exit();
  } else {
    echo "<p style='color: red'>Benutzername oder Passwort falsch</p>";
  }
}