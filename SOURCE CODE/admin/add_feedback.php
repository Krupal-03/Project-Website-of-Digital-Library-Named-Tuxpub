<?php

ob_start();

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Add Feedback</title>
  <!-- styles links -->
  <?php include_once "./includes/style.php"; ?>
  <!-- /.styles links -->
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
            <h1 class="m-0">Add Feedback</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="feedback_list.php">Feedback</a></li>
              <li class="breadcrumb-item active">Add Feedback</li>
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
        $username = trim($_POST['username'] ?? '');
        $feedback = trim($_POST['feedback'] ?? '');

        if ($username === '') $errors[] = "Username is required.";
        if ($feedback === '') $errors[] = "Feedback is required.";

        if (empty($errors)) {
            $stmt = $pdo->prepare("INSERT INTO feedback (name, message) VALUES (?, ?)");
            $stmt->execute([$username, $feedback]);
            $success = "Feedback added successfully.";
            $username = $feedback = '';
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
            <h3 class="card-title">Add New Feedback</h3>
          </div>
          <form method="post">
            <div class="card-body">
              <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" 
                       value="<?= htmlspecialchars($username ?? '') ?>" required>
              </div>
              <div class="form-group">
                <label for="feedback">Feedback</label>
                <textarea class="form-control" id="feedback" name="feedback" rows="4" required><?= htmlspecialchars($feedback ?? '') ?></textarea>
              </div>
            </div>
            <div class="card-footer">
              <button class="btn btn-primary" type="submit">
                <i class="fas fa-plus"></i> Add Feedback
              </button>
              <a href="feedback_list.php" class="btn btn-secondary">
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