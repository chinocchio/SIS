<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Section Management - Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
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
        
        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 5px;
            border: none;
            cursor: pointer;
        }
        
        .btn:hover {
            background-color: #0056b3;
        }
        
        .btn-success {
            background-color: #28a745;
        }
        
        .btn-success:hover {
            background-color: #1e7e34;
        }
        
        .btn-warning {
            background-color: #ffc107;
            color: #212529;
        }
        
        .btn-warning:hover {
            background-color: #e0a800;
        }
        
        .btn-danger {
            background-color: #dc3545;
        }
        
        .btn-danger:hover {
            background-color: #c82333;
        }
        
        .btn-info {
            background-color: #17a2b8;
        }
        
        .btn-info:hover {
            background-color: #138496;
        }
        
        .summary-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #007bff;
        }
        
        .stat-label {
            color: #666;
            margin-top: 5px;
        }
        
        .filters {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .filters select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }
        
        .filters button {
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .table-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        .table tr:hover {
            background-color: #f5f5f5;
        }
        
        .actions {
            display: flex;
            gap: 5px;
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
        
        .capacity-indicator {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            font-weight: bold;
        }
        
        .capacity-low {
            background-color: #d4edda;
            color: #155724;
        }
        
        .capacity-medium {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .capacity-high {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
    <?php include __DIR__ . '/partials/sidebar_styles.php'; ?>
</head>
<body>
    <div class="container">
        <?php include __DIR__ . '/partials/layout_start.php'; ?>
        
        <div class="header">
            <h1>🏫 Section Management</h1>
            <p>Manage class sections, capacity, and student assignments</p>
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
        
        <!-- Summary Statistics -->
        <div class="summary-stats">
            <div class="stat-card">
                <div class="stat-number"><?= $totalSections ?? 0 ?></div>
                <div class="stat-label">Total Sections</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $totalStudents ?? 0 ?></div>
                <div class="stat-label">Total Students</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $currentSchoolYear['name'] ?? 'N/A' ?></div>
                <div class="stat-label">Current School Year</div>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="filters">
            <form method="GET" action="/admin/sections">
                <select name="school_year_id">
                    <option value="">All School Years</option>
                    <?php foreach ($schoolYears as $schoolYear): ?>
                        <option value="<?= $schoolYear['id'] ?>" <?= ($selectedSchoolYear == $schoolYear['id']) ? 'selected' : '' ?>>
                            <?= $schoolYear['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Filter</button>
                <a href="/admin/sections/add" class="btn btn-success">➕ Add New Section</a>
            </form>
        </div>
        
        <!-- Sections Table -->
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Section Name</th>
                        <th>Grade Level</th>
                        <th>Strand</th>
                        <th>School Year</th>
                        <th>Students</th>
                        <th>Capacity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($sections)): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px;">
                                <p>No sections found for the selected school year.</p>
                                <a href="/admin/sections/add" class="btn btn-success">Create First Section</a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($sections as $section): ?>
                            <?php 
                            $capacityPercentage = $section['capacity_max'] > 0 ? ($section['student_count'] / $section['capacity_max']) * 100 : 0;
                            $capacityClass = $capacityPercentage < 70 ? 'capacity-low' : ($capacityPercentage < 90 ? 'capacity-medium' : 'capacity-high');
                            ?>
                            <tr>
                                <td><strong><?= $section['name'] ?></strong></td>
                                <td>Grade <?= $section['grade_level'] ?></td>
                                <td><?= $section['strand_name'] ?? 'N/A' ?></td>
                                <td><?= $section['school_year'] ?></td>
                                <td><?= $section['student_count'] ?></td>
                                <td>
                                    <span class="capacity-indicator <?= $capacityClass ?>">
                                        <?= $section['student_count'] ?>/<?= $section['capacity_max'] ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($section['student_count'] >= $section['capacity_max']): ?>
                                        <span style="color: #dc3545; font-weight: bold;">Full</span>
                                    <?php elseif ($section['student_count'] >= $section['capacity_min']): ?>
                                        <span style="color: #28a745; font-weight: bold;">Optimal</span>
                                    <?php else: ?>
                                        <span style="color: #ffc107; font-weight: bold;">Under Capacity</span>
                                    <?php endif; ?>
                                </td>
                                <td class="actions">
                                    <a href="/admin/sections/view/<?= $section['id'] ?>" class="btn btn-info" title="View Details">👁️</a>
                                    <a href="/admin/sections/edit/<?= $section['id'] ?>" class="btn btn-warning" title="Edit Section">✏️</a>
                                    <a href="/admin/sections/delete/<?= $section['id'] ?>" class="btn btn-danger" title="Delete Section" 
                                       onclick="return confirm('Are you sure you want to delete this section? This action cannot be undone.')">🗑️</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php include __DIR__ . '/partials/layout_end.php'; ?>
    </div>
</body>
</html>
