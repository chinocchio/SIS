<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Profile - Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f6fb;
        }
        
        .container {
            width: 100%;
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
        
        .btn-warning {
            background: #ffc107;
            color: #212529;
        }
        
        .btn-warning:hover {
            background: #e0a800;
        }
        
        .btn-danger {
            background: #dc3545;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .btn-secondary {
            background: #6c757d;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .profile-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .profile-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }
        
        .profile-section h3 {
            color: #495057;
            margin: 0 0 15px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #dee2e6;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: bold;
            color: #495057;
            min-width: 120px;
        }
        
        .info-value {
            color: #333;
            text-align: right;
            flex: 1;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        
        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        
        .username-display {
            background: #667eea;
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .full-name-display {
            background: #28a745;
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .no-data {
            color: #6c757d;
            font-style: italic;
        }
        
        .actions-section {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #bbdefb;
        }
        
        .actions-section h3 {
            color: #1976d2;
            margin: 0 0 15px 0;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
    </style>
    <?php include __DIR__ . '/partials/sidebar_styles.php'; ?>
</head>
<body>
    <div class="container">
        <?php include __DIR__ . '/partials/layout_start.php'; ?>
        <div class="header">
            <h1>👨‍💼 Registrar Profile</h1>
            <div>
                <a href="/admin/registrars/edit/<?= $registrar['id'] ?>" class="btn btn-warning">✏️ Edit Registrar</a>
                <a href="/admin/registrars" class="btn btn-secondary">← Back to Registrars</a>
            </div>
        </div>
        
        <!-- Username Display -->
        <div class="username-display">
            Username: <?= esc($registrar['username']) ?>
        </div>
        
        <!-- Full Name Display -->
        <div class="full-name-display">
            <?= esc($registrar['first_name'] . ' ' . $registrar['last_name']) ?>
        </div>
        
        <div class="profile-grid">
            <!-- Personal Information -->
            <div class="profile-section">
                <h3>📋 Personal Information</h3>
                <div class="info-row">
                    <span class="info-label">First Name:</span>
                    <span class="info-value"><?= esc($registrar['first_name']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Last Name:</span>
                    <span class="info-value"><?= esc($registrar['last_name']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value"><?= esc($registrar['email']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Role:</span>
                    <span class="info-value"><?= esc(ucfirst($registrar['role'])) ?></span>
                </div>
            </div>
            
            <!-- Account Information -->
            <div class="profile-section">
                <h3>🔐 Account Information</h3>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value">
                        <span class="status-badge status-<?= $registrar['is_active'] ? 'active' : 'inactive' ?>">
                            <?= $registrar['is_active'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Last Login:</span>
                    <span class="info-value">
                        <?= $registrar['last_login'] ? date('F d, Y \a\t g:i A', strtotime($registrar['last_login'])) : '<span class="no-data">Never</span>' ?>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Created Date:</span>
                    <span class="info-value">
                        <?= $registrar['created_at'] ? date('F d, Y \a\t g:i A', strtotime($registrar['created_at'])) : '<span class="no-data">Not specified</span>' ?>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Last Updated:</span>
                    <span class="info-value">
                        <?= $registrar['updated_at'] ? date('F d, Y \a\t g:i A', strtotime($registrar['updated_at'])) : '<span class="no-data">Not specified</span>' ?>
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Actions Section -->
        <div class="actions-section">
            <h3>⚡ Quick Actions</h3>
            <div class="action-buttons">
                <a href="/admin/registrars/edit/<?= $registrar['id'] ?>" class="btn btn-warning">✏️ Edit Registrar</a>
                <a href="/admin/registrars/delete/<?= $registrar['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this registrar? This action cannot be undone.')">🗑️ Delete Registrar</a>
                <a href="/admin/registrars" class="btn btn-secondary">← Back to Registrar List</a>
                <a href="/admin/dashboard" class="btn btn-secondary">🏠 Back to Dashboard</a>
            </div>
        </div>
        <?php include __DIR__ . '/partials/layout_end.php'; ?>
    </div>
</body>
</html>


