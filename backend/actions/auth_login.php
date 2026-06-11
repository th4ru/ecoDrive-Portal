<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $_SESSION['login_error'] = "Missing credential validation strings.";
        header("Location: ../../frontend/login.php");
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
    $stmt->execute([':username' => $username]);
    $accountRecord = $stmt->fetch();

    if ($accountRecord && password_verify($password, $accountRecord['password'])) {
        $_SESSION['user_role'] = $accountRecord['role'];
        $_SESSION['user_name'] = $accountRecord['name'];
        
        header("Location: ../../frontend/dashboard.php");
        exit();
    } else {
        $_SESSION['login_error'] = "Invalid administrative signature matching username or password credentials.";
        header("Location: ../../frontend/login.php");
        exit();
    }
} else {
    header("Location: ../../frontend/login.php");
    exit();
}