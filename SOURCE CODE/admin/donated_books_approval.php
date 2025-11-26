<?php

ob_start();

?>


<?php
require 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ✅ Approve donated book → copy to books table
    if (isset($_POST['approve_id'])) {
        $id = $_POST['approve_id'];

        // Fetch book info
        $stmt = $pdo->prepare("SELECT * FROM donated_books WHERE id = ?");
        $stmt->execute([$id]);
        $book = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($book) {
            // 1️⃣ Update status
            $update = $pdo->prepare("UPDATE donated_books SET status = 'approved' WHERE id = ?");
            $update->execute([$id]);

            $insert = $pdo->prepare("
                INSERT INTO books (
                    title, author, isbn, language, edition, paperback, 
                    description, publish_date, upload_date, file_path, thumbnail, extra_files, 
                    publisher_id, category_id, is_latest, downloads
                ) VALUES (
                    :title, :author, :isbn, :language,  :edition, :paperback, 
                    :description, :publish_date, current_timestamp(), :file_path, :thumbnail, :extra_files, 
                    :publisher_id, :category_id, :is_latest, :downloads
                )
            ");

            $insert->execute([
                ':title'        => $book['title'] ?? '',
                ':author'       => $book['author'] ?: 'Unknown',
                ':isbn'         => $book['isbn'] ?: 'Unknown',
                ':language'     => $book['language'] ?: 'Unknown',
                ':edition'      => $book['edition'] ?: 'Unknown',
                ':paperback'    => $book['paperback'] ?: 'Unknown',
                ':description'  => $book['description'] ?: 'No description available.',
                ':publish_date' => $book['publish_date'] ?? null,
                ':file_path'    => $book['file_path'] ?? '',
                ':thumbnail'    => $book['thumbnail_path'] ?? $book['thumbnail'] ?? null,
                ':extra_files'  => $book['extra_images'] ?? null,
                ':publisher_id' => $book['publisher_id'] ?? null,
                ':category_id'  => $book['category_id'] ?? null,
                ':is_latest'    => 0,
                ':downloads'    => 0
            ]);
        }
    }

    // ❌ Delete donated book + files
    if (isset($_POST['delete_id'])) {
        $id = $_POST['delete_id'];

        $stmt = $pdo->prepare("SELECT file_path, thumbnail_path, extra_images FROM donated_books WHERE id = ?");
        $stmt->execute([$id]);
        $fileData = $stmt->fetch(PDO::FETCH_ASSOC);

        $baseDir = __DIR__ . '/uploads/donated_books/';

        // delete pdf/epub
        if (!empty($fileData['file_path'])) {
            $path = $baseDir . basename($fileData['file_path']);
            if (file_exists($path)) unlink($path);
        }

        // delete thumbnail
        if (!empty($fileData['thumbnail_path'])) {
            $path = $baseDir . basename($fileData['thumbnail_path']);
            if (file_exists($path)) unlink($path);
        }

        // delete extra images
        if (!empty($fileData['extra_images'])) {
            $images = explode(",", $fileData['extra_images']);
            foreach ($images as $img) {
                $path = $baseDir . basename(trim($img));
                if (file_exists($path)) unlink($path);
            }
        }

        // delete from db
        $stmt = $pdo->prepare("DELETE FROM donated_books WHERE id = ?");
        $stmt->execute([$id]);
        
        header("Location: donated_books_approval.php?success=2");
        exit;
    }
}

// Handle Search
$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? '';

$where = [];
$params = [];

if (!empty($search)) {
    $where[] = "(db.title LIKE ? OR db.author LIKE ? OR db.donated_by LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($status_filter)) {
    $where[] = "db.status = ?";
    $params[] = $status_filter;
}

$where_clause = '';
if (!empty($where)) {
    $where_clause = "WHERE " . implode(" AND ", $where);
}

