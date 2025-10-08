<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management - Registrar Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f6fb;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header h1 {
            margin: 0;
            font-size: 2em;
        }
        
        .header p {
            margin: 5px 0 0 0;
            opacity: 0.9;
        }
        
        .nav {
            background: white;
            padding: 15px 20px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .nav a {
            color: #667eea;
            text-decoration: none;
            margin-right: 20px;
            padding: 8px 16px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        
        .nav a:hover {
            background-color: #f0f0f0;
        }
        
        .nav .logout {
            background-color: #dc3545;
            color: white;
        }
        
        .nav .logout:hover {
            background-color: #c82333;
        }
        
        .container {
            max-width: 1200px;
            margin: 20px auto;
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
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .btn {
            background: #667eea;
            color: white;
            padding: 12px 24px;
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
        
        .btn-warning {
            background: #ffc107;
            color: #212529;
        }
        
        .btn-warning:hover {
            background: #e0a800;
        }
        
        .btn-danger {
            background: #dc3545;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .btn-info {
            background: #17a2b8;
        }
        
        .btn-info:hover {
            background: #138496;
        }
        
        .search-box {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .search-box input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 250px;
        }
        
        .search-box button {
            padding: 8px 16px;
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-box button:hover {
            background: #5a6268;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        th {
            background: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        
        tr:hover {
            background: #f8f9fa;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
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
        
        .actions {
            display: flex;
            gap: 5px;
        }
        
        .actions .btn {
            padding: 6px 12px;
            font-size: 12px;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            gap: 10px;
        }
        
        .pagination a {
            padding: 8px 12px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #667eea;
            border-radius: 4px;
        }
        
        .pagination a:hover {
            background: #667eea;
            color: white;
        }
        
        .pagination .active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        
        .no-students {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .student-count {
            background: #e3f2fd;
            border: 1px solid #bbdefb;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .student-count h3 {
            margin: 0 0 10px 0;
            color: #1976d2;
        }
        
        .count-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }
        
        .count-item {
            text-align: center;
            padding: 10px;
            background: white;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }
        
        .count-number {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }
        
        .count-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
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
         <div class="header" style="display:flex;justify-content:space-between;align-items:center;">
         <div>
             <h1>👥 Student Management</h1>
             <p>Welcome, <?= session()->get('first_name') ?> <?= session()->get('last_name') ?> (<?= ucfirst(session()->get('role')) ?>)</p>
         </div>
         <div>
             <a href="/index.php/registrar/change-password" class="nav-link" style="margin-right:10px;color:#fff;text-decoration:underline;padding:8px 12px;border-radius:6px;background-color:rgba(255,255,255,0.2);transition:background-color 0.3s;">Change Password</a>
             <a href="/auth/logout" class="logout" style="padding:8px 12px;border-radius:6px;background-color:#dc3545;color:white;text-decoration:none;">Logout</a>
         </div>
     </div>
    
    <div class="nav">
        <div>
            <a href="/registrar/students">👥 Student Management</a>
            <!-- <a href="/registrar/enrollments/pending">Pending Enrollments</a>
            <a href="/registrar/enrollments/approved">Approved</a>
            <a href="/registrar/enrollments/rejected">Rejected</a>
            <a href="/registrar/search">Search Students</a>
            <a href="/registrar/report">Generate Report</a> -->
        </div>
    </div>
    
    <div class="container">
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        
        <!-- Student Count Summary -->
        <div class="student-count">
            <h3>📊 Student Summary</h3>
            <div class="count-grid">
                <div class="count-item">
                    <div class="count-number"><?= $totalStudents ?? 0 ?></div>
                    <div class="count-label">Total Students</div>
                </div>
                <div class="count-item">
                    <div class="count-number"><?= $rejectedStudents ?? 0 ?></div>
                    <div class="count-label">Rejected</div>
                </div>
                <div class="count-item">
                    <div class="count-number"><?= $pendingStudents ?? 0 ?></div>
                    <div class="count-label">Pending Approval</div>
                </div>
                <div class="count-item">
                    <div class="count-number"><?= $approvedStudents ?? 0 ?></div>
                    <div class="count-label">Approved</div>
                </div>
            </div>
        </div>
        
                 <div class="header-actions">
             <div>
                 <a href="/registrar/students/add" class="btn btn-success">➕ Add Student via SF9</a>
             </div>
             
             <div class="search-box">
                 <input type="text" id="searchInput" placeholder="Search by name, LRN, or email..." value="<?= esc($search ?? '') ?>">
                 <button onclick="searchStudents()">🔍 Search</button>
             </div>
         </div>
         
         <!-- Filters Section -->
         <div class="filters-section" style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
             <?php 
             $activeFilters = [];
             if (!empty($status_filter)) $activeFilters[] = "Status: " . ucfirst($status_filter);
             if (!empty($grade_filter)) $activeFilters[] = "Grade: " . $grade_filter;
             if (!empty($enrollment_filter)) $activeFilters[] = "Enrollment: " . ucfirst($enrollment_filter);
             if (!empty($admission_filter)) $activeFilters[] = "Admission: " . ucfirst($admission_filter);
             if (!empty($search)) $activeFilters[] = "Search: " . $search;
             ?>
             
             <?php if (!empty($activeFilters)): ?>
             <div style="background: #e3f2fd; padding: 10px; border-radius: 6px; margin-bottom: 15px;">
                 <strong>Active Filters:</strong> <?= implode(' | ', $activeFilters) ?>
                 <a href="/registrar/students" style="margin-left: 10px; color: #dc3545; text-decoration: none;">[Clear All]</a>
             </div>
             <?php endif; ?>
             <h4 style="margin: 0 0 15px 0; color: #495057;">🔍 Filters</h4>
             <form method="GET" action="/registrar/students" id="filterForm">
                 <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                     <div>
                         <label for="status_filter" style="display: block; margin-bottom: 5px; font-weight: bold; color: #495057;">Status:</label>
                         <select name="status" id="status_filter" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                             <option value="">All Status</option>
                             <option value="pending" <?= ($status_filter ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                             <option value="approved" <?= ($status_filter ?? '') === 'approved' ? 'selected' : '' ?>>Approved</option>
                             <option value="rejected" <?= ($status_filter ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                             <option value="draft" <?= ($status_filter ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                         </select>
                     </div>
                     
                     <div>
                         <label for="grade_filter" style="display: block; margin-bottom: 5px; font-weight: bold; color: #495057;">Grade Level:</label>
                         <select name="grade_level" id="grade_filter" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                             <option value="">All Grades</option>
                             <option value="7" <?= ($grade_filter ?? '') === '7' ? 'selected' : '' ?>>Grade 7</option>
                             <option value="8" <?= ($grade_filter ?? '') === '8' ? 'selected' : '' ?>>Grade 8</option>
                             <option value="9" <?= ($grade_filter ?? '') === '9' ? 'selected' : '' ?>>Grade 9</option>
                             <option value="10" <?= ($grade_filter ?? '') === '10' ? 'selected' : '' ?>>Grade 10</option>
                             <option value="11" <?= ($grade_filter ?? '') === '11' ? 'selected' : '' ?>>Grade 11</option>
                             <option value="12" <?= ($grade_filter ?? '') === '12' ? 'selected' : '' ?>>Grade 12</option>
                         </select>
                     </div>
                     
                     <div>
                         <label for="enrollment_filter" style="display: block; margin-bottom: 5px; font-weight: bold; color: #495057;">Enrollment Type:</label>
                         <select name="enrollment_type" id="enrollment_filter" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                             <option value="">All Types</option>
                             <option value="new" <?= ($enrollment_filter ?? '') === 'new' ? 'selected' : '' ?>>New</option>
                             <option value="transferee" <?= ($enrollment_filter ?? '') === 'transferee' ? 'selected' : '' ?>>Transferee</option>
                             <option value="returning" <?= ($enrollment_filter ?? '') === 'returning' ? 'selected' : '' ?>>Returning</option>
                         </select>
                     </div>
                     
                     <div>
                         <label for="admission_filter" style="display: block; margin-bottom: 5px; font-weight: bold; color: #495057;">Admission Type:</label>
                         <select name="admission_type" id="admission_filter" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                             <option value="">All Types</option>
                             <option value="regular" <?= ($admission_filter ?? '') === 'regular' ? 'selected' : '' ?>>Regular</option>
                             <option value="transferee" <?= ($admission_filter ?? '') === 'transferee' ? 'selected' : '' ?>>Transferee</option>
                             <option value="re-enroll" <?= ($admission_filter ?? '') === 're-enroll' ? 'selected' : '' ?>>Re-enroll</option>
                             <option value="promoted" <?= ($admission_filter ?? '') === 'promoted' ? 'selected' : '' ?>>Promoted</option>
                         </select>
                     </div>
                 </div>
                 
                 <div style="display: flex; gap: 10px; margin-top: 15px;">
                     <button type="submit" class="btn" style="background: #667eea;">Apply Filters</button>
                     <button type="button" class="btn btn-secondary" onclick="clearFilters()">Clear Filters</button>
                     <button type="button" class="btn btn-info" onclick="exportFilteredStudents()">Export Results</button>
                 </div>
             </form>
         </div>
        
                 <?php if (!empty($students)): ?>
             <div style="background: #d4edda; padding: 10px; border-radius: 6px; margin-bottom: 15px;">
                 <strong>📊 Results:</strong> Showing <?= count($students) ?> student(s) 
                 <?php if (!empty($activeFilters)): ?>
                     matching your filters
                 <?php endif; ?>
             </div>
             <table>
                <thead>
                    <tr>
                        <th>LRN</th>
                        <th>Full Name</th>
                        <th>Grade Level</th>
                        <th>Enrollment Type</th>
                        <th>Status</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><strong><?= esc($student['lrn'] ?? 'N/A') ?></strong></td>
                            <td>
                                <strong><?= esc($student['full_name'] ?? 'N/A') ?></strong>
                            </td>
                            <td>Grade <?= esc($student['grade_level']) ?></td>
                            <td><?= esc(ucfirst($student['enrollment_type'] ?? 'N/A')) ?></td>
                            <td>
                                <span class="status-badge status-<?= strtolower($student['status']) ?>">
                                    <?= esc(ucfirst($student['status'] === 'draft' ? 'pending' : $student['status'])) ?>
                                </span>
                                <?php if (($student['status'] ?? '') === 'approved' && !empty($student['approved_by'])): ?>
                                    <br><small>Approved by: Registrar <?= esc($student['approved_by']) ?></small>
                                <?php endif; ?>
                                <?php if (($student['status'] ?? '') === 'rejected' && !empty($student['rejected_by'])): ?>
                                    <br><small>Rejected by: Registrar <?= esc($student['rejected_by']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?= esc(date('M d, Y', strtotime($student['created_at'] ?? 'now'))) ?></td>
                                                         <td class="actions">
                                 <a href="/registrar/students/view/<?= $student['id'] ?>" class="btn btn-info">👁️ View</a>
                                 <a href="/registrar/students/edit/<?= $student['id'] ?>" class="btn btn-warning">✏️ Edit</a>
                                 <?php if ($student['status'] !== 'approved'): ?>
                                     <a href="/registrar/students/approve/<?= $student['id'] ?>" class="btn btn-success" onclick="return confirm('Approve this student?')">✅ Approve</a>
                                 <?php endif; ?>
                                 <?php if ($student['status'] !== 'rejected'): ?>
                                     <button class="btn btn-danger" onclick="showRejectForm(<?= $student['id'] ?>)">❌ Reject</button>
                                 <?php endif; ?>
                                 <a href="/registrar/students/delete/<?= $student['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this student?')">🗑️ Delete</a>
                             </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <!-- Pagination -->
            <?php if (isset($pager)): ?>
                <div class="pagination">
                    <?= $pager->links() ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="no-students">
                <h3>📚 No Students Found</h3>
                <p>There are no students in the system yet. Start by adding a student via SF9 upload.</p>
                <a href="/registrar/students/add" class="btn btn-success">➕ Add First Student</a>
            </div>
        <?php endif; ?>
        
        <!-- Hidden reject forms -->
        <?php foreach ($students as $student): ?>
            <div id="rejectForm<?= $student['id'] ?>" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.3); z-index: 1000; min-width: 400px;">
                <h3>Reject Student: <?= esc($student['full_name']) ?></h3>
                <form method="POST" action="/registrar/students/reject/<?= $student['id'] ?>">
                    <div style="margin-bottom: 15px;">
                        <label for="rejection_reason<?= $student['id'] ?>"><strong>Rejection Reason:</strong></label>
                        <textarea name="rejection_reason" id="rejection_reason<?= $student['id'] ?>" 
                                  required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; min-height: 100px;"></textarea>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                        <button type="button" class="btn btn-secondary" onclick="hideRejectForm(<?= $student['id'] ?>)">Cancel</button>
                    </div>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
    
    <script>
        function showRejectForm(studentId) {
            document.getElementById('rejectForm' + studentId).style.display = 'block';
        }
        
        function hideRejectForm(studentId) {
            document.getElementById('rejectForm' + studentId).style.display = 'none';
        }
        
                 function searchStudents() {
             const searchTerm = document.getElementById('searchInput').value.trim();
             const currentUrl = new URL(window.location.href);
             
             if (searchTerm) {
                 currentUrl.searchParams.set('search', searchTerm);
             } else {
                 currentUrl.searchParams.delete('search');
             }
             
             window.location.href = currentUrl.toString();
         }
         
         function clearFilters() {
             window.location.href = '/registrar/students';
         }
         
         function exportFilteredStudents() {
             const currentUrl = new URL(window.location.href);
             currentUrl.searchParams.set('export', '1');
             window.location.href = currentUrl.toString();
         }
         
         // Search on Enter key
         document.getElementById('searchInput').addEventListener('keypress', function(e) {
             if (e.key === 'Enter') {
                 searchStudents();
             }
         });
         
         // Auto-search after typing (with delay)
         let searchTimeout;
         document.getElementById('searchInput').addEventListener('input', function() {
             clearTimeout(searchTimeout);
             searchTimeout = setTimeout(searchStudents, 500);
         });
    </script>
</body>
</html>
