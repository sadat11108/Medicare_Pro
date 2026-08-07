<?php
require_once 'config.php';

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];
    
    try {
        $stmt = $pdo->prepare("UPDATE billing SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        
        header("Location: billing.php?success=Invoice marked as " . $status . "!");
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