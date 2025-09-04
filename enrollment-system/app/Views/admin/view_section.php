<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Section - Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .container {
            max-width: 1200px;
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
        
        .section-details {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #007bff;
        }
        
        .section-title {
            font-size: 1.8em;
            color: #007bff;
            margin: 0;
        }
        
        .section-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .stat-item {
            text-align: center;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
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
        
        .section-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .info-item {
            padding: 10px 0;
        }
        
        .info-label {
            font-weight: bold;
            color: #333;
        }
        
        .info-value {
            color: #666;
        }
        
        .capacity-indicator {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.9em;
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
        
        .students-section {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .students-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #28a745;
        }
        
        .students-title {
            font-size: 1.5em;
            color: #28a745;
            margin: 0;
        }
        
        .table-container {
            overflow-x: auto;
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
        
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .empty-state h3 {
            margin-bottom: 10px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <div>
                <a href="/admin/dashboard" class="btn">Dashboard</a>
                <a href="/admin/registrars" class="btn btn-info">👨‍💼 Registrars</a>
                <a href="/admin/students" class="btn btn-success">👥 Students</a>
                <a href="/admin/sections" class="btn btn-warning">🏫 Sections</a>
                <a href="/admin/create-school-year" class="btn">School Years</a>
                <a href="/admin/create-admission-timeframe" class="btn">Admission Timeframe</a>
                <a href="/admin/strands" class="btn btn-warning">Strands & Tracks</a>
                <a href="/admin/curriculums" class="btn">Curriculums</a>
                <a href="/admin/subjects" class="btn btn-info">📚 Subjects</a>
                <a href="/admin/users" class="btn">Users</a>
            </div>
            <div>
                <a href="/auth/change-password" class="btn">Change Password</a>
                <a href="/auth/logout" class="btn btn-danger">Logout</a>
            </div>
        </div>
        
        <div class="header">
            <h1>👁️ Section Details</h1>
            <p>View section information and enrolled students</p>
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
        
        <!-- Section Details -->
        <div class="section-details">
            <div class="section-header">
                <h2 class="section-title"><?= $section['name'] ?></h2>
                <div class="actions">
                    <a href="/admin/sections/edit/<?= $section['id'] ?>" class="btn btn-warning">✏️ Edit Section</a>
                    <a href="/admin/sections" class="btn">← Back to Sections</a>
                </div>
            </div>
            
            <!-- Section Statistics -->
            <div class="section-stats">
                <div class="stat-item">
                    <div class="stat-number"><?= $capacity['current'] ?></div>
                    <div class="stat-label">Enrolled Students</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?= $capacity['min'] ?></div>
                    <div class="stat-label">Minimum Capacity</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?= $capacity['max'] ?></div>
                    <div class="stat-label">Maximum Capacity</div>
                </div>
                <div class="stat-item">
                    <?php 
                    $capacityPercentage = $capacity['max'] > 0 ? ($capacity['current'] / $capacity['max']) * 100 : 0;
                    $capacityClass = $capacityPercentage < 70 ? 'capacity-low' : ($capacityPercentage < 90 ? 'capacity-medium' : 'capacity-high');
                    ?>
                    <div class="stat-number">
                        <span class="capacity-indicator <?= $capacityClass ?>">
                            <?= round($capacityPercentage, 1) ?>%
                        </span>
                    </div>
                    <div class="stat-label">Capacity Used</div>
                </div>
            </div>
            
            <!-- Section Information -->
            <div class="section-info">
                <div class="info-item">
                    <div class="info-label">Grade Level:</div>
                    <div class="info-value">Grade <?= $section['grade_level'] ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">School Year:</div>
                    <div class="info-value"><?= $section['school_year'] ?></div>
                </div>
                <?php if ($section['strand_name']): ?>
                    <div class="info-item">
                        <div class="info-label">Strand:</div>
                        <div class="info-value"><?= $section['strand_name'] ?></div>
                    </div>
                <?php endif; ?>
                <div class="info-item">
                    <div class="info-label">Status:</div>
                    <div class="info-value">
                        <?php if ($capacity['current'] >= $capacity['max']): ?>
                            <span style="color: #dc3545; font-weight: bold;">Full</span>
                        <?php elseif ($capacity['current'] >= $capacity['min']): ?>
                            <span style="color: #28a745; font-weight: bold;">Optimal</span>
                        <?php else: ?>
                            <span style="color: #ffc107; font-weight: bold;">Under Capacity</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Students List -->
        <div class="students-section">
            <div class="students-header">
                <h3 class="students-title">Enrolled Students (<?= count($students) ?>)</h3>
            </div>
            
            <?php if (empty($students)): ?>
                <div class="empty-state">
                    <h3>No Students Enrolled</h3>
                    <p>This section currently has no enrolled students.</p>
                    <a href="/admin/students" class="btn btn-success">Manage Students</a>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>LRN</th>
                                <th>Status</th>
                                <th>Enrollment Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><strong><?= $student['full_name'] ?></strong></td>
                                    <td><?= $student['lrn'] ?></td>
                                    <td>
                                        <span style="color: <?= $student['status'] == 'approved' ? '#28a745' : ($student['status'] == 'pending' ? '#ffc107' : '#dc3545') ?>; font-weight: bold;">
                                            <?= ucfirst($student['status']) ?>
                                        </span>
                                    </td>
                                    <td><?= ucfirst($student['enrollment_type'] ?? 'N/A') ?></td>
                                    <td class="actions">
                                        <a href="/admin/students/view/<?= $student['id'] ?>" class="btn btn-info" title="View Student">👁️</a>
                                        <a href="/admin/students/edit/<?= $student['id'] ?>" class="btn btn-warning" title="Edit Student">✏️</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
