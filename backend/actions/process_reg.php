<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

        $_SESSION['user_role'] = 'driver';
        $_SESSION['user_name'] = $name;
        
        
        $_SESSION['driver_data'] = [
            'birthday'      => $birthday,
            'gender'        => $gender,
            'address'       => $address,
            'country'       => $country,
            'region'        => $region,
            'city'          => $city,
            'license_class' => $license_class,
            'vehicle_model' => $vehicle_model,
            'fuel_type'     => $fuel_type
        ];

        header("Location: ../public/dashboard.php");
        exit();

    } catch (\PDOException $errInstance) {
        die("System Storage Framework Exception Failure: " . $errInstance->getMessage());
    }
} else {
    header("Location: ../public/index.php");
    exit();
}