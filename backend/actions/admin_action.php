<?php
session_start();
require_once '../config/database.php';


if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: ../public/login.php");
    exit();
}


if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die("Access Denied: Administrative system validation signature mismatch exception.");
}


if (isset($_GET['delete_id'])) {
    $targetDeleteId = (int)$_GET['delete_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id AND role = 'driver'");
        $stmt->execute([':id' => $targetDeleteId]);
        
        header("Location: ../public/dashboard.php");
        exit();
    } catch (\PDOException $ex) {
        die("Error finalizing row state drop parameters inside storage: " . $ex->getMessage());
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_driver') {
    $id = (int)$_POST['id'];
    $name = trim($_POST['name'] ?? '');
    $birthday = $_POST['birthday'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $license_class = $_POST['license_class'] ?? '';
    $vehicle_model = trim($_POST['vehicle_model'] ?? '');
    $fuel_type = trim($_POST['fuel_type'] ?? '');

    // Validate updated driver age rule (Must still be 24+)
    $dob = new DateTime($birthday);
    $today = new DateTime();
    $calculatedAge = $today->diff($dob)->y;

    if ($calculatedAge < 24) {
        die("Update Rejected: Selected date of birth does not satisfy the 24+ years operational threshold requirements.");
    }

    try {
        $sql = "UPDATE users SET 
                    name = :name, 
                    birthday = :birthday, 
                    gender = :gender, 
                    license_class = :license_class, 
                    vehicle_model = :vehicle_model, 
                    fuel_type = :fuel_type 
                WHERE id = :id AND role = 'driver'";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name'          => $name,
            ':birthday'      => $birthday,
            ':gender'        => $gender,
            ':license_class' => $license_class,
            ':vehicle_model' => $vehicle_model,
            ':fuel_type'     => $fuel_type,
            ':id'            => $id
        ]);

        header("Location: ../public/dashboard.php");
        exit();
    } catch (\PDOException $e) {
        die("Database update failed: " . $e->getMessage());
    }
}