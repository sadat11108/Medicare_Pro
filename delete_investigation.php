<?php
require_once 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM investigations WHERE id = ?");
        $stmt->execute([$id]);
        
        header("Location: investigation.php?success=Lab test deleted successfully!");
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