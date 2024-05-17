<?php
include "functions.php";
include "header.php";

$showForm = true;
$chosenSex = $_POST["sex"] ?? "";
$sexes = ["male", "female", "diverse"];

$max_file_size = 1024 * 1024 * 4;
$upload_dir = __DIR__ . "/uploads/";
$allowed_types = ["image/jpeg", "image/png"];
$allowed_extensions = ["jpeg", "jpg", "png"];

$errors = [
  "sex" => "",
  "firstName" => "",
  "lastName" => "",
  "age" => "",
  "email" => "",
  "phone" => "",
  "street" => "",
  "plz" => "",
  "city" => "",
  "image" => ""
];

$filters = [
  "sex" => FILTER_DEFAULT,
  "firstName" => FILTER_DEFAULT,
  "lastName" => FILTER_DEFAULT,
  "email" => FILTER_VALIDATE_EMAIL,
  "age" => [
    "filter" => FILTER_VALIDATE_INT,
    "options" => ["min_range" => 12, "max_range" => 100]
  ],
  "phone" => FILTER_SANITIZE_NUMBER_INT,
  "street" => FILTER_DEFAULT,
  "plz" => [
    "filter" => FILTER_DEFAULT,
    "options" => ["min_range" => 4, "max_range" => 5]
  ],
  "city" => FILTER_DEFAULT,
];
$filter = [
  "sex" => "",
  "firstName" => "",
  "lastName" => "",
  "age" => "",
  "email" => "",
  "phone" => "",
  "street" => "",
  "plz" => "",
  "city" => "",
];

/*function get_file_path(string $filename, string $path): string
{
  $basename = pathinfo($filename, PATHINFO_FILENAME);
  $extension = pathinfo($filename, PATHINFO_EXTENSION);
  $basename = preg_replace("/[^A-z0-9]/", "-", $basename);
  $i = 0;
  while (file_exists($path . $filename)) {
    $i++;
    $filename = $basename . $i . "." . $extension;
  }
  return __DIR__ . "/uploads/" . $filename;
}*/

if ($_SERVER['REQUEST_METHOD'] == "POST") {

  // Auslesen des Bildes
  $image = $_FILES["image"] ?? null;
  // Kontrolle, ob Bild zu groß ist
  $errors["image"] = $image["error"] === 1 ? "Image is too big" : "";

  if ($image["error"] === 0) {
    $errors["image"] = $image["size"] > $max_file_size ? "File is too big" : "";

    // Kontrolle, ob der MIME-Type des Bildes erlaubt ist
    $typ = mime_content_type($image["tmp_name"]);
    $errors["image"] .= in_array($typ, $allowed_types, true) ? "" : "File type not allowed";

    // Kontrolle, ob die Dateierweiterung erlaubt ist
    $extension = pathinfo(strtolower($image["name"]), PATHINFO_EXTENSION);
    $errors["image"] .= in_array($extension, $allowed_extensions, true) ? "" : "File extension not allowed";

  }

  $filter = filter_input_array(INPUT_POST, $filters);

  $errors["sex"] = $filter["sex"] ? "" : "You have to choose your sex";
  $errors["firstName"] = $filter["firstName"] ? "" : "You have to enter your first name";
  $errors["lastName"] = $filter["lastName"] ? "" : "You have to enter your last name";
  $errors["age"] = $filter["age"] ? "" : "Age must be between 12 and 100";
  $errors["email"] = $filter["email"] ? "" : "Input must have an email format (e.g. user@email.com)";
  $errors["phone"] = $filter["phone"] ? "" : "Please enter a valid phone number (e.g. +43123456789)";
  $errors["street"] = $filter["street"] ? "" : "Please enter your street and number";
  $errors["plz"] = $filter["plz"] ? "" : "Please enter your zip code";
  $errors["city"] = $filter["city"] ? "" : "Please enter your city";

  $noErrors = true;
  foreach ($errors as $key => $value) {
    if ($value != "") {
      $noErrors = false;
    }
  }
  if ($noErrors) {
    /*    $filename = $image["name"];
        $destination = get_file_path($filename, $upload_dir);
        $moved = scale_and_copy($image["tmp_name"], $destination);*/
    $showForm = false;
  }
}
?>

