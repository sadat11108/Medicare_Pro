<?php
require_once 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM symptom_logs WHERE id = ?");
        $stmt->execute([$id]);
        
        header("Location: symptom_log.php?success=Symptom log deleted successfully!");
        exit();
    } catch(PDOException $e) {
        header("Location: symptom_log.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: symptom_log.php");
    exit();
}
?>