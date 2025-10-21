<?php 
    $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
    $isActive = function(string $prefix) use ($currentPath): bool {
        return strpos($currentPath, $prefix) === 0;
    };
    $isDashboardActive = ($currentPath === '/teacher' || $isActive('/teacher/dashboard'));
?>
<aside class="sidebar">
    <div class="nav">
        <!-- Main Navigation -->
        <!-- <a href="/teacher/dashboard" class="btn<?= $isDashboardActive ? ' active' : '' ?>">🏠 Dashboard</a> -->
        <a href="/teacher/grades" class="btn btn-success<?= $isActive('/teacher/grades') ? ' active' : '' ?>">📊 Grade Management</a>
        <a href="/teacher/attendance" class="btn btn-info<?= $isActive('/teacher/attendance') ? ' active' : '' ?>">📋 Attendance</a>
        <!-- <a href="/teacher/reports" class="btn btn-warning<?= $isActive('/teacher/reports') ? ' active' : '' ?>">📋 Reports</a> -->
        
        <!-- Divider -->
        <div class="nav-divider"></div>
        
        <!-- Face Recognition Section -->
        <?php include __DIR__ . '/face_recognition_section.php'; ?>
        
        <!-- Divider -->
        <div class="nav-divider"></div>
        
        <!-- Quick Actions Section -->
        <!-- <div class="nav-section">
            <div class="nav-section-title">⚡ Quick Actions</div>
            <a href="/teacher/grades" class="btn btn-success">📊 Grade Management</a>
            <a href="/face-recognition" class="btn btn-warning">📷 Face Recognition</a>
            <a href="/face-recognition/capture" class="btn btn-primary">📸 Capture Faces</a>
            <a href="/teacher/attendance" class="btn btn-info">📋 Attendance</a>
        </div> -->
        
        <!-- Divider -->
        <!-- <div class="nav-divider"></div> -->
        
        <!-- Account Section -->
        <!-- <div class="nav-section">
            <div class="nav-section-title">👤 Account</div>
            <a href="/index.php/teacher/change-password" class="btn btn-secondary">🔒 Change Password</a>
        </div> -->
        
        <!-- Logout Button -->
        <!-- <a href="/auth/logout" class="btn logout-btn" onclick="return confirm('Are you sure you want to logout?')">🚪 Logout</a> -->
    </div>
</aside>
