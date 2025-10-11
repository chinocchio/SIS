<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Section - Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            margin: 0;
        }
        
        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 5px;
            border: none;
            cursor: pointer;
        }
        
        .btn:hover {
            background-color: #0056b3;
        }
        
        .btn-success {
            background-color: #28a745;
        }
        
        .btn-success:hover {
            background-color: #1e7e34;
        }
        
        .btn-warning {
            background-color: #ffc107;
            color: #212529;
        }
        
        .btn-warning:hover {
            background-color: #e0a800;
        }
        
        .btn-danger {
            background-color: #dc3545;
        }
        
        .btn-danger:hover {
            background-color: #c82333;
        }
        
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
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
        
        .required {
            color: #dc3545;
        }
        
        .help-text {
            font-size: 0.9em;
            color: #666;
            margin-top: 5px;
        }
        
        .section-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
        }
        
        .section-info h3 {
            margin: 0 0 10px 0;
            color: #007bff;
        }
        
        .section-info p {
            margin: 5px 0;
        }
    </style>
    <?php include __DIR__ . '/partials/sidebar_styles.php'; ?>
</head>
<body>
    <div class="container">
        <?php include __DIR__ . '/partials/layout_start.php'; ?>
        
        <div class="header">
            <h1>✏️ Edit Section</h1>
            <p>Update section information and settings</p>
        </div>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        
        <div class="form-container">
            <!-- Section Information Display -->
            <div class="section-info">
                <h3>Current Section Information</h3>
                <p><strong>Section:</strong> <?= $section['name'] ?></p>
                <p><strong>Grade Level:</strong> Grade <?= $section['grade_level'] ?></p>
                <p><strong>School Year:</strong> <?= $section['school_year'] ?></p>
                <?php if ($section['strand_name']): ?>
                    <p><strong>Strand:</strong> <?= $section['strand_name'] ?></p>
                <?php endif; ?>
                <p><strong>Capacity:</strong> <?= $section['capacity_min'] ?> - <?= $section['capacity_max'] ?> students</p>
            </div>
            
            <form method="POST" action="/admin/sections/edit/<?= $section['id'] ?>">
                <div class="form-group">
                    <label for="name">Section Name <span class="required">*</span></label>
                    <input type="text" id="name" name="name" value="<?= old('name', $section['name']) ?>" required>
                    <div class="help-text">Example: 7-A, 11-STEM-A, 12-ABM-B</div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="grade_level">Grade Level <span class="required">*</span></label>
                        <select id="grade_level" name="grade_level" required>
                            <option value="">Select Grade Level</option>
                            <option value="7" <?= old('grade_level', $section['grade_level']) == '7' ? 'selected' : '' ?>>Grade 7 (JHS)</option>
                            <option value="8" <?= old('grade_level', $section['grade_level']) == '8' ? 'selected' : '' ?>>Grade 8 (JHS)</option>
                            <option value="9" <?= old('grade_level', $section['grade_level']) == '9' ? 'selected' : '' ?>>Grade 9 (JHS)</option>
                            <option value="10" <?= old('grade_level', $section['grade_level']) == '10' ? 'selected' : '' ?>>Grade 10 (JHS)</option>
                            <option value="11" <?= old('grade_level', $section['grade_level']) == '11' ? 'selected' : '' ?>>Grade 11 (SHS)</option>
                            <option value="12" <?= old('grade_level', $section['grade_level']) == '12' ? 'selected' : '' ?>>Grade 12 (SHS)</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="school_year_id">School Year <span class="required">*</span></label>
                        <select id="school_year_id" name="school_year_id" required>
                            <option value="">Select School Year</option>
                            <?php foreach ($schoolYears as $schoolYear): ?>
                                <option value="<?= $schoolYear['id'] ?>" <?= old('school_year_id', $section['school_year_id']) == $schoolYear['id'] ? 'selected' : '' ?>>
                                    <?= $schoolYear['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group" id="strand_group" style="display: <?= $section['grade_level'] >= 11 ? 'block' : 'none' ?>;">
                    <label for="strand_id">Strand (SHS Only)</label>
                    <select id="strand_id" name="strand_id">
                        <option value="">Select Strand (Optional)</option>
                        <?php foreach ($strands as $strand): ?>
                            <option value="<?= $strand['id'] ?>" <?= old('strand_id', $section['strand_id']) == $strand['id'] ? 'selected' : '' ?>>
                                <?= $strand['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="help-text">Only required for Senior High School sections</div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="capacity_min">Minimum Capacity</label>
                        <input type="number" id="capacity_min" name="capacity_min" value="<?= old('capacity_min', $section['capacity_min']) ?>" min="1" max="100">
                        <div class="help-text">Minimum number of students required</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="capacity_max">Maximum Capacity</label>
                        <input type="number" id="capacity_max" name="capacity_max" value="<?= old('capacity_max', $section['capacity_max']) ?>" min="1" max="100">
                        <div class="help-text">Maximum number of students allowed</div>
                    </div>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Update Section</button>
                    <a href="/admin/sections" class="btn">Cancel</a>
                </div>
            </form>
        </div>
        <?php include __DIR__ . '/partials/layout_end.php'; ?>
    </div>
    
    <script>
        document.getElementById('grade_level').addEventListener('change', function() {
            const gradeLevel = this.value;
            const strandGroup = document.getElementById('strand_group');
            const strandSelect = document.getElementById('strand_id');
            
            if (gradeLevel >= 11) {
                // SHS - show strand field
                strandGroup.style.display = 'block';
                strandSelect.required = false; // Make it optional
            } else {
                // JHS - hide strand field
                strandGroup.style.display = 'none';
                strandSelect.value = ''; // Clear selection
                strandSelect.required = false;
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
