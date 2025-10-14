<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Management - Admin Dashboard</title>
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
        
        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
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
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        
        tr:hover {
            background-color: #f5f5f5;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
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
        
        .actions {
            display: flex;
            gap: 5px;
        }
        
        .actions .btn {
            margin: 0;
            padding: 5px 10px;
            font-size: 12px;
        }
        
        .no-teachers {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        
        .teacher-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .teacher-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #667eea;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 16px;
        }
        
        .teacher-details h4 {
            margin: 0;
            color: #333;
        }
        
        .teacher-details p {
            margin: 0;
            color: #6c757d;
            font-size: 14px;
        }
        
        .assignment-count {
            background: #e3f2fd;
            color: #1976d2;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .subjects-list {
            font-size: 12px;
            color: #6c757d;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
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
    </style>
    <?php include __DIR__ . '/partials/sidebar_styles.php'; ?>
</head>
<body>
    <div class="container">
        <?php include __DIR__ . '/partials/layout_start.php'; ?>
        <div class="header">
            <h1>👨‍🏫 Teacher Management</h1>
            <p>Manage teacher accounts and subject assignments</p>
            <div style="margin-top: 15px;">
                <a href="/admin/teachers/add" class="btn btn-success">➕ Add Teacher</a>
                <a href="/admin/dashboard" class="btn">🏠 Dashboard</a>
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
        
        <div class="card">
            <?php if (!empty($teachers)): ?>
                <div class="table-responsive">
                <table>
                <thead>
                    <tr>
                        <th>Teacher</th>
                        <th>Email</th>
                        <th>Specialization</th>
                        <th>Status</th>
                        <th>Assignments</th>
                        <th>Subjects</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($teachers as $teacher): ?>
                        <tr>
                            <td>
                                <div class="teacher-info">
                                    <div class="teacher-avatar">
                                        <?= strtoupper(substr($teacher['first_name'], 0, 1) . substr($teacher['last_name'], 0, 1)) ?>
                                    </div>
                                    <div class="teacher-details">
                                        <h4><?= esc($teacher['first_name'] . ' ' . $teacher['last_name']) ?></h4>
                                        <p>ID: <?= $teacher['id'] ?></p>
                                    </div>
                                </div>
                            </td>
                            <td><?= esc($teacher['email']) ?></td>
                            <td><?= esc($teacher['specialization'] ?? 'Not specified') ?></td>
                            <td>
                                <span class="status-badge status-<?= $teacher['is_active'] ? 'active' : 'inactive' ?>">
                                    <?= $teacher['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td>
                                <span class="assignment-count">
                                    <?= $teacher['assignment_count'] ?> assignment(s)
                                </span>
                            </td>
                            <td>
                                <div class="subjects-list" title="<?= esc($teacher['subjects'] ?? 'No subjects assigned') ?>">
                                    <?= esc($teacher['subjects'] ?? 'No subjects assigned') ?>
                                </div>
                            </td>
                            <td class="actions">
                                <a href="/admin/teachers/view/<?= $teacher['id'] ?>" class="btn btn-info">👁️ View</a>
                                <a href="/admin/teachers/edit/<?= $teacher['id'] ?>" class="btn btn-warning">✏️ Edit</a>
                                <a href="/admin/teachers/delete/<?= $teacher['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this teacher? This action cannot be undone.')">🗑️ Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
                </div>
                
                <!-- Pagination -->
                <?php if (isset($pager)): ?>
                    <div class="pagination">
                        <?= $pager->links() ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="no-teachers">
                    <h3>👨‍🏫 No Teachers Found</h3>
                    <p>There are no teachers in the system yet. Start by adding a teacher.</p>
                    <a href="/admin/teachers/add" class="btn btn-success">➕ Add First Teacher</a>
                </div>
            <?php endif; ?>
        </div>
        <?php include __DIR__ . '/partials/layout_end.php'; ?>
    </div>
</body>
</html>
