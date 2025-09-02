<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile - Admin Dashboard</title>
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
        
        .btn-warning {
            background: #ffc107;
            color: #212529;
        }
        
        .btn-warning:hover {
            background: #e0a800;
        }
        
        .btn-secondary {
            background: #6c757d;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .profile-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .profile-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }
        
        .profile-section h3 {
            color: #495057;
            margin: 0 0 15px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #dee2e6;
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
        
        .full-width {
            grid-column: 1 / -1;
        }
        
        .actions-section {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #bbdefb;
        }
        
        .actions-section h3 {
            color: #1976d2;
            margin: 0 0 15px 0;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .no-data {
            color: #6c757d;
            font-style: italic;
        }
        
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
        
        .subjects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        
        .subject-item {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .subject-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .subject-code {
            background: #667eea;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .subject-type {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
        }
        
        .subject-type.core {
            background: #fff3cd;
            color: #856404;
        }
        
        .subject-type.specialized {
            background: #d1ecf1;
            color: #0c5460;
        }

        .subject-type.applied {
            background: #d4edda;
            color: #155724;
        }
        
        .subject-name {
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }
        
        .subject-details {
            font-size: 12px;
            color: #666;
        }
        
        .subject-units {
            display: block;
            margin-bottom: 5px;
        }
        
        .subject-description {
            display: block;
            font-style: italic;
        }

        /* New styles for grade input */
        .grade-input {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }

        .grade-field {
            width: 80px;
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            text-align: center;
        }

        .save-grade-btn {
            padding: 8px 12px;
            font-size: 12px;
        }

        .quarters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); /* Adjust as needed */
            gap: 20px;
        }

        .quarter-section h5 {
            color: #495057;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e9ecef;
        }

        .subjects-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }

                 /* SHS Table Styles */
         .shs-table-container {
             overflow-x: auto;
             margin-top: 15px;
         }

         .shs-subjects-table {
             width: 100%;
             border-collapse: collapse;
             background: white;
             border-radius: 8px;
             overflow: hidden;
             box-shadow: 0 2px 8px rgba(0,0,0,0.1);
         }

         .shs-subjects-table thead {
             background: #667eea;
             color: white;
         }

         .shs-subjects-table th {
             padding: 12px 8px;
             text-align: left;
             font-weight: bold;
             font-size: 14px;
             border-bottom: 2px solid #5a6fd8;
         }

         .shs-subjects-table td {
             padding: 12px 8px;
             border-bottom: 1px solid #e9ecef;
             vertical-align: middle;
         }

         .shs-subjects-table tbody tr:hover {
             background: #f8f9fa;
         }

         .shs-subjects-table tbody tr:nth-child(even) {
             background: #f8f9fa;
         }

         .shs-subjects-table .subject-code {
             background: #667eea;
             color: white;
             padding: 4px 8px;
             border-radius: 4px;
             font-size: 12px;
             font-weight: bold;
             display: inline-block;
         }

         .shs-subjects-table .subject-name {
             font-weight: bold;
             color: #333;
             margin-bottom: 4px;
         }

         .shs-subjects-table .subject-description {
             font-size: 12px;
             color: #666;
             font-style: italic;
         }

         .shs-subjects-table .subject-type {
             padding: 4px 8px;
             border-radius: 12px;
             font-size: 11px;
             font-weight: bold;
             display: inline-block;
         }

         .shs-subjects-table .subject-type.core {
             background: #fff3cd;
             color: #856404;
         }

         .shs-subjects-table .subject-type.specialized {
             background: #d1ecf1;
             color: #0c5460;
         }

         .shs-subjects-table .subject-type.applied {
             background: #d4edda;
             color: #155724;
         }

         .shs-subjects-table .grade-input {
             display: flex;
             align-items: center;
             gap: 8px;
         }

         .shs-subjects-table .grade-field {
             width: 70px;
             padding: 6px;
             border: 1px solid #ced4da;
             border-radius: 4px;
             text-align: center;
             font-size: 14px;
         }

         .shs-subjects-table .save-grade-btn {
             padding: 6px 10px;
             font-size: 11px;
             white-space: nowrap;
         }

         /* New styles for semester-based table */
         .semester-grades {
             display: flex;
             flex-direction: column;
             gap: 8px;
         }

         .quarter-grade {
             display: flex;
             align-items: center;
             gap: 8px;
         }

         .quarter-grade label {
             font-size: 12px;
             font-weight: bold;
             color: #495057;
             min-width: 25px;
         }

         .quarter-grade .grade-field {
             width: 60px;
             padding: 4px 6px;
             border: 1px solid #ced4da;
             border-radius: 4px;
             text-align: center;
             font-size: 12px;
         }

         .save-buttons {
             display: flex;
             flex-direction: column;
             gap: 4px;
         }

         .save-buttons .save-grade-btn {
             padding: 4px 8px;
             font-size: 10px;
             margin: 0;
         }

         .shs-subjects-table .subject-units {
             font-size: 11px;
             color: #6c757d;
             margin-top: 4px;
         }

         /* JHS Table Styles */
         .jhs-table-container {
             overflow-x: auto;
             margin-top: 15px;
         }

         .jhs-subjects-table {
             width: 100%;
             border-collapse: collapse;
             background: white;
             border-radius: 8px;
             overflow: hidden;
             box-shadow: 0 2px 8px rgba(0,0,0,0.1);
         }

         .jhs-subjects-table thead {
             background: #28a745;
             color: white;
         }

         .jhs-subjects-table th {
             padding: 12px 8px;
             text-align: left;
             font-weight: bold;
             font-size: 14px;
             border-bottom: 2px solid #1e7e34;
         }

         .jhs-subjects-table td {
             padding: 12px 8px;
             border-bottom: 1px solid #e9ecef;
             vertical-align: middle;
         }

         .jhs-subjects-table tbody tr:hover {
             background: #f8f9fa;
         }

         .jhs-subjects-table tbody tr:nth-child(even) {
             background: #f8f9fa;
         }

         .jhs-subjects-table .subject-name {
             font-weight: bold;
             color: #333;
             margin-bottom: 4px;
         }

         .jhs-subjects-table .subject-description {
             font-size: 12px;
             color: #666;
             font-style: italic;
         }

         .jhs-subjects-table .subject-type {
             padding: 4px 8px;
             border-radius: 12px;
             font-size: 11px;
             font-weight: bold;
             display: inline-block;
         }

         .jhs-subjects-table .subject-type.core {
             background: #fff3cd;
             color: #856404;
         }

         .jhs-subjects-table .subject-type.specialized {
             background: #d1ecf1;
             color: #0c5460;
         }

         .jhs-subjects-table .subject-type.applied {
             background: #d4edda;
             color: #155724;
         }

         .jhs-subjects-table .grade-field {
             width: 60px;
             padding: 6px;
             border: 1px solid #ced4da;
             border-radius: 4px;
             text-align: center;
             font-size: 14px;
         }

         .jhs-subjects-table .save-grade-btn {
             padding: 4px 8px;
             font-size: 10px;
             margin: 2px;
         }

         .jhs-subjects-table .subject-units {
             font-size: 11px;
             color: #6c757d;
             margin-top: 4px;
         }

         .jhs-subjects-table .no-grade {
             color: #6c757d;
             font-style: italic;
             text-align: center;
         }

         /* Responsive table for mobile devices */
         @media (max-width: 768px) {
             .shs-table-container,
             .jhs-table-container {
                 overflow-x: auto;
             }
             
             .shs-subjects-table,
             .jhs-subjects-table {
                 min-width: 700px;
             }
             
             .shs-subjects-table th,
             .shs-subjects-table td,
             .jhs-subjects-table th,
             .jhs-subjects-table td {
                 padding: 8px 6px;
                 font-size: 12px;
             }
             
             .quarter-grade .grade-field,
             .jhs-subjects-table .grade-field {
                 width: 50px;
                 padding: 3px 4px;
                 font-size: 11px;
             }
             
             .quarter-grade label {
                 font-size: 11px;
                 min-width: 20px;
             }
             
             .save-buttons .save-grade-btn,
             .jhs-subjects-table .save-grade-btn {
                 padding: 3px 6px;
                 font-size: 9px;
             }
         }
            gap: 15px;
        }

        .grade-section h4 {
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #dee2e6;
        }

        .grade-section h4:contains("SHS") {
            border-bottom-color: #667eea;
            color: #667eea;
        }

        .grade-section h4:contains("JHS") {
            border-bottom-color: #28a745;
            color: #28a745;
        }

        .shs-grade-section h4 {
            border-bottom-color: #667eea;
            color: #667eea;
        }

        .jhs-grade-section h4 {
            border-bottom-color: #28a745;
            color: #28a745;
        }

        .semester-info {
            font-size: 11px;
            color: #6c757d;
            font-style: italic;
            margin-top: 2px;
        }

        .no-subjects {
            color: #6c757d;
            font-style: italic;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👤 Student Profile</h1>
            <div>
                <?php if (($student['status'] ?? '') !== 'approved'): ?>
                    <a href="/admin/students/approve/<?= $student['id'] ?>" class="btn" style="background:#28a745; margin-right:10px;" onclick="return confirm('Approve this student?');">✅ Approve</a>
                <?php endif; ?>
                <a href="/admin/students/edit/<?= $student['id'] ?>" class="btn btn-warning">✏️ Edit Student</a>
                <a href="/admin/students" class="btn btn-secondary">← Back to Students</a>
            </div>
        </div>
        
        <!-- LRN Display -->
        <div class="lrn-display">
            LRN: <?= esc($student['lrn']) ?>
        </div>
        
        <!-- Full Name Display -->
        <div class="full-name-display">
            <?= esc($student['full_name']) ?>
        </div>
        
        <div class="profile-grid">
            <!-- Personal Information -->
            <div class="profile-section">
                <h3>📋 Personal Information</h3>
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
            
            <!-- Academic Information -->
            <div class="profile-section">
                <h3>🎓 Academic Information</h3>
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
            
            <!-- School Information -->
            <div class="profile-section">
                <h3>🏫 School Information</h3>
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
            
            <!-- Documents Section -->
            <div class="profile-section full-width">
                <h3>🗂️ Submitted Documents</h3>
                <?php if (!empty($documents)): ?>
                    <table style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr>
                                <th style="text-align:left; padding:8px; border-bottom:1px solid #eee;">Type</th>
                                <th style="text-align:left; padding:8px; border-bottom:1px solid #eee;">Status</th>
                                <th style="text-align:left; padding:8px; border-bottom:1px solid #eee;">Uploaded At</th>
                                <th style="text-align:left; padding:8px; border-bottom:1px solid #eee;">File</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($documents as $doc): ?>
                                <tr>
                                    <td style="padding:8px; border-bottom:1px solid #f1f1f1;"><?= esc($doc['document_type']) ?></td>
                                    <td style="padding:8px; border-bottom:1px solid #f1f1f1;">
                                        <span class="status-badge status-<?= esc(strtolower($doc['status'])) ?>"><?= esc(ucfirst($doc['status'])) ?></span>
                                    </td>
                                    <td style="padding:8px; border-bottom:1px solid #f1f1f1;"><?= esc($doc['uploaded_at']) ?></td>
                                    <td style="padding:8px; border-bottom:1px solid #f1f1f1;">
                                        <?php 
                                            $fileExt = strtolower(pathinfo($doc['file_path'], PATHINFO_EXTENSION));
                                            $streamUrl = base_url('/registrar/document/view/' . $doc['id']);
                                        ?>
                                        <?php if (in_array($fileExt, ['jpg','jpeg','png','gif'])): ?>
                                            <a href="<?= $streamUrl ?>" target="_blank">View Image</a>
                                        <?php else: ?>
                                            <a href="<?= $streamUrl ?>" target="_blank">View Document</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-data">No documents uploaded.</div>
                <?php endif; ?>
            </div>

            <!-- Subjects Section -->
            <div class="profile-section full-width">
                <h3>📚 Subjects & Grades</h3>
                
                <?php if (!empty($student['subjects'])): ?>
                    <?php
                    // Calculate summary information
                    $totalSubjects = count($student['subjects']);
                    $gradeLevels = array_unique(array_column($student['subjects'], 'grade_level'));
                    sort($gradeLevels);
                    $isSHS = in_array(11, $gradeLevels) || in_array(12, $gradeLevels);
                    $isJHS = in_array(7, $gradeLevels) || in_array(8, $gradeLevels) || in_array(9, $gradeLevels) || in_array(10, $gradeLevels);
                    ?>
                    
                    <!-- Summary Information -->
                    <div class="subjects-summary" style="background: #e8f5e8; padding: 15px; border-radius: 6px; border: 1px solid #c3e6c3; margin-bottom: 20px;">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                            <div>
                                <strong>📊 Total Subjects:</strong> <?= $totalSubjects ?>
                            </div>
                            <div>
                                <strong>🎯 Grade Levels:</strong> 
                                <?php if ($isSHS && $isJHS): ?>
                                    JHS (<?= implode(', ', array_filter($gradeLevels, function($g) { return $g <= 10; })) ?>) + SHS (<?= implode(', ', array_filter($gradeLevels, function($g) { return $g >= 11; })) ?>)
                                <?php elseif ($isSHS): ?>
                                    SHS (<?= implode(', ', $gradeLevels) ?>)
                                <?php elseif ($isJHS): ?>
                                    JHS (<?= implode(', ', $gradeLevels) ?>)
                                <?php else: ?>
                                    <?= implode(', ', $gradeLevels) ?>
                                <?php endif; ?>
                            </div>
                            <div>
                                <strong>📅 Academic Structure:</strong>
                                <?php if ($isSHS): ?>
                                    Semester-based (Q1+Q2 = 1st Sem, Q3+Q4 = 2nd Sem)
                                <?php else: ?>
                                    Quarter-based (4 quarters per year)
                                <?php endif; ?>
                            </div>
                            <?php if ($isSHS): ?>
                                <?php
                                // Calculate SHS-specific statistics
                                $shsSubjects = array_filter($student['subjects'], function($s) { return $s['grade_level'] >= 11; });
                                $coreSubjects = array_filter($shsSubjects, function($s) { return $s['is_core'] === 'core'; });
                                $specializedSubjects = array_filter($shsSubjects, function($s) { return $s['is_core'] === 'specialized'; });
                                $appliedSubjects = array_filter($shsSubjects, function($s) { return $s['is_core'] === 'applied'; });
                                ?>
                                <div>
                                    <strong>📚 Subject Categories:</strong><br>
                                    Core: <?= count($coreSubjects) ?> | 
                                    Specialized: <?= count($specializedSubjects) ?> | 
                                    Applied: <?= count($appliedSubjects) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php
                    // Organize subjects by grade level and quarter
                    $organizedSubjects = [];
                    foreach ($student['subjects'] as $subject) {
                        $grade = $subject['grade_level'];
                        $quarter = $subject['quarter'];
                        $organizedSubjects[$grade][$quarter][] = $subject;
                    }
                    
                    // Sort by grade level and quarter
                    ksort($organizedSubjects);
                    ?>
                    
                    <?php foreach ($organizedSubjects as $gradeLevel => $quarters): ?>
                        <div class="grade-section <?= $gradeLevel >= 11 ? 'shs-grade-section' : 'jhs-grade-section' ?>">
                            <h4>
                                <?php if ($gradeLevel >= 11): ?>
                                    🎓 Grade <?= $gradeLevel ?> (SHS)
                                <?php else: ?>
                                    📖 Grade <?= $gradeLevel ?> (JHS)
                                <?php endif; ?>
                            </h4>
                            
                                                         <?php if ($gradeLevel >= 11): ?>
                                 <!-- SHS Table Format -->
                                 <div class="shs-table-container">
                                     <table class="shs-subjects-table">
                                         <thead>
                                             <tr>
                                                 <th>Type</th>
                                                 <th>Subject Name</th>
                                                 <th>1st Sem Grade</th>
                                                 <th>2nd Sem Grade</th>
                                                 <th>Action</th>
                                             </tr>
                                         </thead>
                                         <tbody>
                                             <?php 
                                             // Group subjects by name to combine quarters into semesters
                                             $groupedSubjects = [];
                                             foreach ($quarters as $quarter => $quarterSubjects) {
                                                 foreach ($quarterSubjects as $subject) {
                                                     $subjectKey = $subject['name'] . '_' . $subject['is_core'];
                                                     if (!isset($groupedSubjects[$subjectKey])) {
                                                         $groupedSubjects[$subjectKey] = [
                                                             'name' => $subject['name'],
                                                             'type' => $subject['is_core'],
                                                             'description' => $subject['description'],
                                                             'units' => $subject['units'],
                                                             'quarters' => []
                                                         ];
                                                     }
                                                     $groupedSubjects[$subjectKey]['quarters'][$quarter] = $subject;
                                                 }
                                             }
                                             ?>
                                             
                                             <?php foreach ($groupedSubjects as $subjectKey => $subjectData): ?>
                                                 <tr>
                                                     <td>
                                                         <span class="subject-type <?= $subjectData['type'] === 'core' ? 'core' : (($subjectData['type'] === 'specialized' ? 'specialized' : 'applied')) ?>">
                                                             <?= ucfirst($subjectData['type']) ?>
                                                         </span>
                                                     </td>
                                                     <td>
                                                         <div class="subject-name"><?= esc($subjectData['name']) ?></div>
                                                         <?php if (!empty($subjectData['description'])): ?>
                                                             <div class="subject-description"><?= esc($subjectData['description']) ?></div>
                                                         <?php endif; ?>
                                                         <div class="subject-units"><?= esc($subjectData['units']) ?> unit(s)</div>
                                                     </td>
                                                     <td>
                                                         <!-- 1st Semester Grades (Q1 & Q2) -->
                                                         <div class="semester-grades">
                                                             <?php if (isset($subjectData['quarters'][1])): ?>
                                                                 <div class="quarter-grade">
                                                                     <label>Q1:</label>
                                                                     <input type="number" 
                                                                            id="grade_<?= $subjectData['quarters'][1]['id'] ?>_1" 
                                                                            name="grade_<?= $subjectData['quarters'][1]['id'] ?>_1" 
                                                                            min="75" 
                                                                            max="100" 
                                                                            placeholder="75-100"
                                                                            class="grade-field"
                                                                            data-subject-id="<?= $subjectData['quarters'][1]['id'] ?>"
                                                                            data-quarter="1"
                                                                            data-student-id="<?= $student['id'] ?>">
                                                                 </div>
                                                             <?php endif; ?>
                                                             <?php if (isset($subjectData['quarters'][2])): ?>
                                                                 <div class="quarter-grade">
                                                                     <label>Q2:</label>
                                                                     <input type="number" 
                                                                            id="grade_<?= $subjectData['quarters'][2]['id'] ?>_2" 
                                                                            name="grade_<?= $subjectData['quarters'][2]['id'] ?>_2" 
                                                                            min="75" 
                                                                            max="100" 
                                                                            placeholder="75-100"
                                                                            class="grade-field"
                                                                            data-subject-id="<?= $subjectData['quarters'][2]['id'] ?>"
                                                                            data-quarter="2"
                                                                            data-student-id="<?= $student['id'] ?>">
                                                                 </div>
                                                             <?php endif; ?>
                                                         </div>
                                                     </td>
                                                     <td>
                                                         <!-- 2nd Semester Grades (Q3 & Q4) -->
                                                         <div class="semester-grades">
                                                             <?php if (isset($subjectData['quarters'][3])): ?>
                                                                 <div class="quarter-grade">
                                                                     <label>Q3:</label>
                                                                     <input type="number" 
                                                                            id="grade_<?= $subjectData['quarters'][3]['id'] ?>_3" 
                                                                            name="grade_<?= $subjectData['quarters'][3]['id'] ?>_3" 
                                                                            min="75" 
                                                                            max="100" 
                                                                            placeholder="75-100"
                                                                            class="grade-field"
                                                                            data-subject-id="<?= $subjectData['quarters'][3]['id'] ?>"
                                                                            data-quarter="3"
                                                                            data-student-id="<?= $student['id'] ?>">
                                                                 </div>
                                                             <?php endif; ?>
                                                             <?php if (isset($subjectData['quarters'][4])): ?>
                                                                 <div class="quarter-grade">
                                                                     <label>Q4:</label>
                                                                     <input type="number" 
                                                                            id="grade_<?= $subjectData['quarters'][4]['id'] ?>_4" 
                                                                            name="grade_<?= $subjectData['quarters'][4]['id'] ?>_4" 
                                                                            min="75" 
                                                                            max="100" 
                                                                            placeholder="75-100"
                                                                            class="grade-field"
                                                                            data-subject-id="<?= $subjectData['quarters'][4]['id'] ?>"
                                                                            data-quarter="4"
                                                                            data-student-id="<?= $student['id'] ?>">
                                                                 </div>
                                                             <?php endif; ?>
                                                         </div>
                                                     </td>
                                                     <td>
                                                         <!-- Save buttons for each quarter -->
                                                         <div class="save-buttons">
                                                             <?php foreach ([1, 2, 3, 4] as $quarter): ?>
                                                                 <?php if (isset($subjectData['quarters'][$quarter])): ?>
                                                                     <button type="button" 
                                                                             class="btn btn-sm btn-success save-grade-btn"
                                                                             data-subject-id="<?= $subjectData['quarters'][$quarter]['id'] ?>"
                                                                             data-quarter="<?= $quarter ?>"
                                                                             data-student-id="<?= $student['id'] ?>">
                                                                         Q<?= $quarter ?>
                                                                     </button>
                                                                 <?php endif; ?>
                                                             <?php endforeach; ?>
                                                         </div>
                                                     </td>
                                                 </tr>
                                             <?php endforeach; ?>
                                         </tbody>
                                     </table>
                                 </div>
                                                         <?php else: ?>
                                 <!-- JHS Table Format -->
                                 <div class="jhs-table-container">
                                     <table class="jhs-subjects-table">
                                         <thead>
                                             <tr>
                                                 <th>Type</th>
                                                 <th>Subject Name</th>
                                                 <th>1st Quarter</th>
                                                 <th>2nd Quarter</th>
                                                 <th>3rd Quarter</th>
                                                 <th>4th Quarter</th>
                                                 <th>Action</th>
                                             </tr>
                                         </thead>
                                         <tbody>
                                             <?php 
                                             // Group subjects by name to combine quarters
                                             $groupedSubjects = [];
                                             foreach ($quarters as $quarter => $quarterSubjects) {
                                                 foreach ($quarterSubjects as $subject) {
                                                     $subjectKey = $subject['name'] . '_' . $subject['is_core'];
                                                     if (!isset($groupedSubjects[$subjectKey])) {
                                                         $groupedSubjects[$subjectKey] = [
                                                             'name' => $subject['name'],
                                                             'type' => $subject['is_core'],
                                                             'description' => $subject['description'],
                                                             'units' => $subject['units'],
                                                             'quarters' => []
                                                         ];
                                                     }
                                                     $groupedSubjects[$subjectKey]['quarters'][$quarter] = $subject;
                                                 }
                                             }
                                             ?>
                                             
                                             <?php foreach ($groupedSubjects as $subjectKey => $subjectData): ?>
                                                 <tr>
                                                     <td>
                                                         <span class="subject-type <?= $subjectData['type'] === 'core' ? 'core' : (($subjectData['type'] === 'specialized' ? 'specialized' : 'applied')) ?>">
                                                             <?= ucfirst($subjectData['type']) ?>
                                                         </span>
                                                     </td>
                                                     <td>
                                                         <div class="subject-name"><?= esc($subjectData['name']) ?></div>
                                                         <?php if (!empty($subjectData['description'])): ?>
                                                             <div class="subject-description"><?= esc($subjectData['description']) ?></div>
                                                         <?php endif; ?>
                                                         <div class="subject-units"><?= esc($subjectData['units']) ?> unit(s)</div>
                                                     </td>
                                                     <td>
                                                         <?php if (isset($subjectData['quarters'][1])): ?>
                                                             <input type="number" 
                                                                    id="grade_<?= $subjectData['quarters'][1]['id'] ?>_1" 
                                                                    name="grade_<?= $subjectData['quarters'][1]['id'] ?>_1" 
                                                                    min="75" 
                                                                    max="100" 
                                                                    placeholder="75-100"
                                                                    class="grade-field"
                                                                    data-subject-id="<?= $subjectData['quarters'][1]['id'] ?>"
                                                                    data-quarter="1"
                                                                    data-student-id="<?= $student['id'] ?>">
                                                         <?php else: ?>
                                                             <span class="no-grade">-</span>
                                                         <?php endif; ?>
                                                     </td>
                                                     <td>
                                                         <?php if (isset($subjectData['quarters'][2])): ?>
                                                             <input type="number" 
                                                                    id="grade_<?= $subjectData['quarters'][2]['id'] ?>_2" 
                                                                    name="grade_<?= $subjectData['quarters'][2]['id'] ?>_2" 
                                                                    min="75" 
                                                                    max="100" 
                                                                    placeholder="75-100"
                                                                    class="grade-field"
                                                                    data-subject-id="<?= $subjectData['quarters'][2]['id'] ?>"
                                                                    data-quarter="2"
                                                                    data-student-id="<?= $student['id'] ?>">
                                                         <?php else: ?>
                                                             <span class="no-grade">-</span>
                                                         <?php endif; ?>
                                                     </td>
                                                     <td>
                                                         <?php if (isset($subjectData['quarters'][3])): ?>
                                                             <input type="number" 
                                                                    id="grade_<?= $subjectData['quarters'][3]['id'] ?>_3" 
                                                                    name="grade_<?= $subjectData['quarters'][3]['id'] ?>_3" 
                                                                    min="75" 
                                                                    max="100" 
                                                                    placeholder="75-100"
                                                                    class="grade-field"
                                                                    data-subject-id="<?= $subjectData['quarters'][3]['id'] ?>"
                                                                    data-quarter="3"
                                                                    data-student-id="<?= $student['id'] ?>">
                                                         <?php else: ?>
                                                             <span class="no-grade">-</span>
                                                         <?php endif; ?>
                                                     </td>
                                                     <td>
                                                         <?php if (isset($subjectData['quarters'][4])): ?>
                                                             <input type="number" 
                                                                    id="grade_<?= $subjectData['quarters'][4]['id'] ?>_4" 
                                                                    name="grade_<?= $subjectData['quarters'][4]['id'] ?>_4" 
                                                                    min="75" 
                                                                    max="100" 
                                                                    placeholder="75-100"
                                                                    class="grade-field"
                                                                    data-subject-id="<?= $subjectData['quarters'][4]['id'] ?>"
                                                                    data-quarter="4"
                                                                    data-student-id="<?= $student['id'] ?>">
                                                         <?php else: ?>
                                                             <span class="no-grade">-</span>
                                                         <?php endif; ?>
                                                     </td>
                                                     <td>
                                                         <!-- Save buttons for each quarter -->
                                                         <div class="save-buttons">
                                                             <?php foreach ([1, 2, 3, 4] as $quarter): ?>
                                                                 <?php if (isset($subjectData['quarters'][$quarter])): ?>
                                                                     <button type="button" 
                                                                             class="btn btn-sm btn-success save-grade-btn"
                                                                             data-subject-id="<?= $subjectData['quarters'][$quarter]['id'] ?>"
                                                                             data-quarter="<?= $quarter ?>"
                                                                             data-student-id="<?= $student['id'] ?>">
                                                                         Q<?= $quarter ?>
                                                                     </button>
                                                                 <?php endif; ?>
                                                             <?php endforeach; ?>
                                                         </div>
                                                     </td>
                                                 </tr>
                                             <?php endforeach; ?>
                                         </tbody>
                                     </table>
                                 </div>
                             <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-data">No subjects assigned to this curriculum/strand yet.</div>
                <?php endif; ?>
            </div>
            
            <!-- Account Information -->
            <div class="profile-section">
                <h3>🔐 Account Information</h3>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value">
                        <span class="status-badge status-<?= strtolower($student['status']) ?>">
                            <?= esc(ucfirst($student['status'] === 'draft' ? 'pending' : $student['status'])) ?>
                        </span>
                        <?php if (($student['status'] ?? '') === 'approved' && !empty($student['approved_by'])): ?>
                            <span style="margin-left:8px; color:#6c757d;">Approved by: <?= esc($approvedByName ?? $student['approved_by']) ?></span>
                        <?php endif; ?>
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
        
        <!-- Actions Section -->
        <div class="actions-section full-width">
            <h3>⚡ Quick Actions</h3>
            <div class="action-buttons">
                <a href="/admin/students/edit/<?= $student['id'] ?>" class="btn btn-warning">✏️ Edit Student</a>
                <a href="/admin/students/delete/<?= $student['id'] ?>" class="btn" style="background: #dc3545;" onclick="return confirm('Are you sure you want to delete this student? This action cannot be undone.')">🗑️ Delete Student</a>
                <a href="/admin/students" class="btn btn-secondary">← Back to Student List</a>
                <a href="/admin/dashboard" class="btn btn-secondary">🏠 Back to Dashboard</a>
            </div>
        </div>
    </div>
    
    <script>
        // Handle grade saving
        document.addEventListener('DOMContentLoaded', function() {
            // Add event listeners to all save grade buttons
            document.querySelectorAll('.save-grade-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const subjectId = this.getAttribute('data-subject-id');
                    const quarter = this.getAttribute('data-quarter');
                    const studentId = this.getAttribute('data-student-id');
                    const gradeInput = document.getElementById(`grade_${subjectId}_${quarter}`);
                    const grade = gradeInput.value;
                    
                    if (!grade || grade < 75 || grade > 100) {
                        alert('Please enter a valid grade between 75 and 100');
                        return;
                    }
                    
                    // Save the grade
                    saveGrade(studentId, subjectId, quarter, grade, this);
                });
            });
        });
        
        function saveGrade(studentId, subjectId, quarter, grade, button) {
            // Disable button and show loading state
            button.disabled = true;
            button.textContent = '💾 Saving...';
            
            // Make AJAX request to save grade
            fetch('/admin/students/save-grade', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    student_id: studentId,
                    subject_id: subjectId,
                    quarter: quarter,
                    grade: grade
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    button.textContent = '✅ Saved!';
                    button.style.background = '#28a745';
                    
                    // Reset button after 2 seconds
                    setTimeout(() => {
                        button.textContent = '💾 Save';
                        button.style.background = '#28a745';
                        button.disabled = false;
                    }, 2000);
                } else {
                    // Show error message
                    alert('Error saving grade: ' + (data.message || 'Unknown error'));
                    button.textContent = '💾 Save';
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving grade. Please try again.');
                button.textContent = '💾 Save';
                button.disabled = false;
            });
        }
    </script>
</body>
</html>
