<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create School Year - Admin</title>
    <?php include __DIR__ . '/partials/sidebar_styles.php'; ?>
    <style>
        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fc;
            color: #5a5c69;
        }
        
        .page-heading {
            margin-bottom: 1.5rem;
        }
        
        .page-heading h1 {
            font-size: 1.75rem;
            font-weight: 400;
            color: #5a5c69;
            margin: 0;
        }
        
        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border-left: 0.25rem solid #4e73df;
        }
        
        .header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e3e6f0;
        }
        
        .header h1 {
            color: #4e73df;
            margin-bottom: 0.5rem;
            font-size: 1.5rem;
            font-weight: 400;
        }
        
        .header p {
            color: #858796;
            margin: 0;
            font-size: 0.9rem;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #5a5c69;
            font-size: 0.875rem;
        }
        
        .form-group input {
            width: 100%;
            padding: 0.625rem 0.75rem;
            border: 1px solid #d1d3e2;
            border-radius: 0.35rem;
            font-size: 0.875rem;
            color: #6e707e;
            background-color: #fff;
            box-sizing: border-box;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #bac8f3;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
            background-color: #fff;
        }
        
        .form-group small {
            color: #858796;
            font-size: 0.8rem;
            margin-top: 0.25rem;
            display: block;
        }
        
        .actions {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e3e6f0;
        }
        
        .actions .btn {
            margin: 0 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php include __DIR__ . '/partials/layout_start.php'; ?>
        <div class="form-container">
        <div class="header">
            <h1>Create New School Year</h1>
            <p>Set up a new academic year for the school</p>
        </div>
        
        <form method="post" action="/admin/create-school-year">
            <div class="form-group">
                <label for="name">School Year Name *</label>
                <input type="text" name="name" id="name" placeholder="e.g., 2025-2026" required>
                <small>Format: YYYY-YYYY (e.g., 2025-2026)</small>
            </div>
            
            <div class="form-group">
                <label for="start_date">Start Date *</label>
                <input type="date" name="start_date" id="start_date" required>
            </div>
            
            <div class="form-group">
                <label for="end_date">End Date *</label>
                <input type="date" name="end_date" id="end_date" required>
            </div>
            
            <div class="actions">
                <button type="submit" class="btn">Create School Year</button>
                <a href="/admin" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
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
        <?php include __DIR__ . '/partials/layout_end.php'; ?>
    </div>
</body>
</html>