// 📚 Fetch all donated books with search
$books = $pdo->prepare("
    SELECT db.*, p.name AS publisher_name 
    FROM donated_books db
    LEFT JOIN publishers p ON db.publisher_id = p.id
    $where_clause
    ORDER BY db.created_at DESC
");
$books->execute($params);
$books = $books->fetchAll(PDO::FETCH_ASSOC);

// Count books by status
$count_stmt = $pdo->prepare("SELECT status, COUNT(*) as count FROM donated_books GROUP BY status");
$count_stmt->execute();
$status_counts = $count_stmt->fetchAll(PDO::FETCH_KEY_PAIR);

$total_books = count($books);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Donated Books Approval</title>
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
              <h1 class="m-0">Donated Books Approval</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item active">Donated Books Approval</li>
              </ol>
            </div>
          </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php if ($_GET['success'] == 1): ?>
                        Book approved successfully!
                    <?php elseif ($_GET['success'] == 2): ?>
                        Book deleted successfully!
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?= $status_counts['pending'] ?? 0 ?></h3>
                            <p>Pending Books</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?= $status_counts['approved'] ?? 0 ?></h3>
                            <p>Approved Books</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3><?= $status_counts['rejected'] ?? 0 ?></h3>
                            <p>Rejected Books</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-times"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3><?= $total_books ?></h3>
                            <p>Total Books</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Donated Books Management</h3>
                    <div class="card-tools">
                        <a href="donated_books_list.php" class="btn btn-secondary btn-sm">
                            <i class="fas fa-list"></i> View All Books
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <form method="GET" class="form-inline">
                                <div class="input-group mr-2" style="width: 300px;">
                                    <input type="text" name="search" class="form-control" placeholder="Search books..." 
                                           value="<?= htmlspecialchars($search) ?>">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <select name="status" class="form-control mr-2" onchange="this.form.submit()">
                                    <option value="">All Status</option>
                                    <option value="pending" <?= $status_filter == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="approved" <?= $status_filter == 'approved' ? 'selected' : '' ?>>Approved</option>
                                    <option value="rejected" <?= $status_filter == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                </select>
                                <?php if (!empty($search) || !empty($status_filter)): ?>
                                    <a href="donated_books_approval.php" class="btn btn-default">Clear Filters</a>
                                <?php endif; ?>
                            </form>
                        </div>
                        <div class="col-md-4 text-right">
                            <span class="text-muted">Showing: <?= $total_books ?> books</span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Publisher</th>
                                    <th>Donated By</th>
                                    <th>Status</th>
                                    <th>Uploaded</th>
                                    <th>File</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($books as $book): ?>
                                    <tr>
                                        <td><?= $book['id'] ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($book['title']) ?></strong>
                                            <?php if ($book['isbn'] && $book['isbn'] !== 'Unknown'): ?>
                                                <br><small class="text-muted">ISBN: <?= htmlspecialchars($book['isbn']) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($book['author']) ?></td>
                                        <td><?= htmlspecialchars($book['publisher_name'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($book['donated_by']) ?></td>
                                        <td>
                                            <?php if ($book['status'] === 'pending'): ?>
                                                <span class="badge badge-warning">Pending</span>
                                            <?php elseif ($book['status'] === 'approved'): ?>
                                                <span class="badge badge-success">Approved</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Rejected</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= date('M j, Y g:i A', strtotime($book['created_at'])) ?></td>
                                        <td>
                                            <a href="<?= htmlspecialchars($book['file_path']) ?>" class="btn btn-sm btn-primary" target="_blank">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <?php if ($book['status'] === 'pending'): ?>
                                                    <form method="post" class="d-inline">
                                                        <input type="hidden" name="approve_id" value="<?= $book['id'] ?>">
                                                        <button class="btn btn-success btn-sm" title="Approve Book">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>

                                                <?php if ($book['status'] === 'approved'): ?>
                                                    <a href="edit_donated_book.php?id=<?= $book['id'] ?>" class="btn btn-primary btn-sm" title="Edit Book">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <form method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this book and all associated files?');">
                                                    <input type="hidden" name="delete_id" value="<?= $book['id'] ?>">
                                                    <button class="btn btn-danger btn-sm" title="Delete Book">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                                <?php if (empty($books)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            <?= (empty($search) && empty($status_filter)) ? 'No donated books yet.' : 'No books matching your search criteria.' ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
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

<?php

ob_flush();

?>