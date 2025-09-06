<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - <?= esc($student['full_name']) ?></title>
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
        
        .btn-warning {
            background: #ffc107;
            color: #212529;
        }
        
        .btn-warning:hover {
            background: #e0a800;
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
        
        .status-draft {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-pending {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .status-approved {
            background: #d4edda;
            color: #155724;
        }
        
        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }
        
        .full-width {
            grid-column: 1 / -1;
        }
        
        .actions-section {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #bbdefb;
        }
        
        .actions-section h3 {
            color: #1976d2;
            margin: 0 0 15px 0;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .no-data {
            color: #6c757d;
            font-style: italic;
        }
        
        .lrn-display {
            background: #667eea;
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .full-name-display {
            background: #28a745;
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
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
        
        .document-upload {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            margin-bottom: 20px;
        }
        
        .document-upload h4 {
            margin-top: 0;
            color: #495057;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #495057;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .form-group textarea {
            height: 80px;
            resize: vertical;
        }
        
        /* Grade display styles */
        .grade-display {
            background: #e8f5e8;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #c3e6c3;
            margin-bottom: 20px;
        }
        
        .grade-display h4 {
            margin-top: 0;
            color: #155724;
        }
        
        .grade-value {
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
        }
        
        .no-grades {
            color: #6c757d;
            font-style: italic;
            text-align: center;
            padding: 20px;
        }
        
        /* Responsive design */
        @media (max-width: 768px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👤 Student Dashboard</h1>
            <div>
                <a href="/student/change-password" class="btn btn-warning">🔒 Change Password</a>
                <a href="/auth/logout" class="btn btn-secondary">🚪 Logout</a>
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
        
        <!-- LRN Display -->
        <div class="lrn-display">
            LRN: <?= esc($student['lrn']) ?>
        </div>
        
        <!-- Full Name Display -->
        <div class="full-name-display">
            <?= esc($student['full_name']) ?>
        </div>
        
        <div class="profile-grid">
            <!-- Personal Information -->
            <div class="profile-section">
                <h3>📋 Personal Information</h3>
                <div class="info-row">
                    <span class="info-label">Full Name:</span>
                    <span class="info-value"><?= esc($student['full_name']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Birth Date:</span>
                    <span class="info-value">
                        <?= $student['birth_date'] ? date('F d, Y', strtotime($student['birth_date'])) : '<span class="no-data">Not specified</span>' ?>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Gender:</span>
                    <span class="info-value">
                        <?= $student['gender'] ? esc($student['gender']) : '<span class="no-data">Not specified</span>' ?>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value"><?= esc($student['email']) ?></span>
                </div>
            </div>
            
            <!-- Academic Information -->
            <div class="profile-section">
                <h3>🎓 Academic Information</h3>
                <div class="info-row">
                    <span class="info-label">Grade Level:</span>
                    <span class="info-value">
                        <?= $student['grade_level'] ? 'Grade ' . esc($student['grade_level']) : '<span class="no-data">Not specified</span>' ?>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Previous Grade:</span>
                    <span class="info-value">
                        <?= $student['previous_grade_level'] ? 'Grade ' . esc($student['previous_grade_level']) : '<span class="no-data">Not specified</span>' ?>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Enrollment Type:</span>
                    <span class="info-value"><?= esc(ucfirst($student['enrollment_type'] ?? 'new')) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Admission Type:</span>
                    <span class="info-value"><?= esc(ucfirst($student['admission_type'] ?? 'regular')) ?></span>
                </div>
            </div>
            
            <!-- School Information -->
            <div class="profile-section">
                <h3>🏫 School Information</h3>
                <div class="info-row">
                    <span class="info-label">Previous School:</span>
                    <span class="info-value">
                        <?= $student['previous_school'] ? esc($student['previous_school']) : '<span class="no-data">Not specified</span>' ?>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Strand:</span>
                    <span class="info-value">
                        <?= isset($student['strand_name']) ? esc($student['strand_name']) : '<span class="no-data">Not specified</span>' ?>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Curriculum:</span>
                    <span class="info-value">
                        <?= isset($student['curriculum_name']) ? esc($student['curriculum_name']) : '<span class="no-data">Not specified</span>' ?>
                    </span>
                </div>
            </div>
            
            <!-- Section Information -->
            <div class="profile-section">
                <h3>🏫 Section Information</h3>
                <div class="info-row">
                    <span class="info-label">Current Section:</span>
                    <span class="info-value">
                        <?= isset($student['section_name']) ? esc($student['section_name']) . ' (Grade ' . $student['section_grade_level'] . ')' : '<span class="no-data">Not assigned</span>' ?>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Previous Section:</span>
                    <span class="info-value">
                        <?= isset($student['previous_section_name']) ? esc($student['previous_section_name']) . ' (Grade ' . $student['previous_section_grade_level'] . ')' : '<span class="no-data">Not specified</span>' ?>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Previous School Year:</span>
                    <span class="info-value">
                        <?= $student['previous_school_year'] ? esc($student['previous_school_year']) : '<span class="no-data">Not specified</span>' ?>
                    </span>
                </div>
            </div>
            
            <!-- Document Upload Section -->
            <div class="profile-section full-width">
                <h3>📄 Submit Documents</h3>
                <div class="document-upload">
                    <h4>Upload New Document</h4>
                    <form method="POST" action="/student/submit-document" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="document_type">Document Type:</label>
                            <select name="document_type" id="document_type" required>
                                <option value="">Select document type...</option>
                                <option value="birth_certificate">Birth Certificate</option>
                                <option value="report_card">Report Card (SF9)</option>
                                <option value="good_moral">Certificate of Good Moral Character</option>
                                <option value="form_137">Form 137</option>
                                <option value="id_picture">ID Picture</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="document_file">File:</label>
                            <input type="file" name="document_file" id="document_file" required accept=".jpg,.jpeg,.png,.pdf">
                            <small style="color: #6c757d;">Only JPG, PNG, and PDF files are allowed. Maximum size: 5MB</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description (Optional):</label>
                            <textarea name="description" id="description" placeholder="Add any additional notes about this document..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-success">📤 Upload Document</button>
                    </form>
                </div>
            </div>
            
            <!-- Documents Section -->
            <div class="profile-section full-width">
                <h3>🗂️ Submitted Documents</h3>
                <?php if (!empty($documents)): ?>
                    <table style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr>
                                <th style="text-align:left; padding:8px; border-bottom:1px solid #eee;">Type</th>
                                <th style="text-align:left; padding:8px; border-bottom:1px solid #eee;">Status</th>
                                <th style="text-align:left; padding:8px; border-bottom:1px solid #eee;">Uploaded At</th>
                                <th style="text-align:left; padding:8px; border-bottom:1px solid #eee;">File</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($documents as $doc): ?>
                                <tr>
                                    <td style="padding:8px; border-bottom:1px solid #f1f1f1;"><?= esc(ucfirst(str_replace('_', ' ', $doc['document_type']))) ?></td>
                                    <td style="padding:8px; border-bottom:1px solid #f1f1f1;">
                                        <span class="status-badge status-<?= esc(strtolower($doc['status'])) ?>"><?= esc(ucfirst($doc['status'])) ?></span>
                                    </td>
                                    <td style="padding:8px; border-bottom:1px solid #f1f1f1;"><?= date('M d, Y g:i A', strtotime($doc['uploaded_at'])) ?></td>
                                    <td style="padding:8px; border-bottom:1px solid #f1f1f1;">
                                        <?php 
                                            $fileExt = strtolower(pathinfo($doc['file_path'], PATHINFO_EXTENSION));
                                            $streamUrl = base_url('/registrar/document/view/' . $doc['id']);
                                        ?>
                                        <?php if (in_array($fileExt, ['jpg','jpeg','png','gif'])): ?>
                                            <a href="<?= $streamUrl ?>" target="_blank">View Image</a>
                                        <?php else: ?>
                                            <a href="<?= $streamUrl ?>" target="_blank">View Document</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-data">No documents uploaded yet.</div>
                <?php endif; ?>
            </div>
            
            <!-- Grades Section -->
            <div class="profile-section full-width">
                <h3>📚 Grades & Academic Progress</h3>
                
                <?php if (!empty($grades)): ?>
                    <div class="grade-display">
                        <h4>📊 Current School Year: <?= esc($activeSchoolYear['name'] ?? 'Not Set') ?></h4>
                        <p>You have <?= count($grades) ?> recorded grades for this school year.</p>
                    </div>
                    
                    <table style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr>
                                <th style="text-align:left; padding:8px; border-bottom:1px solid #eee;">Subject</th>
                                <th style="text-align:left; padding:8px; border-bottom:1px solid #eee;">Code</th>
                                <th style="text-align:left; padding:8px; border-bottom:1px solid #eee;">Quarter</th>
                                <th style="text-align:left; padding:8px; border-bottom:1px solid #eee;">Grade</th>
                                <th style="text-align:left; padding:8px; border-bottom:1px solid #eee;">Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($grades as $grade): ?>
                                <tr>
                                    <td style="padding:8px; border-bottom:1px solid #f1f1f1;"><?= esc($grade['subject_name']) ?></td>
                                    <td style="padding:8px; border-bottom:1px solid #f1f1f1;"><?= esc($grade['subject_code']) ?></td>
                                    <td style="padding:8px; border-bottom:1px solid #f1f1f1;">Q<?= $grade['quarter'] ?></td>
                                    <td style="padding:8px; border-bottom:1px solid #f1f1f1;">
                                        <span class="grade-value"><?= $grade['grade'] ?></span>
                                    </td>
                                    <td style="padding:8px; border-bottom:1px solid #f1f1f1;"><?= esc(ucfirst($grade['is_core'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-grades">
                        <h4>📊 No Grades Recorded Yet</h4>
                        <p>Your grades will appear here once your teachers start recording them.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Account Information -->
            <div class="profile-section">
                <h3>🔐 Account Information</h3>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value">
                        <span class="status-badge status-<?= strtolower($student['status']) ?>">
                            <?= esc(ucfirst($student['status'] === 'draft' ? 'pending' : $student['status'])) ?>
                        </span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Created Date:</span>
                    <span class="info-value">
                        <?= $student['created_at'] ? date('F d, Y \a\t g:i A', strtotime($student['created_at'])) : '<span class="no-data">Not specified</span>' ?>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Last Updated:</span>
                    <span class="info-value">
                        <?= $student['updated_at'] ? date('F d, Y \a\t g:i A', strtotime($student['updated_at'])) : '<span class="no-data">Not specified</span>' ?>
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Actions Section -->
        <div class="actions-section full-width">
            <h3>⚡ Quick Actions</h3>
            <div class="action-buttons">
                <a href="/student/change-password" class="btn btn-warning">🔒 Change Password</a>
                <a href="/auth/logout" class="btn btn-secondary">🚪 Logout</a>
            </div>
        </div>
    </div>
</body>
</html>