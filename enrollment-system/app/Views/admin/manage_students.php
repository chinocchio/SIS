<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management - Admin Dashboard</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h1>👥 Student Management</h1>
        
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
                    <div class="count-number"><?= $draftStudents ?? 0 ?></div>
                    <div class="count-label">Draft Status</div>
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
                <a href="/admin/students/add" class="btn btn-success">➕ Add Student via SF9</a>
                <a href="/admin/dashboard" class="btn btn-secondary">🏠 Back to Dashboard</a>
            </div>
            
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search by name, LRN, or email...">
                <button onclick="searchStudents()">🔍 Search</button>
            </div>
        </div>
        
        <?php if (!empty($students)): ?>
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
                                    <?= esc(ucfirst($student['status'])) ?>
                                </span>
                            </td>
                            <td><?= esc(date('M d, Y', strtotime($student['created_at'] ?? 'now'))) ?></td>
                            <td class="actions">
                                <a href="/admin/students/view/<?= $student['id'] ?>" class="btn btn-info">👁️ View</a>
                                <a href="/admin/students/edit/<?= $student['id'] ?>" class="btn btn-warning">✏️ Edit</a>
                                <a href="/admin/students/delete/<?= $student['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this student?')">🗑️ Delete</a>
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
                <a href="/admin/students/add" class="btn btn-success">➕ Add First Student</a>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        function searchStudents() {
            const searchTerm = document.getElementById('searchInput').value.trim();
            if (searchTerm) {
                window.location.href = '/admin/students?search=' + encodeURIComponent(searchTerm);
            }
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
