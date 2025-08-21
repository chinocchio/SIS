<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile - Registrar</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f5f5f5; margin:0; padding:20px; }
        .container { max-width: 1000px; margin: 0 auto; }
        .card { background:#fff; border-radius:8px; padding:20px; box-shadow:0 2px 8px rgba(0,0,0,0.1); margin-bottom:20px; }
        .header { display:flex; justify-content:space-between; align-items:center; }
        .btn { display:inline-block; padding:8px 12px; background:#007bff; color:#fff; border-radius:6px; text-decoration:none; margin-right:8px; }
        .btn-approve { background:#28a745; }
        .btn-reject { background:#dc3545; }
        table { width:100%; border-collapse: collapse; }
        th, td { padding:10px; border-bottom:1px solid #eee; text-align:left; }
        th { background:#fafafa; }
    </style>
 </head>
 <body>
     <div class="container">
         <div class="header">
             <h2>Student Profile: <?= esc($student['first_name'].' '.$student['last_name']) ?></h2>
             <div>
                 <a class="btn" href="/registrar/dashboard">Back to Dashboard</a>
                 <a class="btn" href="/auth/logout" style="background:#dc3545;">Logout</a>
             </div>
         </div>
         
         <div class="card">
             <h3>Basic Information</h3>
             <p><strong>Email:</strong> <?= esc($student['email']) ?></p>
             <p><strong>Grade Level:</strong> Grade <?= esc($student['grade_level']) ?></p>
             <p><strong>Admission Type:</strong> <?= esc(ucfirst($student['admission_type'])) ?></p>
             <p><strong>Status:</strong> <?= esc(ucfirst($student['status'])) ?></p>
         </div>
 
         <div class="card">
             <h3>Uploaded Documents</h3>
             <table>
                 <tr>
                     <th>Type</th>
                     <th>Status</th>
                     <th>Uploaded At</th>
                     <th>File</th>
                     <th>Action</th>
                 </tr>
                 <?php if (!empty($documents)): ?>
                     <?php foreach ($documents as $doc): ?>
                         <tr>
                             <td><?= esc($doc['document_type']) ?></td>
                             <td><?= esc(ucfirst($doc['status'])) ?></td>
                             <td><?= esc($doc['uploaded_at']) ?></td>
                             <td>
                    <?php 
                    $fileExt = strtolower(pathinfo($doc['file_path'], PATHINFO_EXTENSION));
                    if (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                        <img src="<?= base_url($doc['file_path']) ?>" alt="Document Preview" style="max-width:100px; max-height:100px; border:1px solid #ddd; cursor:pointer;" onclick="openImageModal('<?= base_url($doc['file_path']) ?>', '<?= esc($doc['document_type']) ?>')">
                        <br><small><a href="<?= base_url($doc['file_path']) ?>" target="_blank">Full Size</a></small>
                    <?php else: ?>
                        <a href="<?= base_url($doc['file_path']) ?>" target="_blank">View PDF</a>
                    <?php endif; ?>
                </td>
                             <td>
                                 <a class="btn btn-approve" href="/registrar/document/approve/<?= $doc['id'] ?>" onclick="return confirm('Approve this document?')">Approve</a>
                                 <a class="btn btn-reject" href="/registrar/document/reject/<?= $doc['id'] ?>" onclick="return confirm('Reject this document?')">Reject</a>
                             </td>
                         </tr>
                     <?php endforeach; ?>
                 <?php else: ?>
                     <tr><td colspan="5">No documents uploaded.</td></tr>
                 <?php endif; ?>
             </table>
                 </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:1000;">
        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); background:#fff; padding:20px; border-radius:8px; max-width:90%; max-height:90%;">
            <div style="text-align:right; margin-bottom:10px;">
                <button onclick="closeImageModal()" style="background:#dc3545; color:#fff; border:none; padding:5px 10px; border-radius:4px; cursor:pointer;">×</button>
            </div>
            <img id="modalImage" src="" alt="Document Preview" style="max-width:100%; max-height:80vh;">
            <p id="modalTitle" style="text-align:center; margin-top:10px; font-weight:bold;"></p>
        </div>
    </div>

    <script>
        function openImageModal(imageSrc, title) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('imageModal').style.display = 'block';
        }
        
        function closeImageModal() {
            document.getElementById('imageModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });
    </script>
</body>
</html>


