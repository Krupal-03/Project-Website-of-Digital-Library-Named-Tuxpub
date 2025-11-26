<?php

ob_start();

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Dashboard</title>
  <!-- styles links -->
  <?php include_once "./includes/style.php"; ?>
  <!-- /.styles links -->
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <!-- Preloader -->

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
              <h1 class="m-0">Edit Books</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item active">Edit Books</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <?php
      require 'conn.php';

      // Handle search
      $search = $_GET['search'] ?? '';
      $where = '';
      $params = [];

      if (!empty($search)) {
        $where = "WHERE title LIKE ? OR author LIKE ?";
        $params = ["%$search%", "%$search%"];
      }

      // Fetch books with search
      $stmt = $pdo->prepare("SELECT id, title, author, publish_date, file_path, thumbnail FROM books $where ORDER BY id DESC");
      $stmt->execute($params);
      $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
      ?>

      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">All Books</h3>
                  <div class="card-tools">
                    <div class="input-group input-group-sm">
                      <form method="GET" class="form-inline">
                        <input type="text" name="search" class="form-control float-right" placeholder="Search by title or author" value="<?= htmlspecialchars($search) ?>">
                        <div class="input-group-append">
                          <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                          </button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>Thumbnail</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Publish Date</th>
                        <th>View PDF</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($books as $book): ?>
                        <tr>
                          <td>
                            <img src="./<?= $book['thumbnail'] ?>" alt="Thumbnail"
                              onerror="this.onerror=null;this.src='./uploads/thumbnails/default-thumbnail.png';" width="60" height="80">
                          </td>
                          <td><?= htmlspecialchars($book['title']) ?></td>
                          <td><?= htmlspecialchars($book['author']) ?></td>
                          <td><?= htmlspecialchars($book['publish_date']) ?></td>
                          <td>
                            <a href="<?= htmlspecialchars($book['file_path']) ?>" class="btn btn-sm btn-primary" target="_blank">View PDF</a>
                          </td>
                          <td>
                            <a href="edit_book.php?id=<?= $book['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                      <?php if (empty($books)): ?>
                        <tr>
                          <td colspan="6" class="text-center">No books found.</td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
          </div>
        </div>
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <?php include_once "includes/script.php" ?>
  <!-- ./ jQuery -->
</body>
</html>

<?php

ob_flush();

?>