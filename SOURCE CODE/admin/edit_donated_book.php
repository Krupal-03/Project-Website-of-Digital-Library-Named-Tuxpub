<?php
ob_start();
require 'conn.php';

$id = intval($_GET['id']);
$book = $pdo->prepare("SELECT * FROM donated_books WHERE id = ?");
$book->execute([$id]);
$book = $book->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    header("Location: donated_books_approval.php");
    exit;
}

// Fetch publishers and categories for dropdowns
$publishers = $pdo->query("SELECT id, name FROM publishers ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$categories = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

$errors = [];
$success = "";

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $donated_by = trim($_POST['donated_by'] ?? '');
    $publisher_id = $_POST['publisher_id'] ?? null;
    $category_id = $_POST['category_id'] ?? null;
    $publish_date = $_POST['publish_date'] ?? null;
    $isbn = trim($_POST['isbn'] ?? '');
    $language = trim($_POST['language'] ?? '');
    $edition = trim($_POST['edition'] ?? '');
    $paperback = trim($_POST['paperback'] ?? '');
    $description = trim($_POST['description'] ?? '');

    // Validation
    if (empty($title)) {
        $errors[] = "Title is required.";
    }
    if (empty($author)) {
        $errors[] = "Author is required.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE donated_books 
            SET title = ?, author = ?, donated_by = ?, publisher_id = ?, category_id = ?, 
                publish_date = ?, isbn = ?, language = ?, edition = ?, paperback = ?, description = ?
            WHERE id = ?");
        $stmt->execute([$title, $author, $donated_by, $publisher_id, $category_id, 
                       $publish_date, $isbn, $language, $edition, $paperback, $description, $id]);

        $success = "Book updated successfully!";
        
        // Refresh book data
        $book = $pdo->prepare("SELECT * FROM donated_books WHERE id = ?");
        $book->execute([$id]);
        $book = $book->fetch(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Donated Book</title>
  <?php include_once "./includes/style.php"; ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <?php include_once "includes/header.php"; ?>
  <?php include_once "includes/sidebar.php"; ?>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Edit Donated Book</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="donated_books_approval.php">Donated Books</a></li>
              <li class="breadcrumb-item active">Edit Book</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <?php if ($errors): ?>
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-exclamation-triangle"></i> Error!</h5>
            <?php foreach ($errors as $error): ?>
              <div><?= htmlspecialchars($error) ?></div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <?php if ($success): ?>
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check"></i> Success!</h5>
            <?= htmlspecialchars($success) ?>
          </div>
        <?php endif; ?>

        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-edit"></i>
                  Edit Donated Book: <?= htmlspecialchars($book['title']) ?>
                </h3>
                <div class="card-tools">
                  <a href="donated_books_approval.php" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to List
                  </a>
                </div>
              </div>
              <form method="post">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="title">Title *</label>
                        <input type="text" name="title" class="form-control" 
                               value="<?= htmlspecialchars($book['title']) ?>" required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="author">Author *</label>
                        <input type="text" name="author" class="form-control" 
                               value="<?= htmlspecialchars($book['author']) ?>" required>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="donated_by">Donated By</label>
                        <input type="text" name="donated_by" class="form-control" 
                               value="<?= htmlspecialchars($book['donated_by']) ?>">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="isbn">ISBN</label>
                        <input type="text" name="isbn" class="form-control" 
                               value="<?= htmlspecialchars($book['isbn']) ?>">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="publisher_id">Publisher</label>
                        <select name="publisher_id" class="form-control">
                          <option value="">Select Publisher</option>
                          <?php foreach ($publishers as $pub): ?>
                            <option value="<?= $pub['id'] ?>" <?= ($book['publisher_id'] == $pub['id']) ? 'selected' : '' ?>>
                              <?= htmlspecialchars($pub['name']) ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="category_id">Category</label>
                        <select name="category_id" class="form-control">
                          <option value="">Select Category</option>
                          <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($book['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                              <?= htmlspecialchars($cat['name']) ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="publish_date">Publish Date</label>
                        <input type="date" name="publish_date" class="form-control" 
                               value="<?= htmlspecialchars($book['publish_date']) ?>">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="language">Language</label>
                        <input type="text" name="language" class="form-control" 
                               value="<?= htmlspecialchars($book['language']) ?>">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="edition">Edition</label>
                        <input type="text" name="edition" class="form-control" 
                               value="<?= htmlspecialchars($book['edition']) ?>">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="paperback">Paperback</label>
                        <input type="text" name="paperback" class="form-control" 
                               value="<?= htmlspecialchars($book['paperback']) ?>">
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($book['description']) ?></textarea>
                  </div>

                  <!-- File Previews -->
                  <div class="row mt-4">
                    <div class="col-md-4">
                      <div class="card">
                        <div class="card-header">
                          <h5 class="card-title">Book File</h5>
                        </div>
                        <div class="card-body text-center">
                          <a href="<?= htmlspecialchars($book['file_path']) ?>" target="_blank" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i> View File
                          </a>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="card">
                        <div class="card-header">
                          <h5 class="card-title">Thumbnail</h5>
                        </div>
                        <div class="card-body text-center">
                          <?php if ($book['thumbnail']): ?>
                            <img src="<?= htmlspecialchars($book['thumbnail']) ?>" width="100" class="img-thumbnail">
                          <?php else: ?>
                            <span class="text-muted">No thumbnail</span>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="card">
                        <div class="card-header">
                          <h5 class="card-title">Status</h5>
                        </div>
                        <div class="card-body text-center">
                          <span class="badge badge-<?= $book['status'] === 'approved' ? 'success' : ($book['status'] === 'pending' ? 'warning' : 'danger') ?>">
                            <?= htmlspecialchars(ucfirst($book['status'])) ?>
                          </span>
                          <br>
                          <small class="text-muted">
                            Created: <?= date('M j, Y', strtotime($book['created_at'])) ?>
                          </small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Extra Images -->
                  <?php
                  $images = json_decode($book['extra_images'] ?? '[]', true);
                  if ($images && is_array($images) && count($images) > 0): ?>
                    <div class="form-group mt-4">
                      <label>Extra Images</label>
                      <div class="row">
                        <?php foreach ($images as $img): ?>
                          <div class="col-md-2 mb-2">
                            <img src="<?= htmlspecialchars($img) ?>" class="img-thumbnail" style="width: 100%; height: 100px; object-fit: cover;">
                          </div>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  <?php endif; ?>
                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Book
                  </button>
                  <a href="donated_books_approval.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                  </a>
                  <a href="donated_books_approval.php?delete_id=<?= $book['id'] ?>" 
                     class="btn btn-danger float-right"
                     onclick="return confirm('Are you sure you want to delete this book? This action cannot be undone.')">
                    <i class="fas fa-trash"></i> Delete Book
                  </a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <?php include_once "includes/script.php"; ?>
</div>
</body>
</html>

<?php ob_flush(); ?>