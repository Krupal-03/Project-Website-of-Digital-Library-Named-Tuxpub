<?php

ob_start();

?>




<?php
require 'conn.php';
ob_start(); // Prevent header issues

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid book ID.");
}

$id = intval($_GET['id']);

// Fetch book
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    die("Book not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $publish_date = trim($_POST['publish_date']);
    $filePath = $book['file_path'];

    // Update PDF if uploaded
    if (!empty($_FILES['pdf']['name'])) {
        $pdfName = time() . "_" . basename($_FILES["pdf"]["name"]);
        $target = "uploads/pdfs/" . $pdfName;
        if (move_uploaded_file($_FILES["pdf"]["tmp_name"], $target)) {
            $filePath = $target;
        }
    }

    // Update thumbnail if uploaded
    if (!empty($_FILES['thumbnail']['name'])) {
        $thumbPath = "uploads/thumbnails/" . $book['id'] . ".jpg";
        move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $thumbPath);
    }

    // Update DB
    $update = $pdo->prepare("UPDATE books SET title=?, author=?, publish_date=?, file_path=? WHERE id=?");
    $update->execute([$title, $author, $publish_date, $filePath, $id]);

    header("Location: books_list.php?msg=Book updated successfully");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Edit Book</title>
  <?php include_once "./includes/style.php"; ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <?php include_once "includes/header.php"; ?>
  <?php include_once "includes/sidebar.php"; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Book</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="books_list.php">Books</a></li>
              <li class="breadcrumb-item active">Edit Book</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Update Book</h3>
          </div>
          <form method="post" enctype="multipart/form-data">
            <div class="card-body">
              <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($book['title']) ?>" required>
              </div>
              <div class="form-group">
                <label>Author</label>
                <input type="text" name="author" class="form-control" value="<?= htmlspecialchars($book['author']) ?>">
              </div>
              <div class="form-group">
                <label>Publish Date</label>
                <input type="date" name="publish_date" class="form-control" value="<?= htmlspecialchars($book['publish_date']) ?>">
              </div>
              <div class="form-group">
                <label>Current PDF</label><br>
                <a href="<?= htmlspecialchars($book['file_path']) ?>" target="_blank" class="btn btn-sm btn-info">View PDF</a>
                <input type="file" name="pdf" class="form-control mt-2">
              </div>
              <div class="form-group">
                <table class="table table-bordered table-hover">
                  <tr>
                    <td>
                      <?php if ($book['thumbnail']): ?>
                        <img src="<?php echo $book['thumbnail'] ?>" width="100">
                      <?php else: ?>
                        No thumbnail
                      <?php endif; ?>
                    </td>
                  </tr>
                </table>
              </div>
              <div class="form-group">
                <label>Current Thumbnail</label><br>
                <img src="uploads/thumbnails/<?= $book['id'] ?>.jpg" alt="Thumbnail" width="100" height="130"
                     onerror="this.onerror=null;this.src='images/default-thumbnail.jpg';">
                <input type="file" name="thumbnail" class="form-control mt-2">
              </div>
              <div class="form-group">
                <table class="table table-bordered table-hover">
                  <label>Extra Images</label><br>
                  <tr>
                    <td>
                      <?php
                      $images = json_decode($book['extra_files'], true);
                      if ($images && is_array($images)) {
                        foreach ($images as $img) {
                          echo '<img src="' . htmlspecialchars($img) . '" width="60" class="mr-2">';
                        }
                      } else {
                        echo 'No extra images';
                      }
                      ?>
                    </td>
                  </tr>
                </table>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Update Book</button>
              <a href="books_list.php" class="btn btn-secondary">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </section>
  </div>

</div>
<?php include_once "includes/script.php"; ?>
</body>
</html>

<?php

ob_flush();

?>