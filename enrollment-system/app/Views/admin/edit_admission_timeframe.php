<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Admission Timeframe - Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .header h1 {
            margin: 0;
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
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        
        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-right: 10px;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn:hover {
            background-color: #0056b3;
        }
        
        .btn-secondary {
            background-color: #6c757d;
        }
        
        .btn-secondary:hover {
            background-color: #545b62;
        }
        
        .btn-warning {
            background-color: #ffc107;
            color: #212529;
        }
        
        .btn-warning:hover {
            background-color: #e0a800;
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
            <h1>Edit Admission Timeframe</h1>
            <p>Modify the admission timeframe for <?= esc($timeframe['school_year_name'] ?? 'Unknown School Year') ?></p>
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
            <h3>Edit Timeframe</h3>
            <form method="post" action="/admin/edit-admission-timeframe/<?= $timeframe['id'] ?>">
                <div class="form-group">
                    <label for="school_year_id">School Year *</label>
                    <select name="school_year_id" id="school_year_id" required>
                        <option value="">Select School Year</option>
                        <?php foreach ($schoolYears as $schoolYear): ?>
                            <option value="<?= $schoolYear['id'] ?>" <?= ($schoolYear['id'] == $timeframe['school_year_id']) ? 'selected' : '' ?>>
                                <?= esc($schoolYear['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="start_date">Start Date *</label>
                    <input type="date" name="start_date" id="start_date" value="<?= $timeframe['start_date'] ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="end_date">End Date *</label>
                    <input type="date" name="end_date" id="end_date" value="<?= $timeframe['end_date'] ?>" required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-warning">Update Timeframe</button>
                    <a href="/admin" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="/admin" class="btn btn-secondary">Back to Dashboard</a>
        </div>
        <?php include __DIR__ . '/partials/layout_end.php'; ?>
    </div>
</body>
</html>
