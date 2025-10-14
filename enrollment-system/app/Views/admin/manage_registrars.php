<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Management - Admin Dashboard</title>
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
            padding: 6px 12px;
            font-size: 12px;
        }
        
        .no-registrars {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .registrar-count {
            background: #e3f2fd;
            border: 1px solid #bbdefb;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .registrar-count h3 {
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
    <?php include __DIR__ . '/partials/sidebar_styles.php'; ?>
</head>
<body>
    <div class="container">
        <?php include __DIR__ . '/partials/layout_start.php'; ?>
        <div class="header">
            <h1>👨‍💼 Registrar Management</h1>
            <p>Manage registrar accounts and permissions</p>
        </div>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        
        <!-- Registrar Count Summary -->
        <div class="card">
            <div class="registrar-count">
                <h3>📊 Registrar Summary</h3>
                <div class="count-grid">
                    <div class="count-item">
                        <div class="count-number"><?= $totalRegistrars ?? 0 ?></div>
                        <div class="count-label">Total Registrars</div>
                    </div>
                    <div class="count-item">
                        <div class="count-number"><?= $activeRegistrars ?? 0 ?></div>
                        <div class="count-label">Active</div>
                    </div>
                    <div class="count-item">
                        <div class="count-number"><?= $inactiveRegistrars ?? 0 ?></div>
                        <div class="count-label">Inactive</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="header-actions">
            <div>
                <a href="/admin/registrars/add" class="btn btn-success">➕ Add Registrar</a>
                <a href="/admin/dashboard" class="btn btn-secondary">🏠 Back to Dashboard</a>
            </div>
            
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search by name, username, or email...">
                <button onclick="searchRegistrars()">🔍 Search</button>
            </div>
        </div>
        
        <div class="card">
            <?php if (!empty($registrars)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Last Login</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registrars as $registrar): ?>
                            <tr>
                                <td><strong><?= esc($registrar['username'] ?? 'N/A') ?></strong></td>
                                <td>
                                    <strong><?= esc($registrar['first_name'] . ' ' . $registrar['last_name']) ?></strong>
                                </td>
                                <td><?= esc($registrar['email'] ?? 'N/A') ?></td>
                                <td>
                                    <span class="status-badge status-<?= $registrar['is_active'] ? 'active' : 'inactive' ?>">
                                        <?= $registrar['is_active'] ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td>
                                    <?= $registrar['last_login'] ? date('M d, Y g:i A', strtotime($registrar['last_login'])) : 'Never' ?>
                                </td>
                                <td><?= esc(date('M d, Y', strtotime($registrar['created_at'] ?? 'now'))) ?></td>
                                <td class="actions">
                                    <a href="/admin/registrars/view/<?= $registrar['id'] ?>" class="btn btn-info">👁️ View</a>
                                    <a href="/admin/registrars/edit/<?= $registrar['id'] ?>" class="btn btn-warning">✏️ Edit</a>
                                    <a href="/admin/registrars/delete/<?= $registrar['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this registrar?')">🗑️ Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-registrars">
                    <h3>👨‍💼 No Registrars Found</h3>
                    <p>There are no registrars in the system yet. Start by adding a registrar account.</p>
                    <a href="/admin/registrars/add" class="btn btn-success">➕ Add First Registrar</a>
                </div>
            <?php endif; ?>
        </div>
        <?php include __DIR__ . '/partials/layout_end.php'; ?>
    </div>
    
    <script>
        function searchRegistrars() {
            const searchTerm = document.getElementById('searchInput').value.trim();
            if (searchTerm) {
                window.location.href = '/admin/registrars?search=' + encodeURIComponent(searchTerm);
            }
        }
        
        // Search on Enter key
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchRegistrars();
            }
        });
        
        // Auto-search after typing (with delay)
        let searchTimeout;
        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(searchRegistrars, 500);
        });
    </script>
</body>
</html>
