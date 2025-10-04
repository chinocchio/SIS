<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - SIS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f6fb;
        }
        
        .container {
            max-width: 1400px;
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
        
        .btn-info {
            background: #17a2b8;
        }
        
        .btn-info:hover {
            background: #138496;
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
        
        .teacher-info {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #bbdefb;
            margin-bottom: 30px;
        }
        
        .teacher-info h2 {
            margin-top: 0;
            color: #1976d2;
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
            overflow: hidden;
        }
        
        .assignment-header {
            background: #667eea;
            color: white;
            padding: 15px;
        }
        
        .assignment-header h4 {
            margin: 0;
            font-size: 16px;
        }
        
        .assignment-header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        
        .assignment-content {
            padding: 15px;
        }
        
        .students-list {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            background: white;
        }
        
        .student-item {
            padding: 8px 12px;
            border-bottom: 1px solid #f1f3f4;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .student-item:last-child {
            border-bottom: none;
        }
        
        .student-name {
            font-weight: 500;
            color: #333;
        }
        
        .student-lrn {
            font-size: 12px;
            color: #6c757d;
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
            margin-top: 15px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .action-buttons .btn {
            margin: 0;
            font-size: 12px;
            padding: 6px 12px;
        }
        
        .school-year-info {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .school-year-info h4 {
            margin-top: 0;
            color: #856404;
        }
        
        @media (max-width: 768px) {
            .assignments-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👨‍🏫 Teacher Dashboard</h1>
            <div>
                <a href="/auth/logout" class="btn btn-secondary">🚪 Logout</a>
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
        
        <!-- Teacher Welcome Section -->
        <div class="teacher-info">
            <h2>Welcome, <?= esc(session()->get('first_name', 'Teacher') . ' ' . session()->get('last_name', '')) ?>!</h2>
            <p>Manage your assigned subjects and students from this dashboard.</p>
            
            <?php if ($activeSchoolYear): ?>
                <div class="school-year-info">
                    <h4>📅 Current School Year: <?= esc($activeSchoolYear['name']) ?></h4>
                    <p>You are viewing assignments for the <?= esc($activeSchoolYear['name']) ?> school year.</p>
                </div>
            <?php endif; ?>
        </div>
        
        
        <!-- Assignments Section -->
        <div class="assignments-section">
            <h3>📚 My Subject Assignments</h3>
            
            <?php if (!empty($sectionsWithStudents)): ?>
                <div class="assignments-grid">
                    <?php foreach ($sectionsWithStudents as $sectionData): ?>
                        <?php $assignment = $sectionData['assignment']; ?>
                        <?php $students = $sectionData['students']; ?>
                        <?php $studentCount = $sectionData['student_count']; ?>
                        
                        <div class="assignment-card">
                            <div class="assignment-header">
                                <h4><?= esc($assignment['subject_name']) ?></h4>
                                <p><?= esc($assignment['section_name']) ?> - Grade <?= $assignment['section_grade_level'] ?></p>
                                <p>👥 <?= $studentCount ?> students enrolled</p>
                            </div>
                            
                            <div class="assignment-content">
                                <p><strong>Subject Code:</strong> <?= esc($assignment['subject_code']) ?></p>
                                <p><strong>School Year:</strong> <?= esc($assignment['school_year']) ?></p>
                                
                                <?php if (!empty($students)): ?>
                                    <div class="students-list">
                                        <?php foreach ($students as $student): ?>
                                            <div class="student-item">
                                                <div>
                                                    <div class="student-name"><?= esc($student['full_name']) ?></div>
                                                    <div class="student-lrn">LRN: <?= esc($student['lrn']) ?></div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="no-data">
                                        <p>No students enrolled in this section.</p>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="action-buttons">
                                    <a href="/teacher/students/<?= $assignment['section_id'] ?>" class="btn btn-info">
                                        👥 View Students
                                    </a>
                                    <a href="/teacher/grades/<?= $assignment['section_id'] ?>" class="btn btn-success">
                                        📝 Input Grades
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-data">
                    <h4>No Subject Assignments Found</h4>
                    <p>You haven't been assigned to any subjects yet.</p>
                    <p><strong>Contact your administrator to get subject assignments.</strong></p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Quick Actions -->
        <div class="assignments-section">
            <h3>⚡ Quick Actions</h3>
            <div class="action-buttons">
                <a href="/teacher/grades" class="btn btn-success">📊 Grade Management</a>
                <a href="/face-recognition" class="btn btn-warning">📷 Face Recognition</a>
                <a href="/face-recognition/capture" class="btn btn-primary">📸 Capture Faces</a>
                <a href="/teacher/attendance" class="btn btn-info">📋 Attendance</a>
                <!-- <a href="/teacher/reports" class="btn btn-secondary">📋 Generate Reports</a> -->
                <a href="/auth/change-password" class="btn btn-secondary">🔒 Change Password</a>
            </div>
        </div>
    </div>
</body>
</html>