<?php
require_once 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM patients WHERE id = ?");
        $stmt->execute([$id]);
        
        header("Location: patient_management.php?success=Patient deleted successfully!");
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