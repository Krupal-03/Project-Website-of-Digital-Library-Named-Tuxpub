<?php
include_once 'conn.php';

// Handle status updates
if (isset($_POST['update_status'])) {
    $review_id = (int)$_POST['review_id'];
    $status = $_POST['status'];
    
    $stmt = $pdo->prepare("UPDATE reviews SET status = ? WHERE id = ?");
    $stmt->execute([$status, $review_id]);
    
    header("Location: reviews_list.php?success=1");
    exit;
}

// Handle delete
if (isset($_GET['delete_id'])) {
    $review_id = (int)$_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?");
    $stmt->execute([$review_id]);
    
    header("Location: reviews_list.php?success=2");
    exit;
}

// Fetch all reviews with book information
$stmt = $pdo->prepare("
    SELECT r.*, b.title as book_title 
    FROM reviews r 
    LEFT JOIN books b ON r.book_id = b.id 
    ORDER BY r.created_at DESC
");
$stmt->execute();
$reviews = $stmt->fetchAll();

// Count reviews by status
$stmt = $pdo->prepare("SELECT status, COUNT(*) as count FROM reviews GROUP BY status");
$stmt->execute();
$status_counts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Reviews</title>
    <?php include_once "./includes/style.php"; ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <?php include_once "includes/header.php" ?>
    <?php include_once "includes/sidebar.php" ?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Manage Reviews</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item active">Reviews</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php if ($_GET['success'] == 1): ?>
                            Review status updated successfully!
                        <?php elseif ($_GET['success'] == 2): ?>
                            Review deleted successfully!
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3><?= $status_counts['pending'] ?? 0 ?></h3>
                                <p>Pending Reviews</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><?= $status_counts['approved'] ?? 0 ?></h3>
                                <p>Approved Reviews</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-check"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3><?= $status_counts['rejected'] ?? 0 ?></h3>
                                <p>Rejected Reviews</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-times"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3><?= count($reviews) ?></h3>
                                <p>Total Reviews</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-comments"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">All Reviews</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Book</th>
                                        <th>User</th>
                                        <th>Rating</th>
                                        <th>Comment</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reviews as $review): ?>
                                        <tr>
                                            <td><?= $review['id'] ?></td>
                                            <td>
                                                <a href="../book-details.php?id=<?= $review['book_id'] ?>" target="_blank">
                                                    <?= htmlspecialchars($review['book_title']) ?>
                                                </a>
                                            </td>
                                            <td>
                                                <div><?= htmlspecialchars($review['user_name']) ?></div>
                                                <small class="text-muted"><?= htmlspecialchars($review['user_email']) ?></small>
                                            </td>
                                            <td>
                                                <div class="stars">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fas fa-star <?= $i <= $review['rating'] ? 'text-warning' : 'text-muted' ?>" style="font-size: 12px;"></i>
                                                    <?php endfor; ?>
                                                    <br>
                                                    <small class="text-muted">(<?= $review['rating'] ?>/5)</small>
                                                </div>
                                            </td>
                                            <td><?= !empty($review['comment']) ? htmlspecialchars(substr($review['comment'], 0, 100)) . (strlen($review['comment']) > 100 ? '...' : '') : 'No comment' ?></td>
                                            <td>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                                                    <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                                        <option value="pending" <?= $review['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                                        <option value="approved" <?= $review['status'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                                                        <option value="rejected" <?= $review['status'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                                    </select>
                                                    <input type="hidden" name="update_status" value="1">
                                                </form>
                                            </td>
                                            <td><?= date('M j, Y g:i A', strtotime($review['created_at'])) ?></td>
                                            <td>
                                                <a href="?delete_id=<?= $review['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this review?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<?php include_once "includes/script.php" ?>
</body>
</html>