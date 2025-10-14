<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Teacher - Admin Dashboard</title>
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
        
        input[type="text"], input[type="email"], input[type="password"], select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
        }
        
        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2);
        }
        
        input[type="checkbox"] {
            margin-right: 8px;
        }
        
        .required {
            color: #dc3545;
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
        
        .password-section {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .password-section h4 {
            margin-top: 0;
            color: #856404;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✏️ Edit Teacher</h1>
            <div>
                <a href="/admin/teachers/view/<?= $teacher['id'] ?>" class="btn btn-secondary">👁️ View Teacher</a>
                <a href="/admin/teachers" class="btn btn-secondary">← Back to Teachers</a>
            </div>
        </div>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        
        <div class="note">
            <h4>📋 Update Teacher Information</h4>
            <p>Modify the teacher's information below. Leave the password field empty if you don't want to change it.</p>
        </div>
        
        <form action="/admin/teachers/edit/<?= $teacher['id'] ?>" method="post">
            <div class="form-section">
                <h3>👤 Personal Information</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name <span class="required">*</span></label>
                        <input type="text" id="first_name" name="first_name" value="<?= esc($teacher['first_name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name <span class="required">*</span></label>
                        <input type="text" id="last_name" name="last_name" value="<?= esc($teacher['last_name']) ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="username">Username <span class="required">*</span></label>
                    <input type="text" id="username" name="username" value="<?= esc($teacher['username']) ?>" required minlength="3" maxlength="100">
                    <small style="color: #666;">This is used for login (minimum 3 characters)</small>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address <span class="required">*</span></label>
                    <input type="email" id="email" name="email" value="<?= esc($teacher['email']) ?>" required>
                    <small style="color: #666;">Teacher's email address</small>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="is_active" value="1" <?= $teacher['is_active'] ? 'checked' : '' ?>>
                        Active Teacher
                    </label>
                    <small style="color: #666; display: block; margin-top: 5px;">Uncheck to deactivate this teacher account</small>
                </div>
            </div>
            
            <div class="password-section">
                <h4>🔒 Password Change</h4>
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" minlength="6">
                    <small style="color: #666;">Leave empty to keep current password. Minimum 6 characters if changing.</small>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <button type="submit" class="btn">✅ Update Teacher</button>
                <a href="/admin/teachers/view/<?= $teacher['id'] ?>" class="btn btn-secondary">❌ Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
