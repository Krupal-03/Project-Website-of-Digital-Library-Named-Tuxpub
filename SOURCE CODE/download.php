<?php
include_once 'conn.php'; // Your PDO connection file

if (isset($_GET['id'])) {
    $bookId = (int) $_GET['id'];

    // Fetch the book to get the file path
    $stmt = $pdo->prepare("SELECT file_path FROM books WHERE id = ?");
    $stmt->execute([$bookId]);
    $book = $stmt->fetch();

    if ($book) {
        $file = $book['file_path'];
        $fullPath = "./admin/" . $file; // Absolute path to the file

        // Increment downloads count
        $stmtUpdate = $pdo->prepare("UPDATE books SET downloads = downloads + 1 WHERE id = ?");
        $stmtUpdate->execute([$bookId]);

        // Serve the file to download (basic example)
        if (file_exists($fullPath)) {
            // Clear output buffer before sending headers (to avoid headers already sent)
            if (ob_get_level()) {
                ob_end_clean();
            }

            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf'); // better to send specific type for PDFs
            header('Content-Disposition: attachment; filename="' . basename($fullPath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fullPath));
            readfile($fullPath);
            exit;
        } else {
            echo "File not found.";
        }
    } else {
        echo "Invalid book ID.";
    }
} else {
    echo "No book specified.";
}
?>