<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insurance Management - SmartHealth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .sidebar { min-height: 100vh; background: #1a252f; }
        .nav-link { color: #bdc3c7; padding: 15px 20px; border-radius: 5px; margin-bottom: 5px; transition: 0.2s; }
        .nav-link:hover, .nav-link.active { background: #34495e; color: white; }
        .stats-card {
            transition: transform 0.2s;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        .expiring-badge {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
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
                    <h1 class="h2">Insurance Management</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addInsuranceModal">
                        <i class="bi bi-plus-circle"></i> Add Insurance Policy
                    </button>
                </div>

                <?php if(isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_GET['success']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_GET['error']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Statistics Cards -->
                <?php
                $totalPolicies = $pdo->query("SELECT COUNT(*) as count FROM insurance")->fetch()['count'];
                $totalCoverage = $pdo->query("SELECT SUM(coverage_amount) as total FROM insurance")->fetch()['total'] ?? 0;
                $expiringSoon = $pdo->query("SELECT COUNT(*) as count FROM insurance WHERE expiry_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)")->fetch()['count'];
                ?>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card stats-card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-muted">Total Policies</h6>
                                <h2 class="text-primary"><?= $totalPolicies ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stats-card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-muted">Total Coverage</h6>
                                <h2 class="text-success">$<?= number_format($totalCoverage, 2) ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stats-card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-muted">Expiring Soon (30 days)</h6>
                                <h2 class="text-danger <?= $expiringSoon > 0 ? 'expiring-badge' : '' ?>"><?= $expiringSoon ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Patient</th>
                                        <th>Provider</th>
                                        <th>Policy #</th>
                                        <th>Coverage</th>
                                        <th>Expiry Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $pdo->query("SELECT i.*, p.name as patient_name 
                                                         FROM insurance i 
                                                         JOIN patients p ON i.patient_id = p.id 
                                                         ORDER BY i.expiry_date ASC");
                                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                                        $isExpiring = strtotime($row['expiry_date']) <= strtotime('+30 days');
                                        $isExpired = strtotime($row['expiry_date']) < time();
                                    ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= htmlspecialchars($row['patient_name']) ?></td>
                                        <td><?= htmlspecialchars($row['provider']) ?></td>
                                        <td><?= htmlspecialchars($row['policy_number']) ?></td>
                                        <td>$<?= number_format($row['coverage_amount'], 2) ?></td>
                                        <td>
                                            <?= date('M d, Y', strtotime($row['expiry_date'])) ?>
                                            <?php if($isExpired): ?>
                                                <span class="badge bg-danger ms-1">Expired</span>
                                            <?php elseif($isExpiring): ?>
                                                <span class="badge bg-warning ms-1">Expiring Soon</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($isExpired): ?>
                                                <span class="badge bg-danger">Inactive</span>
                                            <?php elseif($isExpiring): ?>
                                                <span class="badge bg-warning">About to Expire</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="view_insurance.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">View</a>
                                            <a href="edit_insurance.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="delete_insurance.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this policy?')">Delete</a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add Insurance Modal -->
    <div class="modal fade" id="addInsuranceModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Insurance Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="add_insurance.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Patient</label>
                            <select name="patient_id" class="form-control" required>
                                <option value="">Select Patient</option>
                                <?php
                                $patients = $pdo->query("SELECT id, name FROM patients ORDER BY name");
                                while($p = $patients->fetch(PDO::FETCH_ASSOC)):
                                ?>
                                <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Insurance Provider</label>
                            <input type="text" name="provider" class="form-control" placeholder="e.g., Blue Cross, Aetna" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Policy Number</label>
                            <input type="text" name="policy_number" class="form-control" placeholder="e.g., POL-123456" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Coverage Amount ($)</label>
                            <input type="number" name="coverage_amount" class="form-control" step="0.01" placeholder="0.00" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Expiry Date</label>
                            <input type="date" name="expiry_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Policy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>