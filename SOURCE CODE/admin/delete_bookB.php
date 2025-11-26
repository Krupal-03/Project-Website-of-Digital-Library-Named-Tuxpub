<?php

ob_start();

?>



<?php
require 'conn.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Fetch file path before deleting
    $stmt = $pdo->prepare("SELECT file_path FROM books WHERE id = ?");
    $stmt->execute([$id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($book) {
        // Delete PDF file if exists
        if (!empty($book['file_path']) && file_exists($book['file_path'])) {
            unlink($book['file_path']);
        }

        // Delete thumbnail if exists
        $thumbPath = "uploads/thumbnails/{$id}.jpg";
        if (file_exists($thumbPath)) {
            unlink($thumbPath);
        }

        // Delete DB record
        $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
        $stmt->execute([$id]);

        $_SESSION['message'] = "Book deleted successfully.";
        $_SESSION['msg_type'] = "success";
    } else {
        $_SESSION['message'] = "Book not found.";
        $_SESSION['msg_type'] = "danger";
    }
} else {
    $_SESSION['message'] = "Invalid request.";
    $_SESSION['msg_type'] = "danger";
}

header("Location: delete_book.php");
exit;
?>

<?php

ob_flush();

?>