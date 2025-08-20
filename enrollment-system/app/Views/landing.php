<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Enrollment Portal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        
        h1 {
            color: #333;
            margin-bottom: 30px;
            font-size: 2.5em;
        }
        
        .description {
            color: #666;
            margin-bottom: 40px;
            line-height: 1.6;
        }
        
        .btn {
            display: inline-block;
            padding: 15px 30px;
            margin: 10px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #545b62;
            transform: translateY(-2px);
        }
        
        .btn-tertiary {
            background-color: #28a745;
            color: white;
        }
        
        .btn-tertiary:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }
        
        .features {
            margin-top: 40px;
            text-align: left;
        }
        
        .features h3 {
            color: #333;
            margin-bottom: 20px;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            color: #555;
        }
        
        .feature-item:before {
            content: "✓";
            color: #28a745;
            font-weight: bold;
            margin-right: 10px;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎓 School Enrollment Portal</h1>
        <p class="description">
            Welcome to our comprehensive Student Information System. 
            Apply for admission, manage your academic journey, and track your progress.
        </p>
        
        <div>
            <a href="/admission/enroll" class="btn btn-primary">📝 Apply for Admission</a>
            <a href="/auth/login" class="btn btn-secondary">🔐 Staff Login</a>
            <a href="/student/login" class="btn btn-tertiary">👨‍🎓 Student Login</a>
        </div>
        
        <div class="features">
            <h3>✨ What We Offer:</h3>
            <div class="feature-item">Smart admission type detection</div>
            <div class="feature-item">Dynamic strand selection for SHS</div>
            <div class="feature-item">Grade progression tracking</div>
            <div class="feature-item">Comprehensive academic management</div>
        </div>
    </div>
</body>
</html>