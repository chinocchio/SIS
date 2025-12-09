<?php 
$pageTitle = 'My Attendance - Student Dashboard';
include __DIR__ . '/partials/layout_start.php'; 
?>

<!-- Page Header -->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">📊 My Attendance Records</h1>
    </div>

    <!-- Info Box -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="info-box">
                        <p><strong>📝 Note:</strong> Your attendance is automatically recorded when teachers use the face recognition system during class sessions.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Attendance</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($attendance) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Subjects</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count(array_unique(array_column($attendance, 'subject_id'))) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Today</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count(array_filter($attendance, function($r) { return date('Y-m-d', strtotime($r['recorded_at'])) === date('Y-m-d'); })) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">📋 My Attendance History</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($attendance)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($attendance as $record): ?>
                                        <tr>
                                            <td>
                                                <strong><?= esc($record['subject_name']) ?></strong>
                                                <br><small><?= esc($record['subject_code']) ?></small>
                                            </td>
                                            <td><?= date('M d, Y', strtotime($record['recorded_at'])) ?></td>
                                            <td><?= date('g:i A', strtotime($record['recorded_at'])) ?></td>
                                            <td>
                                                <span class="status-badge status-present">Present</span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="no-data">
                            <h4>📊 No Attendance Records</h4>
                            <p>You don't have any attendance records yet.</p>
                            <p>Your attendance will be recorded automatically when teachers use the face recognition system during class.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.info-box {
    background: #e3f2fd;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 1px solid #bbdefb;
}

.info-box p {
    margin: 0;
    color: #1976d2;
}

.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
}

.status-present {
    background: #d4edda;
    color: #155724;
}

.no-data {
    color: #6c757d;
    font-style: italic;
    text-align: center;
    padding: 40px;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
</style>

<?php include __DIR__ . '/partials/layout_end.php'; ?>