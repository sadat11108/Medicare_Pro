<?php
require_once 'config.php';

// Check if ID is provided
if (!isset($_GET['id'])) {
    header("Location: preventive.php");
    exit();
}

$id = $_GET['id'];

// Fetch preventive care with patient details
try {
    $stmt = $pdo->prepare("SELECT pc.*, p.name as patient_name, p.age, p.gender, p.contact 
                           FROM preventive_care pc 
                           JOIN patients p ON pc.patient_id = p.id 
                           WHERE pc.id = ?");
    $stmt->execute([$id]);
    $preventive = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$preventive) {
        header("Location: preventive.php?error=Recommendation not found");
        exit();
    }
} catch(PDOException $e) {
    header("Location: preventive.php?error=" . urlencode($e->getMessage()));
    exit();
}

$priorityClass = $preventive['priority'] == 'High' ? 'danger' : ($preventive['priority'] == 'Medium' ? 'warning' : 'success');
$statusClass = $preventive['status'] == 'Completed' ? 'success' : ($preventive['status'] == 'In Progress' ? 'info' : 'warning');
$isOverdue = $preventive['due_date'] && strtotime($preventive['due_date']) < time() && $preventive['status'] != 'Completed';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Recommendation - SmartHealth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .sidebar { min-height: 100vh; background: #1a252f; }
        .nav-link { color: #bdc3c7; padding: 15px 20px; border-radius: 5px; margin-bottom: 5px; transition: 0.2s; }
        .nav-link:hover, .nav-link.active { background: #34495e; color: white; }
        .recommendation-box {
            border: 2px solid #0d6efd;
            border-radius: 10px;
            padding: 30px;
            background: #ffffff;
        }
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
                            <a class="nav-link" href="MedicalRecordManagement.php">
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
                            <a class="nav-link active" href="preventive.php">
                                <i class="bi bi-shield-plus me-2"></i> Preventive Care
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Recommendation Details</h1>
                    <div>
                        <a href="edit_preventive.php?id=<?= $preventive['id'] ?>" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="preventive.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="bi bi-person"></i> Patient Information</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="30%">Patient Name:</th>
                                        <td><strong><?= htmlspecialchars($preventive['patient_name']) ?></strong></td>
                                    </tr>
                                    <tr>
                                        <th>Age:</th>
                                        <td><?= $preventive['age'] ?> years</td>
                                    </tr>
                                    <tr>
                                        <th>Gender:</th>
                                        <td><?= $preventive['gender'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Contact:</th>
                                        <td><?= htmlspecialchars($preventive['contact']) ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-<?= $priorityClass ?> text-white">
                                <h5 class="mb-0"><i class="bi bi-shield-plus"></i> Recommendation Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="recommendation-box">
                                    <h5 class="text-primary">Recommendation</h5>
                                    <p class="border p-3 rounded bg-light"><?= nl2br(htmlspecialchars($preventive['recommendation'])) ?></p>
                                    
                                    <div class="row mt-3">
                                        <div class="col-6">
                                            <h6 class="text-muted">Category</h6>
                                            <span class="badge bg-secondary fs-6 p-2">
                                                <?= $preventive['category'] ?>
                                            </span>
                                        </div>
                                        <div class="col-6">
                                            <h6 class="text-muted">Priority</h6>
                                            <span class="badge bg-<?= $priorityClass ?> fs-6 p-2">
                                                <?= $preventive['priority'] ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-6">
                                            <h6 class="text-muted">Status</h6>
                                            <span class="badge bg-<?= $statusClass ?> fs-6 p-2">
                                                <?= $preventive['status'] ?>
                                            </span>
                                        </div>
                                        <div class="col-6">
                                            <h6 class="text-muted">Due Date</h6>
                                            <p><?= $preventive['due_date'] ? date('F d, Y', strtotime($preventive['due_date'])) : 'No due date' ?></p>
                                            <?php if($isOverdue): ?>
                                                <span class="badge bg-danger">Overdue</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <h6 class="text-muted">Created</h6>
                                        <p class="text-muted"><?= date('F d, Y h:i A', strtotime($preventive['created_at'])) ?></p>
                                    </div>
                                </div>
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