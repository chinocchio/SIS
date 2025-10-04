<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Admin Dashboard - SIS</title>
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
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .card h3 {
            margin-top: 0;
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        
        .status-active {
            color: #28a745;
            font-weight: bold;
        }
        
        .status-inactive {
            color: #dc3545;
            font-weight: bold;
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
        
        .actions {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
        
        .actions .btn {
            margin: 2px;
            font-size: 12px;
            padding: 6px 12px;
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
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="nav" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">
            <div>
                <a href="/admin/dashboard" class="btn">Dashboard</a>
                <a href="/admin/registrars" class="btn btn-info">👨‍💼 Registrars</a>
                <a href="/admin/teachers" class="btn btn-info">👨‍🏫 Teachers</a>
                <a href="/admin/teachers/assign" class="btn btn-warning">📋 Assign Teachers</a>
                <a href="/admin/students" class="btn btn-success">👥 Students</a>
                <a href="/admin/sections" class="btn btn-warning">🏫 Sections</a>
                <a href="/admin/create-school-year" class="btn">School Years</a>
                <!-- <a href="/admin/create-admission-timeframe" class="btn">Admission Timeframe</a> -->
                <a href="/admin/strands" class="btn btn-warning">Strands & Tracks</a>
                <a href="/admin/curriculums" class="btn">Curriculums</a>
                <a href="/admin/subjects" class="btn btn-info">📚 Subjects</a>
                <!-- <a href="/admin/users" class="btn">Users</a> -->
            </div>
            <div>
                <!-- <a href="/auth/change-password" class="btn">Change Password</a> -->
                <a href="/auth/logout" class="btn" style="background-color:#dc3545;">Logout</a>
            </div>
        </div>
        <div class="header">
            <h1>Admin Dashboard</h1>
            <p>Manage School Years, and Student Promotions</p>
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
        
        <div class="dashboard-grid">
            <!-- Current School Year -->
            <div class="card">
                <h3>Current School Year</h3>
                <?php if (isset($activeSchoolYear)): ?>
                    <p><strong>Name:</strong> <?= $activeSchoolYear['name'] ?></p>
                    <p><strong>Start Date:</strong> <?= date('M d, Y', strtotime($activeSchoolYear['start_date'])) ?></p>
                    <p><strong>End Date:</strong> <?= date('M d, Y', strtotime($activeSchoolYear['end_date'])) ?></p>
                    <p><strong>Status:</strong> <span class="status-active">Active</span></p>
                    <p><em>Note: Only one school year can be active at a time.</em></p>
                <?php else: ?>
                    <p>No active school year found.</p>
                    <p><em>You need to activate a school year to manage admissions and student promotions.</em></p>
                <?php endif; ?>
                
                <div style="margin-top: 20px;">
                    <a href="/admin/create-school-year" class="btn">Create New School Year</a>
                </div>
            </div>
            
            <!-- Admission Timeframe -->
            <!-- <div class="card">
                <h3>Admission Timeframe</h3>
                <?php if (isset($admissionTimeframe)): ?>
                    <p><strong>Start Date:</strong> <?= date('M d, Y', strtotime($admissionTimeframe['start_date'])) ?></p>
                    <p><strong>End Date:</strong> <?= date('M d, Y', strtotime($admissionTimeframe['end_date'])) ?></p>
                    <p><strong>Status:</strong> 
                        <?php 
                        $today = date('Y-m-d');
                        $isOpen = ($today >= $admissionTimeframe['start_date'] && $today <= $admissionTimeframe['end_date']);
                        ?>
                        <span class="<?= $isOpen ? 'status-active' : 'status-inactive' ?>">
                            <?= $isOpen ? 'Open' : 'Closed' ?>
                        </span>
                    </p>
                <?php else: ?>
                    <p>No admission timeframe set.</p>
                <?php endif; ?>
                
                <div style="margin-top: 20px;">
                    <a href="/admin/create-admission-timeframe" class="btn">Set Timeframe</a>
                </div>
            </div> -->
            
            <!-- Student Management -->
            <div class="card">
                <h3>Student Management</h3>
                <p>Manage student promotions and grade progression.</p>
                
                <div style="margin-top: 20px;">
                    <a href="/admin/promote-students" class="btn btn-success" onclick="return confirm('This will promote all eligible students to the next grade level. Continue?')">
                        Promote Students
                    </a>
                    <a href="/admin/strands" class="btn btn-warning">Manage Strands</a>
                </div>
            </div>
        </div>
        
        <!-- School Years List -->
        <div class="card">
            <h3>All School Years</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th style="min-width: 200px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($schoolYears)): ?>
                        <?php foreach ($schoolYears as $schoolYear): ?>
                            <tr>
                                <td><?= $schoolYear['name'] ?></td>
                                <td><?= date('M d, Y', strtotime($schoolYear['start_date'])) ?></td>
                                <td><?= date('M d, Y', strtotime($schoolYear['end_date'])) ?></td>
                                <td>
                                    <span class="<?= $schoolYear['is_active'] ? 'status-active' : 'status-inactive' ?>">
                                        <?= $schoolYear['is_active'] ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (!$schoolYear['is_active']): ?>
                                        <a href="/admin/activate-school-year/<?= $schoolYear['id'] ?>" class="btn btn-success">
                                            Activate
                                        </a>
                                    <?php else: ?>
                                        <a href="/admin/deactivate-school-year/<?= $schoolYear['id'] ?>" class="btn btn-warning" onclick="return confirm('Are you sure you want to deactivate this school year? This will remove it as the active school year.')">
                                            Deactivate
                                        </a>
                                    <?php endif; ?>
                                    <a href="/admin/delete-school-year/<?= $schoolYear['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this school year? This action cannot be undone.')">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No school years found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Admission Timeframes List -->
        <!-- <div class="card">
            <h3>All Admission Timeframes</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>School Year</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th style="min-width: 200px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($admissionTimeframes)): ?>
                        <?php foreach ($admissionTimeframes as $timeframe): ?>
                            <tr>
                                <td><?= $timeframe['school_year_name'] ?></td>
                                <td><?= date('M d, Y', strtotime($timeframe['start_date'])) ?></td>
                                <td><?= date('M d, Y', strtotime($timeframe['end_date'])) ?></td>
                                <td>
                                    <?php 
                                    $today = date('Y-m-d');
                                    $isOpen = ($today >= $timeframe['start_date'] && $today <= $timeframe['end_date']);
                                    ?>
                                    <span class="<?= $isOpen ? 'status-active' : 'status-inactive' ?>">
                                        <?= $isOpen ? 'Open' : 'Closed' ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="/admin/edit-admission-timeframe/<?= $timeframe['id'] ?>" class="btn btn-warning">
                                        Edit
                                    </a>
                                    <a href="/admin/delete-admission-timeframe/<?= $timeframe['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this admission timeframe? This action cannot be undone.')">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No admission timeframes found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div> -->
    </div>
</body>
</html>
