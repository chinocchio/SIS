<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Dashboard - SIS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
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
            padding: 0 20px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-card h3 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 1.5em;
        }
        
        .stat-card .number {
            font-size: 2.5em;
            font-weight: bold;
            color: #667eea;
        }
        
        .stat-card .label {
            color: #666;
            margin-top: 5px;
        }
        
        .pending-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .pending-section h2 {
            margin-top: 0;
            color: #333;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        
        .student-list {
            display: grid;
            gap: 15px;
        }
        
        .student-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            background-color: #fafafa;
        }
        
        .student-card h4 {
            margin: 0 0 10px 0;
            color: #333;
        }
        
        .student-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .info-item {
            display: flex;
            align-items: center;
        }
        
        .info-item strong {
            margin-right: 10px;
            color: #555;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        
        .btn-approve {
            background-color: #28a745;
            color: white;
        }
        
        .btn-approve:hover {
            background-color: #218838;
        }
        
        .btn-reject {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-reject:hover {
            background-color: #c82333;
        }
        
        .btn-view {
            background-color: #17a2b8;
            color: white;
        }
        
        .btn-view:hover {
            background-color: #138496;
        }
        
        .no-pending {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 40px;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
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
    <div class="header">
        <h1>📋 Registrar Dashboard</h1>
        <p>Welcome, <?= session()->get('first_name') ?> <?= session()->get('last_name') ?></p>
    </div>
    
    <div class="nav">
        <div>
            <a href="/registrar/dashboard">Dashboard</a>
            <a href="/registrar/enrollments/pending">Pending Enrollments</a>
            <a href="/registrar/enrollments/approved">Approved</a>
            <a href="/registrar/enrollments/rejected">Rejected</a>
            <a href="/registrar/search">Search Students</a>
            <a href="/registrar/report">Generate Report</a>
        </div>
        <div>
            <a href="/auth/change-password">Change Password</a>
            <a href="/auth/logout" class="logout">Logout</a>
        </div>
    </div>
    
    <div class="container">
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
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Students</h3>
                <div class="number"><?= $totalStudents ?></div>
                <div class="label">Enrolled Students</div>
            </div>
            
            <div class="stat-card">
                <h3>Pending</h3>
                <div class="number"><?= $pendingCount ?></div>
                <div class="label">Awaiting Approval</div>
            </div>
            
            <div class="stat-card">
                <h3>Approved</h3>
                <div class="number"><?= $approvedCount ?></div>
                <div class="label">Enrollment Approved</div>
            </div>
            
            <div class="stat-card">
                <h3>Rejected</h3>
                <div class="number"><?= $rejectedCount ?></div>
                <div class="label">Enrollment Rejected</div>
            </div>
        </div>
        
        <div class="pending-section">
            <h2>🚨 Pending Enrollments (<?= count($pendingEnrollments) ?>)</h2>
            
            <?php if (empty($pendingEnrollments)): ?>
                <div class="no-pending">
                    <h3>🎉 No pending enrollments!</h3>
                    <p>All student applications have been processed.</p>
                </div>
            <?php else: ?>
                <div class="student-list">
                    <?php foreach ($pendingEnrollments as $student): ?>
                        <div class="student-card">
                            <h4><?= $student['first_name'] ?> <?= $student['last_name'] ?></h4>
                            
                            <div class="student-info">
                                <div class="info-item">
                                    <strong>Email:</strong>
                                    <span><?= $student['email'] ?></span>
                                </div>
                                <div class="info-item">
                                    <strong>Grade Level:</strong>
                                    <span>Grade <?= $student['grade_level'] ?></span>
                                </div>
                                <div class="info-item">
                                    <strong>Admission Type:</strong>
                                    <span><?= ucfirst($student['admission_type']) ?></span>
                                </div>
                                <div class="info-item">
                                    <strong>Applied:</strong>
                                    <span><?= date('M d, Y', strtotime($student['created_at'])) ?></span>
                                </div>
                            </div>
                            
                            <div class="action-buttons">
                                <a href="/registrar/student/<?= $student['id'] ?>" class="btn btn-view">View Details</a>
                                <form method="post" action="/registrar/approve/<?= $student['id'] ?>" style="display: inline;">
                                    <button type="submit" class="btn btn-approve" onclick="return confirm('Approve this enrollment?')">
                                        ✅ Approve
                                    </button>
                                </form>
                                <button class="btn btn-reject" onclick="showRejectForm(<?= $student['id'] ?>)">
                                    ❌ Reject
                                </button>
                            </div>
                            
                            <!-- Hidden reject form -->
                            <div id="rejectForm<?= $student['id'] ?>" style="display: none; margin-top: 15px;">
                                <form method="post" action="/registrar/reject/<?= $student['id'] ?>">
                                    <div style="margin-bottom: 10px;">
                                        <label for="rejection_reason<?= $student['id'] ?>"><strong>Rejection Reason:</strong></label>
                                        <textarea name="rejection_reason" id="rejection_reason<?= $student['id'] ?>" 
                                                  required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-reject">Confirm Rejection</button>
                                    <button type="button" class="btn" onclick="hideRejectForm(<?= $student['id'] ?>)" style="background-color: #6c757d; color: white;">Cancel</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        function showRejectForm(studentId) {
            document.getElementById('rejectForm' + studentId).style.display = 'block';
        }
        
        function hideRejectForm(studentId) {
            document.getElementById('rejectForm' + studentId).style.display = 'none';
        }
    </script>
</body>
</html>
