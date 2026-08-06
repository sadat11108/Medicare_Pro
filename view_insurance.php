<?php
require_once 'config.php';

// Check if ID is provided
if (!isset($_GET['id'])) {
    header("Location: insurance.php");
    exit();
}

$id = $_GET['id'];

// Fetch insurance with patient details
try {
    $stmt = $pdo->prepare("SELECT i.*, p.name as patient_name, p.age, p.gender, p.contact 
                           FROM insurance i 
                           JOIN patients p ON i.patient_id = p.id 
                           WHERE i.id = ?");
    $stmt->execute([$id]);
    $insurance = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$insurance) {
        header("Location: insurance.php?error=Policy not found");
        exit();
    }
} catch(PDOException $e) {
    header("Location: insurance.php?error=" . urlencode($e->getMessage()));
    exit();
}

$isExpired = strtotime($insurance['expiry_date']) < time();
$isExpiring = strtotime($insurance['expiry_date']) <= strtotime('+30 days') && !$isExpired;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Insurance Policy - SmartHealth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .sidebar { min-height: 100vh; background: #1a252f; }
        .nav-link { color: #bdc3c7; padding: 15px 20px; border-radius: 5px; margin-bottom: 5px; transition: 0.2s; }
        .nav-link:hover, .nav-link.active { background: #34495e; color: white; }
        .policy-box {
            border: 2px solid #0d6efd;
            border-radius: 10px;
            padding: 30px;
            background: #ffffff;
        }
        .policy-header {
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 15px;
            margin-bottom: 20px;
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
                            <a class="nav-link active" href="insurance.php">
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
                    <h1 class="h2">Insurance Policy Details</h1>
                    <div>
                        <a href="edit_insurance.php?id=<?= $insurance['id'] ?>" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="insurance.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <div class="policy-box">
                    <div class="policy-header">
                        <div class="row">
                            <div class="col-8">
                                <h3 class="text-primary"><i class="bi bi-shield-check"></i> Insurance Policy</h3>
                            </div>
                            <div class="col-4 text-end">
                                <span class="badge bg-<?= $isExpired ? 'danger' : ($isExpiring ? 'warning' : 'success') ?> fs-5 p-2">
                                    <?= $isExpired ? 'Expired' : ($isExpiring ? 'Expiring Soon' : 'Active') ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Policy Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="35%">Policy ID:</th>
                                    <td>#<?= str_pad($insurance['id'], 6, '0', STR_PAD_LEFT) ?></td>
                                </tr>
                                <tr>
                                    <th>Provider:</th>
                                    <td><strong><?= htmlspecialchars($insurance['provider']) ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Policy Number:</th>
                                    <td><code><?= htmlspecialchars($insurance['policy_number']) ?></code></td>
                                </tr>
                                <tr>
                                    <th>Coverage Amount:</th>
                                    <td><strong class="text-success">$<?= number_format($insurance['coverage_amount'], 2) ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Expiry Date:</th>
                                    <td><?= date('F d, Y', strtotime($insurance['expiry_date'])) ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Patient Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="35%">Patient Name:</th>
                                    <td><strong><?= htmlspecialchars($insurance['patient_name']) ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Age:</th>
                                    <td><?= $insurance['age'] ?> years</td>
                                </tr>
                                <tr>
                                    <th>Gender:</th>
                                    <td><?= $insurance['gender'] ?></td>
                                </tr>
                                <tr>
                                    <th>Contact:</th>
                                    <td><?= htmlspecialchars($insurance['contact']) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <?php if($isExpiring && !$isExpired): ?>
                        <div class="alert alert-warning mt-3">
                            <i class="bi bi-exclamation-triangle"></i> This policy is expiring on <?= date('F d, Y', strtotime($insurance['expiry_date'])) ?>. Please renew soon!
                        </div>
                    <?php endif; ?>

                    <?php if($isExpired): ?>
                        <div class="alert alert-danger mt-3">
                            <i class="bi bi-exclamation-circle"></i> This policy has expired on <?= date('F d, Y', strtotime($insurance['expiry_date'])) ?>. Please renew immediately!
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>