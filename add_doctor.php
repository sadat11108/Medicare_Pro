<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $specialty = $_POST['specialty'];
    $contact = $_POST['contact'];
    
    try {
        $sql = "INSERT INTO doctors (name, specialty, contact) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $specialty, $contact]);
        
        header("Location: Doctor&AppointmentManagement.php?success=Doctor added successfully!");
        exit();
    } catch(PDOException $e) {
        header("Location: Doctor&AppointmentManagement.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: Doctor&AppointmentManagement.php");
    exit();
}
?>