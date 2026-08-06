<?php
require_once 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM medical_records WHERE id = ?");
        $stmt->execute([$id]);
        
        header("Location: MedicalRecordManagement.php?success=Record deleted successfully!");
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