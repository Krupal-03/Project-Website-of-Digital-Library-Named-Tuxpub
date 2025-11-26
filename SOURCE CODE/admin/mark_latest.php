<?php
require 'conn.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Step 1: Unmark all books
    $pdo->query("UPDATE books SET is_latest = 0");

    // Step 2: Mark selected book as latest
    $stmt = $pdo->prepare("UPDATE books SET is_latest = 1 WHERE id = ?");
    $stmt->execute([$id]);

    

    header('Location:setting.php'); // Replace with your dashboard filename
    exit;
}
?>
