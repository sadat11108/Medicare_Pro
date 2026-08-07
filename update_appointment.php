<?php
require_once 'config.php';

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];
    
    try {
        $stmt = $pdo->prepare("UPDATE appointments SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        
        header("Location: Doctor&AppointmentManagement.php?success=Appointment updated to " . $status . "!");
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