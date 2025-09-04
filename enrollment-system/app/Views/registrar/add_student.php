<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student - Registrar Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f6fb;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header h1 {
            margin: 0;
            font-size: 2em;
        }
        
        .header p {
            margin: 5px 0 0 0;
            opacity: 0.9;
        }
        
        .nav {
            background: white;
            padding: 15px 20px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .nav a {
            color: #667eea;
            text-decoration: none;
            margin-right: 20px;
            padding: 8px 16px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        
        .nav a:hover {
            background-color: #f0f0f0;
        }
        
        .nav .logout {
            background-color: #dc3545;
            color: white;
        }
        
        .nav .logout:hover {
            background-color: #c82333;
        }
        
        .container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
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
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2);
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
            background: #667eea;
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
            background: #5a6fd8;
        }
        
        .btn-success {
            background: #28a745;
        }
        
        .btn-success:hover {
            background: #218838;
        }
        
        .btn-secondary {
            background: #6c757d;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
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
    </style>
</head>
<body>
    <div class="header" style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h1>➕ Add New Student</h1>
            <p>Welcome, <?= session()->get('first_name') ?> <?= session()->get('last_name') ?></p>
        </div>
        <div>
            <a href="/auth/change-password" class="nav-link" style="margin-right:10px;color:#fff;text-decoration:underline;">Change Password</a>
            <a href="/auth/logout" class="logout" style="padding:8px 12px;border-radius:6px;">Logout</a>
        </div>
    </div>
    
    <div class="nav">
        <div>
            <a href="/registrar/students">👥 Student Management</a>
            <a href="/registrar/enrollments/pending">Pending Enrollments</a>
            <a href="/registrar/enrollments/approved">Approved</a>
            <a href="/registrar/enrollments/rejected">Rejected</a>
            <a href="/registrar/search">Search Students</a>
            <a href="/registrar/report">Generate Report</a>
        </div>
    </div>
    
    <div class="container">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        
        <form method="POST" action="/registrar/students/add">
            <h2>Student Information</h2>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="lrn">LRN <span class="required">*</span></label>
                    <input type="text" id="lrn" name="lrn" value="<?= old('lrn') ?>" required>
                    <div class="help-text">Learner Reference Number</div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" id="email" name="email" value="<?= old('email') ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="first_name">First Name <span class="required">*</span></label>
                    <input type="text" id="first_name" name="first_name" value="<?= old('first_name') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="middle_name">Middle Name</label>
                    <input type="text" id="middle_name" name="middle_name" value="<?= old('middle_name') ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="last_name">Last Name <span class="required">*</span></label>
                <input type="text" id="last_name" name="last_name" value="<?= old('last_name') ?>" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="birth_date">Birth Date</label>
                    <input type="date" id="birth_date" name="birth_date" value="<?= old('birth_date') ?>">
                </div>
                
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender">
                        <option value="">Select Gender</option>
                        <option value="Male" <?= old('gender') == 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= old('gender') == 'Female' ? 'selected' : '' ?>>Female</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="grade_level">Grade Level <span class="required">*</span></label>
                    <select id="grade_level" name="grade_level" required>
                        <option value="">Select Grade Level</option>
                        <option value="7" <?= old('grade_level') == '7' ? 'selected' : '' ?>>Grade 7</option>
                        <option value="8" <?= old('grade_level') == '8' ? 'selected' : '' ?>>Grade 8</option>
                        <option value="9" <?= old('grade_level') == '9' ? 'selected' : '' ?>>Grade 9</option>
                        <option value="10" <?= old('grade_level') == '10' ? 'selected' : '' ?>>Grade 10</option>
                        <option value="11" <?= old('grade_level') == '11' ? 'selected' : '' ?>>Grade 11</option>
                        <option value="12" <?= old('grade_level') == '12' ? 'selected' : '' ?>>Grade 12</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="previous_grade_level">Previous Grade Level</label>
                    <select id="previous_grade_level" name="previous_grade_level">
                        <option value="">Select Previous Grade</option>
                        <option value="6" <?= old('previous_grade_level') == '6' ? 'selected' : '' ?>>Grade 6</option>
                        <option value="7" <?= old('previous_grade_level') == '7' ? 'selected' : '' ?>>Grade 7</option>
                        <option value="8" <?= old('previous_grade_level') == '8' ? 'selected' : '' ?>>Grade 8</option>
                        <option value="9" <?= old('previous_grade_level') == '9' ? 'selected' : '' ?>>Grade 9</option>
                        <option value="10" <?= old('previous_grade_level') == '10' ? 'selected' : '' ?>>Grade 10</option>
                        <option value="11" <?= old('previous_grade_level') == '11' ? 'selected' : '' ?>>Grade 11</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="admission_type">Admission Type</label>
                    <select id="admission_type" name="admission_type">
                        <option value="regular" <?= old('admission_type') == 'regular' ? 'selected' : '' ?>>Regular</option>
                        <option value="transferee" <?= old('admission_type') == 'transferee' ? 'selected' : '' ?>>Transferee</option>
                        <option value="returnee" <?= old('admission_type') == 'returnee' ? 'selected' : '' ?>>Returnee</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="enrollment_type">Enrollment Type</label>
                    <select id="enrollment_type" name="enrollment_type">
                        <option value="new" <?= old('enrollment_type') == 'new' ? 'selected' : '' ?>>New</option>
                        <option value="old" <?= old('enrollment_type') == 'old' ? 'selected' : '' ?>>Old</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group" id="strand_group" style="display: none;">
                <label for="strand_id">Strand (SHS Only)</label>
                <select id="strand_id" name="strand_id">
                    <option value="">Select Strand</option>
                    <?php foreach ($strands as $strand): ?>
                        <option value="<?= $strand['id'] ?>" <?= old('strand_id') == $strand['id'] ? 'selected' : '' ?>>
                            <?= $strand['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="curriculum_id">Curriculum</label>
                <select id="curriculum_id" name="curriculum_id">
                    <option value="">Select Curriculum</option>
                    <?php foreach ($curriculums as $curriculum): ?>
                        <option value="<?= $curriculum['id'] ?>" <?= old('curriculum_id') == $curriculum['id'] ? 'selected' : '' ?>>
                            <?= $curriculum['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="previous_school">Previous School</label>
                <input type="text" id="previous_school" name="previous_school" value="<?= old('previous_school') ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password <span class="required">*</span></label>
                <input type="password" id="password" name="password" required>
                <div class="help-text">Minimum 6 characters</div>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-success">➕ Create Student</button>
                <a href="/registrar/students" class="btn btn-secondary">Cancel</a>
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
        
        // Trigger on page load if there's a pre-selected value
        document.addEventListener('DOMContentLoaded', function() {
            const gradeSelect = document.getElementById('grade_level');
            if (gradeSelect.value) {
                gradeSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
</body>
</html>
