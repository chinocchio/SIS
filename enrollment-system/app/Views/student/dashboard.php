<?php 
$pageTitle = 'Student Dashboard - ' . esc($student['full_name']);
include __DIR__ . '/partials/layout_start.php'; 
?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<!-- Page Header -->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">👤 Student Dashboard</h1>
    </div>

    <!-- LRN Display -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body text-center">
                    <div class="lrn-display">
                        LRN: <?= esc($student['lrn']) ?>
                    </div>
                    <div class="full-name-display">
                        <?= esc($student['full_name']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Personal Information -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">📋 Personal Information</h6>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">Full Name:</span>
                        <span class="info-value"><?= esc($student['full_name']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Birth Date:</span>
                        <span class="info-value">
                            <?= $student['birth_date'] ? date('F d, Y', strtotime($student['birth_date'])) : '<span class="no-data">Not specified</span>' ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Gender:</span>
                        <span class="info-value">
                            <?= $student['gender'] ? esc($student['gender']) : '<span class="no-data">Not specified</span>' ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value"><?= esc($student['email']) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic Information -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">🎓 Academic Information</h6>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">Grade Level:</span>
                        <span class="info-value">
                            <?= $student['grade_level'] ? 'Grade ' . esc($student['grade_level']) : '<span class="no-data">Not specified</span>' ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Previous Grade:</span>
                        <span class="info-value">
                            <?= $student['previous_grade_level'] ? 'Grade ' . esc($student['previous_grade_level']) : '<span class="no-data">Not specified</span>' ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Enrollment Type:</span>
                        <span class="info-value"><?= esc(ucfirst($student['enrollment_type'] ?? 'new')) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Admission Type:</span>
                        <span class="info-value"><?= esc(ucfirst($student['admission_type'] ?? 'regular')) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- School Information -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">🏫 School Information</h6>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">Previous School:</span>
                        <span class="info-value">
                            <?= $student['previous_school'] ? esc($student['previous_school']) : '<span class="no-data">Not specified</span>' ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Strand:</span>
                        <span class="info-value">
                            <?= isset($student['strand_name']) ? esc($student['strand_name']) : '<span class="no-data">Not specified</span>' ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Curriculum:</span>
                        <span class="info-value">
                            <?= isset($student['curriculum_name']) ? esc($student['curriculum_name']) : '<span class="no-data">Not specified</span>' ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Information -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">🏫 Section Information</h6>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">Current Section:</span>
                        <span class="info-value">
                            <?= isset($student['section_name']) ? esc($student['section_name']) . ' (Grade ' . $student['section_grade_level'] . ')' : '<span class="no-data">Not assigned</span>' ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Previous Section:</span>
                        <span class="info-value">
                            <?= isset($student['previous_section_name']) ? esc($student['previous_section_name']) . ' (Grade ' . $student['previous_section_grade_level'] . ')' : '<span class="no-data">Not specified</span>' ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Previous School Year:</span>
                        <span class="info-value">
                            <?= $student['previous_school_year'] ? esc($student['previous_school_year']) : '<span class="no-data">Not specified</span>' ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>


        <!-- Account Information -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">🔐 Account Information</h6>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="info-value">
                            <span class="status-badge status-<?= strtolower($student['status']) ?>">
                                <?= esc(ucfirst($student['status'] === 'draft' ? 'pending' : $student['status'])) ?>
                            </span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Created Date:</span>
                        <span class="info-value">
                            <?= $student['created_at'] ? date('F d, Y \a\t g:i A', strtotime($student['created_at'])) : '<span class="no-data">Not specified</span>' ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Last Updated:</span>
                        <span class="info-value">
                            <?= $student['updated_at'] ? date('F d, Y \a\t g:i A', strtotime($student['updated_at'])) : '<span class="no-data">Not specified</span>' ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <!-- <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">⚡ Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="action-buttons">
                        <a href="/student/attendance" class="btn btn-info">📋 My Attendance</a>
                        <a href="/student/change-password" class="btn btn-warning">🔒 Change Password</a>
                        <a href="/auth/logout" class="btn btn-secondary">🚪 Logout</a>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</div>

<?php include __DIR__ . '/partials/layout_end.php'; ?>

<style>
.lrn-display {
    background: #667eea;
    color: white;
    padding: 15px;
    border-radius: 8px;
    text-align: center;
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 20px;
}

.full-name-display {
    background: #28a745;
    color: white;
    padding: 15px;
    border-radius: 8px;
    text-align: center;
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 20px;
}

.info-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    padding: 8px 0;
    border-bottom: 1px solid #e9ecef;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: bold;
    color: #495057;
    min-width: 120px;
}

.info-value {
    color: #333;
    text-align: right;
    flex: 1;
}

.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
}

.status-draft {
    background: #fff3cd;
    color: #856404;
}

.status-pending {
    background: #d1ecf1;
    color: #0c5460;
}

.status-approved {
    background: #d4edda;
    color: #155724;
}

.status-rejected {
    background: #f8d7da;
    color: #721c24;
}

.no-data {
    color: #6c757d;
    font-style: italic;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
</style>

<?php include __DIR__ . '/partials/layout_end.php'; ?>