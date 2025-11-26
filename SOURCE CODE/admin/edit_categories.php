<?php

ob_start();

?>



<?php
require 'conn.php';

// Update category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $id = intval($_POST['update_id']);
    $name = trim($_POST['name']);
    $icon_path = null;

    if ($name === '') {
        $_SESSION['error'] = "Category name is required.";
    } else {
        // Fetch old icon
        $stmt = $pdo->prepare("SELECT icon FROM categories WHERE id=?");
        $stmt->execute([$id]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        $icon_path = $category['icon'];

        // Upload new icon
        if (isset($_FILES['icon']) && $_FILES['icon']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/uploads/categories/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $filename = time() . "_" . basename($_FILES['icon']['name']);
            $destination = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['icon']['tmp_name'], $destination)) {
                if (!empty($icon_path) && file_exists($icon_path)) {
                    unlink($icon_path);
                }
                $icon_path = 'uploads/categories/' . $filename;
            } else {
                $_SESSION['error'] = "Failed to upload icon.";
            }
        }

        if (!isset($_SESSION['error'])) {
            $stmt = $pdo->prepare("UPDATE categories SET name=?, icon=? WHERE id=?");
            if ($stmt->execute([$name, $icon_path, $id])) {
                $_SESSION['success'] = "Category updated successfully.";
            } else {
                $_SESSION['error'] = "Failed to update category.";
            }
        }
    }
    
    header("Location: edit_categories.php?id=" . $id);
    exit;
}

// Get category ID from URL if editing single category
$edit_id = isset($_GET['id']) ? intval($_GET['id']) : null;

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

// If editing single category, get that category's data
$current_category = null;
if ($edit_id) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$edit_id]);
    $current_category = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Edit Categories</title>
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
              <h1 class="m-0">Edit Categories</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item"><a href="categories_list.php">Categories</a></li>
                <li class="breadcrumb-item active">Edit Categories</li>
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
                  <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible m-3">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                      <?= htmlspecialchars($_SESSION['error']) ?>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                  <?php endif; ?>

                  <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible m-3">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                      <?= htmlspecialchars($_SESSION['success']) ?>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                  <?php endif; ?>

                  <?php if ($edit_id && $current_category): ?>
                    <!-- Single Category Edit Form -->
                    <div class="p-3 border-bottom">
                      <h5>Edit Category: <?= htmlspecialchars($current_category['name']) ?></h5>
                      <form method="post" enctype="multipart/form-data" class="row align-items-end">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" value="<?= htmlspecialchars($current_category['name']) ?>" class="form-control" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Icon</label>
                            <input type="file" name="icon" class="form-control-file">
                            <?php if ($current_category['icon']): ?>
                              <small class="form-text text-muted">
                                Current: <img src="<?= htmlspecialchars($current_category['icon']) ?>" width="30" height="30" class="ml-1">
                              </small>
                            <?php endif; ?>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <input type="hidden" name="update_id" value="<?= $current_category['id'] ?>">
                          <button type="submit" class="btn btn-primary mr-2">Update</button>
                          <a href="edit_categories.php" class="btn btn-secondary">Cancel</a>
                        </div>
                      </form>
                    </div>
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
                                   width="50" height="50"
                                   onerror="this.onerror=null;this.src='images/default-icon.png';"
                                   class="img-circle elevation-2">
                            </td>
                            <td><?= htmlspecialchars($cat['name']) ?></td>
                            <td>
                              <a href="edit_categories.php?id=<?= $cat['id'] ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Edit
                              </a>
                              <a href="delete_category.php?id=<?= $cat['id'] ?>" 
                                 class="btn btn-sm btn-danger"
                                 onclick="return confirm('Are you sure you want to delete <?= htmlspecialchars($cat['name']) ?>?')">
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
  <?php include_once "includes/script.php" ?>
</body>
</html>

<?php

ob_flush();

?>