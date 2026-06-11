<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $_SESSION['login_error'] = "Missing credential validation strings.";
        header("Location: ../public/login.php");
        exit();
    }

    // Lookup structural profile matching the specific username target
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
    $stmt->execute([':username' => $username]);
    $accountRecord = $stmt->fetch();

    // Verify stored context hash signatures
    if ($accountRecord && password_verify($password, $accountRecord['password'])) {
        // Construct Session State Token Layout Configuration
        $_SESSION['user_role'] = $accountRecord['role'];
        $_SESSION['user_name'] = $accountRecord['name'];
        
        header("Location: ../public/dashboard.php");
        exit();
    } else {
        $_SESSION['login_error'] = "Invalid administrative signature matching username or password credentials.";
        header("Location: ../public/login.php");
        exit();
    }
} else {
    header("Location: ../public/login.php");
    exit();
}