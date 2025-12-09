<?php 
    $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
    $isActive = function(string $path) use ($currentPath): bool {
        return strpos($currentPath, $path) === 0;
    };
?>

<nav class="sidebar">
    <div class="nav">
        <!-- Main Navigation -->
        <div class="nav-section">
            <div class="nav-section-title">📚 Main Menu</div>
            <a href="/student/dashboard" class="btn<?= $isActive('/student/dashboard') ? ' active' : '' ?>">🏠 Dashboard</a>
            <a href="/student/attendance" class="btn<?= $isActive('/student/attendance') ? ' active' : '' ?>">📋 My Attendance</a>
        </div>
        
        <!-- Divider -->
        <div class="nav-divider"></div>
        
        <!-- Academic Section -->
        <div class="nav-section">
            <div class="nav-section-title">🎓 Academic</div>
            <a href="/student/grades" class="btn<?= $isActive('/student/grades') ? ' active' : '' ?>">📊 My Grades</a>
        </div>
        
        <!-- Divider -->
        <div class="nav-divider"></div>
        
        <!-- Documents Section -->
        <div class="nav-section">
            <div class="nav-section-title">📄 Documents</div>
            <a href="/student/documents" class="btn<?= $isActive('/student/documents') ? ' active' : '' ?>">🗂️ Document Management</a>
        </div>
        
        <!-- Divider -->
        <div class="nav-divider"></div>
    </div>
</nav>