<h2>Admission Form</h2>

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
    <div class="form-group">
        <label for="first_name">First Name *</label>
        <input type="text" name="first_name" id="first_name" placeholder="First Name" required>
    </div>
    
    <div class="form-group">
        <label for="last_name">Last Name *</label>
        <input type="text" name="last_name" id="last_name" placeholder="Last Name" required>
    </div>
    
    <div class="form-group">
        <label for="email">Email *</label>
        <input type="email" name="email" id="email" placeholder="Email" required>
    </div>
    
    <div class="form-group">
        <label for="password">Password *</label>
        <input type="password" name="password" id="password" placeholder="Password" required>
    </div>
    
    <div class="form-group">
        <label for="grade_level">Grade Level *</label>
        <select name="grade_level" id="grade_level" required onchange="updateStrandVisibility()">
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
        <label for="previous_grade_level">Previous Grade Level (if applicable)</label>
        <select name="previous_grade_level" id="previous_grade_level">
            <option value="">-- Select Previous Grade --</option>
            <option value="7">Grade 7</option>
            <option value="8">Grade 8</option>
            <option value="9">Grade 9</option>
            <option value="10">Grade 10</option>
            <option value="11">Grade 11</option>
            <option value="12">Grade 12</option>
        </select>
    </div>
    
    <div class="form-group" id="strand_div" style="display: none;">
        <label for="strand_id">Strand/Track * (Required for SHS)</label>
        <select name="strand_id" id="strand_id" required>
            <option value="">-- Select Strand --</option>
            <?php if (isset($strands)): ?>
                <?php foreach ($strands as $strand): ?>
                    <option value="<?= $strand['id'] ?>"><?= $strand['name'] ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label>Admission Type</label>
        <input type="text" id="admission_type_display" readonly value="Will be determined automatically">
        <small>Admission type is automatically determined based on your grade level and previous grade.</small>
    </div>
    
    <button type="submit" id="submitButton">Submit Application</button>
</form>

<script>
function updateStrandVisibility() {
    const gradeLevel = document.getElementById('grade_level').value;
    const strandDiv = document.getElementById('strand_div');
    const previousGradeDiv = document.getElementById('previous_grade_div');
    const strandSelect = document.getElementById('strand_id');
    const previousGradeSelect = document.getElementById('previous_grade_level');
    
    // Show/hide strand selection for SHS
    if (gradeLevel >= 11) {
        strandDiv.style.display = 'block';
        strandSelect.required = true;
    } else {
        strandDiv.style.display = 'none';
        strandSelect.required = false;
    }
    
    // Show previous grade for non-Grade 7 students
    if (gradeLevel > 7) {
        previousGradeDiv.style.display = 'block';
    } else {
        previousGradeDiv.style.display = 'none';
        previousGradeSelect.value = '';
    }
    
    // Update admission type display
    updateAdmissionType();
}

function updateAdmissionType() {
    const gradeLevel = parseInt(document.getElementById('grade_level').value) || 0;
    const previousGrade = parseInt(document.getElementById('previous_grade_level').value) || 0;
    const submitButton = document.getElementById('submitButton');
    
    let admissionType = '';
    let isValid = true;
    
    if (gradeLevel === 0) {
        admissionType = 'Please select grade level';
        isValid = false;
    } else if (previousGrade === 0) {
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
    
    document.getElementById('admission_type_display').value = admissionType;
    
    // Enable/disable submit button based on validity
    if (submitButton) {
        submitButton.disabled = !isValid;
        submitButton.style.opacity = isValid ? '1' : '0.5';
        submitButton.style.cursor = isValid ? 'pointer' : 'not-allowed';
    }
}

// Add event listeners
document.getElementById('grade_level').addEventListener('change', updateStrandVisibility);
document.getElementById('previous_grade_level').addEventListener('change', updateAdmissionType);
</script>

<style>
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

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
}

.form-group small {
    color: #666;
    font-size: 14px;
}

button[type="submit"] {
    background-color: #007bff;
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

button[type="submit"]:hover {
    background-color: #0056b3;
}

button[type="submit"]:disabled {
    background-color: #6c757d;
    cursor: not-allowed;
}

button[type="submit"]:disabled:hover {
    background-color: #6c757d;
}
</style>
