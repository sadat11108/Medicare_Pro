<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $description = $_POST['description'] ?? 'Medical Services';
    $amount = $_POST['amount'];
    
    try {
        $sql = "INSERT INTO billing (patient_id, description, amount, status) VALUES (?, ?, ?, 'Unpaid')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$patient_id, $description, $amount]);
        
        header("Location: billing.php?success=Invoice created successfully!");
        exit();
    } catch(PDOException $e) {
        header("Location: billing.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: billing.php");
    exit();
}
?>