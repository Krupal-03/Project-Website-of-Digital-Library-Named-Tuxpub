<?php

ob_start();

?>



<?php
require 'conn.php';

// Handle deletion before output
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);

    // Delete icon file if exists
    $stmt = $pdo->prepare("SELECT icon FROM categories WHERE id=?");
    $stmt->execute([$id]);
    $cat = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($cat && file_exists($cat['icon'])) unlink($cat['icon']);

    // Set books.category_id to NULL
    $stmt = $pdo->prepare("UPDATE books SET category_id=NULL WHERE category_id=?");
    $stmt->execute([$id]);

    // Delete category
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id=?");
    $stmt->execute([$id]);

    $_SESSION['success'] = "Category deleted successfully.";
    header("Location: categories_list.php");
    exit;
}

// Handle search
$search = $_GET['search'] ?? '';
$where = '';
$params = [];

if (!empty($search)) {
    $where = "WHERE name LIKE ?";
    $params = ["%$search%"];
}

// Fetch categories with search
$stmt = $pdo->prepare("SELECT * FROM categories $where ORDER BY id DESC");
$stmt->execute($params);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Categories List</title>
  <!-- styles links -->
  <?php  include_once "./includes/style.php"; ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <?php   include_once "includes/header.php" ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php   include_once "includes/sidebar.php" ?>
  <!-- ./ Main Sidebar Container -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Categories List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Categories List</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">All Categories</h3>
                <div class="card-tools">
                  <div class="input-group input-group-sm ml-2" >
                    <form method="GET" class="form-inline">
                      <input type="text" name="search" class="form-control float-right" placeholder="Search categories..." value="<?= htmlspecialchars($search) ?>">
                      <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                          <i class="fas fa-search"></i>
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <div class="card-body table-responsive p-0">
                <!-- Alert Messages -->
                <?php if (isset($_SESSION['success'])): ?>
                  <div class="alert alert-success alert-dismissible m-3">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?= htmlspecialchars($_SESSION['success']) ?>
                  </div>
                  <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Icon</th>
                      <th>Name</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($categories)): ?>
                      <tr>
                        <td colspan="4" class="text-center py-4">No categories found.</td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($categories as $cat): ?>
                        <tr>
                          <td><?= $cat['id'] ?></td>
                          <td>
                            <img src="<?= htmlspecialchars($cat['icon'] ?: 'images/default-icon.png') ?>" 
                                 alt="Icon" width="50" height="50"
                                 onerror="this.onerror=null;this.src='images/default-icon.png';"
                                 class="img-circle elevation-2">
                          </td>
                          <td><?= htmlspecialchars($cat['name']) ?></td>
                          <td>
                            <a href="edit_categories.php?id=<?= $cat['id'] ?>" class="btn btn-sm btn-warning">
                              <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="categories_list.php?delete_id=<?= $cat['id'] ?>" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Are you sure you want to delete <?= htmlspecialchars($cat['name']) ?>?');">
                              <i class="fas fa-trash"></i> Delete
                            </a>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
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
<?php   include_once "includes/script.php" ?>
</body>
</html>

<?php

ob_flush();

?>