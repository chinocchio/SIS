<?php 
    $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
    $isActive = function(string $prefix) use ($currentPath): bool {
        return strpos($currentPath, $prefix) === 0;
    };
?>
<!-- Face Recognition Section -->
<div class="nav-section">
    <div class="nav-section-title">📷 Face Recognition</div>
    <a href="/face-recognition" class="btn btn-secondary<?= $isActive('/face-recognition') && !$isActive('/face-recognition/capture') ? ' active' : '' ?>">📷 Take Attendance</a>
    <a href="/face-recognition/capture" class="btn btn-secondary<?= $isActive('/face-recognition/capture') ? ' active' : '' ?>">📸 Capture Faces</a>
</div>
