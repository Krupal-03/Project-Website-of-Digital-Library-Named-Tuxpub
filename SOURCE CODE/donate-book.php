<?php
include_once 'header.php';
include_once 'conn.php';

// Directories
$targetDir = './admin/uploads/donated/';
$thumbnailDir = './admin/uploads/donated/thumbnails/';
$extraImagesDir = './admin/uploads/donated/extra_images/';

// Create directories if missing
foreach ([$targetDir, $thumbnailDir, $extraImagesDir] as $dir) {
    if (!is_dir($dir)) mkdir($dir, 0755, true);
}

// Fetch dropdown data
$publishers = $pdo->query("SELECT id, name FROM publishers ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$categories = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');   
    $author = trim($_POST['author'] ?? '');
    $isbn = trim($_POST['isbn'] ?? '');
    $language = trim($_POST['language'] ?? '');
    $edition = trim($_POST['edition'] ?? '');
    $paperback = trim($_POST['paperback'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $publisher_id = $_POST['publisher_id'] ?: null;
    $category_id = $_POST['category_id'] ?: null;
    $publish_date = $_POST['publish_date'] ?: null;
    $donated_by = trim($_POST['donated_by'] ?? '');

    // Validation
    if ($title === '') $errors[] = "Title is required.";
    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK)
        $errors[] = "PDF/EPUB file is required.";

    if (empty($errors)) {
        // File upload (PDF/EPUB)
        $file = $_FILES['file'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['pdf', 'epub'])) {
            $errors[] = "Only PDF or EPUB files allowed.";
        } else {
            $file_path = $targetDir . time() . '_' . basename($file['name']);
            if (!move_uploaded_file($file['tmp_name'], $file_path)) {
                $errors[] = "Failed to upload main file.";
            }
        }

        // Thumbnail upload
        $thumbnail = null;
        if (!empty($_FILES['thumbnail']['name'])) {
            $thumb = $_FILES['thumbnail'];
            $thumbnail = $thumbnailDir . time() . '_' . basename($thumb['name']);
            if (!move_uploaded_file($thumb['tmp_name'], $thumbnail)) {
                $errors[] = "Failed to upload thumbnail.";
            }
        }

        // Extra images upload
        $extra_images_paths = [];
        if (!empty($_FILES['extra_images']['name'][0])) {
            foreach ($_FILES['extra_images']['tmp_name'] as $key => $tmp_name) {
                $img_name = $extraImagesDir . time() . '_' . $_FILES['extra_images']['name'][$key];
                if (move_uploaded_file($tmp_name, $img_name)) {
                    $extra_images_paths[] = $img_name;
                } else {
                    $errors[] = "Failed to upload extra image.";
                }
            }
        }

        // Database insert
        if (empty($errors)) {
            $targetDir = './uploads/donated/';
            $thumbnailDir = './uploads/donated/thumbnails/';
            $extraImagesDir = './uploads/donated/extra_images/';
            $thumbnail = $thumbnailDir . time() . '_' . basename($thumb['name']);
            $file_path = $targetDir . time() . '_' . basename($file['name']);
            $extra_images_paths = [];
            if (!empty($_FILES['extra_images']['name'][0])) {
            foreach ($_FILES['extra_images']['tmp_name'] as $key => $tmp_name) {
                $img_name = $extraImagesDir . time() . '_' . $_FILES['extra_images']['name'][$key];
               
                    $extra_images_paths[] = $img_name;
               
        }
    }













 $sql = "INSERT INTO donated_books 
      ( title, author, donated_by, isbn, language, publisher_id, edition, paperback, description, publish_date, file_path, thumbnail, extra_images,category_id) 
      VALUES 
      ( :title, :author, :donated_by, :isbn, :language, :publisher_id, :edition, :paperback, :description, :publish_date, :file_path, :thumbnail, :extra_images, :category_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':title' => $title,
      ':author' => $author ?: 'Unknown',
      ':donated_by' => $donated_by ?: 'Anonymous',
      ':isbn' => $isbn ?: 'Unknown',
      ':language' => $language ?: 'Unknown',
      ':publisher_id' => $publisher_id,
      ':edition' => $edition ?: 'Unknown',
      ':paperback' => $paperback ?: 'Not Counted',
      ':description' => $description ?: 'No description available.',
      ':publish_date' => $publish_date ?: null,
      ':file_path' => $file_path,
      ':thumbnail' => $thumbnail,
      ':extra_images' => json_encode($extra_images_paths),
      ':category_id' => $category_id

    ]);


// 



            $success = "Book donated successfully. Pending approval. Thanks for Donating!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Donate Book</title>
    <link rel="stylesheet" href="css/adminlte.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Header -->
    <div class="page-heading header-text">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h3>Donate Books</h3>
                    <span class="breadcrumb"><a href="index.php">Home</a> > Donate Books</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-wrapper">
        <section class="content frm-donate">
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
                    <div class="card-header">
                        <h3 class="card-title">Book Details</h3>
                    </div>
                    <form method="post" enctype="multipart/form-data">
                        <div class="card-body">

                            <div class="form-group">
                                <label>Title *</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Author</label>
                                <input type="text" name="author" class="form-control">
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
                                    <option value="">Select Publisher</option>
                                    <?php foreach ($publishers as $p): ?>
                                        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Category</label>
                                <select name="category_id" class="form-control">
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $c): ?>
                                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
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
                                <input type="date" name="publish_date" class="form-control">
                            </div>
                            <div class="form-group">
                                            <label>Description</label>
                                            <textarea name="description" rows="4" class="form-control"><?= htmlspecialchars($description ?? '') ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Donated By</label>
                                <input type="text" name="donated_by" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>PDF/EPUB File *</label>
                                <input type="file" name="file" class="form-control" accept=".pdf,.epub" required>
                            </div>

                            <div class="form-group">
                                <label>Thumbnail</label>
                                <input type="file" name="thumbnail" class="form-control" accept="image/*">
                            </div>

                            <div class="form-group">
                                <label>Extra Images</label>
                                <input type="file" name="extra_images[]" class="form-control" accept="image/*" multiple>
                            </div>

                        </div>
                        <div class="card-footer d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary mt-3">Donate Book</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <?php include_once 'footer.php'; ?>
</div>

<?php include_once 'script.php'; ?>
</body>
</html>