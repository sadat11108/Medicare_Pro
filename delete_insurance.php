<?php
require_once 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM insurance WHERE id = ?");
        $stmt->execute([$id]);
        
        header("Location: insurance.php?success=Insurance policy deleted successfully!");
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