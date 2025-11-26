<?php

ob_start();

?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Delete Donated Books</title>
  <!-- styles links -->
  <?php include_once "./includes/style.php"; ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <?php include_once "includes/header.php" ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php include_once "includes/sidebar.php" ?>
  <!-- ./ Main Sidebar Container -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Delete Donated Books</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="donated_books_approval.php">Donated Books</a></li>
              <li class="breadcrumb-item active">Delete Books</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <?php
    require 'conn.php';

    // Handle Search
    $search = $_GET['search'] ?? '';
    $status_filter = $_GET['status'] ?? '';

    $where = [];
    $params = [];

    if (!empty($search)) {
        $where[] = "(db.title LIKE ? OR db.author LIKE ? OR db.donated_by LIKE ? OR p.name LIKE ?)";
        $params[] = "%$search%";
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

    // Fetch all donated books with publisher name and search
    $stmt = $pdo->prepare("SELECT db.*, p.name as publisher_name 
                         FROM donated_books db
                         LEFT JOIN publishers p ON db.publisher_id = p.id
                         $where_clause
                         ORDER BY db.created_at DESC");
    $stmt->execute($params);
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Count books by status
    $count_stmt = $pdo->prepare("SELECT status, COUNT(*) as count FROM donated_books GROUP BY status");
    $count_stmt->execute();
    $status_counts = $count_stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    $total_books = count($books);
    ?>

    <section class="content">
      <div class="container-fluid">
        <?php if (isset($_GET['success'])): ?>
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            Book deleted successfully!
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
            <h3 class="card-title">Delete Donated Books</h3>
            <div class="card-tools">
              <a href="donated_books_approval.php" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Approval
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
                    <a href="delete_donated_book.php" class="btn btn-default">Clear Filters</a>
                  <?php endif; ?>
                </form>
              </div>
              <div class="col-md-4 text-right">
                <span class="text-muted">Showing: <?= $total_books ?> books</span>
              </div>
            </div>

            <div class="alert alert-warning">
              <i class="icon fas fa-exclamation-triangle"></i>
              <strong>Warning:</strong> This page allows permanent deletion of donated books. Deleted books cannot be recovered.
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
                    <th>Publish Date</th>
                    <th>Status</th>
                    <th>File</th>
                    <th>Delete Action</th>
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
                      <td><?= $book['publish_date'] ? date('M j, Y', strtotime($book['publish_date'])) : 'N/A' ?></td>
                      <td>
                        <?php if ($book['status'] === 'pending'): ?>
                          <span class="badge badge-warning">Pending</span>
                        <?php elseif ($book['status'] === 'approved'): ?>
                          <span class="badge badge-success">Approved</span>
                        <?php else: ?>
                          <span class="badge badge-danger">Rejected</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <a href="<?= htmlspecialchars($book['file_path']) ?>" class="btn btn-sm btn-primary" target="_blank">
                          <i class="fas fa-eye"></i> View
                        </a>
                      </td>
                      <td>
                        <a href="delete_donated_book_action.php?id=<?= $book['id'] ?>" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirm('⚠️ WARNING: This will permanently delete this book and all associated files!\\n\\nBook: <?= addslashes($book['title']) ?>\\n\\nThis action cannot be undone!\\n\\nAre you absolutely sure?');">
                          <i class="fas fa-trash"></i> Delete Permanently
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>

                  <?php if (empty($books)): ?>
                    <tr>
                      <td colspan="9" class="text-center">
                        <?= (empty($search) && empty($status_filter)) ? 'No donated books found.' : 'No books matching your search criteria.' ?>
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

  <!-- Footer -->
</div>

<!-- jQuery -->
<?php include_once "includes/script.php" ?>
</body>
</html>

<?php

ob_flush();

?>