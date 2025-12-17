<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Management - Teacher Dashboard</title>
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
        
        .assignment-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-item {
            text-align: center;
            background: white;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #4e73df;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
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
        
        @media (max-width: 768px) {
            .assignments-grid {
                grid-template-columns: 1fr;
            }
            
            .assignment-stats {
                grid-template-columns: repeat(2, 1fr);
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
            <h1>📊 Grade Management</h1>
            <p>Manage grades for your assigned subjects and sections</p>
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
            
            <!-- Assignments Section -->
            <div class="assignments-section">
                <h3>📚 Your Subject Assignments</h3>
                
                <?php if (!empty($assignments)): ?>
                    <div class="assignments-grid">
                        <?php foreach ($assignments as $assignment): 
                            $base = $assignment['base_assignment'];
                            $isJHS = $assignment['is_jhs'];
                            $isSHS = $assignment['is_shs'];
                            $gradeLevel = $assignment['grade_level'];
                            $groupedSubjects = $assignment['grouped_subjects'];
                        ?>
                            <div class="assignment-card">
                                <div class="assignment-header">
                                    <h4><?= esc($base['subject_name']) ?></h4>
                                    <p><?= esc($base['section_name']) ?> - Grade <?= $base['section_grade_level'] ?>
                                        <?php if ($isJHS): ?>
                                            <span style="color: #1cc88a; font-weight: bold;">(JHS)</span>
                                        <?php elseif ($isSHS): ?>
                                            <span style="color: #4e73df; font-weight: bold;">(SHS)</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                
                                <div class="assignment-content">
                                    <div class="assignment-stats">
                                        <div class="stat-item">
                                            <div class="stat-number"><?= esc($base['subject_code']) ?></div>
                                            <div class="stat-label">Subject Code</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-number"><?= esc($base['school_year']) ?></div>
                                            <div class="stat-label">School Year</div>
                                        </div>
                                    </div>
                                    
                                    <?php if ($isJHS): ?>
                                        <!-- JHS: Display Quarters 1-4 -->
                                        <div style="margin-top: 20px;">
                                            <h5 style="margin-bottom: 15px; color: #495057; font-size: 14px; font-weight: bold;">Quarters:</h5>
                                            <div class="action-buttons">
                                                <?php for ($q = 1; $q <= 4; $q++): 
                                                    $quarterKey = 'quarter_' . $q;
                                                    if (isset($groupedSubjects[$quarterKey])) {
                                                        $quarterSubject = $groupedSubjects[$quarterKey]['subjects'][0]; // Get first subject for this quarter
                                                ?>
                                                    <a href="/teacher/grades/<?= $base['section_id'] ?>?subject_id=<?= $quarterSubject['id'] ?>" 
                                                       class="btn btn-success" 
                                                       style="flex: 1; min-width: 80px;">
                                                        📝 Q<?= $q ?>
                                                    </a>
                                                <?php } else { ?>
                                                    <div class="btn btn-secondary" style="flex: 1; min-width: 80px; opacity: 0.5; cursor: not-allowed;">
                                                        Q<?= $q ?>
                                                    </div>
                                                <?php } ?>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                    <?php elseif ($isSHS): ?>
                                        <!-- SHS: Display by Semester and Quarter -->
                                        <div style="margin-top: 20px;">
                                            <?php for ($sem = 1; $sem <= 2; $sem++): ?>
                                                <h5 style="margin: 15px 0 10px 0; color: #495057; font-size: 14px; font-weight: bold;">
                                                    Semester <?= $sem ?>:
                                                </h5>
                                                <div class="action-buttons" style="margin-bottom: 10px;">
                                                    <?php 
                                                    $quarters = $sem === 1 ? [1, 2] : [3, 4];
                                                    foreach ($quarters as $q): 
                                                        $quarterKey = 'semester_' . $sem . '_quarter_' . $q;
                                                        if (isset($groupedSubjects[$quarterKey])) {
                                                            $quarterSubject = $groupedSubjects[$quarterKey]['subjects'][0]; // Get first subject for this quarter
                                                    ?>
                                                        <a href="/teacher/grades/<?= $base['section_id'] ?>?subject_id=<?= $quarterSubject['id'] ?>" 
                                                           class="btn btn-success" 
                                                           style="flex: 1; min-width: 80px;">
                                                            📝Input grade S<?= $sem ?> Q<?= $q ?>
                                                        </a>
                                                    <?php } else { ?>
                                                        <div class="btn btn-secondary" style="flex: 1; min-width: 80px; opacity: 0.5; cursor: not-allowed;">
                                                            S<?= $sem ?> Q<?= $q ?>
                                                        </div>
                                                    <?php } ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endfor; ?>
                                        </div>
                                    <?php endif; ?>
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