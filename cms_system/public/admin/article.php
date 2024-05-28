<?php
require "../../src/bootstrap.php";

// Variablen initialisieren für Bildupload

$path_to_img = "/uploads/";
$allowed_types = ["image/jpeg", "image/png"];
$allowed_extensions = ["jpeg", "jpg", "png"];
$max_size = 1920 * 1080 * 2;

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT) ?? "";
$tmp_path = $_FILES["image_file"]["tmp_name"] ?? "";
$save_to = "";

$article = [
  "id" => $id,
  "title" => "",
  "summary" => "",
  "content" => "",
  "published" => false,
  "category_id" => 0,
  "user_id" => 0,
  "images_id" => null,
  "image_file" => "",
  "image_alt" => ""
];

$errors = [
  "issue" => "",
  "title" => "",
  "summary" => "",
  "content" => "",
  "user" => "",
  "category" => "",
  "image_file" => "",
  "image_alt" => "",
];

// Lade alle Kategorien von der Datenbank

if (isset($cms)) {
  $categories = $cms->getCategory()->getAll();
  $users = $cms->getUser()->getAll();
  if ($id) {
    $article = $cms->getArticle()->fetch($id, false);
    if (!$article) {
      redirect("articles.php", ["error" => "article not found"]);
    }
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Bild auslesen
  if (isset($_FILES["image_file"])) {
    $image = $_FILES["image_file"];

    // Bildgröße validieren
    $errors["image_file"] = $image["error"] === 1 ? "The image is too large" : "";

    // Wenn ein Bild hochgeladen wurde, dann wird es validiert
    if ($tmp_path && $image["error"] === UPLOAD_ERR_OK) {
      // Alt-Text wird gesetzt
      $article["image_alt"] = filter_input(INPUT_POST, "image_alt");
      // Alt-Text validieren
      $errors["image_alt"] = Validate::isText($article["image_alt"], 1, 254) ? "" : "Alt text must be between 1 and 254 characters";

      // Bildtyp wird validiert
      $typ = mime_content_type($tmp_path);
      $errors["image_file"] .= in_array($typ, $allowed_types) ? "" : "The file type is not allowed";
      // Bildendung wird validiert
      $extension = pathinfo(strtolower($image["name"]), PATHINFO_EXTENSION);
      $errors["image_file"] .= in_array($extension, $allowed_extensions) ? "" : "The file extension $extension is not allowed";
      // Bildgröße wird validiert
      $errors["image_file"] .= $image["size"] > $max_size ? "The image exceeds the maximum upload size ($max_size Bytes)" : "";

      // Wenn es keine Fehler gibt, wird ein Speicherort für das Bild festgelegt
      if ($errors["image_file"] === "" && $errors["image_alt"] === "") {
        $article["image_file"] = $image["name"];
        $save_to = get_file_path($image["name"], $path_to_img, true);
      }
    }
  }

  // Daten aus dem Formular auslesen
  $article["title"] = filter_input(INPUT_POST, "title");
  $article["summary"] = filter_input(INPUT_POST, "summary");
  $article["content"] = filter_input(INPUT_POST, "content");
  $article["user_id"] = filter_input(INPUT_POST, "user_id", FILTER_VALIDATE_INT);
  $article["category_id"] = filter_input(INPUT_POST, "category_id", FILTER_VALIDATE_INT);
  $article["published"] = filter_input(INPUT_POST, "published", FILTER_VALIDATE_BOOLEAN) ? 1 : 0;


  // Error-Meldung erstellen und zusätzliche Validierung
  $errors["title"] = Validate::isText($article["title"]) ? "" : "Title must be between 1 and 100 characters";
  $errors["summary"] = Validate::isText($article["summary"], 1, 200) ? "" : "Summary must be between 1 and 200 characters";
  $errors["content"] = Validate::isText($article["content"], 1, 10000) ? "" : "Content must be between 1 and 10000 characters";
  $errors["user"] = Validate::is_user_id($article["user_id"], $users) ? "" : "User not found";
  $errors["category"] = Validate::is_category($article["category_id"], $categories) ? "" : "Category not found";

  $problems = implode($errors);

  if (!$problems) {
    // bindings beinhaltet alle Variablen die der Funktion Statement::bindvalue übergeben werden (oder auch Statement::execute als Array)
    $bindings = $article;
    try {

      // Wenn ein Bild hochgeladen wurde, wird es gespeichert
      if ($save_to) {
        scale_and_copy($tmp_path, $save_to);

        $stmt = $cms->getImage()->push($article["image_file"], $article["image_alt"]);
        // lastInsertId gibt die ID des zuletzt eingefügten Datensatzes zurück
        $bindings["images_id"] = $cms->getImage()->getLatestImageId()["LAST_INSERT_ID()"];
      }

      // Da ab hier die Bildtabelle aktualisiert wurde, werden die Bilddaten aus dem bindings Array entfernt.
      // Für das INSERT (Anlegen eines neuen Datensatzes) wird die ID automatisch mit autoinkrement erstellt
      // und muss deshalb hier aus den bindings entfernt werden.
      unset($bindings["image_file"], $bindings["image_alt"]);

      // Code, wenn ein Artikel bearbeitet wurde
      if ($id) {
        // Wenn die ID vorhanden ist, wird ein UPDATE durchgeführt und die ID wird wieder in das bindings Array aufgenommen.
        unset($bindings["author"], $bindings["created"], $bindings["category"], $bindings["image_id"]);
        $cms->getArticle()->update($bindings);

      } else {
        // Code, wenn ein neuer Artikel erstellt wird
        unset($bindings["id"]);
        $cms->getArticle()->push($bindings);
      }

      redirect("articles.php", ["success" => "Article successfully saved"]);
    } catch (PDOException $e) {

      $errors["issue"] = $e->getMessage();
    }
  }
}

?>

<?php include '../includes/header-admin.php'; ?>
<main class="p-10">
    <h2 class="text-3xl text-blue-500 mb-8 text-center"><?= $article['id'] ? 'Edit ' : 'New ' ?>Article</h2>
  <?php if ($errors['issue']): ?>
      <p class="error text-red-500 bg-red-200 p-5 rounded-md"><?= $errors['issue'] ?></p>
  <?php endif ?>
    <form action="article.php?id=<?= e($id) ?>" method="POST" enctype="multipart/form-data"
          class="grid gap-6 mb-6 md:grid-cols-2 md:w-full">
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 pt-2" for="title">Title</label>
            <input type="text" id="title" name="title" value="<?= e($article['title']) ?>"
                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            <span class="text-red-500"><?= $errors['title'] ?></span>
            <label class="block mb-2 text-sm font-medium text-gray-900 pt-2" for="summary">Summary</label>
            <textarea id="summary" name="summary"
                      class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"><?= e($article['summary']) ?></textarea>
            <span class="text-red-500"><?= $errors['summary'] ?></span>
            <label class="block mb-2 text-sm font-medium text-gray-900 pt-2" for="content">Content</label>
            <textarea id="content" rows="10" name="content"
                      class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"><?= e($article['content']) ?></textarea>
            <span class="text-red-500"><?= $errors['content'] ?></span>
        </div>
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 pt-2" for="category">Category</label>
            <select id="category_id" name="category_id"
                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                <option>select category</option>
              <?php foreach ($categories as $category): ?>
                  <option value="<?= $category['id'] ?>" <?= $category['id'] === $article['category_id'] ? 'selected' : '' ?>><?= e($category['name']) ?></option>
              <?php endforeach; ?>
            </select>
            <span class="text-red-500"><?= $errors['category'] ?></span>
            <label class="block mb-2 text-sm font-medium text-gray-900 pt-2" for="user_id">User</label>
            <select id="user_id" name="user_id"
                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                <option>select user</option>
              <?php foreach ($users as $user): ?>
                  <option value="<?= $user['id'] ?>" <?= $user['id'] === $article['user_id'] ? 'selected' : '' ?>><?= e($user['forename']) ?> <?= e($user['surname']) ?></option>
              <?php endforeach; ?>
            </select>
            <span class="text-red-500"><?= $errors['user'] ?></span>
          <?php if (!$article['image_file']) : ?>
              <label class="block mb-2 text-sm font-medium text-gray-900" for="image_file pt-2">Image</label>
              <input type="file" id="image_file" accept="image/jpeg, image/png" name="image_file"
                     class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
              <span class="text-red-500"><?= $errors['image_file'] ?></span>
          <?php else: ?>
              <img src="../uploads/<?= e($article['image_file']) ?>" alt="<?= e($article['image_alt']) ?>"
                   class="w-full h-auto"/>
              <span>Alt Text: <?= e($article['image_alt']) ?></span>
              <a href="alt-text-edit.php?id=<?= e($article['id']) ?>" class="text-blue-500">Edit Alt Text</a>
              <a href="img-delete.php?id=<?= e($article['id']) ?>" class="text-red-500">Delete Image</a>
          <?php endif; ?>
            <label class="block mb-2 text-sm font-medium text-gray-900 pt-2" for="image_alt">Image Alt</label>
            <input type="text" id="image_alt" name="image_alt" value="<?= e($article['image_alt'] ?? '') ?>"
                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            <span class="text-red-500"><?= $errors['image_alt'] ?></span>
            <label class="block mb-2 text-sm font-medium text-gray-900 pt-2" for="published">Published</label>
            <input type="checkbox" id="published" name="published" <?= $article['published'] ? 'checked' : '' ?>
                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600">
        </div>
        <button type="submit" class="text-white bg-blue-500 p-3 rounded-md hover:bg-pink-600">Save</button>
    </form>
</main>
<?php include '../includes/footer-admin.php'; ?>
