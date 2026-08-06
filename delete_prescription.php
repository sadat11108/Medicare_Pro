<?php
require_once 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM prescriptions WHERE id = ?");
        $stmt->execute([$id]);
        
        header("Location: Prescription.php?success=Prescription deleted successfully!");
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