<?php
session_start();

// Session security checks
if (!isset($_SESSION['username'])) {
    header("Location: index.php?session_expired=1");
    exit();
}

// Check session age (8 hours max)
$max_session_age = 8 * 60 * 60; // 8 hours in seconds
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > $max_session_age)) {
    session_unset();
    session_destroy();
    header("Location: index.php?session_expired=1");
    exit();
}

// Check user agent for session hijacking
if (isset($_SESSION['user_agent']) && $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
    session_unset();
    session_destroy();
    header("Location: index.php?session_expired=1");
    exit();
}

// Check IP address (optional - can be commented out if users have dynamic IPs)
/*
if (isset($_SESSION['ip_address']) && $_SESSION['ip_address'] !== $_SERVER['REMOTE_ADDR']) {
    session_unset();
    session_destroy();
    header("Location: index.php?session_expired=1");
    exit();
}
*/

// Update session time on activity (optional - extends session on user activity)
$_SESSION['last_activity'] = time();
?>