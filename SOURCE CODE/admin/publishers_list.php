<?php
require 'conn.php';

// Handle delete action
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    // Check if any book uses this publisher
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM books WHERE publisher_id = ?");
    $stmt->execute([$delete_id]);
    $count = $stmt->fetchColumn();  

    if ($count > 0) {
        $_SESSION['error'] = "⚠️ Cannot delete this publisher because it is assigned to $count book(s).";
    } else {
        // Fetch publisher icon
        $stmt = $pdo->prepare("SELECT icon FROM publishers WHERE id = ?");
        $stmt->execute([$delete_id]);
        $publisher = $stmt->fetch(PDO::FETCH_ASSOC);

        // Delete icon file if it exists
        if ($publisher && !empty($publisher['icon']) && file_exists($publisher['icon'])) {
            unlink($publisher['icon']);
        }

        // Delete publisher record
        $stmt = $pdo->prepare("DELETE FROM publishers WHERE id = ?");
        $stmt->execute([$delete_id]);

        $_SESSION['success'] = "✅ Publisher deleted successfully.";
    }

    // Redirect to avoid form resubmission
    header("Location: publishers_list.php");
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

// Fetch all publishers with search
$stmt = $pdo->prepare("SELECT * FROM publishers $where ORDER BY id DESC");
$stmt->execute($params);
$publishers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Publishers</title>
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
              <h1 class="m-0">Publishers</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item active">Publishers</li>
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
                  <h3 class="card-title">All Publishers</h3>
                  <div class="card-tools">
                    <div class="input-group input-group-sm ml-2" >
                      <form method="GET" class="form-inline">
                        <input type="text" name="search" class="form-control float-right" placeholder="Search publishers..." value="<?= htmlspecialchars($search) ?>">
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
                      <?php if (empty($publishers)): ?>
                        <tr>
                          <td colspan="4" class="text-center py-4">No publishers found.</td>
                        </tr>
                      <?php else: ?>
                        <?php foreach ($publishers as $pub): ?>
                          <tr>
                            <td><?= $pub['id'] ?></td>
                            <td>
                              <img src="<?= htmlspecialchars($pub['icon'] ?: 'images/default-icon.png') ?>" 
                                   alt="Icon" width="50" height="50"
                                   onerror="this.onerror=null;this.src='images/default-icon.png';"
                                   class="img-circle elevation-2">
                            </td>
                            <td><?= htmlspecialchars($pub['name']) ?></td>
                            <td>
                              <a href="edit_publisher.php?id=<?= $pub['id'] ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Edit
                              </a>
                              <a href="publishers_list.php?delete_id=<?= $pub['id'] ?>" 
                                 class="btn btn-sm btn-danger"
                                 onclick="return confirm('Are you sure you want to delete <?= htmlspecialchars($pub['name']) ?>?');">
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