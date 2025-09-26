<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Teacher Dashboard</title>
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
        
        .page-info {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #bbdefb;
            margin-bottom: 30px;
        }
        
        .page-info h2 {
            margin-top: 0;
            color: #1976d2;
        }
        
        .reports-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
        }
        
        .report-card {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .report-header {
            background: #667eea;
            color: white;
            padding: 15px;
        }
        
        .report-header h4 {
            margin: 0;
            font-size: 16px;
        }
        
        .report-header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        
        .report-content {
            padding: 15px;
        }
        
        .report-description {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 15px;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .action-buttons .btn {
            margin: 0;
            font-size: 12px;
            padding: 6px 12px;
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
        
        .quick-actions {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            margin-top: 30px;
        }
        
        .quick-actions h3 {
            margin-top: 0;
            color: #495057;
        }
        
        .quick-actions .action-buttons {
            margin-top: 15px;
        }
        
        .report-types {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .report-type {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            text-align: center;
        }
        
        .report-type h4 {
            margin-top: 0;
            color: #333;
        }
        
        .report-type p {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 15px;
        }
        
        @media (max-width: 768px) {
            .reports-grid {
                grid-template-columns: 1fr;
            }
            
            .report-types {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Reports & Analytics</h1>
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
        
        <!-- Page Information -->
        <div class="page-info">
            <h2>📊 Reports & Analytics Overview</h2>
            <p>Generate comprehensive reports for your assigned subjects and sections. View student progress, grade distributions, and create report cards.</p>
            
            <?php if ($activeSchoolYear): ?>
                <p><strong>Current School Year:</strong> <?= esc($activeSchoolYear['name']) ?></p>
            <?php endif; ?>
        </div>
        
        <!-- Subject Assignments -->
        <?php if (!empty($assignments)): ?>
            <div class="reports-grid">
                <?php foreach ($assignments as $assignment): ?>
                    <div class="report-card">
                        <div class="report-header">
                            <h4><?= esc($assignment['subject_name']) ?></h4>
                            <p><?= esc($assignment['section_name']) ?> - Grade <?= $assignment['section_grade_level'] ?></p>
                        </div>
                        
                        <div class="report-content">
                            <div class="report-description">
                                <strong>Subject Code:</strong> <?= esc($assignment['subject_code']) ?><br>
                                <strong>School Year:</strong> <?= esc($assignment['school_year']) ?>
                            </div>
                            
                            <div class="action-buttons">
                                <a href="/teacher/section-report/<?= $assignment['section_id'] ?>" class="btn btn-info">
                                    📊 Section Report
                                </a>
                                <a href="/teacher/grade-distribution/<?= $assignment['section_id'] ?>" class="btn btn-success">
                                    📈 Grade Distribution
                                </a>
                                <a href="/teacher/students/<?= $assignment['section_id'] ?>" class="btn btn-secondary">
                                    👥 View Students
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
        
        <!-- Report Types -->
        <div class="quick-actions">
            <h3>📋 Available Report Types</h3>
            <div class="report-types">
                <div class="report-type">
                    <h4>📊 Section Reports</h4>
                    <p>Comprehensive overview of student performance in a specific section</p>
                    <a href="#" class="btn btn-info">Generate</a>
                </div>
                
                <div class="report-type">
                    <h4>📈 Grade Distribution</h4>
                    <p>Statistical analysis of grade distribution and class performance</p>
                    <a href="#" class="btn btn-success">Generate</a>
                </div>
                
                <div class="report-type">
                    <h4>📋 Report Cards</h4>
                    <p>Individual student report cards with grades and comments</p>
                    <a href="#" class="btn btn-secondary">Generate</a>
                </div>
                
                <div class="report-type">
                    <h4>📊 Progress Reports</h4>
                    <p>Student progress tracking and improvement recommendations</p>
                    <a href="#" class="btn btn-info">Generate</a>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="quick-actions">
            <h3>⚡ Quick Actions</h3>
            <div class="action-buttons">
                <a href="/teacher/grades" class="btn btn-success">📊 Grade Management</a>
                <a href="/teacher/dashboard" class="btn btn-secondary">🏠 Dashboard</a>
                <a href="/auth/change-password" class="btn btn-secondary">🔒 Change Password</a>
            </div>
        </div>
    </div>
</body>
</html>
