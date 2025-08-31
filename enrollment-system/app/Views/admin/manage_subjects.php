<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject Management - Admin Dashboard</title>
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
        
        .btn-secondary {
            background: #6c757d;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
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
        
        .core-badge {
            background: #fff3cd;
            color: #856404;
        }
        
        .elective-badge {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .actions {
            display: flex;
            gap: 5px;
        }
        
        .actions .btn {
            padding: 6px 12px;
            font-size: 12px;
        }
        
        .no-subjects {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .subject-count {
            background: #e3f2fd;
            border: 1px solid #bbdefb;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .subject-count h3 {
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
        
        .curriculum-filter {
            margin-bottom: 20px;
        }
        
        .curriculum-filter select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            min-width: 200px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📚 Subject Management</h1>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        
        <!-- Subject Count Summary -->
        <div class="subject-count">
            <h3>📊 Subject Summary</h3>
            <div class="count-grid">
                <div class="count-item">
                    <div class="count-number"><?= count($subjects ?? []) ?></div>
                    <div class="count-label">Total Subjects</div>
                </div>
                <div class="count-item">
                    <div class="count-number"><?= count(array_filter($subjects ?? [], function($s) { return $s['is_core'] == 1; })) ?></div>
                    <div class="count-label">Core Subjects</div>
                </div>
                <div class="count-item">
                    <div class="count-number"><?= count(array_filter($subjects ?? [], function($s) { return $s['is_core'] == 0; })) ?></div>
                    <div class="count-label">Elective Subjects</div>
                </div>
                <div class="count-item">
                    <div class="count-number"><?= count($curriculums ?? []) ?></div>
                    <div class="count-label">Curriculums</div>
                </div>
            </div>
        </div>
        
        <div class="header-actions">
            <div>
                <a href="/admin/subjects/add" class="btn btn-success">➕ Add Subject</a>
                <a href="/admin/curriculums" class="btn btn-info">📖 Manage Curriculums</a>
                <a href="/admin/dashboard" class="btn btn-secondary">🏠 Back to Dashboard</a>
            </div>
            
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search by subject code, name, or curriculum...">
                <button onclick="searchSubjects()">🔍 Search</button>
            </div>
        </div>
        
        <!-- Curriculum Filter -->
        <div class="curriculum-filter">
            <label for="curriculumFilter">Filter by Curriculum:</label>
            <select id="curriculumFilter" onchange="filterByCurriculum()">
                <option value="">All Curriculums</option>
                <?php foreach ($curriculums ?? [] as $curriculum): ?>
                    <option value="<?= esc($curriculum['id']) ?>"><?= esc($curriculum['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <?php if (!empty($subjects)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Curriculum</th>
                        <th>Units</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subjects as $subject): ?>
                        <tr data-curriculum="<?= esc($subject['curriculum_id']) ?>">
                            <td><strong><?= esc($subject['code']) ?></strong></td>
                            <td>
                                <strong><?= esc($subject['name']) ?></strong>
                                <?php if (!empty($subject['description'])): ?>
                                    <br><small><?= esc($subject['description']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($subject['curriculum_name']) ?></td>
                            <td><?= esc($subject['units']) ?> unit(s)</td>
                            <td>
                                <span class="status-badge <?= $subject['is_core'] ? 'core-badge' : 'elective-badge' ?>">
                                    <?= $subject['is_core'] ? 'Core' : 'Elective' ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-<?= $subject['is_active'] ? 'active' : 'inactive' ?>">
                                    <?= $subject['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td class="actions">
                                <a href="/admin/subjects/edit/<?= $subject['id'] ?>" class="btn btn-warning">✏️ Edit</a>
                                <a href="/admin/subjects/delete/<?= $subject['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this subject?')">🗑️ Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-subjects">
                <h3>📚 No Subjects Found</h3>
                <p>There are no subjects in the system yet. Start by adding subjects to your curriculums.</p>
                <a href="/admin/subjects/add" class="btn btn-success">➕ Add First Subject</a>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        function searchSubjects() {
            const searchTerm = document.getElementById('searchInput').value.trim();
            if (searchTerm) {
                // Simple client-side search
                const rows = document.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchTerm.toLowerCase())) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
        }
        
        function filterByCurriculum() {
            const curriculumId = document.getElementById('curriculumFilter').value;
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                if (!curriculumId || row.dataset.curriculum === curriculumId) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        // Search on Enter key
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchSubjects();
            }
        });
        
        // Auto-search after typing (with delay)
        let searchTimeout;
        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(searchSubjects, 500);
        });
    </script>
</body>
</html>
