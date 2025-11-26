


<?php

ob_start();

?>


<?php
require 'conn.php';

$errors = [];
$success = "";

// Fetch publishers and categories
$publishers = $pdo->query("SELECT id, name FROM publishers ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$categories = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Trim and collect input
  $title = trim($_POST['title'] ?? '');
  $author = trim($_POST['author'] ?? '');
  $isbn = trim($_POST['isbn'] ?? '');
  $language = trim($_POST['language'] ?? '');
  $edition = trim($_POST['edition'] ?? '');
  $paperback = trim($_POST['paperback'] ?? '');
  $description = trim($_POST['description'] ?? '');
  $publish_date = trim($_POST['publish_date'] ?? '');
  $publisher_id = $_POST['publisher_id'] ?? null;
  $category_id = $_POST['category_id'] ?? null;

  // Validate inputs
  if ($title === '') $errors[] = "Title is required.";
  if (!isset($_FILES['pdf_file']) || $_FILES['pdf_file']['error'] !== UPLOAD_ERR_OK)
    $errors[] = "Main PDF file is required.";

  // Get publisher name
  $publisher_name = 'Unknown';
  if ($publisher_id) {
    $stmt = $pdo->prepare("SELECT name FROM publishers WHERE id = ?");
    $stmt->execute([$publisher_id]);
    $publisher_name = $stmt->fetchColumn() ?: 'Unknown';
  }

  // Upload main PDF
  $mainPdfPath = null;
  if (empty($errors)) {
    $pdfFile = $_FILES['pdf_file'];
    $mime = mime_content_type($pdfFile['tmp_name']);
    if ($mime !== 'application/pdf') {
      $errors[] = "Main file must be a PDF.";
    } else {
      if (!is_dir(__DIR__ . '/uploads')) mkdir(__DIR__ . '/uploads', 0755, true);
      $filename = uniqid('book_', true) . '.pdf';
      $mainPdfPath = 'uploads/' . $filename;
      move_uploaded_file($pdfFile['tmp_name'], __DIR__ . '/' . $mainPdfPath);
    }
  }

  // Upload thumbnail
  $thumbnailPath = null;
  if (!empty($_FILES['thumbnail']['name'])) {
    if (!is_dir(__DIR__ . '/uploads/thumbnails')) mkdir(__DIR__ . '/uploads/thumbnails', 0755, true);
    $thumbName = 'thumb_' . uniqid() . '.jpg';
    $thumbnailPath = 'uploads/thumbnails/' . $thumbName;
    move_uploaded_file($_FILES['thumbnail']['tmp_name'], __DIR__ . '/' . $thumbnailPath);
  }

  // Upload extra files
  $extraFiles = [];
  if (!empty($_FILES['extra_files']['name'][0])) {
    if (!is_dir(__DIR__ . '/uploads/extra')) mkdir(__DIR__ . '/uploads/extra', 0755, true);
    foreach ($_FILES['extra_files']['name'] as $index => $name) {
      $tmpName = $_FILES['extra_files']['tmp_name'][$index];
      $safeName = 'extra_' . uniqid() . '_' . basename($name);
      $extraPath = 'uploads/extra/' . $safeName;
      move_uploaded_file($tmpName, __DIR__ . '/' . $extraPath);
      $extraFiles[] = $extraPath;
    }
  }

  // Insert into database
  if (empty($errors)) {
    $sql = "INSERT INTO books 
      (title, author, isbn, language, publisher, edition, paperback, description, publish_date, file_path, thumbnail, extra_files, publisher_id, category_id) 
      VALUES 
      (:title, :author, :isbn, :language, :publisher, :edition, :paperback, :description, :publish_date, :file_path, :thumbnail, :extra_files, :publisher_id, :category_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':title' => $title,
      ':author' => $author ?: 'Unknown',
      ':isbn' => $isbn ?: 'Unknown',
      ':language' => $language ?: 'Unknown',
      ':publisher' => $publisher_name,
      ':edition' => $edition ?: 'Unknown',
      ':paperback' => $paperback ?: 'Not Counted',
      ':description' => $description ?: 'No description available.',
      ':publish_date' => $publish_date ?: null,
      ':file_path' => $mainPdfPath,
      ':thumbnail' => $thumbnailPath,
      ':extra_files' => json_encode($extraFiles),
      ':publisher_id' => $publisher_id,
      ':category_id' => $category_id
    ]);
    $success = "Book uploaded successfully!";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Add Book</title>
  <?php include_once "./includes/style.php"; ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <?php include_once "./includes/header.php"; ?>
  <?php include_once "./includes/sidebar.php"; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add New Book</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Add Book</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">

        <?php if (!empty($errors)): ?>
          <div class="alert alert-danger">
            <ul>
              <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <?php if ($success): ?>
          <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <div class="card card-primary">
          <div class="card-header"><h3 class="card-title">Book Details</h3></div>
          <form method="post" enctype="multipart/form-data">
            <div class="card-body">
              <div class="form-group">
                <label>Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($title ?? '') ?>" required>
              </div>
              <div class="form-group">
                <label>Author</label>
                <input type="text" name="author" class="form-control" value="<?= htmlspecialchars($author ?? '') ?>">
              </div>
              <div class="form-group">
                <label>ISBN</label>
                <input type="text" name="isbn" class="form-control" value="<?= htmlspecialchars($isbn ?? '') ?>">
              </div>
              <div class="form-group">
                <label>Language</label>
                <input type="text" name="language" class="form-control" value="<?= htmlspecialchars($language ?? '') ?>">
              </div>

              <div class="form-group">
                <label>Publisher</label>
                <select name="publisher_id" class="form-control">
                  <option value="">-- Select Publisher --</option>
                  <?php foreach ($publishers as $pub): ?>
                    <option value="<?= $pub['id'] ?>" <?= (isset($_POST['publisher_id']) && $_POST['publisher_id'] == $pub['id']) ? 'selected' : '' ?>>
                      <?= htmlspecialchars($pub['name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="form-group">
                <label>Category</label>
                <select name="category_id" class="form-control">
                  <option value="">-- Select Category --</option>
                  <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (isset($_POST['category_id']) && $_POST['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                      <?= htmlspecialchars($cat['name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="form-group">
                <label>Edition</label>
                <input type="text" name="edition" class="form-control" value="<?= htmlspecialchars($edition ?? '') ?>">
              </div>
              <div class="form-group">
                <label>Paperback</label>
                <input type="text" name="paperback" class="form-control" value="<?= htmlspecialchars($paperback ?? '') ?>">
              </div>
              <div class="form-group">
                <label>Publish Date</label>
                <input type="date" name="publish_date" class="form-control" value="<?= htmlspecialchars($publish_date ?? '') ?>">
              </div>
              <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4" class="form-control"><?= htmlspecialchars($description ?? '') ?></textarea>
              </div>
              <div class="form-group">
                <label>Main PDF <span class="text-danger">*</span></label>
                <input type="file" name="pdf_file" class="form-control-file" accept=".pdf" required>
              </div>
              <div class="form-group">
                <label>Thumbnail</label>
                <input type="file" name="thumbnail" class="form-control-file" accept="image/*">
              </div>
              <div class="form-group">
                <label>Extra Files</label>
                <input type="file" name="extra_files[]" class="form-control-file" multiple>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Add Book</button>
            </div>
          </form>
        </div>
      </div>
    </section>
  </div>

</div>
<?php include_once "./includes/script.php"; ?>
</body>
</html>


<?php

ob_flush();

?>