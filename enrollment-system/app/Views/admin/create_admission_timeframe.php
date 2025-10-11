<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admission Timeframe - Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #007bff;
            margin-bottom: 10px;
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
        
        .form-group select,
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        
        .form-group select:focus,
        .form-group input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
        }
        
        .btn {
            background-color: #007bff;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
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
        
        .actions {
            text-align: center;
            margin-top: 30px;
        }
        
        .info-box {
            background-color: #e7f3ff;
            border: 1px solid #b3d9ff;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .info-box h4 {
            margin-top: 0;
            color: #0056b3;
        }
    </style>
    <?php include __DIR__ . '/partials/sidebar_styles.php'; ?>
</head>
<body>
    <div class="container">
        <?php include __DIR__ . '/partials/layout_start.php'; ?>
        <div class="header">
            <h1>Create Admission Timeframe</h1>
            <p>Set the period when students can apply for admission</p>
        </div>
        
        <div class="info-box">
            <h4>Important Information</h4>
            <p>This timeframe will control when students can submit admission applications. Outside of this period, the admission form will be closed.</p>
        </div>
        
        <form method="post" action="/admin/create-admission-timeframe">
            <div class="form-group">
                <label for="school_year_id">School Year *</label>
                <select name="school_year_id" id="school_year_id" required>
                    <option value="">-- Select School Year --</option>
                    <?php if (isset($schoolYears)): ?>
                        <?php foreach ($schoolYears as $schoolYear): ?>
                            <option value="<?= $schoolYear['id'] ?>"><?= $schoolYear['name'] ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="start_date">Admission Start Date *</label>
                <input type="date" name="start_date" id="start_date" required>
            </div>
            
            <div class="form-group">
                <label for="end_date">Admission End Date *</label>
                <input type="date" name="end_date" id="end_date" required>
            </div>
            
            <div class="actions">
                <button type="submit" class="btn">Create Timeframe</button>
                <a href="/admin" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
        <?php include __DIR__ . '/partials/layout_end.php'; ?>
    </div>
    
    <script>
        // Set minimum dates
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('start_date').min = today;
        document.getElementById('end_date').min = today;
        
        // Ensure end date is after start date
        document.getElementById('start_date').addEventListener('change', function() {
            document.getElementById('end_date').min = this.value;
        });
    </script>
</body>
</html>
