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
        
        .subject-type.elective {
            background: #d1ecf1;
            color: #0c5460;
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
            gap: 15px;
        }

        .grade-section h4 {
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #dee2e6;
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
            
            <!-- Subjects Section -->
            <div class="profile-section full-width">
                <h3>📚 Subjects & Grades</h3>
                <?php if (!empty($student['subjects'])): ?>
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
                        <div class="grade-section">
                            <h4>Grade <?= $gradeLevel ?></h4>
                            <div class="quarters-grid">
                                <?php for ($q = 1; $q <= 4; $q++): ?>
                                    <div class="quarter-section">
                                        <h5><?= $q ?>st Quarter</h5>
                                        <?php if (isset($quarters[$q])): ?>
                                            <div class="subjects-list">
                                                <?php foreach ($quarters[$q] as $subject): ?>
                                                    <div class="subject-item">
                                                        <div class="subject-header">
                                                            <span class="subject-code"><?= esc($subject['code']) ?></span>
                                                            <span class="subject-type <?= $subject['is_core'] ? 'core' : 'elective' ?>">
                                                                <?= $subject['is_core'] ? 'Core' : 'Elective' ?>
                                                            </span>
                                                        </div>
                                                        <div class="subject-name"><?= esc($subject['name']) ?></div>
                                                        <div class="subject-details">
                                                            <span class="subject-units"><?= esc($subject['units']) ?> unit(s)</span>
                                                            <?php if (!empty($subject['description'])): ?>
                                                                <span class="subject-description"><?= esc($subject['description']) ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="grade-input">
                                                            <label for="grade_<?= $subject['id'] ?>_<?= $q ?>">Grade:</label>
                                                            <input type="number" 
                                                                   id="grade_<?= $subject['id'] ?>_<?= $q ?>" 
                                                                   name="grade_<?= $subject['id'] ?>_<?= $q ?>" 
                                                                   min="75" 
                                                                   max="100" 
                                                                   placeholder="75-100"
                                                                   class="grade-field"
                                                                   data-subject-id="<?= $subject['id'] ?>"
                                                                   data-quarter="<?= $q ?>"
                                                                   data-student-id="<?= $student['id'] ?>">
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-success save-grade-btn"
                                                                    data-subject-id="<?= $subject['id'] ?>"
                                                                    data-quarter="<?= $q ?>"
                                                                    data-student-id="<?= $student['id'] ?>">
                                                                💾 Save
                                                            </button>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="no-subjects">No subjects for this quarter</div>
                                        <?php endif; ?>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-data">No subjects assigned to this curriculum yet.</div>
                <?php endif; ?>
            </div>
            
            <!-- Account Information -->
            <div class="profile-section">
                <h3>🔐 Account Information</h3>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value">
                        <span class="status-badge status-<?= strtolower($student['status']) ?>">
                            <?= esc(ucfirst($student['status'])) ?>
                        </span>
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
