<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Records - Teacher Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f8f9fc;
        }
        
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 0;
        }
        
        .header {
            padding: 2rem;
            background: white;
            border-bottom: 1px solid #e3e6f0;
        }
        
        .header h1 {
            color: #333;
            margin: 0;
            font-size: 2rem;
        }
        
        .header p {
            color: #6c757d;
            margin: 0.5rem 0 0 0;
        }
        
        .content {
            padding: 2rem;
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
        
        .assignments-section {
            margin-bottom: 30px;
        }
        
        .assignments-section h3 {
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
        }
        
        .assignments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
        }
        
        .assignment-card {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            transition: all 0.3s ease;
        }
        
        .assignment-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .assignment-header {
            margin-bottom: 15px;
        }
        
        .assignment-header h4 {
            margin: 0 0 5px 0;
            color: #333;
            font-size: 18px;
        }
        
        .assignment-subject {
            color: #6c757d;
            font-size: 14px;
            font-weight: bold;
        }
        
        .assignment-content {
            margin-bottom: 20px;
        }
        
        .assignment-content p {
            margin: 5px 0;
            font-size: 14px;
            color: #6c757d;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .action-buttons .btn {
            margin: 0;
            flex: 1;
            min-width: 120px;
            text-align: center;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        
        .no-data h4 {
            margin-bottom: 10px;
            color: #495057;
        }
        
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .attendance-table th,
        .attendance-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .attendance-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .attendance-table tr:hover {
            background-color: #f5f5f5;
        }
        
        .student-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .student-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #667eea;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
        }
        
        .student-details h4 {
            margin: 0;
            color: #333;
            font-size: 16px;
        }
        
        .student-details p {
            margin: 0;
            color: #6c757d;
            font-size: 14px;
        }
        
        .attendance-status {
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .attendance-status.present {
            background: #d4edda;
            color: #155724;
        }
        
        .attendance-status.absent {
            background: #f8d7da;
            color: #721c24;
        }
        
        .attendance-status.pending {
            background: #e2e3e5;
            color: #6c757d;
        }
        
        .attendance-summary {
            background: #e8f5e8;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #c3e6cb;
            margin-bottom: 30px;
        }
        
        .attendance-summary h3 {
            margin-top: 0;
            color: #155724;
        }
        
        .summary-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        
        .stat-item {
            background: white;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #c3e6cb;
            text-align: center;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
        }
        
        .table-container {
            max-height: 600px;
            overflow-y: auto;
            overflow-x: auto;
            border: 1px solid #e9ecef;
            border-radius: 8px;
        }
        
        .attendance-table {
            min-width: 100%;
        }
        
        .attendance-table th:first-child,
        .attendance-table td:first-child {
            position: sticky;
            left: 0;
            background: #f8f9fa;
            z-index: 5;
        }
        
        .attendance-table th:nth-child(2),
        .attendance-table td:nth-child(2) {
            position: sticky;
            left: 200px;
            background: #f8f9fa;
            z-index: 5;
        }
        
        @media (max-width: 768px) {
            .summary-stats {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .attendance-table {
                font-size: 14px;
            }
            
            .attendance-table th,
            .attendance-table td {
                padding: 8px;
            }
        }
    </style>
    <?php include __DIR__ . '/partials/sidebar_styles.php'; ?>
</head>
<body>
    <div class="container">
        <?php include __DIR__ . '/partials/layout_start.php'; ?>
        <div class="header">
            <h1>📋 Attendance Records</h1>
            <p>Manage student attendance for your assigned subjects</p>
        </div>
        
        <div class="content">
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
            
            <!-- Attendance Summary -->
            <!-- <?php if (!empty($attendanceData)): ?>
                <div class="attendance-summary">
                    <h3>📊 Attendance Summary</h3>
                    <div class="summary-stats">
                        <?php 
                        $totalStudents = 0;
                        $totalRecords = 0;
                        $totalDates = 0;
                        foreach ($attendanceData as $data) {
                            $totalStudents += count($data['students']);
                            $totalRecords += count($data['attendance_lookup']);
                            $totalDates += count($data['dates']);
                        }
                        ?>
                        <div class="stat-item">
                            <div class="stat-number"><?= $totalStudents ?></div>
                            <div class="stat-label">Total Students</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?= count($attendanceData) ?></div>
                            <div class="stat-label">Subjects</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?= $totalRecords ?></div>
                            <div class="stat-label">Attendance Records</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?= $totalDates ?></div>
                            <div class="stat-label">Days</div>
                        </div>
                        <h4>No Subject Assignments Found</h4>
                        <p>You haven't been assigned to any subjects yet.</p>
                        <p><strong>Contact your administrator to get subject assignments.</strong></p>
                    </div>
                <?php endif; ?>
            </div> -->
            
            <!-- Attendance Records by Subject -->
            <?php if (!empty($attendanceData)): ?>
                <?php foreach ($attendanceData as $subjectData): ?>
                    <div class="assignments-section">
                        <h3>📋 <?= esc($subjectData['subject']['subject_name']) ?> - <?= esc($subjectData['subject']['subject_code']) ?></h3>
                        <p>
                            <strong>Section:</strong> <?= esc($subjectData['subject']['section_name']) ?> | 
                            <strong>School Year:</strong> <?= esc($subjectData['subject']['school_year']) ?>
                            <?php if (!empty($subjectData['curriculum_name']) && $subjectData['grade_level'] <= 10): ?>
                                | <strong>Curriculum:</strong> <?= esc($subjectData['curriculum_name']) ?>
                            <?php elseif (!empty($subjectData['strand_name']) && $subjectData['grade_level'] >= 11): ?>
                                | <strong>Strand:</strong> <?= esc($subjectData['strand_name']) ?>
                            <?php endif; ?>
                        </p>
                        
                        <?php if (!empty($subjectData['students']) && !empty($subjectData['dates'])): ?>
                            <div class="table-container">
                                <table class="attendance-table">
                                    <thead>
                                        <tr>
                                            <th>Student Name</th>
                                            <th>LRN</th>
                                            <?php foreach ($subjectData['dates'] as $date): ?>
                                                <th><?= date('M d', strtotime($date)) ?></th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($subjectData['students'] as $student): ?>
                                            <tr>
                                                <td>
                                                    <div class="student-info">
                                                        <div class="student-avatar">
                                                            <?= strtoupper(substr($student['full_name'], 0, 2)) ?>
                                                        </div>
                                                        <div class="student-details">
                                                            <h4><?= esc($student['full_name']) ?></h4>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?= esc($student['lrn']) ?></td>
                                                <?php foreach ($subjectData['dates'] as $date): ?>
                                                    <td>
                                                        <?php if (isset($subjectData['attendance_lookup'][$student['id']][$date])): ?>
                                                            <span class="attendance-status present">Present</span>
                                                        <?php else: ?>
                                                            <?php 
                                                            // Check if it's the end of the day (after 4 PM) or if it's a past date
                                                            $currentDate = date('Y-m-d');
                                                            $currentTime = date('H:i');
                                                            $isEndOfDay = ($currentTime >= '16:00' || $date < $currentDate);
                                                            ?>
                                                            <?php if ($isEndOfDay): ?>
                                                                <span class="attendance-status absent">Absent</span>
                                                            <?php else: ?>
                                                                <span class="attendance-status pending">-</span>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php elseif (!empty($subjectData['students']) && empty($subjectData['dates'])): ?>
                            <div class="no-data">
                                <h4>No Attendance Records Yet</h4>
                                <p>No attendance has been recorded for this subject yet.</p>
                                <p><strong>Use the "Take Attendance" button above to start recording attendance.</strong></p>
                            </div>
                        <?php else: ?>
                            <div class="no-data">
                                <h4>No Students Enrolled</h4>
                                <p>No students are enrolled in this section.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="assignments-section">
                    <h3>📋 Attendance Records</h3>
                    <div class="no-data">
                        <h4>No Subject Assignments Found</h4>
                        <p>You haven't been assigned to any subjects yet.</p>
                        <p><strong>Contact your administrator to get subject assignments.</strong></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php include __DIR__ . '/partials/layout_end.php'; ?>
    </div>
</body>
</html>