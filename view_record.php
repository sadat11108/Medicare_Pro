<?php
require_once 'config.php';

// Check if ID is provided
if (!isset($_GET['id'])) {
    header("Location: MedicalRecordManagement.php");
    exit();
}

$id = $_GET['id'];

// Fetch medical record with patient details
try {
    $stmt = $pdo->prepare("SELECT mr.*, p.name as patient_name, p.age, p.gender, p.contact 
                           FROM medical_records mr 
                           JOIN patients p ON mr.patient_id = p.id 
                           WHERE mr.id = ?");
    $stmt->execute([$id]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$record) {
        header("Location: MedicalRecordManagement.php?error=Record not found");
        exit();
    }
} catch(PDOException $e) {
    header("Location: MedicalRecordManagement.php?error=" . urlencode($e->getMessage()));
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Medical Record - SmartHealth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .sidebar { min-height: 100vh; background: #1a252f; }
        .nav-link { color: #bdc3c7; padding: 15px 20px; border-radius: 5px; margin-bottom: 5px; transition: 0.2s; }
        .nav-link:hover, .nav-link.active { background: #34495e; color: white; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar">
                <div class="position-sticky pt-3">
                    <h5 class="text-center text-white py-3 border-bottom border-secondary mb-4">
                        <i class="bi bi-heart-pulse-fill text-danger"></i> SmartHealth
                    </h5>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="patient_management.php">
                                <i class="bi bi-person-lines-fill me-2"></i> View Records
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="MedicalRecordManagement.php">
                                <i class="bi bi-file-medical me-2"></i> Medical Records
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Doctor&AppointmentManagement.php">
                                <i class="bi bi-calendar-check me-2"></i> Appointments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Prescription.php">
                                <i class="bi bi-capsule me-2"></i> Prescriptions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="investigation.php">
                                <i class="bi bi-microscope me-2"></i> Investigations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="billing.php">
                                <i class="bi bi-receipt me-2"></i> Billing
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="insurance.php">
                                <i class="bi bi-shield-check me-2"></i> Insurance
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="symptom_log.php">
                                <i class="bi bi-thermometer-half me-2"></i> Symptoms
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="preventive.php">
                                <i class="bi bi-shield-plus me-2"></i> Preventive Care
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Medical Record Details</h1>
                    <div>
                        <a href="edit_record.php?id=<?= $record['id'] ?>" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit Record
                        </a>
                        <a href="MedicalRecordManagement.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="bi bi-person"></i> Patient Information</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="30%">Patient Name:</th>
                                        <td><strong><?= htmlspecialchars($record['patient_name']) ?></strong></td>
                                    </tr>
                                    <tr>
                                        <th>Age:</th>
                                        <td><?= $record['age'] ?> years</td>
                                    </tr>
                                    <tr>
                                        <th>Gender:</th>
                                        <td><?= $record['gender'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Contact:</th>
                                        <td><?= htmlspecialchars($record['contact']) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Record Date:</th>
                                        <td><?= date('F d, Y', strtotime($record['record_date'])) ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="bi bi-file-medical"></i> Medical Information</h5>
                            </div>
                            <div class="card-body">
                                <h6 class="text-muted">Diagnosis</h6>
                                <p class="border p-3 rounded bg-light"><?= nl2br(htmlspecialchars($record['diagnosis'])) ?></p>
                                
                                <h6 class="text-muted mt-3">Treatment</h6>
                                <p class="border p-3 rounded bg-light"><?= nl2br(htmlspecialchars($record['treatment'] ?? 'No treatment recorded')) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>