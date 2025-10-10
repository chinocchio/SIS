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
            <h3>📋 Attendance Records</h3>
            
            <?php if (!empty($attendance)): ?>
                <?php
                    // Group attendance by student
                    $studentAttendance = [];
                    $allDates = [];
                    
                    foreach ($attendance as $record) {
                        $studentKey = $record['student_id'];
                        $date = date('Y-m-d', strtotime($record['recorded_at']));
                        
                        if (!isset($studentAttendance[$studentKey])) {
                            $studentAttendance[$studentKey] = [
                                'name' => $record['student_name'],
                                'lrn' => $record['lrn'],
                                'dates' => []
                            ];
                        }
                        
                        $studentAttendance[$studentKey]['dates'][$date] = 'Present';
                        
                        if (!in_array($date, $allDates)) {
                            $allDates[] = $date;
                        }
                    }
                    
                    // Sort dates
                    sort($allDates);
                ?>
                
                <div style="overflow-x: auto;">
                    <table class="attendance-table">
                        <thead>
                            <tr>
                                <th style="position: sticky; left: 0; background: #f8f9fa; z-index: 10;">Student Name</th>
                                <th style="position: sticky; left: 200px; background: #f8f9fa; z-index: 10;">LRN</th>
                                <?php foreach ($allDates as $date): ?>
                                    <th style="text-align: center; min-width: 100px;">
                                        <?= date('M d', strtotime($date)) ?><br>
                                        <small><?= date('Y', strtotime($date)) ?></small>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($studentAttendance as $student): ?>
                                <tr>
                                    <td style="position: sticky; left: 0; background: white; z-index: 5;">
                                        <strong><?= esc($student['name']) ?></strong>
                                    </td>
                                    <td style="position: sticky; left: 200px; background: white; z-index: 5;">
                                        <?= esc($student['lrn']) ?>
                                    </td>
                                    <?php foreach ($allDates as $date): ?>
                                        <td style="text-align: center;">
                                            <?php if (isset($student['dates'][$date])): ?>
                                                <span class="status-badge status-present">✓</span>
                                            <?php else: ?>
                                                <span style="color: #dc3545;">—</span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
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
