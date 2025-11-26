<?php

ob_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Edit Feedback</title>
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
            <h1 class="m-0">Edit Feedback</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="feedback_list.php">Feedback</a></li>
              <li class="breadcrumb-item active">Edit Feedback</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <?php
    require 'conn.php';

    if (!isset($_GET['id'])) {
        header("Location: feedback_list.php");
        exit;
    }

    $id = intval($_GET['id']);

    // Fetch feedback
    $stmt = $pdo->prepare("SELECT * FROM feedback WHERE id=?");
    $stmt->execute([$id]);
    $feedback = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$feedback) {
        header("Location: feedback_list.php");
        exit;
    }

    $errors = [];
    $success = "";

    // Handle update
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $feedback_text = trim($_POST['feedback'] ?? '');

        // Validation
        if (empty($username)) {
            $errors[] = "Username is required.";
        }
        if (empty($feedback_text)) {
            $errors[] = "Feedback content is required.";
        }

        if (empty($errors)) {
            $stmt = $pdo->prepare("UPDATE feedback SET name=?, message=?, updated_at=NOW() WHERE id=?");
            $stmt->execute([$username, $feedback_text, $id]);
            $success = "Feedback updated successfully!";
            
            // Refresh feedback data
            $stmt = $pdo->prepare("SELECT * FROM feedback WHERE id=?");
            $stmt->execute([$id]);
            $feedback = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
    ?>

    <section class="content">
      <div class="container-fluid">
        <?php if ($errors): ?>
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-exclamation-triangle"></i> Error!</h5>
            <?php foreach ($errors as $error): ?>
              <div><?= htmlspecialchars($error) ?></div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <?php if ($success): ?>
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check"></i> Success!</h5>
            <?= htmlspecialchars($success) ?>
          </div>
        <?php endif; ?>

        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-edit"></i>
                  Edit Feedback #<?= $feedback['id'] ?>
                </h3>
                <div class="card-tools">
                  <a href="feedback_list.php" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to List
                  </a>
                </div>
              </div>
              <form method="post">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="name" name="username" 
                               value="<?= htmlspecialchars($feedback['name']) ?>" 
                               placeholder="Enter username" required>
                        <small class="form-text text-muted">
                          The name of the person who provided the feedback
                        </small>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Created Date</label>
                        <input type="text" class="form-control" 
                               value="<?= date('M j, Y g:i A', strtotime($feedback['created_at'])) ?>" 
                               readonly>
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="feedback">Feedback Content</label>
                    <textarea class="form-control" id="feedback" name="feedback" rows="6" 
                              placeholder="Enter feedback content" required><?= htmlspecialchars($feedback['message']) ?></textarea>
                    <small class="form-text text-muted">
                      The actual feedback message provided by the user
                    </small>
                  </div>

                  <?php if (!empty($feedback['updated_at']) && $feedback['updated_at'] != $feedback['created_at']): ?>
                    <div class="form-group">
                      <label>Last Updated</label>
                      <input type="text" class="form-control" 
                             value="<?= date('M j, Y g:i A', strtotime($feedback['updated_at'])) ?>" 
                             readonly>
                    </div>
                  <?php endif; ?>
                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Feedback
                  </button>
                  <a href="feedback_list.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                  </a>
                  <a href="feedback_list.php?delete_id=<?= $feedback['id'] ?>" 
                     class="btn btn-danger float-right"
                     onclick="return confirm('Are you sure you want to delete this feedback? This action cannot be undone.')">
                    <i class="fas fa-trash"></i> Delete
                  </a>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Additional Information Card -->
        <div class="row">
          <div class="col-md-12">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-info-circle"></i>
                  Feedback Information
                </h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-3">
                    <strong>Feedback ID:</strong> #<?= $feedback['id'] ?>
                  </div>
                  <div class="col-md-3">
                    <strong>Username:</strong> <?= htmlspecialchars($feedback['name']) ?>
                  </div>
                  <div class="col-md-3">
                    <strong>Created:</strong> <?= date('M j, Y', strtotime($feedback['created_at'])) ?>
                  </div>
                  <div class="col-md-3">
                    <strong>Characters:</strong> <?= strlen($feedback['message']) ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Footer -->
  <?php include_once "./includes/footer.php"; ?>
</div>

<!-- jQuery -->
<?php include_once "includes/script.php" ?>

<script>
$(document).ready(function() {
  // Character count for feedback textarea
  $('#feedback').on('input', function() {
    const charCount = $(this).val().length;
    $('strong:contains("Characters:")').parent().html('<strong>Characters:</strong> ' + charCount);
  });

  // Form submission confirmation
  $('form').on('submit', function() {
    const username = $('#username').val().trim();
    const feedback = $('#feedback').val().trim();
    
    if (username === '' || feedback === '') {
      alert('Please fill in all required fields.');
      return false;
    }
    
    return true;
  });
});
</script>

</body>
</html>
<?php

ob_flush();

?>