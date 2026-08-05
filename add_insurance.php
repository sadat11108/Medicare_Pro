<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $provider = $_POST['provider'];
    $policy_number = $_POST['policy_number'];
    $coverage_amount = $_POST['coverage_amount'];
    $expiry_date = $_POST['expiry_date'];
    
    try {
        $sql = "INSERT INTO insurance (patient_id, provider, policy_number, coverage_amount, expiry_date) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$patient_id, $provider, $policy_number, $coverage_amount, $expiry_date]);
        
        header("Location: insurance.php?success=Insurance policy added successfully!");
        exit();
    } catch(PDOException $e) {
        header("Location: insurance.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: insurance.php");
    exit();
}
?>