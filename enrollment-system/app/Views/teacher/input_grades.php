<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Grades - <?= esc($section['name']) ?> - Teacher Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f6fb;
        }
        
        .container {
            max-width: 1000px;
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
        
        .subject-info {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #bbdefb;
            margin-bottom: 30px;
        }
        
        .subject-info h2 {
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
        
        .grades-form {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }
        
        .grades-form h3 {
            margin-top: 0;
            color: #495057;
        }
        
        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .grades-table th,
        .grades-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .grades-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        
        .grades-table tr:hover {
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
        
        .grade-input {
            width: 80px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
            font-size: 14px;
        }
        
        .grade-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2);
        }
        
        .grade-status {
            font-size: 12px;
            padding: 2px 6px;
            border-radius: 12px;
            font-weight: bold;
        }
        
        .grade-status.new {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .grade-status.existing {
            background: #d4edda;
            color: #155724;
        }
        
        .form-actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .form-actions .btn {
            margin: 0;
        }
        
        .instructions {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .instructions h4 {
            margin-top: 0;
            color: #856404;
        }
        
        .instructions ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        
        .instructions li {
            margin-bottom: 5px;
            color: #856404;
        }
        
        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📝 Input Grades - <?= esc($section['name']) ?></h1>
            <div>
                <a href="/teacher/students/<?= $section['id'] ?>" class="btn btn-secondary">← Back to Students</a>
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
        
        <!-- Subject Information -->
        <div class="subject-info">
            <h2>📚 Grade Input Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Subject</div>
                    <div class="info-value"><?= esc($subjectInfo['subject_name']) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Subject Code</div>
                    <div class="info-value"><?= esc($subjectInfo['subject_code']) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Section</div>
                    <div class="info-value"><?= esc($section['name']) ?> - Grade <?= $section['grade_level'] ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">School Year</div>
                    <div class="info-value"><?= esc($activeSchoolYear['name']) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Total Students</div>
                    <div class="info-value"><?= count($students) ?> enrolled</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Grades Status</div>
                    <div class="info-value">
                        <?= count($gradesLookup) ?> graded, <?= count($students) - count($gradesLookup) ?> pending
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Instructions -->
        <div class="instructions">
            <h4>📋 Grade Input Instructions</h4>
            <ul>
                <li>Enter grades on a scale of 0-100</li>
                <li>Leave blank to skip a student</li>
                <li>Existing grades will be updated</li>
                <li>New grades will be added</li>
                <li>Click "Save All Grades" to submit</li>
            </ul>
        </div>
        
        <!-- Grades Form -->
        <div class="grades-form">
            <h3>📊 Student Grades</h3>
            
            <form method="POST" action="/teacher/save-grades">
                <input type="hidden" name="subject_id" value="<?= $subjectInfo['subject_id'] ?>">
                <input type="hidden" name="school_year_id" value="<?= $activeSchoolYear['id'] ?>">
                <input type="hidden" name="section_id" value="<?= $section['id'] ?>">
                
                <table class="grades-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>LRN</th>
                            <th>Current Grade</th>
                            <th>New Grade</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <?php $currentGrade = $gradesLookup[$student['id']] ?? null; ?>
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
                                <td>
                                    <?php if ($currentGrade !== null): ?>
                                        <strong style="color: #28a745;"><?= $currentGrade ?></strong>
                                    <?php else: ?>
                                        <span style="color: #6c757d;">No grade</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <input type="number" 
                                           name="grades[<?= $student['id'] ?>]" 
                                           class="grade-input" 
                                           min="0" 
                                           max="100" 
                                           step="0.01"
                                           value="<?= $currentGrade ?? '' ?>"
                                           placeholder="0-100">
                                </td>
                                <td>
                                    <?php if ($currentGrade !== null): ?>
                                        <span class="grade-status existing">Existing</span>
                                    <?php else: ?>
                                        <span class="grade-status new">New</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-success">💾 Save All Grades</button>
                    <a href="/teacher/students/<?= $section['id'] ?>" class="btn btn-secondary">← Back to Students</a>
                    <a href="/teacher/dashboard" class="btn btn-secondary">🏠 Dashboard</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
