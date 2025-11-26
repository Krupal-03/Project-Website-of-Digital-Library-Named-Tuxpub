<?php

ob_start();

?>
<?php
include_once 'conn.php';

// Handle status updates
if (isset($_POST['update_status'])) {
    $message_id = (int)$_POST['message_id'];
    $status = $_POST['status'];
    $admin_notes = trim($_POST['admin_notes']);
    
    $stmt = $pdo->prepare("UPDATE contact_messages SET status = ?, admin_notes = ? WHERE id = ?");
    $stmt->execute([$status, $admin_notes, $message_id]);
    
    header("Location: contact_messages.php?success=1");
    exit;
}

// Handle delete
if (isset($_GET['delete_id'])) {
    $message_id = (int)$_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->execute([$message_id]);
    
    header("Location: contact_messages.php?success=2");
    exit;
}

// Fetch all contact messages
$stmt = $pdo->prepare("SELECT * FROM contact_messages ORDER BY created_at DESC");
$stmt->execute();
$messages = $stmt->fetchAll();

// Count messages by status
$stmt = $pdo->prepare("SELECT status, COUNT(*) as count FROM contact_messages GROUP BY status");
$stmt->execute();
$status_counts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Messages</title>
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
                        <h1 class="m-0">Contact Messages</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item active">Contact Messages</li>
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
                            Message status updated successfully!
                        <?php elseif ($_GET['success'] == 2): ?>
                            Message deleted successfully!
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3><?= $status_counts['unread'] ?? 0 ?></h3>
                                <p>Unread Messages</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><?= $status_counts['read'] ?? 0 ?></h3>
                                <p>Read Messages</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-envelope-open"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3><?= $status_counts['replied'] ?? 0 ?></h3>
                                <p>Replied Messages</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-reply"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3><?= count($messages) ?></h3>
                                <p>Total Messages</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-comments"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">All Contact Messages</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Subject</th>
                                        <th>Message</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($messages as $message): ?>
                                        <tr>
                                            <td><?= $message['id'] ?></td>
                                            <td>
                                                <strong><?= htmlspecialchars($message['name']) ?> <?= htmlspecialchars($message['surname']) ?></strong>
                                            </td>
                                            <td>
                                                <a href="mailto:<?= htmlspecialchars($message['email']) ?>">
                                                    <?= htmlspecialchars($message['email']) ?>
                                                </a>
                                            </td>
                                            <td><?= htmlspecialchars($message['subject']) ?></td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#messageModal<?= $message['id'] ?>">
                                                    View Message
                                                </button>
                                            </td>
                                            <td>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="message_id" value="<?= $message['id'] ?>">
                                                    <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                                        <option value="unread" <?= $message['status'] == 'unread' ? 'selected' : '' ?>>Unread</option>
                                                        <option value="read" <?= $message['status'] == 'read' ? 'selected' : '' ?>>Read</option>
                                                        <option value="replied" <?= $message['status'] == 'replied' ? 'selected' : '' ?>>Replied</option>
                                                    </select>
                                                    <input type="hidden" name="update_status" value="1">
                                                    <input type="hidden" name="admin_notes" value="<?= htmlspecialchars($message['admin_notes']) ?>">
                                                </form>
                                            </td>
                                            <td><?= date('M j, Y g:i A', strtotime($message['created_at'])) ?></td>
                                            <td>
                                                <a href="?delete_id=<?= $message['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this message?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>

                                        <!-- Message Modal -->
                                        <div class="modal fade" id="messageModal<?= $message['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel<?= $message['id'] ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="messageModalLabel<?= $message['id'] ?>">
                                                            Message from <?= htmlspecialchars($message['name']) ?> <?= htmlspecialchars($message['surname']) ?>
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <p><strong>Name:</strong> <?= htmlspecialchars($message['name']) ?> <?= htmlspecialchars($message['surname']) ?></p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <p><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($message['email']) ?>"><?= htmlspecialchars($message['email']) ?></a></p>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <p><strong>Subject:</strong> <?= htmlspecialchars($message['subject']) ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <p><strong>Message:</strong></p>
                                                                <div class="message-content p-3 bg-light rounded">
                                                                    <?= nl2br(htmlspecialchars($message['message'])) ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-12">
                                                                <form method="POST">
                                                                    <input type="hidden" name="message_id" value="<?= $message['id'] ?>">
                                                                    <div class="form-group">
                                                                        <label for="admin_notes<?= $message['id'] ?>"><strong>Admin Notes:</strong></label>
                                                                        <textarea class="form-control" id="admin_notes<?= $message['id'] ?>" name="admin_notes" rows="3" placeholder="Add internal notes here..."><?= htmlspecialchars($message['admin_notes']) ?></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="status<?= $message['id'] ?>"><strong>Status:</strong></label>
                                                                        <select class="form-control" id="status<?= $message['id'] ?>" name="status">
                                                                            <option value="unread" <?= $message['status'] == 'unread' ? 'selected' : '' ?>>Unread</option>
                                                                            <option value="read" <?= $message['status'] == 'read' ? 'selected' : '' ?>>Read</option>
                                                                            <option value="replied" <?= $message['status'] == 'replied' ? 'selected' : '' ?>>Replied</option>
                                                                        </select>
                                                                    </div>
                                                                    <button type="submit" name="update_status" class="btn btn-primary">Update</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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

<?php

ob_flush();

?>