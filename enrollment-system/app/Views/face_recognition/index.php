<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Face Recognition - SIS</title>
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
        
        .btn-secondary {
            background: #6c757d;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
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
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 6px;
        }
        
        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .alert-error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        .subjects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .subject-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .subject-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .subject-card h3 {
            margin-top: 0;
            color: #495057;
        }
        
        .subject-info {
            margin-bottom: 15px;
        }
        
        .subject-info p {
            margin: 5px 0;
            color: #6c757d;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        
        .no-data h4 {
            margin-bottom: 10px;
        }
        
        .quick-actions {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
        
        .quick-actions h3 {
            margin-bottom: 15px;
            color: #495057;
        }
        
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        @media (max-width: 768px) {
            .subjects-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn {
                margin-left: 0;
                margin-top: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📷 Face Recognition Attendance</h1>
            <div>
                <a href="/teacher/dashboard" class="btn btn-secondary">🏠 Dashboard</a>
                <a href="/auth/logout" class="btn btn-secondary">🚪 Logout</a>
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
        
        <div class="teacher-info">
            <h2>Welcome, <?= esc(session()->get('first_name', 'Teacher') . ' ' . session()->get('last_name', '')) ?>!</h2>
            <p>Select a subject to start face recognition attendance.</p>
        </div>
        
        <?php if (!empty($subjects)): ?>
            <div class="subjects-section">
                <h3>📚 Your Assigned Subjects</h3>
                <div class="subjects-grid">
                    <?php foreach ($subjects as $subject): ?>
                        <div class="subject-card">
                            <h3><?= esc($subject['name']) ?></h3>
                            <div class="subject-info">
                                <p><strong>Code:</strong> <?= esc($subject['code']) ?></p>
                                <p><strong>Section:</strong> <?= esc($subject['section_name']) ?></p>
                            </div>
                            <div class="action-buttons">
                                <a href="/face-recognition/attendance/<?= $subject['id'] ?>" class="btn btn-success">
                                    📷 Start Attendance
                                </a>
                                <a href="/face-recognition/students/<?= $subject['id'] ?>" class="btn btn-warning" onclick="showStudents(<?= $subject['id'] ?>)">
                                    👥 View Students
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="no-data">
                <h4>📚 No Subject Assignments Found</h4>
                <p>You haven't been assigned to any subjects yet.</p>
                <p><strong>Contact your administrator to get subject assignments.</strong></p>
            </div>
        <?php endif; ?>
        
        <!-- Quick Actions -->
        <div class="quick-actions">
            <h3>⚡ Quick Actions</h3>
            <div class="action-buttons">
                <a href="/face-recognition/test" class="btn btn-warning">🧪 Test Camera</a>
                <a href="/teacher/dashboard" class="btn btn-secondary">🏠 Teacher Dashboard</a>
                <a href="/teacher/attendance" class="btn btn-secondary">📋 View Attendance Records</a>
                <a href="/teacher/grades" class="btn btn-secondary">📊 Grade Management</a>
                <a href="/auth/change-password" class="btn btn-secondary">🔒 Change Password</a>
            </div>
        </div>
    </div>

    <script>
        function showStudents(subjectId) {
            fetch(`/face-recognition/students/${subjectId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let message = `Students in this subject:\n\n`;
                        data.students.forEach(student => {
                            const faceStatus = student.has_face_encoding ? '✅' : '❌';
                            message += `${faceStatus} ${student.name} (LRN: ${student.lrn})\n`;
                        });
                        alert(message);
                    } else {
                        alert('Failed to load students');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading students');
                });
        }
    </script>
</body>
</html>
