<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $contact = $_POST['contact'];
    
    try {
        $sql = "INSERT INTO patients (name, age, gender, contact) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $age, $gender, $contact]);
        
        header("Location: patient_management.php?success=Patient added successfully!");
        exit();
    } catch(PDOException $e) {
        header("Location: patient_management.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: patient_management.php");
    exit();
}
?>