<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Strands & Tracks - Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .container {
            max-width: 1400px;
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
        
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
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
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        
        .form-group textarea {
            height: 80px;
            resize: vertical;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .checkbox-group input[type="checkbox"] {
            width: auto;
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
        
        .btn-danger {
            background-color: #dc3545;
        }
        
        .btn-danger:hover {
            background-color: #c82333;
        }
        
        .btn-success {
            background-color: #28a745;
        }
        
        .btn-success:hover {
            background-color: #1e7e34;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        .status-active {
            color: #28a745;
            font-weight: bold;
        }
        
        .status-inactive {
            color: #dc3545;
            font-weight: bold;
        }
        
        .actions {
            display: flex;
            gap: 5px;
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
        
        .level-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .level-jhs {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        
        .level-shs {
            background-color: #f3e5f5;
            color: #7b1fa2;
        }
        
        .track-section {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .track-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .track-title {
            font-size: 1.1rem;
            font-weight: bold;
            color: #333;
        }
        
        .strand-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .strand-item {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .strand-info {
            flex: 1;
        }
        
        .strand-name {
            font-weight: bold;
            color: #333;
        }
        
        .strand-description {
            color: #666;
            font-size: 0.9rem;
            margin-top: 4px;
        }
        
        .strand-actions {
            display: flex;
            gap: 5px;
        }
        
        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }
    </style>
    <?php include __DIR__ . '/partials/sidebar_styles.php'; ?>
</head>
<body>
    <div class="container">
        <?php include __DIR__ . '/partials/layout_start.php'; ?>
        <div class="header">
            <h1>Manage Strands & Tracks</h1>
            <p>Add, edit, and manage tracks and their associated strands</p>
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
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <?= $error ?>
            </div>
        <?php endif; ?>
        
        <div class="content-grid">
            <!-- Track Management -->
            <div class="card">
                <h3>Track Management</h3>
                
                <!-- Add New Track Form -->
                <form method="post" action="/admin/strands/add-track" id="trackForm">
                    <input type="hidden" name="track_id" id="track_id">
                    
                    <div class="form-group">
                        <label for="track_name">Track Name *</label>
                        <input type="text" name="track_name" id="track_name" placeholder="e.g., Academic Track, TVL Track" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="track_level">Level *</label>
                        <select name="track_level" id="track_level" required>
                            <option value="">Select Level</option>
                            <option value="jhs">Junior High School (JHS)</option>
                            <option value="shs">Senior High School (SHS)</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="track_description">Description</label>
                        <textarea name="track_description" id="track_description" placeholder="Brief description of the track"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" name="track_is_active" id="track_is_active" value="1" checked>
                            <label for="track_is_active">Active</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-success" id="trackSubmitBtn">Add Track</button>
                        <button type="button" class="btn btn-secondary" onclick="resetTrackForm()">Clear</button>
                    </div>
                </form>
            </div>
            
            <!-- Strand Management -->
            <div class="card">
                <h3>Strand Management</h3>
                
                <!-- Add New Strand Form -->
                <form method="post" action="/admin/addStrand" id="strandForm">
                    <input type="hidden" name="strand_id" id="strand_id">
                    
                    <div class="form-group">
                        <label for="track_id">Track *</label>
                        <select name="track_id" id="track_id" required>
                            <option value="">-- Select Track --</option>
                            <?php if (isset($tracks)): ?>
                                <?php foreach ($tracks as $track): ?>
                                    <option value="<?= $track['id'] ?>"><?= esc($track['name']) ?> (<?= strtoupper($track['level']) ?>)</option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="name">Strand Name *</label>
                        <input type="text" name="name" id="name" placeholder="e.g., STEM, ABM, HUMSS" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" placeholder="Brief description of the strand"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" name="is_active" id="is_active" value="1" checked>
                            <label for="is_active">Active</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn" id="strandSubmitBtn">Add Strand</button>
                        <button type="button" class="btn btn-secondary" onclick="resetStrandForm()">Clear</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Tracks and Strands Display -->
        <div class="card">
            <h3>Current Tracks and Strands</h3>
            
            <?php if (isset($tracks) && !empty($tracks)): ?>
                <?php foreach ($tracks as $track): ?>
                    <div class="track-section">
                        <div class="track-header">
                            <div>
                                <span class="track-title"><?= esc($track['name']) ?></span>
                                <span class="level-badge level-<?= $track['level'] ?>"><?= strtoupper($track['level']) ?></span>
                                <span class="<?= $track['is_active'] ? 'status-active' : 'status-inactive' ?>">
                                    <?= $track['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </div>
                            <div class="actions">
                                <button class="btn btn-warning btn-sm" onclick="editTrack(<?= htmlspecialchars(json_encode($track)) ?>)">Edit Track</button>
                                <a href="/admin/strands/delete-track/<?= $track['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this track? This action cannot be undone.')">Delete Track</a>
                            </div>
                        </div>
                        
                        <?php if (isset($track['description']) && $track['description']): ?>
                            <p style="margin-bottom: 15px; color: #666;"><?= esc($track['description']) ?></p>
                        <?php endif; ?>
                        
                        <ul class="strand-list">
                            <?php 
                            $trackStrands = [];
                            if (isset($strands)) {
                                foreach ($strands as $strand) {
                                    if ($strand['track_id'] == $track['id']) {
                                        $trackStrands[] = $strand;
                                    }
                                }
                            }
                            ?>
                            
                            <?php if (!empty($trackStrands)): ?>
                                <?php foreach ($trackStrands as $strand): ?>
                                    <li class="strand-item">
                                        <div class="strand-info">
                                            <div class="strand-name"><?= esc($strand['name']) ?></div>
                                            <?php if ($strand['description']): ?>
                                                <div class="strand-description"><?= esc($strand['description']) ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="strand-actions">
                                            <span class="<?= $strand['is_active'] ? 'status-active' : 'status-inactive' ?>">
                                                <?= $strand['is_active'] ? 'Active' : 'Inactive' ?>
                                            </span>
                                            <button class="btn btn-warning btn-sm" onclick="editStrand(<?= htmlspecialchars(json_encode($strand)) ?>)">Edit</button>
                                            <a href="/admin/deleteStrand/<?= $strand['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this strand?')">Delete</a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="strand-item">
                                    <div class="strand-info">
                                        <em>No strands assigned to this track yet.</em>
                                    </div>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No tracks found. Please create a track first.</p>
            <?php endif; ?>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="/admin" class="btn btn-secondary">Back to Dashboard</a>
        </div>
        <?php include __DIR__ . '/partials/layout_end.php'; ?>
    </div>
    
    <script>
        // Track form management
        function editTrack(track) {
            // Update form title and button
            document.querySelector('.card h3').textContent = 'Edit Track';
            document.getElementById('trackSubmitBtn').textContent = 'Update Track';
            
            // Fill form with track data
            document.getElementById('track_id').value = track.id;
            document.getElementById('track_name').value = track.name;
            document.getElementById('track_level').value = track.level;
            document.getElementById('track_description').value = track.description || '';
            document.getElementById('track_is_active').checked = track.is_active == 1;
            
            // Change form action to edit
            document.getElementById('trackForm').action = '/admin/strands/edit-track/' + track.id;
        }
        
        function resetTrackForm() {
            // Reset form title and button
            document.querySelector('.card h3').textContent = 'Track Management';
            document.getElementById('trackSubmitBtn').textContent = 'Add Track';
            
            // Clear form
            document.getElementById('trackForm').reset();
            document.getElementById('track_id').value = '';
            
            // Reset form action to add
            document.getElementById('trackForm').action = '/admin/strands/add-track';
        }
        
        // Strand form management
        function editStrand(strand) {
            // Update form title and button
            document.querySelectorAll('.card h3')[1].textContent = 'Edit Strand';
            document.getElementById('strandSubmitBtn').textContent = 'Update Strand';
            
            // Fill form with strand data
            document.getElementById('strand_id').value = strand.id;
            document.getElementById('track_id').value = strand.track_id || '';
            document.getElementById('name').value = strand.name;
            document.getElementById('description').value = strand.description || '';
            document.getElementById('is_active').checked = strand.is_active == 1;
            
            // Change form action to edit
            document.getElementById('strandForm').action = '/admin/editStrand/' + strand.id;
        }
        
        function resetStrandForm() {
            // Reset form title and button
            document.querySelectorAll('.card h3')[1].textContent = 'Strand Management';
            document.getElementById('strandSubmitBtn').textContent = 'Add Strand';
            
            // Clear form
            document.getElementById('strandForm').reset();
            document.getElementById('strand_id').value = '';
            
            // Reset form action to add
            document.getElementById('strandForm').action = '/admin/addStrand';
        }
    </script>
</body>
</html>
