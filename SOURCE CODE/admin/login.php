
<?php

ob_start();

?>



<?php
include_once 'conn.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Input validation
    if (empty($username) || empty($password)) {
        header("Location: index.php?error=1");
        exit();
    }

    // Prevent brute force - add delay
    sleep(1);

    try {
        // PDO prepared statement
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE aname = :username AND is_active = 1");
        $stmt->execute(['username' => $username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin) {
            // Check if password needs rehashing (from plain text to hashed)
            if (password_needs_rehash($admin['apass'], PASSWORD_DEFAULT) || 
                (!password_verify($password, $admin['apass']) && $password === $admin['apass'])) {
                
                // Migrate plain text password to hashed
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $updateStmt = $pdo->prepare("UPDATE admin SET apass = :password WHERE id = :id");
                $updateStmt->execute(['password' => $hashed_password, 'id' => $admin['id']]);
                
                // Update admin array with new hash
                $admin['apass'] = $hashed_password;
            }

            // Verify password
            if (password_verify($password, $admin['apass'])) {
                // Regenerate session ID for security
                session_regenerate_id(true);
                
                $_SESSION['username'] = $admin['aname'];
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_path'] = $admin['path'];
                $_SESSION['login_time'] = time();
                $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];

                // Update last login
                $updateLogin = $pdo->prepare("UPDATE admin SET last_login = NOW() WHERE id = :id");
                $updateLogin->execute(['id' => $admin['id']]);

                header("Location: dashboard.php");
                exit();
            } else {
                // Wrong password
                header("Location: index.php?error=1");
                exit();
            }
        } else {
            // Admin user not found
            header("Location: index.php?error=1");
            exit();
        }
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        header("Location: index.php?error=2");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>

<?php

ob_flush();

?>