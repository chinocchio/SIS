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
        
        .teacher-info {
            background: #e8f5e8;
            padding: 25px;
            border-radius: 8px;
            border: 1px solid #c3e6cb;
            margin-bottom: 30px;
        }
        
        .teacher-info h2 {
            margin-top: 0;
            color: #155724;
        }
        
        .school-year-info {
            background: white;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #c3e6cb;
            margin-top: 15px;
        }
        
        .school-year-info h4 {
            margin-top: 0;
            color: #155724;
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
        
        .students-list {
            margin: 15px 0;
        }
        
        .student-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .student-item:last-child {
            border-bottom: none;
        }
        
        .student-name {
            font-weight: 500;
            color: #333;
        }
        
        .student-lrn {
            color: #6c757d;
            font-size: 12px;
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
        
        @media (max-width: 768px) {
            .assignments-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .action-buttons .btn {
                flex: none;
            }
        }
    </style>
    <?php include __DIR__ . '/partials/sidebar_styles.php'; ?>
</head>
<body>
    <div class="container">
        <?php include __DIR__ . '/partials/layout_start.php'; ?>
        <div class="header">
            <h1>👨‍🏫 Teacher Dashboard</h1>
            <p>Manage your assigned subjects and students from this dashboard.</p>
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
                <h3>📚 Your Subject Assignments</h3>
                
                <?php if (!empty($assignments)): ?>
                    <div class="assignments-grid">
                        <?php foreach ($assignments as $assignment): ?>
                            <?php 
                            // Get students for this section
                            $studentModel = new \App\Models\StudentModel();
                            $students = $studentModel->getStudentsBySection($assignment['section_id']);
                            ?>
                            <div class="assignment-card">
                                <div class="assignment-header">
                                    <h4><?= esc($assignment['subject_name']) ?></h4>
                                    <div class="assignment-subject"><?= esc($assignment['subject_code']) ?></div>
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
                                        <a href="/teacher/grades/<?= $assignment['section_id'] ?>?subject_id=<?= $assignment['subject_id'] ?>" class="btn btn-success">
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
        </div>
        <?php include __DIR__ . '/partials/layout_end.php'; ?>
    </div>
</body>
</html>