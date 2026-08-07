<?php
require_once 'config.php';

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];
    
    try {
        $stmt = $pdo->prepare("UPDATE investigations SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        
        header("Location: investigation.php?success=Test marked as " . $status . "!");
        exit();
    } catch(PDOException $e) {
        header("Location: investigation.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: investigation.php");
    exit();
}
?>