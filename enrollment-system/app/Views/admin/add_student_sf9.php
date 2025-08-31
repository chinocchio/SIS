<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student via SF9 - Admin Dashboard</title>
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
        
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .form-section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background: #fafafa;
        }
        
        .form-section h3 {
            margin-top: 0;
            color: #555;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 15px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        
        input[type="text"], input[type="email"], input[type="password"], select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
        }
        
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 2px dashed #667eea;
            border-radius: 6px;
            background: #f8f9ff;
            cursor: pointer;
        }
        
        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus, select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2);
        }
        
        .btn {
            background: #667eea;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            margin-right: 10px;
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
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 6px;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        .note {
            background: #e3f2fd;
            border: 1px solid #bbdefb;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .note h4 {
            margin-top: 0;
            color: #1976d2;
        }
        
        .ocr-preview {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            display: none;
        }
        
        .ocr-preview.show {
            display: block;
        }
        
        .ocr-field {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
            align-items: center;
        }
        
        .ocr-field label {
            font-weight: bold;
            color: #495057;
        }
        
        .ocr-field input {
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        
        .password-display {
            background: #e9ecef;
            padding: 10px;
            border-radius: 6px;
            font-family: monospace;
            font-size: 14px;
            margin: 10px 0;
        }
        
        @media (max-width: 768px) {
            .form-row, .ocr-field {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎓 Add Student via SF9 (Form 137)</h1>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        
                 <div class="note">
             <h4>📋 How it works:</h4>
             <ol>
                 <li>Upload a scanned SF9 (Form 137) document</li>
                 <li>The system will automatically extract student information using OCR</li>
                 <li>Review and edit the extracted information if needed</li>
                 <li>Create the student account with LRN as username and generated password</li>
                 <li>The student can immediately login and access their dashboard</li>
             </ol>
             <p><strong>Note:</strong> Only LRN and Full Name are required. Other fields are optional and can be filled later.</p>
         </div>
        
        <form action="/admin/students/add" method="post" enctype="multipart/form-data" id="sf9Form">
            <div class="form-section">
                <h3>📄 Upload SF9 Document</h3>
                <div class="form-group">
                    <label for="sf9_file">Select SF9 (Form 137) File *</label>
                    <input type="file" name="sf9_file" id="sf9_file" accept="image/*,.pdf" required>
                    <p style="margin-top: 5px; color: #666; font-size: 14px;">
                        Supported formats: JPG, PNG, PDF, BMP, TIFF. Ensure the document is clear and readable.
                    </p>
                </div>
                <button type="submit" class="btn">🔍 Scan & Extract Information</button>
            </div>
        </form>
        
        <!-- OCR Preview Section -->
        <div class="ocr-preview" id="ocrPreview">
            <h3>🔍 Extracted Information</h3>
            <p style="color: #666; margin-bottom: 20px;">Review and edit the extracted information before creating the account:</p>
            
            <form action="/admin/students/create" method="post" id="createStudentForm">
                                                  <div class="form-row">
                     <div class="form-group">
                         <label for="grade_level">Grade Level</label>
                         <select id="grade_level" name="grade_level">
                             <option value="">Select Grade Level</option>
                             <option value="7">Grade 7 (JHS)</option>
                             <option value="8">Grade 8 (JHS)</option>
                             <option value="9">Grade 9 (JHS)</option>
                             <option value="10">Grade 10 (JHS)</option>
                             <option value="11">Grade 11 (SHS)</option>
                             <option value="12">Grade 12 (SHS)</option>
                         </select>
                         <small style="color: #666;">Note: SF9 shows current grade, student enrolls in next grade level</small>
                     </div>
                     <div class="form-group">
                         <label for="enrollment_type">Enrollment Type</label>
                         <select id="enrollment_type" name="enrollment_type">
                             <option value="">Select Type</option>
                             <option value="new">New Student</option>
                             <option value="transferee">Transferee</option>
                             <option value="returning">Returning Student</option>
                         </select>
                     </div>
                 </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="full_name">Full Name *</label>
                        <input type="text" id="full_name" name="full_name" required>
                    </div>
                    <div class="form-group">
                        <label for="lrn">LRN (Learner Reference Number) *</label>
                        <input type="text" id="lrn" name="lrn" required maxlength="12" pattern="[0-9]{12}">
                        <small style="color: #666;">12-digit number from SF9</small>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="birth_date">Date of Birth</label>
                        <input type="date" id="birth_date" name="birth_date">
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender">
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>
                
                                 <div class="form-group">
                     <label for="previous_school">Previous School (if applicable)</label>
                     <input type="text" id="previous_school" name="previous_school">
                 </div>
                
                <div class="form-group">
                    <label for="strand_id">Strand (for SHS students)</label>
                    <select id="strand_id" name="strand_id">
                        <option value="">Select Strand (if SHS)</option>
                        <?php if (isset($strands)): ?>
                            <?php foreach ($strands as $strand): ?>
                                <option value="<?= $strand['id'] ?>"><?= esc($strand['name']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="curriculum_id">Curriculum (for JHS students)</label>
                    <select id="curriculum_id" name="curriculum_id">
                        <option value="">Select Curriculum (if JHS)</option>
                        <?php if (isset($curriculums)): ?>
                            <?php foreach ($curriculums as $curriculum): ?>
                                <option value="<?= $curriculum['id'] ?>"><?= esc($curriculum['name']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="password-display">
                    <strong>Generated Password:</strong> <span id="generatedPassword"></span>
                    <button type="button" class="btn btn-secondary" onclick="generateNewPassword()" style="margin-left: 10px; padding: 5px 10px; font-size: 12px;">🔄 New Password</button>
                </div>
                
                <div style="margin-top: 20px;">
                    <button type="submit" class="btn">✅ Create Student Account</button>
                    <a href="/admin/students" class="btn btn-secondary">❌ Cancel</a>
                </div>
            </form>
        </div>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="/admin/students" style="color: #667eea; text-decoration: none;">← Back to Student Management</a>
        </div>
    </div>
    
    <script>
        // Generate initial password
        generateNewPassword();
        
        function generateNewPassword() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
            let password = '';
            for (let i = 0; i < 12; i++) {
                password += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('generatedPassword').textContent = password;
        }
        
        // Show/hide curriculum/strand based on grade level
        document.getElementById('grade_level').addEventListener('change', function() {
            const gradeLevel = parseInt(this.value);
            const curriculumSelect = document.getElementById('curriculum_id');
            const strandSelect = document.getElementById('strand_id');
            
            if (gradeLevel >= 7 && gradeLevel <= 10) {
                // JHS - show curriculum, hide strand
                curriculumSelect.parentElement.style.display = 'block';
                strandSelect.parentElement.style.display = 'none';
                curriculumSelect.required = true;
                strandSelect.required = false;
            } else if (gradeLevel >= 11) {
                // SHS - show strand, hide curriculum
                curriculumSelect.parentElement.style.display = 'none';
                strandSelect.parentElement.style.display = 'block';
                curriculumSelect.required = false;
                strandSelect.required = true;
            } else {
                // Hide both
                curriculumSelect.parentElement.style.display = 'none';
                strandSelect.parentElement.style.display = 'none';
                curriculumSelect.required = false;
                strandSelect.required = false;
            }
        });
        
        // Handle form submission for OCR processing
        document.getElementById('sf9Form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('/admin/students/add', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Server returned non-JSON response. Check server logs for details.');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Populate form fields with extracted data
                    if (data.extracted_data) {
                        document.getElementById('lrn').value = data.extracted_data.lrn || '';
                        document.getElementById('full_name').value = data.extracted_data.full_name || '';
                        document.getElementById('birth_date').value = data.extracted_data.birth_date || '';
                        document.getElementById('gender').value = data.extracted_data.gender || '';
                        document.getElementById('grade_level').value = data.extracted_data.grade_level || '';
                        document.getElementById('previous_school').value = data.extracted_data.previous_school || '';
                    }
                    
                    // Show OCR preview section
                    document.getElementById('ocrPreview').classList.add('show');
                    
                    // Show success message with grade level info
                    let gradeInfo = '';
                    if (data.debug_info && data.debug_info.grade_processing) {
                        const sf9Grade = data.debug_info.grade_processing.sf9_grade;
                        const enrollingGrade = data.debug_info.grade_processing.enrolling_grade;
                        if (sf9Grade !== 'NOT FOUND' && enrollingGrade !== 'NOT FOUND') {
                            gradeInfo = ` (SF9 shows Grade ${sf9Grade}, student will enroll in Grade ${enrollingGrade})`;
                        }
                    }
                    showAlert('Document scanned successfully! Please review the extracted information.' + gradeInfo, 'success');
                } else {
                    showAlert('Error: ' + data.error, 'error');
                }
            })
            .catch(error => {
                console.error('Full error:', error);
                showAlert('Error processing document: ' + error.message, 'error');
            });
        });
        
        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.textContent = message;
            
            const container = document.querySelector('.container');
            container.insertBefore(alertDiv, container.firstChild);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
    </script>
</body>
</html>