<?php if ($showForm) : ?>
    <main>
        <h1>Profile</h1>
        <h3>Input Personal Data</h3>

        <form action="form.php" method="post" enctype="multipart/form-data">
            <label>Sex</label>

            <div style="display: flex">
              <?php foreach ($sexes as $sex): ?>
                  <label><?= $sex ?>
                      <input type="radio" name="sex" value="<?= $sex ?>" <?= $chosenSex == $sex ? "checked" : "" ?>>
                  </label>
              <?php endforeach; ?>
                <span style="color: red"><?= $errors["sex"] ?></span>
            </div>
            <label for="firstName">First Name
                <input type="text" id="firstName" name="firstName" placeholder="First Name"
                       value="<?= e((string)$filter["firstName"]) ?>">
                <span style="color: red"><?= $errors["firstName"] ?></span>
            </label>
            <label for="lastName">Last Name
                <input type="text" id="lastName" name="lastName" placeholder="Last Name"
                       value="<?= e((string)$filter["lastName"]) ?>">
                <span style="color: red"><?= $errors["lastName"] ?></span>
            </label>
            <label for="age">Age
                <input type="text" id="age" name="age" placeholder="Age" value="<?= e((string)$filter["age"]) ?>">
                <span style="color: red"><?= $errors["age"] ?></span>
            </label>
            <label for="email">E-Mail
                <input type="text" id="email" name="email" placeholder="E-Mail"
                       value="<?= e((string)$filter["email"]) ?>">
                <span style="color: red"><?= $errors["email"] ?></span>
            </label>
            <label for="phone">Phone Number
                <input type="text" id="phone" name="phone" placeholder="Phone Number"
                       value="<?= e((string)$filter["phone"]) ?>">
                <span style="color: red"><?= $errors["phone"] ?></span>
            </label>
            <label for="street">Street
                <input type="text" id="street" name="street" placeholder="Street"
                       value="<?= e((string)$filter["street"]) ?>">
                <span style="color: red"><?= $errors["street"] ?></span>
            </label>
            <label for="plz">Zip Code
                <input type="text" id="plz" name="plz" placeholder="Zip Code"
                       value="<?= e((string)$filter["plz"]) ?>">
                <span style="color: red"><?= $errors["plz"] ?></span>
            </label>
            <label for="city">City
                <input type="text" id="city" name="city" placeholder="City"
                       value="<?= e((string)$filter["city"]) ?>">
                <span style="color: red"><?= $errors["city"] ?></span>
            </label>

            <label for="image">Upload Profile Picture
                <input type="file" name="image" id="image" accept="image/jpeg, image/png">
            </label>
            <span style="color: red"><?= $errors["image"] ?></span>
            <input type="submit" value="Send">
        </form>
    </main>
<?php endif; ?>

<?php if (!$showForm) : ?>
    <main style="display: flex">
        <div style="margin-right: 100px">
          <?php
          $imageData = file_get_contents($_FILES['image']['tmp_name']);
          echo sprintf('<img src="data:image/png;base64,%s" width="400" height="600"/>', base64_encode($imageData));
          ?>
            <!--<img src="<?php /*= __DIR__ . "/uploads/" . $_FILES["image"]["name"] */ ?>" alt="profile-pic">-->
        </div>
        <div style="width: 30vw">
            <h3>Persönliche Daten</h3>
            <table>
                <tr>
                    <td>First Name</td>
                    <td><?= $_POST["firstName"] ?></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><?= $_POST["lastName"] ?></td>
                </tr>
                <tr>
                    <td>Sex</td>
                    <td><?= $_POST["sex"] ?></td>
                </tr>
                <tr>
                    <td>Age</td>
                    <td><?= $_POST["age"] ?></td>
                </tr>
                <tr>
                    <td>E-Mail</td>
                    <td><?= $_POST["email"] ?></td>
                </tr>
                <tr>
                    <td>Phone Number</td>
                    <td><?= $_POST["phone"] ?></td>
                </tr>
                <tr>
                    <td>Street</td>
                    <td><?= $_POST["street"] ?></td>
                </tr>
                <tr>
                    <td>ZIP Code</td>
                    <td><?= $_POST["plz"] ?></td>
                </tr>
                <tr>
                    <td>City</td>
                    <td><?= $_POST["city"] ?></td>
                </tr>

            </table>
        </div>
    </main>
    <a href="index.php" style="margin-left: 20vw"><-- Back</a>
<?php endif; ?>
