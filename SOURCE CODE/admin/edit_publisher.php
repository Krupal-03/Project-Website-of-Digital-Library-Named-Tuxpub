<?php

ob_start();

?>
<?php
require 'conn.php';

// Handle update if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $id = intval($_POST['update_id']);
    $name = trim($_POST['name']);

    $errors = [];
    $success = "";

    if ($name === '') {
        $errors[] = "Publisher name is required.";
    } else {
        // Get old icon
        $stmt = $pdo->prepare("SELECT icon FROM publishers WHERE id = ?");
        $stmt->execute([$id]);
        $publisher = $stmt->fetch(PDO::FETCH_ASSOC);

        $icon_path = $publisher['icon'];

        // Handle new icon upload
        if (isset($_FILES['icon']) && $_FILES['icon']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
            $mime = mime_content_type($_FILES['icon']['tmp_name']);
            if (!in_array($mime, $allowed)) {
                $errors[] = "Icon must be an image (png, jpg, jpeg, gif).";
            } else {
                $uploadDir = __DIR__ . '/uploads/publishers/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

                $filename = time() . "_" . basename($_FILES['icon']['name']);
                $destination = $uploadDir . $filename;

                if (move_uploaded_file($_FILES['icon']['tmp_name'], $destination)) {
                    if (!empty($icon_path) && file_exists($icon_path)) {
                        unlink($icon_path);
                    }
                    $icon_path = 'uploads/publishers/' . $filename;
                } else {
                    $errors[] = "Failed to upload icon.";
                }
            }
        }

        if (empty($errors)) {
            $stmt = $pdo->prepare("UPDATE publishers SET name = ?, icon = ? WHERE id = ?");
            if ($stmt->execute([$name, $icon_path, $id])) {
                $success = "Publisher updated successfully.";
            } else {
                $errors[] = "Failed to update publisher.";
            }
        }
    }
}

// Get publisher ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid publisher ID.");
}

$id = intval($_GET['id']);

// Fetch publisher data
$stmt = $pdo->prepare("SELECT * FROM publishers WHERE id = ?");
$stmt->execute([$id]);
$publisher = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$publisher) {
    die("Publisher not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Edit Publisher</title>
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
                            <h1 class="m-0">Edit Publisher</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="publishers_list.php">Publishers</a></li>
                                <li class="breadcrumb-item active">Edit Publisher</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <?php if(isset($errors) && $errors): ?>
                        <div class="alert alert-danger">
                            <?= implode("<br>", $errors) ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(isset($success) && $success): ?>
                        <div class="alert alert-success">
                            <?= $success ?>
                        </div>
                    <?php endif; ?>

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Publisher: <?= htmlspecialchars($publisher['name']) ?></h3>
                        </div>
                        <form method="post" enctype="multipart/form-data">
                            <input type="hidden" name="update_id" value="<?= $publisher['id'] ?>">
                            
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Publisher Name</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="<?= htmlspecialchars($publisher['name']) ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="icon">Publisher Icon</label>
                                    <div class="mb-2">
                                        <?php if ($publisher['icon']): ?>
                                            <img src="<?= htmlspecialchars($publisher['icon']) ?>" 
                                                 alt="Current Icon" width="80" height="80"
                                                 onerror="this.onerror=null;this.src='images/default-icon.png';"
                                                 class="img-circle elevation-2">
                                        <?php else: ?>
                                            <span class="text-muted">No icon</span>
                                        <?php endif; ?>
                                    </div>
                                    <input type="file" class="form-control-file" id="icon" name="icon" accept="image/*">
                                    <small class="form-text text-muted">Leave empty to keep current icon. Supported formats: PNG, JPG, JPEG, GIF</small>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-save"></i> Update Publisher
                                </button>
                                <a href="publishers_list.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to List
                                </a>
                                <a href="delete_publisher.php?delete_id=<?= $publisher['id'] ?>" 
                                   class="btn btn-danger float-right"
                                   onclick="return confirm('Are you sure you want to delete this publisher?');">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <?php include_once "./includes/script.php"; ?>
</body>
</html>
<?php

ob_flush();

?>