<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Records - Teacher Dashboard</title>
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
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            text-align: center;
        }
        
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 14px;
        }
        
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .attendance-table th,
        .attendance-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .attendance-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        
        .attendance-table tr:hover {
            background-color: #f5f5f5;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-style: italic;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-present {
            background: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 Attendance Records</h1>
            <div>
                <a href="/teacher/dashboard" class="btn btn-secondary">← Back to Dashboard</a>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= count($attendance) ?></div>
                <div class="stat-label">Total Records</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= count(array_unique(array_column($attendance, 'student_id'))) ?></div>
                <div class="stat-label">Unique Students</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= count(array_unique(array_column($attendance, 'subject_id'))) ?></div>
                <div class="stat-label">Subjects</div>
            </div>
        </div>
        
        <!-- Attendance Table -->
        <div>
            <h3>📋 Recent Attendance Records</h3>
            
            <?php if (!empty($attendance)): ?>
                <table class="attendance-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>LRN</th>
                            <th>Subject</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attendance as $record): ?>
                            <tr>
                                <td>
                                    <strong><?= esc($record['student_name']) ?></strong>
                                </td>
                                <td><?= esc($record['lrn']) ?></td>
                                <td>
                                    <strong><?= esc($record['subject_name']) ?></strong>
                                    <br><small><?= esc($record['subject_code']) ?></small>
                                </td>
                                <td>
                                    <?= date('M d, Y', strtotime($record['recorded_at'])) ?>
                                    <br><small><?= date('g:i A', strtotime($record['recorded_at'])) ?></small>
                                </td>
                                <td>
                                    <span class="status-badge status-present">Present</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <h4>📊 No Attendance Records</h4>
                    <p>No attendance records found for your assigned subjects.</p>
                    <p>Students will appear here once they use the face recognition system.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
