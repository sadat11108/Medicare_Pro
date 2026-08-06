<?php
require_once 'config.php';

// Check if ID is provided
if (!isset($_GET['id'])) {
    header("Location: billing.php");
    exit();
}

$id = $_GET['id'];

// Fetch bill with patient details
try {
    $stmt = $pdo->prepare("SELECT b.*, p.name as patient_name, p.age, p.gender, p.contact 
                           FROM billing b 
                           JOIN patients p ON b.patient_id = p.id 
                           WHERE b.id = ?");
    $stmt->execute([$id]);
    $bill = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$bill) {
        header("Location: billing.php?error=Invoice not found");
        exit();
    }
} catch(PDOException $e) {
    header("Location: billing.php?error=" . urlencode($e->getMessage()));
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Invoice - SmartHealth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .sidebar { min-height: 100vh; background: #1a252f; }
        .nav-link { color: #bdc3c7; padding: 15px 20px; border-radius: 5px; margin-bottom: 5px; transition: 0.2s; }
        .nav-link:hover, .nav-link.active { background: #34495e; color: white; }
        .invoice-box {
            border: 2px solid #0d6efd;
            border-radius: 10px;
            padding: 30px;
            background: #ffffff;
        }
        .invoice-header {
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        @media print {
            .sidebar, .btn, .no-print { display: none !important; }
            .col-md-9 { width: 100% !important; }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar no-print">
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
                            <a class="nav-link active" href="billing.php">
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
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom no-print">
                    <h1 class="h2">Invoice Details</h1>
                    <div>
                        <button onclick="window.print()" class="btn btn-primary">
                            <i class="bi bi-printer"></i> Print Invoice
                        </button>
                        <a href="billing.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <div class="invoice-box">
                    <div class="invoice-header">
                        <div class="row">
                            <div class="col-6">
                                <h3 class="text-primary">SmartHealth</h3>
                                <p class="text-muted">Healthcare Management System</p>
                            </div>
                            <div class="col-6 text-end">
                                <h4>INVOICE</h4>
                                <p class="text-muted">#<?= str_pad($bill['id'], 6, '0', STR_PAD_LEFT) ?></p>
                                <p class="text-muted">Date: <?= date('F d, Y', strtotime($bill['bill_date'])) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-6">
                            <h6>Bill To:</h6>
                            <p>
                                <strong><?= htmlspecialchars($bill['patient_name']) ?></strong><br>
                                Age: <?= $bill['age'] ?> years<br>
                                Gender: <?= $bill['gender'] ?><br>
                                Contact: <?= htmlspecialchars($bill['contact']) ?>
                            </p>
                        </div>
                        <div class="col-6 text-end">
                            <h6>Status:</h6>
                            <span class="badge bg-<?= $bill['status'] == 'Paid' ? 'success' : 'warning' ?> fs-5 p-2">
                                <?= $bill['status'] ?>
                            </span>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Description</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= htmlspecialchars($bill['description'] ?? 'Medical Services') ?></td>
                                <td class="text-end">$<?= number_format($bill['amount'], 2) ?></td>
                            </tr>
                            <tr class="table-light">
                                <td><strong>Total</strong></td>
                                <td class="text-end"><strong>$<?= number_format($bill['amount'], 2) ?></strong></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="text-center mt-4 text-muted">
                        <p>Thank you for choosing SmartHealth. Payment is due upon receipt.</p>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>