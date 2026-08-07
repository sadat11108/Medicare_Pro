<?php
require_once 'config.php';

// Check if ID is provided
if (!isset($_GET['id'])) {
    header("Location: billing.php");
    exit();
}

$id = $_GET['id'];

// Fetch bill data
try {
    $stmt = $pdo->prepare("SELECT * FROM billing WHERE id = ?");
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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $status = $_POST['status'];
    
    try {
        $sql = "UPDATE billing SET patient_id = ?, description = ?, amount = ?, status = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$patient_id, $description, $amount, $status, $id]);
        
        header("Location: billing.php?success=Invoice updated successfully!");
        exit();
    } catch(PDOException $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Invoice - SmartHealth</title>
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
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Edit Invoice</h1>
                    <a href="billing.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Invoices
                    </a>
                </div>

                <?php if(isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Patient</label>
                                <select name="patient_id" class="form-control" required>
                                    <option value="">Select Patient</option>
                                    <?php
                                    $patients = $pdo->query("SELECT id, name FROM patients ORDER BY name");
                                    while($p = $patients->fetch(PDO::FETCH_ASSOC)):
                                    ?>
                                    <option value="<?= $p['id'] ?>" <?= $p['id'] == $bill['patient_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($p['name']) ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <input type="text" name="description" class="form-control" value="<?= htmlspecialchars($bill['description'] ?? 'Medical Services') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Amount ($)</label>
                                <input type="number" name="amount" class="form-control" step="0.01" value="<?= $bill['amount'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="Unpaid" <?= $bill['status'] == 'Unpaid' ? 'selected' : '' ?>>Unpaid</option>
                                    <option value="Paid" <?= $bill['status'] == 'Paid' ? 'selected' : '' ?>>Paid</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Invoice</button>
                            <a href="billing.php" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>