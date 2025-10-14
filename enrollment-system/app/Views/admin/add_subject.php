<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Subject - Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f6fb;
        }
        
        .container {
            width: 100%;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
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
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #495057;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin: 0;
        }
        
        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
        
        .note {
            background: #e3f2fd;
            border: 1px solid #bbdefb;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .note h4 {
            margin: 0 0 15px 0;
            color: #1976d2;
        }
        
        .note ol {
            margin: 0;
            padding-left: 20px;
        }
        
        .note li {
            margin-bottom: 8px;
            color: #1976d2;
        }
        
        .required {
            color: #dc3545;
        }
        
        .help-text {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }
    </style>
    <?php include __DIR__ . '/partials/sidebar_styles.php'; ?>
</head>
<body>
    <div class="container">
        <?php include __DIR__ . '/partials/layout_start.php'; ?>
        <div class="header-actions">
            <h1>📚 Add New Subject</h1>
            <a href="/admin/subjects" class="btn btn-secondary">← Back to Subjects</a>
        </div>
        
        <div class="note">
            <h4>📋 How to add a subject:</h4>
            <ol>
                <li><strong>JHS Subjects:</strong> Select curriculum and enter subject name. System automatically creates subjects for all grade levels (7-10) and quarters (1-4).</li>
                <li><strong>SHS Subjects:</strong> Select strand, grade level, semester, and subject category (Core/Applied/Specialized). System automatically creates subjects for both quarters in the selected semester.</li>
                <li>Provide the full subject name and optional description</li>
                <li>Set the subject status (active/inactive)</li>
            </ol>
        </div>
        
        <form action="/admin/subjects" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="subject_type">Subject Type <span class="required">*</span></label>
                    <select id="subject_type" name="subject_type" required onchange="toggleSubjectFields()">
                        <option value="">Select Subject Type</option>
                        <option value="jhs">JHS (Junior High School)</option>
                        <option value="shs">SHS (Senior High School)</option>
                    </select>
                    <div class="help-text">Choose whether this is a JHS or SHS subject</div>
                </div>
            </div>
            
            <div class="form-row" id="jhs_fields" style="display: none;">
                <div class="form-group">
                    <label for="curriculum_id">Curriculum <span class="required">*</span></label>
                    <select id="curriculum_id" name="curriculum_id">
                        <option value="">Select Curriculum</option>
                        <?php foreach ($curriculums as $curriculum): ?>
                            <option value="<?= esc($curriculum['id']) ?>"><?= esc($curriculum['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="help-text">Choose which curriculum this subject belongs to</div>
                </div>
                
                <div class="form-group">
                    <label>JHS Subject Info</label>
                    <div style="background: #e8f5e8; padding: 15px; border-radius: 6px; border: 1px solid #c3e6c3;">
                        <p style="margin: 0; color: #155724;"><strong>ℹ️ Note:</strong> This subject will automatically be created for:</p>
                        <ul style="margin: 10px 0 0 20px; color: #155724;">
                            <li>All grade levels (7, 8, 9, 10)</li>
                            <li>All quarters (1st, 2nd, 3rd, 4th)</li>
                            <li>All subjects are core (required)</li>
                            <li>Default units: 1.0</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="form-row" id="shs_fields" style="display: none;">
                <div class="form-group">
                    <label for="strand_id">Strand <span class="required">*</span></label>
                    <select id="strand_id" name="strand_id">
                        <option value="">Select Strand</option>
                        <?php foreach ($strands as $strand): ?>
                            <option value="<?= esc($strand['id']) ?>"><?= esc($strand['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="help-text">Choose which strand this subject belongs to</div>
                </div>
                
                <div class="form-group">
                    <label for="grade_level_shs">Grade Level <span class="required">*</span></label>
                    <select id="grade_level_shs" name="grade_level_shs">
                        <option value="">Select Grade</option>
                        <option value="11">Grade 11</option>
                        <option value="12">Grade 12</option>
                    </select>
                    <div class="help-text">Grade level for this subject</div>
                </div>
            </div>
            
            <div class="form-row" id="quarter_field" style="display: none;">
                <div class="form-group">
                    <label for="semester">Semester <span class="required">*</span></label>
                    <select id="semester" name="semester" onchange="updateQuarterInfo()">
                        <option value="">Select Semester</option>
                        <option value="1">1st Semester</option>
                        <option value="2">2nd Semester</option>
                    </select>
                    <div class="help-text">Choose the semester for this SHS subject. 1st Semester = Q1+Q2, 2nd Semester = Q3+Q4</div>
                </div>
                
                <div class="form-group">
                    <label>Quarter Assignment</label>
                    <div id="quarterInfo" style="background: #e8f5e8; padding: 15px; border-radius: 6px; border: 1px solid #c3e6c3;">
                        <p style="margin: 0; color: #155724;"><strong>ℹ️ Note:</strong> Select a semester to see quarter assignments</p>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="name">Subject Name <span class="required">*</span></label>
                <input type="text" id="name" name="name" required maxlength="100" placeholder="e.g., Mathematics">
                <div class="help-text">Full name of the subject</div>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3" placeholder="Brief description of what this subject covers..."></textarea>
                <div class="help-text">Optional description of the subject content</div>
            </div>
            
            <div class="form-group" id="jhs_core_notice" style="display: none;">
                <label>JHS Subject Type</label>
                <div style="background: #e8f5e8; padding: 15px; border-radius: 6px; border: 1px solid #c3e6c3;">
                    <p style="margin: 0; color: #155724;"><strong>✅ Core Subject:</strong> All JHS subjects are automatically marked as core (required) subjects.</p>
                </div>
            </div>
            
            <div class="form-group" id="shs_subject_type" style="display: none;">
                <label for="shs_subject_category">Subject Category <span class="required">*</span></label>
                <select id="shs_subject_category" name="shs_subject_category">
                    <option value="">Select Subject Category</option>
                    <option value="core">Core Subject</option>
                    <option value="applied">Applied Subject</option>
                    <option value="specialized">Specialized Subject</option>
                </select>
                <div class="help-text">Choose the category of this SHS subject</div>
            </div>
            
            <div class="form-group">
                <label>Status</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="is_active" name="is_active" value="1" checked>
                    <label for="is_active">Active</label>
                </div>
                <div class="help-text">Active subjects are available for enrollment</div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-success">➕ Create Subject</button>
                <a href="/admin/subjects" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
        <?php include __DIR__ . '/partials/layout_end.php'; ?>
    </div>
    
    <script>
        // Auto-format subject code to uppercase
        document.getElementById('code').addEventListener('input', function(e) {
            this.value = this.value.toUpperCase();
        });
        
        // Toggle subject type fields
        function toggleSubjectFields() {
            const subjectType = document.getElementById('subject_type').value;
            const jhsFields = document.getElementById('jhs_fields');
            const shsFields = document.getElementById('shs_fields');
            const quarterField = document.getElementById('quarter_field');
            const jhsCoreNotice = document.getElementById('jhs_core_notice');
            const shsSubjectType = document.getElementById('shs_subject_type');
            
            // Hide all fields first
            jhsFields.style.display = 'none';
            shsFields.style.display = 'none';
            quarterField.style.display = 'none';
            jhsCoreNotice.style.display = 'none';
            shsSubjectType.style.display = 'none';
            
            // Show relevant fields based on selection
            if (subjectType === 'jhs') {
                jhsFields.style.display = 'grid';
                // Make JHS fields required
                document.getElementById('curriculum_id').required = true;
                // Make SHS fields not required
                document.getElementById('strand_id').required = false;
                document.getElementById('grade_level_shs').required = false;
                document.getElementById('semester').required = false;
                document.getElementById('shs_subject_category').required = false;
                jhsCoreNotice.style.display = 'block';
            } else if (subjectType === 'shs') {
                shsFields.style.display = 'grid';
                quarterField.style.display = 'grid';
                shsSubjectType.style.display = 'block';
                // Make SHS fields required
                document.getElementById('strand_id').required = true;
                document.getElementById('grade_level_shs').required = true;
                document.getElementById('semester').required = true;
                document.getElementById('shs_subject_category').required = true;
                // Make JHS fields not required
                document.getElementById('curriculum_id').required = false;
                jhsCoreNotice.style.display = 'none';
            }
        }
        
        // Update quarter information based on semester selection
        function updateQuarterInfo() {
            const semester = document.getElementById('semester').value;
            const quarterInfo = document.getElementById('quarterInfo');
            
            if (semester === '1') {
                quarterInfo.innerHTML = '<p style="margin: 0; color: #155724;"><strong>✅ 1st Semester:</strong> This subject will be automatically assigned to 1st and 2nd Quarters</p>';
            } else if (semester === '2') {
                quarterInfo.innerHTML = '<p style="margin: 0; color: #155724;"><strong>✅ 2nd Semester:</strong> This subject will be automatically assigned to 3rd and 4th Quarters</p>';
            } else {
                quarterInfo.innerHTML = '<p style="margin: 0; color: #155724;"><strong>ℹ️ Note:</strong> Select a semester to see quarter assignments</p>';
            }
        }
        
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const subjectType = document.getElementById('subject_type').value;
            const name = document.getElementById('name').value;
            
            // Check subject type specific fields
            let isValid = true;
            let errorMessage = '';
            
            if (subjectType === 'jhs') {
                const curriculum = document.getElementById('curriculum_id').value;
                
                if (!curriculum) {
                    isValid = false;
                    errorMessage = 'Please select curriculum for JHS subject.';
                }
            } else if (subjectType === 'shs') {
                const strand = document.getElementById('strand_id').value;
                const gradeLevel = document.getElementById('grade_level_shs').value;
                const semester = document.getElementById('semester').value;
                const subjectCategory = document.getElementById('shs_subject_category').value;
                
                if (!strand || !gradeLevel || !semester || !subjectCategory) {
                    isValid = false;
                    errorMessage = 'Please fill in all required fields for SHS subject.';
                }
            }
            
            if (!name) {
                isValid = false;
                errorMessage = 'Please enter the subject name.';
            }
            
            if (!isValid) {
                e.preventDefault();
                alert(errorMessage);
                return false;
            }
            
            if (name.length < 2) {
                e.preventDefault();
                alert('Subject name must be at least 2 characters long');
                return false;
            }
            
            console.log('Form validation passed, submitting...');
        });
    </script>
</body>
</html>
