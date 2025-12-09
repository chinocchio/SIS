<?php 
    $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
    $isActive = function(string $prefix) use ($currentPath): bool {
        return strpos($currentPath, $prefix) === 0;
    };
    $isDashboardActive = ($currentPath === '/admin' || $isActive('/admin/dashboard'));
?>
<aside class="sidebar">
    <div class="nav">
        <a href="/admin/dashboard" class="btn<?= $isDashboardActive ? ' active' : '' ?>">Dashboard</a>
        <a href="/admin/registrars" class="btn btn-info<?= $isActive('/admin/registrars') ? ' active' : '' ?>">👨‍💼 Registrars</a>
        <a href="/admin/teachers" class="btn btn-info<?= $isActive('/admin/teachers') && !$isActive('/admin/teachers/assign') ? ' active' : '' ?>">👨‍🏫 Teachers</a>
        <a href="/admin/teachers/assign" class="btn btn-warning<?= $isActive('/admin/teachers/assign') ? ' active' : '' ?>">📋 Assign Teachers</a>
        <a href="/admin/students" class="btn btn-success<?= $isActive('/admin/students') ? ' active' : '' ?>">👥 Students</a>
        <a href="/admin/sections" class="btn btn-warning<?= $isActive('/admin/sections') ? ' active' : '' ?>">🏫 Sections</a>
        <a href="/admin/create-school-year" class="btn<?= $isActive('/admin/create-school-year') ? ' active' : '' ?>">School Years</a>
        <!-- <a href="/admin/create-admission-timeframe" class="btn">Admission Timeframe</a> -->
        <a href="/admin/strands" class="btn btn-warning<?= $isActive('/admin/strands') ? ' active' : '' ?>">Strands & Tracks</a>
        <a href="/admin/curriculums" class="btn<?= $isActive('/admin/curriculums') ? ' active' : '' ?>">Curriculums</a>
        <a href="/admin/subjects" class="btn btn-info<?= $isActive('/admin/subjects') ? ' active' : '' ?>">📚 Subjects</a>
        <!-- <a href="/admin/users" class="btn">Users</a> -->
        <!-- <a href="/auth/logout" class="btn" style="background-color:#dc3545;margin-top:8px;">Logout</a> -->
    </div>
</aside>

