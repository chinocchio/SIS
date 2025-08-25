<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Admission Form - SIS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 300;
        }
        
        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .form-container {
            padding: 40px 30px;
        }
        
        .alert {
            padding: 15px 20px;
            margin-bottom: 25px;
            border-radius: 10px;
            font-weight: 500;
        }
        
        .alert-error {
            background-color: #fee;
            color: #c53030;
            border-left: 4px solid #c53030;
        }
        
        .alert-success {
            background-color: #f0fff4;
            color: #2f855a;
            border-left: 4px solid #2f855a;
        }
        
        .form-section {
            margin-bottom: 35px;
            padding: 25px;
            background: #f8f9fa;
            border-radius: 15px;
            border-left: 4px solid #007bff;
        }
        
        .section-title {
            font-size: 1.3rem;
            color: #2d3748;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #4a5568;
            font-size: 0.95rem;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }
        
        .form-group small {
            display: block;
            margin-top: 8px;
            color: #718096;
            font-size: 0.85rem;
            line-height: 1.4;
        }
        
        .curriculum-selection {
            background: #f0f9ff;
            border: 2px solid #0ea5e9;
            border-radius: 15px;
            padding: 25px;
            margin: 25px 0;
        }
        
        .curriculum-header {
            background: #0ea5e9;
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .curriculum-options {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .curriculum-option {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .curriculum-option:hover {
            border-color: #0ea5e9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .curriculum-option.selected {
            border-color: #0ea5e9;
            background: #f0f9ff;
        }
        
        .curriculum-option input[type="radio"] {
            position: absolute;
            opacity: 0;
        }
        
                 .curriculum-option .option-title {
             font-weight: 600;
             color: #1e293b;
             margin-bottom: 5px;
             font-size: 1.1rem;
         }
         
         .curriculum-option .option-track {
             font-weight: 500;
             color: #0ea5e9;
             margin-bottom: 8px;
             font-size: 0.9rem;
         }
         
         .curriculum-option.disabled {
             opacity: 0.6;
             cursor: not-allowed;
             background: #f1f5f9;
         }
         
         .curriculum-option.disabled:hover {
             transform: none;
             box-shadow: none;
             border-color: #e2e8f0;
         }
        
        .curmission-type-display {
            background: #f7fafc;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin: 25px 0;
        }
        
        .admission-type-label {
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 10px;
            font-size: 1rem;
        }
        
        .admission-type-value {
            font-size: 1.2rem;
            font-weight: 600;
            color: #007bff;
            padding: 10px;
            background: white;
            border-radius: 8px;
            border: 2px solid #e2e8f0;
        }
        
        .submit-section {
            text-align: center;
            margin-top: 30px;
        }
        
        .submit-btn {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 18px 40px;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.4);
        }
        
        .submit-btn:disabled {
            background: #cbd5e0;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .back-link:hover {
            color: #0056b3;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .container {
                margin: 10px;
                border-radius: 15px;
            }
            
            .header {
                padding: 30px 20px;
            }
            
            .form-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Student Admission Form</h1>
            <p>Complete your application to join our institution</p>
        </div>
        
        <div class="form-container">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-error">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>
            
            <form method="post" action="/admission/submit" id="admissionForm">
                <!-- Personal Information Section -->
                <div class="form-section">
                    <div class="section-title">📝 Personal Information</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First Name *</label>
                            <input type="text" name="first_name" id="first_name" placeholder="Enter your first name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="last_name">Last Name *</label>
                            <input type="text" name="last_name" id="last_name" placeholder="Enter your last name" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" name="email" id="email" placeholder="Enter your email address" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password *</label>
                            <input type="password" name="password" id="password" placeholder="Create a password" required>
                        </div>
                    </div>
                </div>
                
                <!-- Academic Information Section -->
                <div class="form-section">
                    <div class="section-title">🎓 Academic Information</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="grade_level">Grade Level *</label>
                            <select name="grade_level" id="grade_level" required onchange="updateFormVisibility()">
                                <option value="">-- Select Grade Level --</option>
                                <option value="7">Grade 7</option>
                                <option value="8">Grade 8</option>
                                <option value="9">Grade 9</option>
                                <option value="10">Grade 10</option>
                                <option value="11">Grade 11</option>
                                <option value="12">Grade 12</option>
                            </select>
                        </div>
                        
                        <div class="form-group" id="previous_grade_div" style="display: none;">
                            <label for="previous_grade_level">Previous Grade Level</label>
                            <select name="previous_grade_level" id="previous_grade_level" onchange="updateAdmissionType()">
                                <option value="">-- Select Previous Grade --</option>
                                <option value="7">Grade 7</option>
                                <option value="8">Grade 8</option>
                                <option value="9">Grade 9</option>
                                <option value="10">Grade 10</option>
                            </select>
                            <small>Required for students who have attended school before (JHS only)</small>
                        </div>
                    </div>
                    
                    <div class="form-group" id="strand_div" style="display: none;">
                        <label for="strand_id">Strand/Track * (Required for SHS)</label>
                        <select name="strand_id" id="strand_id" required>
                            <option value="">-- Select Strand --</option>
                            <?php if (isset($strands)): ?>
                                <?php 
                                $currentTrack = '';
                                foreach ($strands as $strand): 
                                    if ($strand['track_name'] !== $currentTrack):
                                        if ($currentTrack !== '') echo '</optgroup>';
                                        $currentTrack = $strand['track_name'];
                                        echo '<optgroup label="' . esc($strand['track_name']) . ' (' . strtoupper($strand['track_level']) . ')"';
                                        if ($strand['track_level'] === 'jhs') echo ' disabled';
                                        echo '>';
                                    endif;
                                ?>
                                    <option value="<?= $strand['id'] ?>" <?= ($strand['track_level'] === 'jhs') ? 'disabled' : '' ?>>
                                        <?= esc($strand['name']) ?>
                                    </option>
                                <?php 
                                    if ($strand['track_name'] !== $currentTrack) echo '</optgroup>';
                                endforeach; 
                                if ($currentTrack !== '') echo '</optgroup>';
                                ?>
                            <?php endif; ?>
                        </select>
                        <small>Choose your specialization track for Senior High School</small>
                    </div>
                </div>
                
                <!-- Curriculum Selection Section (JHS Only) -->
                <div class="form-section" id="curriculum_section" style="display: none;">
                    <div class="section-title">📚 Curriculum Selection (JHS Only)</div>
                    <div class="curriculum-selection">
                        <div class="curriculum-header">
                            Select Curriculum (Required for JHS)
                        </div>
                        <div class="curriculum-options">
                            <?php if (isset($curriculums) && !empty($curriculums)): ?>
                                <?php foreach ($curriculums as $curriculum): ?>
                                    <div class="curriculum-option" onclick="selectCurriculum(this, <?= $curriculum['id'] ?>)">
                                        <input type="radio" name="curriculum_id" value="<?= $curriculum['id'] ?>" id="curriculum_<?= $curriculum['id'] ?>" required>
                                        <div class="option-title"><?= esc($curriculum['name']) ?></div>
                                        <?php if ($curriculum['description']): ?>
                                            <small><?= esc($curriculum['description']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="curriculum-option disabled">
                                    <div class="option-title">No Curriculums Available</div>
                                    <small>Please contact the administrator to set up curriculum options</small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Admission Type Display -->
                <div class="admission-type-display">
                    <div class="admission-type-label">Admission Type</div>
                    <div class="admission-type-value" id="admission_type_display">
                        Will be determined automatically
                    </div>
                    <small>Admission type is automatically determined based on your grade level</small>
                </div>
                
                <!-- Submit Section -->
                <div class="submit-section">
                    <button type="submit" id="submitButton" class="submit-btn">
                        Submit Application
                    </button>
                    <br>
                    <a href="/" class="back-link">← Back to Home</a>
                </div>
            </form>
        </div>
    </div>
    
    <script>
                 function selectCurriculum(element, curriculumId) {
             // Remove selection from all options
             document.querySelectorAll('.curriculum-option').forEach(option => {
                 option.classList.remove('selected');
                 option.querySelector('input[type="radio"]').checked = false;
             });
             
             // Select the clicked option
             element.classList.add('selected');
             element.querySelector('input[type="radio"]').checked = true;
         }
        
        function updateFormVisibility() {
            const gradeLevel = parseInt(document.getElementById('grade_level').value) || 0;
            const strandDiv = document.getElementById('strand_div');
            const previousGradeDiv = document.getElementById('previous_grade_div');
            const curriculumSection = document.getElementById('curriculum_section');
            const strandSelect = document.getElementById('strand_id');
            const previousGradeSelect = document.getElementById('previous_grade_level');
            
            // Show/hide strand selection for SHS (Grade 11+)
            if (gradeLevel >= 11) {
                strandDiv.style.display = 'block';
                strandSelect.required = true;
                // Hide curriculum section for SHS
                curriculumSection.style.display = 'none';
                // Hide previous grade for SHS
                previousGradeDiv.style.display = 'none';
                previousGradeSelect.value = '';
            } else {
                strandDiv.style.display = 'none';
                strandSelect.required = false;
                // Show curriculum section for JHS
                curriculumSection.style.display = 'block';
                // Show previous grade for non-Grade 7 JHS students
                if (gradeLevel > 7) {
                    previousGradeDiv.style.display = 'block';
                } else {
                    previousGradeDiv.style.display = 'none';
                    previousGradeSelect.value = '';
                }
            }
            
            // Update admission type display
            updateAdmissionType();
        }
        
        function updateAdmissionType() {
            const gradeLevel = parseInt(document.getElementById('grade_level').value) || 0;
            const previousGrade = parseInt(document.getElementById('previous_grade_level').value) || 0;
            const submitButton = document.getElementById('submitButton');
            const typeDisplay = document.getElementById('admission_type_display');
            
            let admissionType = '';
            let isValid = true;
            
            if (gradeLevel === 0) {
                admissionType = 'Please select grade level';
                isValid = false;
            } else if (gradeLevel >= 11) {
                // SHS students
                admissionType = 'Senior High School Student';
                isValid = true;
            } else {
                // JHS students
                if (previousGrade === 0) {
                    if (gradeLevel === 7) {
                        admissionType = 'Regular (New Student)';
                    } else {
                        admissionType = 'Transferee (New Student)';
                    }
                } else {
                    if (gradeLevel > previousGrade) {
                        admissionType = 'Promoted (Moving to next grade)';
                    } else if (gradeLevel === previousGrade) {
                        admissionType = 'Re-enroll (Same grade level)';
                    } else {
                        admissionType = '❌ Invalid: Cannot move to lower grade';
                        isValid = false;
                    }
                }
            }
            
            typeDisplay.textContent = admissionType;
            
            // Enable/disable submit button based on validity
            if (submitButton) {
                submitButton.disabled = !isValid;
            }
        }
        
        // Add event listeners
        document.getElementById('grade_level').addEventListener('change', updateFormVisibility);
        document.getElementById('previous_grade_level').addEventListener('change', updateAdmissionType);
        
        // Form validation before submission
        document.getElementById('admissionForm').addEventListener('submit', function(e) {
            const gradeLevel = parseInt(document.getElementById('grade_level').value) || 0;
            const curriculumId = document.querySelector('input[name="curriculum_id"]:checked');
            
            // Validate curriculum selection for JHS students
            if (gradeLevel >= 7 && gradeLevel <= 10 && !curriculumId) {
                e.preventDefault();
                alert('Please select a curriculum for Junior High School students.');
                return false;
            }
            
            // Validate strand selection for SHS students
            if (gradeLevel >= 11) {
                const strandId = document.getElementById('strand_id').value;
                if (!strandId) {
                    e.preventDefault();
                    alert('Please select a strand for Senior High School students.');
                    return false;
                }
            }
        });
    </script>
</body>
</html>
