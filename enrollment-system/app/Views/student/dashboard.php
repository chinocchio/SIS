<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <style>
        body { margin:0; padding:0; font-family: Arial, sans-serif; background:#f5f6fb; }
        .topbar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:#fff; padding:16px 20px; display:flex; justify-content:space-between; align-items:center; }
        .topbar a { color:#fff; text-decoration: underline; margin-left:12px; }
        .container { max-width: 1100px; margin: 20px auto; padding: 0 16px; }
        .greeting { background:#fff; border-radius:12px; padding:18px 20px; box-shadow:0 10px 25px rgba(0,0,0,.05); margin-bottom:16px; }
        .grid { display:grid; grid-template-columns: 1fr; gap:16px; }
        @media(min-width: 900px){ .grid { grid-template-columns: 1fr 1fr; } }
        .card { background:#fff; border-radius:12px; padding:18px 20px; box-shadow:0 10px 25px rgba(0,0,0,.05); }
        h2 { margin:0 0 6px 0; color:#333; }
        h3 { margin:0 0 12px 0; color:#333; }
        .muted { color:#666; }
        .alert { padding: 12px 14px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; }
        .alert-error { background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; }
        .alert-success { background:#d4edda; color:#155724; border:1px solid #c3e6cb; }
        label { display:block; margin: 10px 0 6px 0; font-weight:bold; color:#333; }
        select, input[type="file"] { width:100%; padding:10px; border-radius:8px; border:1px solid #ddd; }
        .btn { display:inline-block; padding:10px 16px; border:none; border-radius:8px; background:#667eea; color:#fff; cursor:pointer; }
        .btn:hover { filter:brightness(.95); }
        table { width:100%; border-collapse: collapse; }
        th, td { padding: 10px; border-bottom:1px solid #eee; text-align:left; }
        th { background:#fafafa; }
    </style>
</head>
<body>
    <div class="topbar">
        <div><strong>Student Dashboard</strong></div>
        <div>
            <a href="/">Home</a>
            <a href="/student/profile/edit">Edit Profile</a>
            <a href="/student/logout">Logout</a>
        </div>
    </div>
    <div class="container">
        <div class="greeting">
            <h2>Welcome <?= esc($student['first_name']) ?></h2>
            <div class="muted">
                <strong>LRN:</strong> <?= esc($student['lrn'] ?? 'N/A') ?> | 
                <strong>Account Status:</strong> <?= esc(ucfirst($student['status'])) ?>
            </div>
            <?php if ($student['status'] === 'draft'): ?>
                <div style="margin-top: 10px; padding: 10px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 6px; color: #856404;">
                    <strong>📋 Next Steps:</strong> Please upload all required documents to complete your account setup.
                </div>
            <?php elseif ($student['status'] === 'pending'): ?>
                <div style="margin-top: 10px; padding: 10px; background: #d1ecf1; border: 1px solid #bee5eb; border-radius: 6px; color: #0c5460;">
                    <strong>⏳ Status Update:</strong> Your documents are under review by the registrar. Please wait for approval.
                </div>
            <?php elseif ($student['status'] === 'approved'): ?>
                <div style="margin-top: 10px; padding: 10px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 6px; color: #155724;">
                    <strong>✅ Enrollment Complete:</strong> Your enrollment has been approved! Welcome to our school.
                </div>
            <?php endif; ?>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <div class="grid">
            <div class="card">
                <h3>📄 Upload Required Documents</h3>
                <form action="/student/upload-document" method="post" enctype="multipart/form-data">
                    <label for="document_type">Document Type *</label>
                    <select name="document_type" id="document_type" required>
                        <option value="">Select Document Type</option>
                        <option value="Birth Certificate (PSA)">Birth Certificate (PSA)</option>
                        <option value="Form 137 (Transcript)">Form 137 (Transcript)</option>
                        <option value="Good Moral Certificate">Good Moral Certificate</option>
                        <option value="Report Card">Report Card</option>
                        <option value="ID Photo">ID Photo</option>
                        <option value="Other">Other</option>
                    </select>
                    <label for="document_file">Select File *</label>
                    <input type="file" name="document_file" id="document_file" accept="image/*,.pdf" required>
                    <div style="margin-top:10px;">
                        <button class="btn" type="submit">📤 Upload Document</button>
                    </div>
                    <p class="muted" style="margin-top:8px; font-size:13px;">
                        📋 <strong>Required Documents:</strong> Please upload clear scanned copies of all required documents.
                        <br><small>💡 <strong>Supported formats:</strong> JPG, PNG, PDF, BMP, TIFF</small>
                    </p>
                </form>
            </div>

            <div class="card">
                <h3>Your Uploaded Documents</h3>
                <table>
                    <tr>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Uploaded At</th>
                        <th>File</th>
                    </tr>
                    <?php if (!empty($documents)): ?>
                        <?php foreach ($documents as $doc): ?>
                            <tr>
                                <td><?= esc($doc['document_type']) ?></td>
                                <td><?= esc(ucfirst($doc['status'])) ?></td>
                                <td><?= esc($doc['uploaded_at']) ?></td>
                                <td><a href="/student/document/<?= $doc['id'] ?>" target="_blank">View</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4">No documents uploaded yet.</td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>