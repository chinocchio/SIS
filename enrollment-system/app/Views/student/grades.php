<?php 
$pageTitle = 'My Grades - Student Dashboard';
include __DIR__ . '/partials/layout_start.php'; 
?>

<!-- Page Header -->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">📊 My Grades</h1>
    </div>

    <!-- School Year Info -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="grade-display">
                        <h4>📊 Current School Year: <?= esc($activeSchoolYear['name'] ?? 'Not Set') ?></h4>
                        <p>Your subjects for Grade <?= $student['grade_level'] ?> 
                           <?php if ($student['curriculum_id']): ?>
                               (<?= esc($student['curriculum_name'] ?? 'JHS Curriculum') ?>)
                           <?php elseif ($student['strand_id']): ?>
                               (<?= esc($student['strand_name'] ?? 'SHS Strand') ?>)
                           <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grades Content -->
    <div class="row">
        <div class="col-12">
            <?php if (!empty($allSubjects)): ?>
                <?php 
                // Group subjects by quarter for JHS or by semester for SHS
                $groupedSubjects = [];
                foreach ($allSubjects as $subject) {
                    if ($student['curriculum_id']) {
                        // JHS - group by quarter
                        $groupedSubjects['Q' . $subject['quarter']][] = $subject;
                    } else {
                        // SHS - group by semester, then by quarter
                        $semester = 'Semester ' . $subject['semester'];
                        if (!isset($groupedSubjects[$semester])) {
                            $groupedSubjects[$semester] = [];
                        }
                        $groupedSubjects[$semester]['Q' . $subject['quarter']][] = $subject;
                    }
                }
                ?>
                
                <?php foreach ($groupedSubjects as $period => $subjects): ?>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary"><?= esc($period) ?></h6>
                        </div>
                        <div class="card-body">
                            <?php if ($student['curriculum_id']): ?>
                                <!-- JHS: Direct subjects array -->
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Subject</th>
                                                <th>Code</th>
                                                <th>Units</th>
                                                <th>Type</th>
                                                <th>Grade</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($subjects as $subject): 
                                                // Find recorded grade for this subject
                                                $recordedGrade = null;
                                                foreach ($grades as $grade) {
                                                    if ($grade['subject_id'] == $subject['id']) {
                                                        $recordedGrade = $grade;
                                                        break;
                                                    }
                                                }
                                            ?>
                                                <tr>
                                                    <td>
                                                        <strong><?= esc($subject['name']) ?></strong>
                                                    </td>
                                                    <td>
                                                        <?= esc($subject['code']) ?>
                                                    </td>
                                                    <td>
                                                        <?= $subject['units'] ?>
                                                    </td>
                                                    <td>
                                                        <?= esc(ucfirst($subject['is_core'])) ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($recordedGrade): ?>
                                                            <span class="grade-value"><?= $recordedGrade['grade'] ?></span>
                                                        <?php else: ?>
                                                            <span style="color: #6c757d; font-style: italic;">Not recorded</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($recordedGrade): ?>
                                                            <span class="status-badge status-approved">Recorded</span>
                                                        <?php else: ?>
                                                            <span class="status-badge status-pending">Pending</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <!-- SHS: Nested by quarters within semester -->
                                <?php foreach ($subjects as $quarter => $quarterSubjects): ?>
                                    <div style="margin-bottom: 20px;">
                                        <h5 style="color: #495057; margin-bottom: 10px; padding: 8px; background: #e9ecef; border-radius: 4px;">
                                            <?= esc($quarter) ?>
                                        </h5>
                                        
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Subject</th>
                                                        <th>Code</th>
                                                        <th>Units</th>
                                                        <th>Type</th>
                                                        <th>Grade</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($quarterSubjects as $subject): 
                                                        // Find recorded grade for this subject
                                                        $recordedGrade = null;
                                                        foreach ($grades as $grade) {
                                                            if ($grade['subject_id'] == $subject['id']) {
                                                                $recordedGrade = $grade;
                                                                break;
                                                            }
                                                        }
                                                    ?>
                                                        <tr>
                                                            <td>
                                                                <strong><?= esc($subject['name']) ?></strong>
                                                            </td>
                                                            <td>
                                                                <?= esc($subject['code']) ?>
                                                            </td>
                                                            <td>
                                                                <?= $subject['units'] ?>
                                                            </td>
                                                            <td>
                                                                <?= esc(ucfirst($subject['is_core'])) ?>
                                                            </td>
                                                            <td>
                                                                <?php if ($recordedGrade): ?>
                                                                    <span class="grade-value"><?= $recordedGrade['grade'] ?></span>
                                                                <?php else: ?>
                                                                    <span style="color: #6c757d; font-style: italic;">Not recorded</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if ($recordedGrade): ?>
                                                                    <span class="status-badge status-approved">Recorded</span>
                                                                <?php else: ?>
                                                                    <span class="status-badge status-pending">Pending</span>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <!-- Note Section -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div style="background: #e3f2fd; padding: 15px; border-radius: 8px;">
                            <p style="margin: 0; color: #1976d2;">
                                <strong>📝 Note:</strong> Grades marked as "Pending" will be updated by your teachers as they record them throughout the school year.
                            </p>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="no-grades">
                            <h4>📊 No Subjects Found</h4>
                            <p>No subjects are currently assigned to your curriculum/strand and grade level.</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.grade-display {
    background: #e8f5e8;
    padding: 15px;
    border-radius: 6px;
    border: 1px solid #c3e6c3;
    margin-bottom: 20px;
}

.grade-display h4 {
    margin-top: 0;
    color: #155724;
}

.grade-value {
    font-size: 18px;
    font-weight: bold;
    color: #4e73df;
}

.no-grades {
    color: #6c757d;
    font-style: italic;
    text-align: center;
    padding: 20px;
}

.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
}

.status-pending {
    background: #d1ecf1;
    color: #0c5460;
}

.status-approved {
    background: #d4edda;
    color: #155724;
}
</style>

<?php include __DIR__ . '/partials/layout_end.php'; ?>
