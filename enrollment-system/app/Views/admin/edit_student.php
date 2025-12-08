<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - Admin Dashboard</title>
    <style>
        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fc;
            color: #5a5c69;
        }
        
        .container {
            width: 100%;
            margin: 0 auto;
        }
        
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 0 auto;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #4e73df;
            box-shadow: 0 0 0 2px rgba(78, 115, 223, 0.2);
        }
        
        .form-group input[readonly] {
            background-color: #e9ecef;
            cursor: not-allowed;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .required {
            color: #dc3545;
        }
        
        .help-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        .btn {
            background: #4e73df;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
        }
        
        .btn:hover {
            background: #2e59d9;
        }
        
        .btn-success {
            background: #1cc88a;
        }
        
        .btn-success:hover {
            background: #17a673;
        }
        
        .btn-secondary {
            background: #858796;
        }
        
        .btn-secondary:hover {
            background: #717384;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 10px;
        }
        
        .status-approved {
            background: #d4edda;
            color: #155724;
        }
        
        .status-pending {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }
        
        .status-draft {
            background: #fff3cd;
            color: #856404;
        }
    </style>
    <?php include __DIR__ . '/partials/sidebar_styles.php'; ?>
</head>
<body>
    <div class="container">
        <?php include __DIR__ . '/partials/layout_start.php'; ?>
        
        <div class="form-container">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            
            <div style="margin-bottom: 20px;">
                <h2>✏️ Editing: <?= esc($student['full_name']) ?></h2>
                <span class="status-badge status-<?= strtolower($student['status']) ?>">
                    <?= ucfirst($student['status']) ?>
                </span>
            </div>
            
            <form method="POST" action="/admin/students/edit/<?= $student['id'] ?>">
                <h3>Student Information</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="lrn">LRN <span class="required">*</span></label>
                        <input type="text" id="lrn" name="lrn" value="<?= esc($student['lrn']) ?>" required readonly>
                        <div class="help-text">Learner Reference Number (cannot be edited)</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email <span class="required">*</span></label>
                        <input type="email" id="email" name="email" value="<?= esc($student['email']) ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="full_name">Full Name <span class="required">*</span></label>
                    <input type="text" id="full_name" name="full_name" value="<?= esc($student['full_name'] ?? trim(($student['first_name'] ?? '') . ' ' . ($student['middle_name'] ?? '') . ' ' . ($student['last_name'] ?? ''))) ?>" required>
                    <div class="help-text">Enter the student's full name</div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="birth_date">Birth Date</label>
                        <input type="date" id="birth_date" name="birth_date" value="<?= esc($student['birth_date'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender">
                            <option value="">Select Gender</option>
                            <option value="Male" <?= ($student['gender'] ?? '') == 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= ($student['gender'] ?? '') == 'Female' ? 'selected' : '' ?>>Female</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="grade_level">Grade Level <span class="required">*</span></label>
                        <select id="grade_level" name="grade_level" required>
                            <option value="">Select Grade Level</option>
                            <option value="7" <?= $student['grade_level'] == '7' ? 'selected' : '' ?>>Grade 7</option>
                            <option value="8" <?= $student['grade_level'] == '8' ? 'selected' : '' ?>>Grade 8</option>
                            <option value="9" <?= $student['grade_level'] == '9' ? 'selected' : '' ?>>Grade 9</option>
                            <option value="10" <?= $student['grade_level'] == '10' ? 'selected' : '' ?>>Grade 10</option>
                            <option value="11" <?= $student['grade_level'] == '11' ? 'selected' : '' ?>>Grade 11</option>
                            <option value="12" <?= $student['grade_level'] == '12' ? 'selected' : '' ?>>Grade 12</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="previous_grade_level">Previous Grade Level</label>
                        <select id="previous_grade_level" name="previous_grade_level">
                            <option value="">Select Previous Grade</option>
                            <option value="6" <?= ($student['previous_grade_level'] ?? '') == '6' ? 'selected' : '' ?>>Grade 6</option>
                            <option value="7" <?= ($student['previous_grade_level'] ?? '') == '7' ? 'selected' : '' ?>>Grade 7</option>
                            <option value="8" <?= ($student['previous_grade_level'] ?? '') == '8' ? 'selected' : '' ?>>Grade 8</option>
                            <option value="9" <?= ($student['previous_grade_level'] ?? '') == '9' ? 'selected' : '' ?>>Grade 9</option>
                            <option value="10" <?= ($student['previous_grade_level'] ?? '') == '10' ? 'selected' : '' ?>>Grade 10</option>
                            <option value="11" <?= ($student['previous_grade_level'] ?? '') == '11' ? 'selected' : '' ?>>Grade 11</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="admission_type">Admission Type</label>
                        <select id="admission_type" name="admission_type">
                            <option value="regular" <?= ($student['admission_type'] ?? '') == 'regular' ? 'selected' : '' ?>>Regular</option>
                            <option value="transferee" <?= ($student['admission_type'] ?? '') == 'transferee' ? 'selected' : '' ?>>Transferee</option>
                            <option value="returnee" <?= ($student['admission_type'] ?? '') == 'returnee' ? 'selected' : '' ?>>Returnee</option>
                            <option value="promoted" <?= ($student['admission_type'] ?? '') == 'promoted' ? 'selected' : '' ?>>Promoted</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="enrollment_type">Enrollment Type</label>
                        <select id="enrollment_type" name="enrollment_type">
                            <option value="new" <?= ($student['enrollment_type'] ?? '') == 'new' ? 'selected' : '' ?>>New</option>
                            <option value="old" <?= ($student['enrollment_type'] ?? '') == 'old' ? 'selected' : '' ?>>Old</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="pending" <?= ($student['status'] ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="approved" <?= ($student['status'] ?? '') == 'approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="rejected" <?= ($student['status'] ?? '') == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                        <option value="draft" <?= ($student['status'] ?? '') == 'draft' ? 'selected' : '' ?>>Draft</option>
                    </select>
                </div>
                
                <div class="form-group" id="strand_group" style="display: <?= ($student['grade_level'] >= 11) ? 'block' : 'none' ?>;">
                    <label for="strand_id">Strand (SHS Only)</label>
                    <select id="strand_id" name="strand_id">
                        <option value="">Select Strand</option>
                        <?php foreach ($strands as $strand): ?>
                            <option value="<?= $strand['id'] ?>" <?= ($student['strand_id'] ?? '') == $strand['id'] ? 'selected' : '' ?>>
                                <?= esc($strand['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="curriculum_id">Curriculum</label>
                    <select id="curriculum_id" name="curriculum_id">
                        <option value="">Select Curriculum</option>
                        <?php foreach ($curriculums as $curriculum): ?>
                            <option value="<?= $curriculum['id'] ?>" <?= ($student['curriculum_id'] ?? '') == $curriculum['id'] ? 'selected' : '' ?>>
                                <?= esc($curriculum['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="previous_school">Previous School</label>
                    <input type="text" id="previous_school" name="previous_school" value="<?= esc($student['previous_school'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="previous_school_year">Previous School Year</label>
                    <input type="text" id="previous_school_year" name="previous_school_year" value="<?= esc($student['previous_school_year'] ?? '') ?>" placeholder="e.g., 2023-2024">
                </div>
                
                <div class="form-group">
                    <label for="password">New Password (leave blank to keep current)</label>
                    <input type="password" id="password" name="password">
                    <div class="help-text">Only fill if you want to change the password</div>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-success">💾 Update Student</button>
                    <a href="/admin/students" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
        
        <script>
            // Show/hide strand field based on grade level
            document.getElementById('grade_level').addEventListener('change', function() {
                const gradeLevel = this.value;
                const strandGroup = document.getElementById('strand_group');
                
                if (gradeLevel >= 11) {
                    strandGroup.style.display = 'block';
                } else {
                    strandGroup.style.display = 'none';
                    document.getElementById('strand_id').value = '';
                }
            });
        </script>
        
        <?php include __DIR__ . '/partials/layout_end.php'; ?>
    </div>
</body>
</html>