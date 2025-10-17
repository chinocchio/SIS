<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Curriculums - Admin</title>
    <style>
        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fc;
            color: #5a5c69;
        }
        
        .container {
            width: 100%;
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
        
        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
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
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
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
            text-decoration: none;
            display: inline-block;
        }
        
        .btn:hover {
            background-color: #0056b3;
        }
        
        .btn-success {
            background-color: #28a745;
        }
        
        .btn-success:hover {
            background-color: #1e7e34;
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
        
        .btn-secondary {
            background-color: #6c757d;
        }
        
        .btn-secondary:hover {
            background-color: #545b62;
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
        
        .track-badge {
            padding: 3px 6px;
            border-radius: 8px;
            font-size: 11px;
            background-color: #fff3cd;
            color: #856404;
        }
        
        .main-content {
            padding: 1.5rem;
            min-height: 100vh;
        }
    </style>
    <?php include __DIR__ . '/partials/sidebar_styles.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/partials/layout_start.php'; ?>
        
        <div class="header">
            <h1>Manage Curriculums</h1>
            <p>Add, edit, and manage curriculum types for JHS and SHS students</p>
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
        
        <!-- Add New Curriculum Form -->
        <div class="card">
            <h3>Add New Curriculum</h3>
            <form method="post" action="/admin/curriculums">
                                 <div class="form-group">
                     <label for="name">Curriculum Name *</label>
                     <input type="text" name="name" id="name" required placeholder="e.g., Basic Education Curriculum">
                 </div>
                    
                    <div class="form-group">
                        <label for="is_active">Status</label>
                        <select name="is_active" id="is_active">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" rows="3" placeholder="Brief description of the curriculum"></textarea>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Add Curriculum</button>
                    <button type="reset" class="btn btn-secondary">Reset Form</button>
                </div>
            </form>

            <div class="card">
            <h3>All Curriculums</h3>
            <table class="table">
                                 <thead>
                     <tr>
                         <th>Name</th>
                         <th>Description</th>
                         <th>Status</th>
                         <th style="min-width: 150px;">Actions</th>
                     </tr>
                 </thead>
                <tbody>
                    <?php if (isset($curriculums) && !empty($curriculums)): ?>
                        <?php foreach ($curriculums as $curriculum): ?>
                                                         <tr>
                                 <td><strong><?= esc($curriculum['name']) ?></strong></td>
                                 <td><?= esc($curriculum['description'] ?: 'No description') ?></td>
                                 <td>
                                     <span class="<?= $curriculum['is_active'] ? 'status-active' : 'status-inactive' ?>">
                                         <?= $curriculum['is_active'] ? 'Active' : 'Inactive' ?>
                                     </span>
                                 </td>
                                 <td>
                                     <button class="btn btn-warning btn-sm" onclick="editCurriculum(<?= $curriculum['id'] ?>, '<?= esc($curriculum['name']) ?>', '<?= esc($curriculum['description']) ?>', <?= $curriculum['is_active'] ?>)">
                                         Edit
                                     </button>
                                     <a href="/admin/curriculums/delete/<?= $curriculum['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this curriculum? This action cannot be undone.')">
                                         Delete
                                     </a>
                                 </td>
                             </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                                                 <tr>
                             <td colspan="4">No curriculums found.</td>
                         </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        </div>
        
        <!-- Curriculums List -->

        
        <!-- Edit Curriculum Modal -->
        <div id="editModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
            <div class="modal-content" style="background-color: white; margin: 5% auto; padding: 20px; border-radius: 8px; width: 80%; max-width: 600px;">
                <h3>Edit Curriculum</h3>
                <form id="editForm" method="post">
                    <div class="form-group">
                        <label for="edit_name">Curriculum Name *</label>
                        <input type="text" name="name" id="edit_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_is_active">Status</label>
                        <select name="is_active" id="edit_is_active">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_description">Description</label>
                        <textarea name="description" id="edit_description" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-warning">Update Curriculum</button>
                        <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        
    <?php include __DIR__ . '/partials/layout_end.php'; ?>
    
    <script>
        function editCurriculum(id, name, description, isActive) {
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_description').value = description || '';
            document.getElementById('edit_is_active').value = isActive;
            
            // Set form action
            document.getElementById('editForm').action = `/admin/curriculums/edit/${id}`;
            
            // Show modal
            document.getElementById('editModal').style.display = 'block';
        }
        
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>
