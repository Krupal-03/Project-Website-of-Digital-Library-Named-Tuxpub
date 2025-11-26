<?php

ob_start();

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Manage Feedback</title>
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
            <h1 class="m-0">Manage Feedback</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Feedback</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <?php
    require 'conn.php';

    // Handle Delete
    if (isset($_GET['delete_id'])) {
        $id = intval($_GET['delete_id']);
        $stmt = $pdo->prepare("DELETE FROM feedback WHERE id=?");
        $stmt->execute([$id]);
        header("Location: delete_feedback.php?success=1");
        exit;
    }

    // Handle Search
    $search = $_GET['search'] ?? '';
    $where = '';
    $params = [];

    if (!empty($search)) {
        $where = "WHERE username LIKE ? OR feedback LIKE ?";
        $params = ["%$search%", "%$search%"];
    }

    // Fetch feedback with search
    $stmt = $pdo->prepare("SELECT * FROM feedback $where ORDER BY id DESC");
    $stmt->execute($params);
    $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Count total feedback
    $total_stmt = $pdo->query("SELECT COUNT(*) as total FROM feedback");
    $total_feedback = $total_stmt->fetch()['total'];
    ?>

    <section class="content">
      <div class="container-fluid">
        <?php if (isset($_GET['success'])): ?>
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            Feedback deleted successfully!
          </div>
        <?php endif; ?>

        <div class="row mb-3">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Feedback Management</h3>
                <div class="card-tools">
                  <a href="add_feedback.php" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add Feedback
                  </a>
                </div>
              </div>
              <div class="card-body">
                <div class="row mb-3">
                  <div class="col-md-6">
                    <form method="GET" class="form-inline">
                      <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search feedback..." 
                               value="<?= htmlspecialchars($search) ?>">
                        <div class="input-group-append">
                          <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                          </button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="col-md-6 text-right">
                    <span class="text-muted">Total: <?= $total_feedback ?> feedback entries</span>
                  </div>
                </div>

                <div class="table-responsive">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Feedback</th>
                        <th>Created At</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if ($feedbacks): ?>
                        <?php foreach ($feedbacks as $f): ?>
                          <tr>
                            <td><?= $f['id'] ?></td>
                            <td><?= htmlspecialchars($f['name']) ?></td>
                            <td><?= htmlspecialchars($f['message']) ?></td>
                            <td><?= date('M j, Y g:i A', strtotime($f['created_at'])) ?></td>
                            <td>
                              <a href="edit_single_feedback.php?id=<?= $f['id'] ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                              </a>
                              <a href="delete_feedback.php?delete_id=<?= $f['id'] ?>" 
                                 class="btn btn-danger btn-sm" 
                                 onclick="return confirm('Are you sure you want to delete this feedback?')">
                                <i class="fas fa-trash"></i> Delete
                              </a>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="5" class="text-center">
                            <?= empty($search) ? 'No feedback found.' : 'No feedback matching your search.' ?>
                          </td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
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