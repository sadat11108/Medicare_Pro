<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preventive Care - SmartHealth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .sidebar { min-height: 100vh; background: #1a252f; }
        .nav-link { color: #bdc3c7; padding: 15px 20px; border-radius: 5px; margin-bottom: 5px; transition: 0.2s; }
        .nav-link:hover, .nav-link.active { background: #34495e; color: white; }
        .stats-card { transition: transform 0.2s; }
        .stats-card:hover { transform: translateY(-5px); }
        .priority-high { background-color: #dc3545; color: white; }
        .priority-medium { background-color: #ffc107; color: #212529; }
        .priority-low { background-color: #28a745; color: white; }
        .category-badge { font-size: 0.8rem; padding: 4px 10px; }
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
                    <h1 class="h2">Preventive Care & Recommendations</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPreventiveModal">
                        <i class="bi bi-plus-circle"></i> Add Recommendation
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
                try {
                    $totalRecommendations = $pdo->query("SELECT COUNT(*) as count FROM preventive_care")->fetch()['count'] ?? 0;
                    $pendingCount = $pdo->query("SELECT COUNT(*) as count FROM preventive_care WHERE status = 'Pending'")->fetch()['count'] ?? 0;
                    $completedCount = $pdo->query("SELECT COUNT(*) as count FROM preventive_care WHERE status = 'Completed'")->fetch()['count'] ?? 0;
                    $highPriority = $pdo->query("SELECT COUNT(*) as count FROM preventive_care WHERE priority = 'High' AND status != 'Completed'")->fetch()['count'] ?? 0;
                } catch(PDOException $e) {
                    $totalRecommendations = 0;
                    $pendingCount = 0;
                    $completedCount = 0;
                    $highPriority = 0;
                }
                ?>
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card stats-card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-muted">Total Recommendations</h6>
                                <h2 class="text-primary"><?= $totalRecommendations ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-muted">Pending</h6>
                                <h2 class="text-warning"><?= $pendingCount ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-muted">Completed</h6>
                                <h2 class="text-success"><?= $completedCount ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-muted">High Priority</h6>
                                <h2 class="text-danger"><?= $highPriority ?></h2>
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
                                        <th>Recommendation</th>
                                        <th>Category</th>
                                        <th>Priority</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    try {
                                        $stmt = $pdo->query("SELECT pc.*, p.name as patient_name 
                                                              FROM preventive_care pc 
                                                              JOIN patients p ON pc.patient_id = p.id 
                                                              ORDER BY pc.priority = 'High' DESC, pc.due_date ASC");
                                        if($stmt->rowCount() > 0) {
                                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                                                $priorityClass = $row['priority'] == 'High' ? 'priority-high' : ($row['priority'] == 'Medium' ? 'priority-medium' : 'priority-low');
                                                $statusClass = $row['status'] == 'Completed' ? 'success' : ($row['status'] == 'In Progress' ? 'info' : 'warning');
                                    ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= htmlspecialchars($row['patient_name']) ?></td>
                                        <td><?= htmlspecialchars(substr($row['recommendation'], 0, 50)) ?>...</td>
                                        <td>
                                            <span class="badge bg-secondary category-badge">
                                                <?= $row['category'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge <?= $priorityClass ?>">
                                                <?= $row['priority'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if($row['due_date']): ?>
                                                <?= date('M d, Y', strtotime($row['due_date'])) ?>
                                                <?php if(strtotime($row['due_date']) < time() && $row['status'] != 'Completed'): ?>
                                                    <span class="badge bg-danger ms-1">Overdue</span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted">No due date</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $statusClass ?>">
                                                <?= $row['status'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="view_preventive.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">View</a>
                                            <a href="edit_preventive.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <?php if($row['status'] != 'Completed'): ?>
                                                <a href="update_preventive.php?id=<?= $row['id'] ?>&status=Completed" class="btn btn-sm btn-success">Complete</a>
                                            <?php endif; ?>
                                            <a href="delete_preventive.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this recommendation?')">Delete</a>
                                        </td>
                                    </tr>
                                    <?php 
                                            endwhile;
                                        } else {
                                    ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-3">
                                            <i class="bi bi-info-circle"></i> No recommendations found. Click "Add Recommendation" to add one.
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    } catch(PDOException $e) {
                                    ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-danger py-3">
                                            <i class="bi bi-exclamation-triangle"></i> Error: <?= htmlspecialchars($e->getMessage()) ?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add Preventive Care Modal -->
    <div class="modal fade" id="addPreventiveModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Preventive Care Recommendation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="add_preventive.php" method="POST">
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
                            <label class="form-label">Recommendation</label>
                            <textarea name="recommendation" class="form-control" rows="3" placeholder="e.g., Annual physical checkup, Flu vaccination" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-control" required>
                                <option value="Diet">Diet</option>
                                <option value="Exercise">Exercise</option>
                                <option value="Vaccination">Vaccination</option>
                                <option value="Screening">Screening</option>
                                <option value="Lifestyle">Lifestyle</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Priority</label>
                            <select name="priority" class="form-control" required>
                                <option value="Low">Low</option>
                                <option value="Medium" selected>Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Due Date</label>
                            <input type="date" name="due_date" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Recommendation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>