<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Subject - Admin Dashboard</title>
    <style>
        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fc;
            color: #5a5c69;
        }
        
        .form-container {
            width: 100%;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .main-content {
            padding: 1.5rem;
            min-height: 100vh;
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
        
        .current-info {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .current-info h4 {
            margin: 0 0 10px 0;
            color: #495057;
        }
    </style>
    <?php include __DIR__ . '/partials/sidebar_styles.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/partials/layout_start.php'; ?>
        <div class="form-container">
        <div class="header-actions">
            <h1>✏️ Edit Subject</h1>
            <a href="/admin/subjects" class="btn btn-secondary">← Back to Subjects</a>
        </div>
        
        <div class="current-info">
            <h4>📋 Current Subject Information</h4>
            <p><strong>Code:</strong> <?= esc($subject['code']) ?> | <strong>Name:</strong> <?= esc($subject['name']) ?> | <strong>Curriculum:</strong> <?= esc($subject['curriculum_id']) ?></p>
        </div>
        
        <div class="note">
            <h4>📝 How to edit a subject:</h4>
            <ol>
                <li>Modify the subject details as needed</li>
                <li>Ensure the subject code remains unique within its curriculum</li>
                <li>Update units, description, or status if required</li>
                <li>Save changes to update the subject</li>
            </ol>
        </div>
        
        <form action="/admin/subjects/edit/<?= $subject['id'] ?>" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="curriculum_id">Curriculum <span class="required">*</span></label>
                    <select id="curriculum_id" name="curriculum_id" required>
                        <option value="">Select Curriculum</option>
                        <?php foreach ($curriculums as $curriculum): ?>
                            <option value="<?= esc($curriculum['id']) ?>" <?= ($curriculum['id'] == $subject['curriculum_id']) ? 'selected' : '' ?>>
                                <?= esc($curriculum['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="help-text">Choose which curriculum this subject belongs to</div>
                </div>
                
                <div class="form-group">
                    <label for="code">Subject Code <span class="required">*</span></label>
                    <input type="text" id="code" name="code" required maxlength="20" placeholder="e.g., MATH101" value="<?= esc($subject['code']) ?>">
                    <div class="help-text">Unique code within the curriculum (2-20 characters)</div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="name">Subject Name <span class="required">*</span></label>
                <input type="text" id="name" name="name" required maxlength="100" placeholder="e.g., Mathematics" value="<?= esc($subject['name']) ?>">
                <div class="help-text">Full name of the subject</div>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3" placeholder="Brief description of what this subject covers..."><?= esc($subject['description'] ?? '') ?></textarea>
                <div class="help-text">Optional description of the subject content</div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="units">Units <span class="required">*</span></label>
                    <input type="number" id="units" name="units" required min="1" max="9" step="0.5" placeholder="1.0" value="<?= esc($subject['units']) ?>">
                    <div class="help-text">Number of units (typically 1-6 for JHS subjects)</div>
                </div>
                
                <div class="form-group">
                    <label for="is_core">Subject Category <span class="required">*</span></label>
                    <select id="is_core" name="is_core" required>
                        <option value="core" <?= ($subject['is_core'] === 'core') ? 'selected' : '' ?>>Core</option>
                        <option value="specialized" <?= ($subject['is_core'] === 'specialized') ? 'selected' : '' ?>>Specialized</option>
                        <option value="applied" <?= ($subject['is_core'] === 'applied') ? 'selected' : '' ?>>Applied</option>
                    </select>
                    <div class="help-text">Core: Required for all students, Specialized: Strand-specific, Applied: Practical skills</div>
                </div>
            </div>
            
            <div class="form-group">
                <label>Status</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="is_active" name="is_active" value="1" <?= ($subject['is_active'] == 1) ? 'checked' : '' ?>>
                    <label for="is_active">Active</label>
                </div>
                <div class="help-text">Active subjects are available for enrollment</div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-success">💾 Update Subject</button>
                <a href="/admin/subjects" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
    
    <script>
        // Auto-format subject code to uppercase
        document.getElementById('code').addEventListener('input', function(e) {
            this.value = this.value.toUpperCase();
        });
        
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const curriculum = document.getElementById('curriculum_id').value;
            const code = document.getElementById('code').value;
            const name = document.getElementById('name').value;
            const units = document.getElementById('units').value;
            
            if (!curriculum || !code || !name || !units) {
                e.preventDefault();
                alert('Please fill in all required fields marked with *');
                return false;
            }
            
            if (code.length < 2) {
                e.preventDefault();
                alert('Subject code must be at least 2 characters long');
                return false;
            }
            
            if (name.length < 2) {
                e.preventDefault();
                alert('Subject name must be at least 2 characters long');
                return false;
            }
            
            if (units < 1 || units > 9) {
                e.preventDefault();
                alert('Units must be between 1 and 9');
                return false;
            }
        });
    </script>
        </div>
    <?php include __DIR__ . '/partials/layout_end.php'; ?>
</body>
</html>
