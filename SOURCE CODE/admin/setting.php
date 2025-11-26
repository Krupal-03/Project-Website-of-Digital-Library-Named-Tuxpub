<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Settings</title>
  <?php include_once "./includes/style.php"; ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <!-- Navbar -->
    <?php include_once "includes/header.php"; ?>

    <!-- Sidebar -->
    <?php include_once "includes/sidebar.php"; ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">System Settings</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item active">Settings</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <?php
      include_once "conn.php";

      // Add new admin user
      if (isset($_POST['add_admin'])) {
        $username = trim($_POST['aname']);
        $password = $_POST['apass'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO admin (aname, apass) VALUES (?, ?)");
        if ($stmt->execute([$username, $hashed_password])) {
          $admin_success = "Admin user added successfully!";
        } else {
          $admin_error = "Failed to add admin user.";
        }
      }

      // Edit admin user
      if (isset($_POST['edit_admin'])) {
        $id = $_POST['id'];
        $username = trim($_POST['aname']);
        $password = $_POST['apass'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE admin SET aname = ?, apass = ? WHERE id = ?");
        if ($stmt->execute([$username, $hashed_password, $id])) {
          $admin_success = "Admin user updated successfully!";
        } else {
          $admin_error = "Failed to update admin user.";
        }
      }

      // Delete admin user
      if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        // Prevent deleting the last admin
        $admin_count = $pdo->query("SELECT COUNT(*) as count FROM admin")->fetch()['count'];
        if ($admin_count > 1) {
          $stmt = $pdo->prepare("DELETE FROM admin WHERE id = ?");
          if ($stmt->execute([$id])) {
            $admin_success = "Admin user deleted successfully!";
          } else {
            $admin_error = "Failed to delete admin user.";
          }
        } else {
          $admin_error = "Cannot delete the last admin user.";
        }
      }

      // Handle search for admins
      $admin_search = $_GET['admin_search'] ?? '';
      $admin_where = '';
      $admin_params = [];

      if (!empty($admin_search)) {
        $admin_where = "WHERE aname LIKE ?";
        $admin_params[] = "%$admin_search%";
      }

      // Fetch admins with search
      $admin_stmt = $pdo->prepare("SELECT * FROM admin $admin_where ORDER BY id ASC");
      $admin_stmt->execute($admin_params);
      $admins = $admin_stmt->fetchAll();

      // Handle search for books
      $book_search = $_GET['book_search'] ?? '';
      $book_where = '';
      $book_params = [];

      if (!empty($book_search)) {
        $book_where = "WHERE title LIKE ? OR author LIKE ?";
        $book_params[] = "%$book_search%";
        $book_params[] = "%$book_search%";
      }

      // Fetch books with search
      $book_stmt = $pdo->prepare("SELECT id, title, author, publish_date, thumbnail, is_latest FROM books $book_where ORDER BY id DESC");
      $book_stmt->execute($book_params);
      $books = $book_stmt->fetchAll();

      // Count latest books
      $latest_count_stmt = $pdo->query("SELECT COUNT(*) as count FROM books WHERE is_latest = 1");
      $latest_count = $latest_count_stmt->fetch()['count'];

      $total_books = count($books);
      ?>

      <section class="content">
        <div class="container-fluid">
          <!-- Success/Error Messages -->
          <?php if (isset($admin_success)): ?>
            <div class="alert alert-success alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <?= htmlspecialchars($admin_success) ?>
            </div>
          <?php endif; ?>

          <?php if (isset($admin_error)): ?>
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <?= htmlspecialchars($admin_error) ?>
            </div>
          <?php endif; ?>

          <!-- Admin Management Section -->
          <div class="row">
            <div class="col-md-12">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-users-cog"></i>
                    Admin Management
                  </h3>
                </div>
                <div class="card-body">
                  <!-- Add Admin Form -->
                  <div class="row mb-4">
                    <div class="col-md-6">
                      <div class="card card-info">
                        <div class="card-header">
                          <h3 class="card-title">Add New Admin</h3>
                        </div>
                        <form method="POST">
                          <div class="card-body">
                            <div class="form-group">
                              <label>Username</label>
                              <input type="text" name="aname" class="form-control" placeholder="Enter username" required>
                            </div>
                            <div class="form-group">
                              <label>Password</label>
                              <input type="password" name="apass" class="form-control" placeholder="Enter password" required>
                            </div>
                          </div>
                          <div class="card-footer">
                            <button type="submit" name="add_admin" class="btn btn-primary">
                              <i class="fas fa-user-plus"></i> Add Admin
                            </button>
                          </div>
                        </form>
                      </div>
                    </div>
                    
                    <div class="col-md-6">
                      <div class="card card-warning">
                        <div class="card-header">
                          <h3 class="card-title">Security Utilities</h3>
                        </div>
                        <div class="card-body">
                          <p class="mb-3">Migrate plain text passwords to secure hashed passwords for better security.</p>
                          <a href="migrate_passwords.php" class="btn btn-warning" onclick="return confirm('This will hash all plain text passwords. Continue?')">
                            <i class="fas fa-shield-alt mr-2"></i>Migrate Passwords to Hash
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Admin List with Search -->
                  <div class="card card-secondary">
                    <div class="card-header">
                      <h3 class="card-title">Manage Admin Users</h3>
                      <div class="card-tools">
                        <span class="badge badge-light">Total: <?= count($admins) ?> admins</span>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="row mb-3">
                        <div class="col-md-6">
                          <form method="GET" class="form-inline">
                            <div class="input-group" style="width: 300px;">
                              <input type="text" name="admin_search" class="form-control" placeholder="Search admins..." 
                                     value="<?= htmlspecialchars($admin_search) ?>">
                              <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                  <i class="fas fa-search"></i>
                                </button>
                              </div>
                            </div>
                            <?php if (!empty($admin_search)): ?>
                              <a href="setting.php" class="btn btn-default ml-2">Clear</a>
                            <?php endif; ?>
                          </form>
                        </div>
                      </div>

                      <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Username</th>
                              <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($admins as $admin): ?>
                              <tr>
                                <td><?= $admin['id'] ?></td>
                                <td>
                                  <?= htmlspecialchars($admin['aname']) ?>
                                  <?php if ($admin['id'] == 6): ?>
                                    <span class="badge badge-primary ml-2">Default</span>
                                  <?php endif; ?>
                                </td>
                                <td>
                                  <div class="btn-group">
                                    <button class="btn btn-info btn-sm" 
                                            onclick="showEditForm(<?= $admin['id'] ?>, '<?= htmlspecialchars($admin['aname']) ?>')"
                                            title="Edit Admin">
                                      <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <?php if ($admin['id'] != 6 && count($admins) > 1): ?>
                                      <a href="?delete=<?= $admin['id'] ?>" class="btn btn-danger btn-sm"
                                         onclick="return confirm('Are you sure you want to delete this admin user?');"
                                         title="Delete Admin">
                                        <i class="fas fa-trash"></i> Delete
                                      </a>
                                    <?php else: ?>
                                      <button class="btn btn-danger btn-sm" disabled title="Cannot delete default or last admin">
                                        <i class="fas fa-trash"></i> Delete
                                      </button>
                                    <?php endif; ?>
                                  </div>
                                </td>
                              </tr>
                            <?php endforeach; ?>
                            
                            <?php if (empty($admins)): ?>
                              <tr>
                                <td colspan="3" class="text-center">
                                  <?= empty($admin_search) ? 'No admin users found.' : 'No admins matching your search.' ?>
                                </td>
                              </tr>
                            <?php endif; ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>

                  <!-- Edit Admin Form (Hidden) -->
                  <div class="card card-warning mt-3" id="editFormCard" style="display: none;">
                    <div class="card-header">
                      <h3 class="card-title">
                        <i class="fas fa-edit"></i>
                        Edit Admin User
                      </h3>
                    </div>
                    <form method="POST">
                      <div class="card-body">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="form-group">
                          <label>Username</label>
                          <input type="text" name="aname" id="edit_aname" class="form-control" required>
                        </div>
                        <div class="form-group">
                          <label>New Password</label>
                          <input type="password" name="apass" id="edit_apass" class="form-control" 
                                 placeholder="Enter new password" required>
                          <small class="form-text text-muted">Enter a new password for this admin user.</small>
                        </div>
                      </div>
                      <div class="card-footer">
                        <button type="submit" name="edit_admin" class="btn btn-warning">
                          <i class="fas fa-save"></i> Update Admin
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="hideEditForm()">
                          <i class="fas fa-times"></i> Cancel
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Book Management Section -->
          <div class="row mt-4">
            <div class="col-md-12">
              <div class="card card-success">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-book"></i>
                    Hero Section Book Selection
                  </h3>
                  <div class="card-tools">
                    <span class="badge badge-success"><?= $latest_count ?> latest books</span>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row mb-3">
                    <div class="col-md-6">
                      <form method="GET" class="form-inline">
                        <div class="input-group" style="width: 300px;">
                          <input type="text" name="book_search" class="form-control" placeholder="Search books..." 
                                 value="<?= htmlspecialchars($book_search) ?>">
                          <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                              <i class="fas fa-search"></i>
                            </button>
                          </div>
                        </div>
                        <?php if (!empty($book_search)): ?>
                          <a href="setting.php" class="btn btn-default ml-2">Clear</a>
                        <?php endif; ?>
                      </form>
                    </div>
                    <div class="col-md-6 text-right">
                      <span class="text-muted">Showing: <?= $total_books ?> books</span>
                    </div>
                  </div>

                  <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                      <thead>
                        <tr>
                          <th>Thumbnail</th>
                          <th>Title</th>
                          <th>Author</th>
                          <th>Publish Date</th>
                          <th>Latest Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($books as $book): ?>
                          <tr>
                            <td>
                              <img src="<?= htmlspecialchars($book['thumbnail']) ?>" 
                                   width="60" height="80" 
                                   class="img-thumbnail"
                                   onerror="this.src='./uploads/default-thumbnail.jpg'"
                                   alt="<?= htmlspecialchars($book['title']) ?>">
                            </td>
                            <td>
                              <strong><?= htmlspecialchars($book['title']) ?></strong>
                            </td>
                            <td><?= htmlspecialchars($book['author']) ?></td>
                            <td><?= $book['publish_date'] ? date('M j, Y', strtotime($book['publish_date'])) : 'N/A' ?></td>
                            <td>
                              <?php if ($book['is_latest']): ?>
                                <span class="badge badge-success">
                                  <i class="fas fa-star"></i> Latest
                                </span>
                              <?php else: ?>
                                <span class="badge badge-secondary">Regular</span>
                              <?php endif; ?>
                            </td>
                            <td>
                              <?php if (!$book['is_latest']): ?>
                                <a href="mark_latest.php?id=<?= $book['id'] ?>" 
                                   class="btn btn-info btn-sm"
                                   onclick="return confirm('Mark \"<?= addslashes($book['title']) ?>\" as latest book?')">
                                  <i class="fas fa-star"></i> Mark as Latest
                                </a>
                              <?php else: ?>
                                <a href="mark_regular.php?id=<?= $book['id'] ?>" 
                                   class="btn btn-warning btn-sm"
                                   onclick="return confirm('Remove \"<?= addslashes($book['title']) ?>\" from latest books?')">
                                  <i class="fas fa-star-half-alt"></i> Remove Latest
                                </a>
                              <?php endif; ?>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($books)): ?>
                          <tr>
                            <td colspan="6" class="text-center">
                              <?= empty($book_search) ? 'No books found.' : 'No books matching your search.' ?>
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

      <!-- Edit Admin JS -->
      <script>
        function showEditForm(id, username) {
          document.getElementById('edit_id').value = id;
          document.getElementById('edit_aname').value = username;
          document.getElementById('edit_apass').value = '';
          document.getElementById('editFormCard').style.display = 'block';
          // Scroll to edit form
          document.getElementById('editFormCard').scrollIntoView({ behavior: 'smooth' });
        }
        
        function hideEditForm() {
          document.getElementById('editFormCard').style.display = 'none';
        }
      </script>

    </div> <!-- /.content-wrapper -->

    <!-- Footer -->

  </div> <!-- ./wrapper -->

  <?php include_once "includes/script.php"; ?>
</body>
</html>