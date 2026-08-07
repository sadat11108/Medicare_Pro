<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Symptom & Disease Management - SmartHealth</title>
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
        .severity-high { background-color: #dc3545; color: white; }
        .severity-medium { background-color: #ffc107; color: #212529; }
        .severity-low { background-color: #28a745; color: white; }
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
                            <a class="nav-link active" href="symptom_log.php">
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
                    <h1 class="h2">Symptom & Disease Management</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSymptomModal">
                        <i class="bi bi-plus-circle"></i> Log Symptoms
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
                $totalSymptoms = $pdo->query("SELECT COUNT(*) as count FROM symptom_logs")->fetch()['count'];
                $highSeverity = $pdo->query("SELECT COUNT(*) as count FROM symptom_logs WHERE severity = 'High'")->fetch()['count'];
                $recentLogs = $pdo->query("SELECT COUNT(*) as count FROM symptom_logs WHERE log_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)")->fetch()['count'];
                ?>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card stats-card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-muted">Total Symptom Logs</h6>
                                <h2 class="text-primary"><?= $totalSymptoms ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stats-card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-muted">High Severity</h6>
                                <h2 class="text-danger"><?= $highSeverity ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stats-card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-muted">Last 7 Days</h6>
                                <h2 class="text-success"><?= $recentLogs ?></h2>
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
                                        <th>Symptoms</th>
                                        <th>Disease</th>
                                        <th>Severity</th>
                                        <th>Log Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $pdo->query("SELECT s.*, p.name as patient_name 
                                                         FROM symptom_logs s 
                                                         JOIN patients p ON s.patient_id = p.id 
                                                         ORDER BY s.log_date DESC");
                                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                                        $severityClass = $row['severity'] == 'High' ? 'severity-high' : ($row['severity'] == 'Medium' ? 'severity-medium' : 'severity-low');
                                    ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= htmlspecialchars($row['patient_name']) ?></td>
                                        <td><?= htmlspecialchars($row['symptoms']) ?></td>
                                        <td><?= htmlspecialchars($row['disease'] ?? 'Not Diagnosed') ?></td>
                                        <td>
                                            <span class="badge <?= $severityClass ?>">
                                                <?= $row['severity'] ?>
                                            </span>
                                        </td>
                                        <td><?= date('M d, Y', strtotime($row['log_date'])) ?></td>
                                        <td>
                                            <a href="view_symptom.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">View</a>
                                            <a href="edit_symptom.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="delete_symptom.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this symptom log?')">Delete</a>
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

    <!-- Add Symptom Modal -->
    <div class="modal fade" id="addSymptomModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Log Patient Symptoms</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="add_symptom.php" method="POST">
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
                            <label class="form-label">Symptoms</label>
                            <textarea name="symptoms" class="form-control" rows="3" placeholder="e.g., Fever, Cough, Headache, Fatigue" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Disease (if diagnosed)</label>
                            <input type="text" name="disease" class="form-control" placeholder="e.g., COVID-19, Flu, Migraine">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Severity</label>
                            <select name="severity" class="form-control" required>
                                <option value="Low">Low</option>
                                <option value="Medium" selected>Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Log Symptoms</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>