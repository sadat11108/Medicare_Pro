<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $diagnosis = $_POST['diagnosis'];
    $treatment = $_POST['treatment'] ?? '';
    
    try {
        $sql = "INSERT INTO medical_records (patient_id, diagnosis, treatment) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$patient_id, $diagnosis, $treatment]);
        
        header("Location: MedicalRecordManagement.php?success=Medical record added successfully!");
        exit();
    } catch(PDOException $e) {
        header("Location: MedicalRecordManagement.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: MedicalRecordManagement.php");
    exit();
}
?>