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
            padding: 20px;
            background: #f5f6fb;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }
        
        .header h1 {
            color: #333;
            margin: 0;
        }
        
        .btn {
            background: #667eea;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-left: 10px;
        }
        
        .btn:hover {
            background: #5a6fd8;
        }
        
        .btn-secondary {
            background: #6c757d;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .btn-success {
            background: #28a745;
        }
        
        .btn-success:hover {
            background: #218838;
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
            background: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #bbdefb;
            margin-bottom: 30px;
        }
        
        .section-info h2 {
            margin-top: 0;
            color: #1976d2;
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
        
        .student-info {
            display: flex;
            align-items: center;
            gap: 12px;
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
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-style: italic;
        }
        
        .no-data h4 {
            color: #495057;
            margin-bottom: 10px;
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
        
        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👥 Students in <?= esc($section['name']) ?></h1>
            <div>
                <a href="/teacher/dashboard" class="btn btn-secondary">← Back to Dashboard</a>
            </div>
        </div>
        
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
                    <div class="info-label">Subject</div>
                    <div class="info-value"><?= esc($subjectInfo['subject_name']) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Subject Code</div>
                    <div class="info-value"><?= esc($subjectInfo['subject_code']) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">School Year</div>
                    <div class="info-value"><?= esc($subjectInfo['school_year']) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Total Students</div>
                    <div class="info-value"><?= count($students) ?> enrolled</div>
                </div>
            </div>
        </div>
        
        <!-- Students List -->
        <?php if (!empty($students)): ?>
            <table class="students-table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>LRN</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Actions</th>
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
                                        <p><?= esc($student['lrn']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td><?= esc($student['lrn']) ?></td>
                            <td><?= esc($student['email']) ?></td>
                            <td>
                                <span style="color: #28a745; font-weight: bold;">
                                    <?= ucfirst($student['status']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="/teacher/student-grades/<?= $student['id'] ?>/<?= $subjectInfo['school_year_id'] ?>" 
                                   class="btn btn-success" style="padding: 5px 10px; font-size: 12px;">
                                    📊 View Grades
                                </a>
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
            <a href="/teacher/grades/<?= $section['id'] ?>" class="btn btn-success">
                📝 Input Grades
            </a>
            <a href="/teacher/dashboard" class="btn btn-secondary">
                ← Back to Dashboard
            </a>
        </div>
    </div>
</body>
</html>
