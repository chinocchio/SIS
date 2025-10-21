<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students - <?= esc($section['name']) ?> - Teacher Dashboard</title>
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
        
        .section-info {
            background: #e8f5e8;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #c3e6cb;
            margin-bottom: 30px;
        }
        
        .section-info h2 {
            margin-top: 0;
            color: #155724;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        
        .info-item {
            background: white;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }
        
        .info-label {
            font-weight: bold;
            color: #495057;
            font-size: 14px;
        }
        
        .info-value {
            color: #333;
            margin-top: 5px;
        }
        
        .students-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .students-table th,
        .students-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .students-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        
        .students-table tr:hover {
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
        
        .action-buttons {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .action-buttons .btn {
            margin: 0;
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
        
        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
    <?php include __DIR__ . '/partials/sidebar_styles.php'; ?>
</head>
<body>
    <div class="container">
        <?php include __DIR__ . '/partials/layout_start.php'; ?>
        <div class="header">
            <h1>👥 Students - <?= esc($section['name']) ?></h1>
            <p>View and manage students in this section</p>
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
            
            <!-- Section Information -->
            <div class="section-info">
                <h2>📚 Section Information</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Section Name</div>
                        <div class="info-value"><?= esc($section['name']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Grade Level</div>
                        <div class="info-value">Grade <?= $section['grade_level'] ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">School Year</div>
                        <div class="info-value"><?= esc($activeSchoolYear['name']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Total Students</div>
                        <div class="info-value"><?= count($students) ?> enrolled</div>
                    </div>
                </div>
            </div>
            
            <!-- Students Table -->
            <?php if (!empty($students)): ?>
                <table class="students-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>LRN</th>
                            <th>Email</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
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
                                <td><?= esc($student['email']) ?></td>
                                <td>
                                    <span style="color: <?= $student['status'] === 'approved' ? '#28a745' : ($student['status'] === 'pending' ? '#ffc107' : '#dc3545') ?>;">
                                        <?= ucfirst($student['status']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <h4>No Students Found</h4>
                    <p>There are no students enrolled in this section.</p>
                </div>
            <?php endif; ?>
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="/teacher/grades/<?= $section['id'] ?>?subject_id=<?= $subjectInfo['subject_id'] ?>" class="btn btn-success">
                    📝 Input Grades
                </a>
                <a href="/teacher/dashboard" class="btn btn-secondary">
                    ← Back to Dashboard
                </a>
            </div>
        </div>
        <?php include __DIR__ . '/partials/layout_end.php'; ?>
    </div>
</body>
</html>