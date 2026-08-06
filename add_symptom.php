<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $symptoms = $_POST['symptoms'];
    $disease = $_POST['disease'] ?? '';
    $severity = $_POST['severity'];
    
    try {
        $sql = "INSERT INTO symptom_logs (patient_id, symptoms, disease, severity) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$patient_id, $symptoms, $disease, $severity]);
        
        header("Location: symptom_log.php?success=Symptoms logged successfully!");
        exit();
    } catch(PDOException $e) {
        header("Location: symptom_log.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: symptom_log.php");
    exit();
}
?>