<?php
include_once 'conn.php';
include_once 'session_check.php';

// Only allow if user is logged in as admin
if ($_SESSION['username'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

// Migrate plain text passwords to hashed
$admins = $pdo->query("SELECT id, apass FROM admin")->fetchAll();
$migrated = 0;

foreach ($admins as $admin) {
    // Check if password is already hashed
    if (!password_needs_rehash($admin['apass'], PASSWORD_DEFAULT)) {
        continue;
    }
    
    // Hash the plain text password
    $hashed_password = password_hash($admin['apass'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE admin SET apass = ? WHERE id = ?");
    $stmt->execute([$hashed_password, $admin['id']]);
    $migrated++;
}

echo "<script>alert('Migrated $migrated passwords to secure hash.'); window.location.href='setting.php';</script>";
?>