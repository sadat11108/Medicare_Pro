<?php
require_once 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM billing WHERE id = ?");
        $stmt->execute([$id]);
        
        header("Location: billing.php?success=Invoice deleted successfully!");
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