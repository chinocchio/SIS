<?php 
$pageTitle = 'Document Management - Student Dashboard';
include __DIR__ . '/partials/layout_start.php'; 
?>

<!-- Page Header -->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">🗂️ Document Management</h1>
    </div>

    <!-- Document Upload Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">📄 Upload New Document</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="/student/submit-document" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="document_type">Document Type:</label>
                                    <select name="document_type" id="document_type" required class="form-control">
                                        <option value="">Select document type...</option>
                                        <option value="birth_certificate">Birth Certificate</option>
                                        <option value="report_card">Report Card (SF9)</option>
                                        <option value="good_moral">Certificate of Good Moral Character</option>
                                        <option value="form_137">Form 137</option>
                                        <option value="id_picture">ID Picture</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="document_file">File:</label>
                                    <input type="file" name="document_file" id="document_file" required accept=".jpg,.jpeg,.png,.pdf" class="form-control">
                                    <small style="color: #6c757d;">Only JPG, PNG, and PDF files are allowed. Maximum size: 5MB</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="description">Description (Optional):</label>
                                    <textarea name="description" id="description" placeholder="Add any additional notes about this document..." class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">📤 Upload Document</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents List Section -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">🗂️ Submitted Documents</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($documents)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Uploaded At</th>
                                        <th>Description</th>
                                        <th>File</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($documents as $doc): ?>
                                        <tr>
                                            <td>
                                                <strong><?= esc(ucfirst(str_replace('_', ' ', $doc['document_type']))) ?></strong>
                                            </td>
                                            <td>
                                                <span class="status-badge status-<?= esc(strtolower($doc['status'])) ?>"><?= esc(ucfirst($doc['status'])) ?></span>
                                            </td>
                                            <td><?= date('M d, Y g:i A', strtotime($doc['uploaded_at'])) ?></td>
                                            <td>
                                                <?= $doc['description'] ? esc($doc['description']) : '<span class="text-muted">No description</span>' ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    $fileExt = strtolower(pathinfo($doc['file_path'], PATHINFO_EXTENSION));
                                                    $viewUrl = base_url('/student/document/view/' . $doc['id']);
                                                    $downloadUrl = base_url('/student/document/download/' . $doc['id']);
                                                ?>
                                                <div class="btn-group" role="group">
                                                    <a href="#" onclick="openDocModal('<?= $viewUrl ?>'); return false;" class="btn btn-info btn-sm">👁️ View</a>
                                                    <a href="<?= $downloadUrl ?>" class="btn btn-success btn-sm">📥 Download</a>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if ($doc['status'] === 'draft' || $doc['status'] === 'pending'): ?>
                                                    <a href="/student/document/delete/<?= $doc['id'] ?>" 
                                                       class="btn btn-danger btn-sm"
                                                       onclick="return confirm('Are you sure you want to delete this document? This action cannot be undone.')">
                                                        🗑️ Delete
                                                    </a>
                                                <?php else: ?>
                                                    <span style="color: #6c757d; font-style: italic; font-size: 12px;">Cannot delete approved documents</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="no-data">
                            <h4>📄 No Documents Uploaded</h4>
                            <p>You haven't uploaded any documents yet.</p>
                            <p>Use the upload form above to submit your required documents.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Document Requirements Info -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">📋 Document Requirements</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Required Documents:</h6>
                            <ul class="list-unstyled">
                                <li>📄 Birth Certificate</li>
                                <li>📊 Report Card (SF9)</li>
                                <li>📜 Certificate of Good Moral Character</li>
                                <li>📋 Form 137</li>
                                <li>📸 ID Picture</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>File Requirements:</h6>
                            <ul class="list-unstyled">
                                <li>✅ JPG, PNG, or PDF format</li>
                                <li>✅ Maximum file size: 5MB</li>
                                <li>✅ Clear and readable</li>
                                <li>✅ Complete document</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Document Modal -->
<div id="docModal" style="display:none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 2000;">
    <div style="position: absolute; top: 5%; left: 50%; transform: translateX(-50%); width: 90%; max-width: 900px; height: 90%; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
        <div style="display:flex; justify-content: space-between; align-items:center; padding: 10px 15px; background:#f8f9fa; border-bottom:1px solid #e9ecef;">
            <strong>Document Viewer</strong>
            <button onclick="closeDocModal()" class="btn btn-secondary">Close</button>
        </div>
        <iframe id="docFrame" src="" style="width:100%; height: calc(100% - 48px); border:0;"></iframe>
    </div>
</div>

<script>
    function openDocModal(url) {
        var modal = document.getElementById('docModal');
        var iframe = document.getElementById('docFrame');
        iframe.src = url;
        modal.style.display = 'block';
    }
    function closeDocModal() {
        var modal = document.getElementById('docModal');
        var iframe = document.getElementById('docFrame');
        iframe.src = '';
        modal.style.display = 'none';
    }
</script>

<style>
.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
}

.status-draft {
    background: #fff3cd;
    color: #856404;
}

.status-pending {
    background: #d1ecf1;
    color: #0c5460;
}

.status-approved {
    background: #d4edda;
    color: #155724;
}

.status-rejected {
    background: #f8d7da;
    color: #721c24;
}

.no-data {
    color: #6c757d;
    font-style: italic;
    text-align: center;
    padding: 40px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #495057;
}

.form-control {
    width: 100%;
    padding: 8px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    font-size: 14px;
}

.btn-group .btn {
    margin-right: 5px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>

<?php include __DIR__ . '/partials/layout_end.php'; ?>
