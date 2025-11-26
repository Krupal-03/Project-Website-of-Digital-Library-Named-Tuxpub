<?php

ob_start();

?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Add Category</title>
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
            <h1 class="m-0">Add Category</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="categories_list.php">Categories</a></li>
              <li class="breadcrumb-item active">Add Category</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <?php
    require 'conn.php';
    $errors = [];
    $success = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $icon_path = null;

        if ($name === '') $errors[] = "Category name is required.";

        // Handle icon upload
        if (isset($_FILES['icon']) && $_FILES['icon']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/uploads/categories/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $filename = time() . "_" . basename($_FILES['icon']['name']);
            $destination = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['icon']['tmp_name'], $destination)) {
                $icon_path = 'uploads/categories/' . $filename;
            } else {
                $errors[] = "Failed to upload icon.";
            }
        }

        if (empty($errors)) {
            $stmt = $pdo->prepare("INSERT INTO categories (name, icon) VALUES (?, ?)");
            if ($stmt->execute([$name, $icon_path])) {
                $success = "Category added successfully.";
                $name = '';
            } else {
                $errors[] = "Failed to add category.";
            }
        }
    }
    ?>

    <section class="content">
      <div class="container-fluid">
        <?php if($errors): ?>
          <div class="alert alert-danger">
            <?= implode("<br>", $errors) ?>
          </div>
        <?php endif; ?>
        
        <?php if($success): ?>
          <div class="alert alert-success">
            <?= $success ?>
          </div>
        <?php endif; ?>

        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Add New Category</h3>
          </div>
          <form method="post" enctype="multipart/form-data">
            <div class="card-body">
              <div class="form-group">
                <label for="name">Category Name</label>
                <input type="text" class="form-control" id="name" name="name" 
                       value="<?= htmlspecialchars($name ?? '') ?>" required>
              </div>
              <div class="form-group">
                <label for="icon">Category Icon</label>
                <input type="file" class="form-control-file" id="icon" name="icon" accept="image/*">
                <small class="form-text text-muted">Supported formats: PNG, JPG, JPEG, GIF</small>
              </div>
            </div>
            <div class="card-footer">
              <button class="btn btn-primary" type="submit">
                <i class="fas fa-plus"></i> Add Category
              </button>
              <a href="categories_list.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
              </a>
            </div>
          </form>
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