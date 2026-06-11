<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Secure input data collection variables
    $name = trim($_POST['name'] ?? '');
    $birthday = $_POST['birthday'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $address = trim($_POST['address'] ?? '');
    $country = $_POST['country'] ?? '';
    $region = $_POST['region'] ?? '';
    $city = $_POST['city'] ?? '';
    $license_class = $_POST['license_class'] ?? '';
    $vehicle_model = trim($_POST['vehicle_model'] ?? '');
    $fuel_type = trim($_POST['fuel_type'] ?? '');

    // 1. Strict Server-Side Validation: Evaluate 24+ Age Limit Logic Guardrail
    if (empty($birthday)) {
        die("Error: Validation parameters broken. Date of Birth missing.");
    }
    
    $dob = new DateTime($birthday);
    $today = new DateTime();
    $calculatedAge = $today->diff($dob)->y;

    if ($calculatedAge < 24) {
        die("Security Core Failure: Server-side validation failed. Registration profile restricted. Drivers must match or exceed 24 years threshold.");
    }

    try {
        // 2. Execute PDO Structured Secure Insert Query Statement
        $sqlQuery = "INSERT INTO users (name, birthday, gender, address, country, region, city, license_class, vehicle_model, fuel_type, role) 
                     VALUES (:name, :birthday, :gender, :address, :country, :region, :city, :license_class, :vehicle_model, :fuel_type, 'driver')";
        
        $statementHandler = $pdo->prepare($sqlQuery);
        $statementHandler->execute([
            ':name'          => $name,
            ':birthday'      => $birthday,
            ':gender'        => $gender,
            ':address'       => $address,
            ':country'       => $country,
            ':region'        => $region,
            ':city'          => $city,
            ':license_class' => $license_class,
            ':vehicle_model' => $vehicle_model,
            ':fuel_type'     => $fuel_type
        ]);

        // 3. Instantiate State Context Storage Matrix to Map UI Access Routing
        $_SESSION['user_role'] = 'driver';
        $_SESSION['user_name'] = $name;

        header("Location: ../public/dashboard.php");
        exit();

    } catch (\PDOException $errInstance) {
        die("System Storage Framework Exception Failure: " . $errInstance->getMessage());
    }
} else {
    header("Location: ../public/index.php");
    exit();
}