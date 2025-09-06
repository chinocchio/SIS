<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Profile - Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f6fb;
        }
        
        .container {
            max-width: 1200px;
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
        
        .btn-success {
            background: #28a745;
        }
        
        .btn-success:hover {
            background: #218838;
        }
        
        .btn-danger {
            background: #dc3545;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
        
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        
        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        
        .full-width {
            grid-column: 1 / -1;
        }
        
        .teacher-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #667eea;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 32px;
            margin: 0 auto 20px;
        }
        
        .assignments-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .assignments-table th,
        .assignments-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .assignments-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        
        .assignments-table tr:hover {
            background-color: #f5f5f5;
        }
        
        .assignment-form {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #bbdefb;
            margin-top: 20px;
        }
        
        .assignment-form h4 {
            margin-top: 0;
            color: #1976d2;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #495057;
        }
        
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        
        .no-assignments {
            text-align: center;
            padding: 20px;
            color: #6c757d;
            font-style: italic;
        }
        
        @media (max-width: 768px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👨‍🏫 Teacher Profile</h1>
            <div>
                <a href="/admin/teachers/edit/<?= $teacher['id'] ?>" class="btn btn-warning">✏️ Edit Teacher</a>
                <a href="/admin/teachers" class="btn btn-secondary">← Back to Teachers</a>
            </div>
        </div>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        
        <div class="profile-grid">
            <!-- Teacher Information -->
            <div class="profile-section">
                <div class="teacher-avatar">
                    <?= strtoupper(substr($teacher['first_name'], 0, 1) . substr($teacher['last_name'], 0, 1)) ?>
                </div>
                <h3>👤 Teacher Information</h3>
                <div class="info-row">
                    <span class="info-label">Full Name:</span>
                    <span class="info-value"><?= esc($teacher['first_name'] . ' ' . $teacher['last_name']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Username:</span>
                    <span class="info-value"><?= esc($teacher['username']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value"><?= esc($teacher['email']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value">
                        <span class="status-badge status-<?= $teacher['is_active'] ? 'active' : 'inactive' ?>">
                            <?= $teacher['is_active'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Created:</span>
                    <span class="info-value">
                        <?= $teacher['created_at'] ? date('F d, Y \a\t g:i A', strtotime($teacher['created_at'])) : 'Not specified' ?>
                    </span>
                </div>
            </div>
            
            <!-- Assignment Summary -->
            <div class="profile-section">
                <h3>📊 Assignment Summary</h3>
                <div class="info-row">
                    <span class="info-label">Total Assignments:</span>
                    <span class="info-value"><?= count($teacher['assignments']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Active School Year:</span>
                    <span class="info-value"><?= esc($activeSchoolYear['name'] ?? 'Not set') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Available Subjects:</span>
                    <span class="info-value"><?= count($availableSubjects) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Available Sections:</span>
                    <span class="info-value"><?= count($sections) ?></span>
                </div>
            </div>
        </div>
        
        <!-- Subject Assignments -->
        <div class="profile-section full-width">
            <h3>📚 Subject Assignments</h3>
            
            <?php if (!empty($teacher['assignments'])): ?>
                <table class="assignments-table">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Section</th>
                            <th>School Year</th>
                            <th>Assigned Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($teacher['assignments'] as $assignment): ?>
                            <tr>
                                <td>
                                    <strong><?= esc($assignment['subject_name']) ?></strong>
                                    <br><small><?= esc($assignment['subject_code']) ?></small>
                                </td>
                                <td>
                                    <?= esc($assignment['section_name']) ?>
                                    <br><small>Grade <?= $assignment['section_grade_level'] ?></small>
                                </td>
                                <td><?= esc($assignment['school_year']) ?></td>
                                <td><?= date('M d, Y', strtotime($assignment['created_at'])) ?></td>
                                <td>
                                    <a href="/admin/teachers/remove-assignment/<?= $assignment['id'] ?>" 
                                       class="btn btn-danger" 
                                       style="padding: 5px 10px; font-size: 12px;"
                                       onclick="return confirm('Remove this subject assignment?')">
                                        🗑️ Remove
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-assignments">
                    <p>No subject assignments found for this teacher.</p>
                </div>
            <?php endif; ?>
            
            <!-- Assignment Form -->
            <?php if (!empty($availableSubjects) && !empty($sections) && !empty($activeSchoolYear)): ?>
                <div class="assignment-form">
                    <h4>➕ Assign New Subject</h4>
                    <form method="POST" action="/admin/teachers/assign-subject">
                        <input type="hidden" name="teacher_id" value="<?= $teacher['id'] ?>">
                        <input type="hidden" name="school_year_id" value="<?= $activeSchoolYear['id'] ?>">
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="subject_id">Subject *</label>
                                <select name="subject_id" id="subject_id" required>
                                    <option value="">Select Subject</option>
                                    <?php foreach ($availableSubjects as $subject): ?>
                                        <option value="<?= $subject['id'] ?>">
                                            <?= esc($subject['name']) ?> 
                                            <?php if ($subject['curriculum_name']): ?>
                                                (<?= esc($subject['curriculum_name']) ?>)
                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="section_id">Section *</label>
                                <select name="section_id" id="section_id" required>
                                    <option value="">Select Section</option>
                                    <?php foreach ($sections as $section): ?>
                                        <option value="<?= $section['id'] ?>">
                                            <?= esc($section['name']) ?> (Grade <?= $section['grade_level'] ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-success" style="width: 100%;">Assign Subject</button>
                            </div>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <div class="no-assignments">
                    <p>Cannot assign subjects: Missing subjects, sections, or active school year.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
