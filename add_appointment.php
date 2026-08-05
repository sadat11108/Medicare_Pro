<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    
    try {
        $sql = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, status) VALUES (?, ?, ?, 'Pending')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$patient_id, $doctor_id, $appointment_date]);
        
        header("Location: Doctor&AppointmentManagement.php?success=Appointment created successfully!");
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