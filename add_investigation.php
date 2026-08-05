<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $test_type = $_POST['test_type'];
    $result = $_POST['result'] ?? '';
    
    try {
        $sql = "INSERT INTO investigations (patient_id, test_type, result, status) VALUES (?, ?, ?, 'Pending')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$patient_id, $test_type, $result]);
        
        header("Location: investigation.php?success=Lab test added successfully!");
        exit();
    } catch(PDOException $e) {
        header("Location: investigation.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: investigation.php");
    exit();
}
?>