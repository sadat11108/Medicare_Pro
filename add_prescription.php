<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $medication = $_POST['medication'];
    $dosage = $_POST['dosage'];
    $instructions = $_POST['instructions'] ?? '';
    
    try {
        $sql = "INSERT INTO prescriptions (patient_id, medication, dosage, instructions) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$patient_id, $medication, $dosage, $instructions]);
        
        header("Location: Prescription.php?success=Prescription added successfully!");
        exit();
    } catch(PDOException $e) {
        header("Location: Prescription.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: Prescription.php");
    exit();
}
?>