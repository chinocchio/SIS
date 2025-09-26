<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile - Registrar Dashboard</title>
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👤 Student Profile</h1>
            <div>
                <a href="/registrar/students/edit/<?= $student['id'] ?>" class="btn btn-warning">✏️ Edit Student</a>
                <a href="/registrar/students" class="btn btn-secondary">← Back to Students</a>
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
                
                <!-- Section Assignment Form -->
                <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #e9ecef;">
                    <h4 style="margin-bottom: 15px; color: #495057;">Assign to Section</h4>
                    <form method="POST" action="/registrar/students/assign-section" style="display: flex; gap: 10px; align-items: end;">
                        <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
                        <div style="flex: 1;">
                            <label for="section_id" style="display: block; margin-bottom: 5px; font-weight: bold; color: #495057;">Select Section:</label>
                            <select name="section_id" id="section_id" required style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                                <option value="">Choose a section...</option>
                                <?php
                                // Get available sections for this student's grade level
                                $sectionModel = new \App\Models\SectionModel();
                                $availableSections = $sectionModel->getAvailableSections($student['grade_level'], $student['strand_id']);
                                foreach ($availableSections as $section):
                                    $capacity = $sectionModel->getSectionCapacity($section['id']);
                                    $isFull = $capacity['current'] >= $capacity['max'];
                                    $isCurrent = $section['id'] == $student['section_id'];
                                ?>
                                    <option value="<?= $section['id'] ?>" <?= $isCurrent ? 'selected' : '' ?> <?= $isFull && !$isCurrent ? 'disabled' : '' ?>>
                                        <?= $section['name'] ?> (<?= $capacity['current'] ?>/<?= $capacity['max'] ?> students)
                                        <?= $isFull && !$isCurrent ? ' - FULL' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success" style="padding: 8px 16px;">Assign</button>
                        <?php if ($student['section_id']): ?>
                            <a href="/registrar/students/remove-section/<?= $student['id'] ?>" class="btn btn-warning" style="padding: 8px 16px;" onclick="return confirm('Remove student from current section?')">Remove</a>
                        <?php endif; ?>
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
                                    <td style="padding:8px; border-bottom:1px solid #f1f1f1;"><?= esc($doc['document_type']) ?></td>
                                    <td style="padding:8px; border-bottom:1px solid #f1f1f1;">
                                        <span class="status-badge status-<?= esc(strtolower($doc['status'])) ?>"><?= esc(ucfirst($doc['status'])) ?></span>
                                    </td>
                                    <td style="padding:8px; border-bottom:1px solid #f1f1f1;"><?= esc($doc['uploaded_at']) ?></td>
                                    <td style="padding:8px; border-bottom:1px solid #f1f1f1;">
                                        <?php 
                                            $fileExt = strtolower(pathinfo($doc['file_path'], PATHINFO_EXTENSION));
                                            $viewUrl = base_url('/registrar/document/view/' . $doc['id']);
                                            $downloadUrl = base_url('/registrar/document/download/' . $doc['id']);
                                        ?>
                                        <a href="#" onclick="openDocModal('<?= $viewUrl ?>'); return false;">View</a>
                                        &nbsp;|&nbsp;
                                        <a href="<?= $downloadUrl ?>">Download</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-data">No documents uploaded.</div>
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
                        <?php if (($student['status'] ?? '') === 'approved' && !empty($student['approved_by'])): ?>
                            <span style="margin-left:8px; color:#6c757d;">Approved by: Registrar <?= esc($approvedByName ?? $student['approved_by']) ?></span>
                        <?php endif; ?>
                        <?php if (($student['status'] ?? '') === 'rejected' && !empty($student['rejected_by'])): ?>
                            <span style="margin-left:8px; color:#6c757d;">Rejected by: Registrar <?= esc($student['rejected_by']) ?></span>
                        <?php endif; ?>
                    </span>
                </div>
                <?php if (($student['status'] ?? '') === 'rejected' && !empty($student['rejection_reason'])): ?>
                <div class="info-row">
                    <span class="info-label">Rejection Reason:</span>
                    <span class="info-value">
                        <span style="color: #dc3545; font-style: italic;"><?= esc($student['rejection_reason']) ?></span>
                    </span>
                </div>
                <?php endif; ?>
                <?php if (($student['status'] ?? '') === 'rejected' && !empty($student['rejected_at'])): ?>
                <div class="info-row">
                    <span class="info-label">Rejected Date:</span>
                    <span class="info-value">
                        <?= date('F d, Y \a\t g:i A', strtotime($student['rejected_at'])) ?>
                    </span>
                </div>
                <?php endif; ?>
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
                <a href="/registrar/students/edit/<?= $student['id'] ?>" class="btn btn-warning">✏️ Edit Student</a>
                <?php if ($student['status'] !== 'approved'): ?>
                    <a href="/registrar/students/approve/<?= $student['id'] ?>" class="btn" style="background: #28a745;" onclick="return confirm('Approve this student?')">✅ Approve Student</a>
                <?php endif; ?>
                <?php if ($student['status'] !== 'rejected'): ?>
                    <button class="btn" style="background: #dc3545;" onclick="showRejectForm()">❌ Reject Student</button>
                <?php endif; ?>
                <a href="/registrar/students/delete/<?= $student['id'] ?>" class="btn" style="background: #dc3545;" onclick="return confirm('Are you sure you want to delete this student? This action cannot be undone.')">🗑️ Delete Student</a>
                <a href="/registrar/students" class="btn btn-secondary">← Back to Student List</a>
                <a href="/registrar/dashboard" class="btn btn-secondary">🏠 Back to Dashboard</a>
            </div>
        </div>
        
        <!-- Hidden reject form -->
        <div id="rejectForm" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.3); z-index: 1000; min-width: 400px;">
            <h3>Reject Student: <?= esc($student['full_name']) ?></h3>
            <form method="POST" action="/registrar/students/reject/<?= $student['id'] ?>">
                <div style="margin-bottom: 15px;">
                    <label for="rejection_reason"><strong>Rejection Reason:</strong></label>
                    <textarea name="rejection_reason" id="rejection_reason" 
                              required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; min-height: 100px;"></textarea>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn" style="background: #dc3545;">Confirm Rejection</button>
                    <button type="button" class="btn btn-secondary" onclick="hideRejectForm()">Cancel</button>
                </div>
            </form>
        </div>
        
        <script>
            function showRejectForm() {
                document.getElementById('rejectForm').style.display = 'block';
            }
            
            function hideRejectForm() {
                document.getElementById('rejectForm').style.display = 'none';
            }

            function openDocModal(url) {
                var modal = document.getElementById('docModal');
                var iframe = document.getElementById('docFrame');
                iframe.src = url;
                modal.style.display = 'block';
            }
            function closeDocModal() {
                var modal = document.getElementById('docModal');
                var iframe = document.getElementById('docFrame');
                iframe.src = '';
                modal.style.display = 'none';
            }
        </script>
        
        <!-- Document Modal -->
        <div id="docModal" style="display:none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 2000;">
            <div style="position: absolute; top: 5%; left: 50%; transform: translateX(-50%); width: 90%; max-width: 900px; height: 90%; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
                <div style="display:flex; justify-content: space-between; align-items:center; padding: 10px 15px; background:#f8f9fa; border-bottom:1px solid #e9ecef;">
                    <strong>Document Viewer</strong>
                    <button onclick="closeDocModal()" class="btn btn-secondary">Close</button>
                </div>
                <iframe id="docFrame" src="" style="width:100%; height: calc(100% - 48px); border:0;"></iframe>
            </div>
        </div>
    </div>
</body>
</html>


