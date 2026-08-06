<?php
require_once 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM doctors WHERE id = ?");
        $stmt->execute([$id]);
        
        header("Location: Doctor&AppointmentManagement.php?success=Doctor deleted successfully!");
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