<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Healthcare Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --sidebar-color: #1a252f; }
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        .sidebar { min-height: 100vh; background: var(--sidebar-color); color: white; transition: all 0.3s; }
        .nav-link { color: #bdc3c7; padding: 15px 20px; border-radius: 5px; margin-bottom: 5px; transition: 0.2s; }
        .nav-link:hover, .nav-link.active { background: #34495e; color: white; }
        
        .feature-btn { 
            padding: 25px 20px; 
            text-align: left; 
            font-weight: 600; 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            border: none;
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none;
        }
        .feature-btn:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
        .feature-btn i { font-size: 1.8rem; }
        
        .dropdown-menu { border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .dropdown-item { padding: 10px 20px; }
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
                        <a class="nav-link active" href="dashboard.php">
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
                        <a class="nav-link" href="preventive.php">
                            <i class="bi bi-shield-plus me-2"></i> Preventive Care
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Healthcare Management Control Panel</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <span class="badge bg-light text-dark border p-2"><i class="bi bi-calendar3"></i> <?= date('F Y') ?></span>
                </div>
            </div>

            <!-- Statistics Cards -->
            <?php
            try {
                $totalPatients = $pdo->query("SELECT COUNT(*) as count FROM patients")->fetch()['count'] ?? 0;
                $totalDoctors = $pdo->query("SELECT COUNT(*) as count FROM doctors")->fetch()['count'] ?? 0;
                $totalAppointments = $pdo->query("SELECT COUNT(*) as count FROM appointments")->fetch()['count'] ?? 0;
                $pendingAppointments = $pdo->query("SELECT COUNT(*) as count FROM appointments WHERE status = 'Pending'")->fetch()['count'] ?? 0;
            } catch(PDOException $e) {
                $totalPatients = 0;
                $totalDoctors = 0;
                $totalAppointments = 0;
                $pendingAppointments = 0;
            }
            ?>
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted">Total Patients</h6>
                            <h2 class="text-primary"><?= $totalPatients ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted">Total Doctors</h6>
                            <h2 class="text-success"><?= $totalDoctors ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted">Total Appointments</h6>
                            <h2 class="text-info"><?= $totalAppointments ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted">Pending Appointments</h6>
                            <h2 class="text-warning"><?= $pendingAppointments ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Module Cards -->
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <a href="MedicalRecordManagement.php" class="btn btn-primary w-100 feature-btn shadow-sm">
                        <span>Patient & Medical Record Management</span>
                        <i class="bi bi-person-badge"></i>
                    </a>
                </div>

                <div class="col-md-6 col-lg-4">
                    <a href="Doctor&AppointmentManagement.php" class="btn btn-success w-100 feature-btn shadow-sm">
                        <span>Doctor & Appointment Management</span>
                        <i class="bi bi-calendar-check"></i>
                    </a>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="dropdown">
                        <button class="btn btn-info text-white w-100 feature-btn shadow-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <span>Prescription & Investigation Management</span>
                            <i class="bi bi-flask"></i>
                        </button>
                        <ul class="dropdown-menu w-100">
                            <li><a class="dropdown-item" href="Prescription.php"><i class="bi bi-capsule me-2"></i> Prescription Management</a></li>
                            <li><a class="dropdown-item" href="investigation.php"><i class="bi bi-microscope me-2"></i> Investigation (Lab Tests)</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="dropdown">
                        <button class="btn btn-dark w-100 feature-btn shadow-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <span>Billing & Insurance Automation</span>
                            <i class="bi bi-cash-stack"></i>
                        </button>
                        <ul class="dropdown-menu w-100">
                            <li><a class="dropdown-item" href="billing.php"><i class="bi bi-receipt me-2"></i> Billing (Invoices)</a></li>
                            <li><a class="dropdown-item" href="insurance.php"><i class="bi bi-shield-check me-2"></i> Insurance (Claims)</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <a href="symptom_log.php" class="btn btn-warning w-100 feature-btn shadow-sm">
                        <span>Symptom & Disease Management</span>
                        <i class="bi bi-thermometer-half"></i>
                    </a>
                </div>

                <div class="col-md-6 col-lg-4">
                    <a href="preventive.php" class="btn btn-secondary w-100 feature-btn shadow-sm">
                        <span>Recommendations & Preventive Care</span>
                        <i class="bi bi-shield-plus"></i>
                    </a>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="mt-5">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 id="records">Recent Clinical Activity</h4>
                    <button class="btn btn-sm btn-outline-secondary">View All Logs</button>
                </div>
                <div class="card shadow-sm mt-3">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Patient ID</th>
                                    <th>Patient Name</th>
                                    <th>Status (Appointment)</th>
                                    <th>Latest Diagnosis</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                try {
                                    // Check if tables exist and have data
                                    $stmt = $pdo->query("SELECT p.id, p.name, a.status, mr.diagnosis 
                                        FROM patients p 
                                        LEFT JOIN appointments a ON p.id = a.patient_id 
                                        LEFT JOIN medical_records mr ON p.id = mr.patient_id 
                                        ORDER BY p.id DESC LIMIT 5");
                                    
                                    $rowCount = $stmt->rowCount();
                                    if($rowCount > 0) {
                                        while($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                                ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $row['status'] == 'Pending' ? 'warning' : ($row['status'] == 'Approved' ? 'success' : 'secondary') ?>">
                                            <?= $row['status'] ?? 'No Appointment' ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($row['diagnosis'] ?? 'No Diagnosis') ?></td>
                                    <td><a href="patient_management.php" class="btn btn-sm btn-outline-primary">View</a></td>
                                </tr>
                                <?php 
                                        endwhile;
                                    } else {
                                        // Show sample data if no records exist
                                ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">
                                        <i class="bi bi-info-circle"></i> No patient records found. Please add patients to see activity.
                                    </td>
                                </tr>
                                <?php
                                    }
                                } catch(PDOException $e) {
                                    // Show error message
                                ?>
                                <tr>
                                    <td colspan="5" class="text-center text-danger py-3">
                                        <i class="bi bi-exclamation-triangle"></i> Unable to load recent activity. Please check your database connection.
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>