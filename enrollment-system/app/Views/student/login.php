<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: #fff;
            width: 95%;
            max-width: 420px;
            padding: 32px;
            border-radius: 14px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        }
        .title {
            margin: 0 0 8px 0;
            color: #333;
            text-align: center;
        }
        .subtitle {
            margin: 0 0 24px 0;
            color: #666;
            text-align: center;
        }
        .form-group { margin-bottom: 16px; }
        label { display: block; margin-bottom: 6px; color: #333; font-weight: bold; }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            box-sizing: border-box;
            transition: border-color .2s ease;
        }
        input:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102,126,234,.12); }
        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: transform .15s ease;
        }
        .btn:hover { transform: translateY(-2px); }
        .links { margin-top: 16px; text-align: center; }
        .links a { color: #667eea; text-decoration: none; margin: 0 8px; }
        .links a:hover { text-decoration: underline; }
        .alert { padding: 12px 14px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>
    </head>
<body>
    <div class="container">
        <h2 class="title">Student Login</h2>
        <p class="subtitle">Access your dashboard to view grades, attendance, and documents.</p>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <form method="post" action="/auth/authenticate">
            <div class="form-group">
                <label for="lrn">LRN (Learner Reference Number)</label>
                <input type="text" id="lrn" name="username" placeholder="Enter your 12-digit LRN" required maxlength="12" pattern="[0-9]{12}">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Your password" required>
            </div>
            <button class="btn" type="submit">🔐 Login</button>
        </form>

        <div class="links">
            <a href="/">Home</a>
            <a href="/admission/enroll">Apply for Admission</a>
        </div>
        
        <div style="background: #e3f2fd; padding: 12px; border-radius: 8px; margin-top: 16px; font-size: 13px;">
            <p style="margin: 0 0 8px 0; font-weight: bold; color: #1976d2;">📋 Student Login Info:</p>
            <p style="margin: 0; color: #666;">• Use your 12-digit LRN (Learner Reference Number)</p>
            <p style="margin: 0; color: #666;">• Your LRN can be found on your report card or school records</p>
            <p style="margin: 0; color: #666;">• Contact your registrar if you don't have your LRN</p>
        </div>
    </div>
    
    <script>
        // Simple LRN validation - only allow digits
        document.getElementById('lrn').addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, ''); // Remove non-digits
        });
    </script>
</body>
</html>
