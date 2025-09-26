<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - SIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-camera"></i> Capture Student Faces
                    </h3>
                    <div class="card-tools">
                        <a href="/teacher/dashboard" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Student List -->
                        <div class="col-md-6">
                            <h5><i class="fas fa-users"></i> Students</h5>
                            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                <table class="table table-striped table-hover">
                                    <thead class="thead-dark sticky-top">
                                        <tr>
                                            <th>Name</th>
                                            <th>LRN</th>
                                            <th>Section</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($students)): ?>
                                            <?php foreach ($students as $student): ?>
                                                <tr>
                                                    <td><?= esc($student['full_name']) ?></td>
                                                    <td><?= esc($student['lrn']) ?></td>
                                                    <td>
                                                        <span class="badge badge-info">
                                                            Grade <?= $student['grade_level'] ?> - <?= esc($student['section_name']) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($student['face_encoding'])): ?>
                                                            <span class="badge badge-success">
                                                                <i class="fas fa-check"></i> Captured
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge badge-warning">
                                                                <i class="fas fa-times"></i> Not Captured
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-primary btn-sm capture-face-btn" 
                                                                data-student-id="<?= $student['id'] ?>"
                                                                data-student-name="<?= esc($student['full_name']) ?>"
                                                                data-student-lrn="<?= esc($student['lrn']) ?>"
                                                                <?= !empty($student['face_encoding']) ? 'disabled' : '' ?>>
                                                            <i class="fas fa-camera"></i> 
                                                            <?= !empty($student['face_encoding']) ? 'Recapture' : 'Capture' ?>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">
                                                    <i class="fas fa-info-circle"></i> No students assigned to you
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Camera Interface -->
                        <div class="col-md-6">
                            <h5><i class="fas fa-video"></i> Camera</h5>
                            <div class="camera-container">
                                <div id="camera-preview" class="text-center" style="display: none;">
                                    <video id="video" width="320" height="240" autoplay></video>
                                    <br><br>
                                    <button id="capture-btn" class="btn btn-success">
                                        <i class="fas fa-camera"></i> Capture Face
                                    </button>
                                    <button id="stop-camera-btn" class="btn btn-danger">
                                        <i class="fas fa-stop"></i> Stop Camera
                                    </button>
                                </div>
                                
                                <div id="capture-instructions" class="text-center">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Instructions:</strong><br>
                                        1. Click on a student's "Capture" button<br>
                                        2. Allow camera access when prompted<br>
                                        3. Position the student's face in the camera<br>
                                        4. Click "Capture Face" to save the face encoding
                                    </div>
                                </div>
                                
                                <div id="capture-result" class="mt-3" style="display: none;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden canvas for image capture -->
<canvas id="canvas" style="display: none;"></canvas>

<script>
let currentStream = null;
let currentStudentId = null;
let currentStudentName = null;
let currentStudentLrn = null;

// Capture face button click handler
document.querySelectorAll('.capture-face-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        currentStudentId = this.dataset.studentId;
        currentStudentName = this.dataset.studentName;
        currentStudentLrn = this.dataset.studentLrn;
        
        startCamera();
    });
});

// Start camera
async function startCamera() {
    try {
        currentStream = await navigator.mediaDevices.getUserMedia({ 
            video: { 
                width: 320, 
                height: 240,
                facingMode: 'user'
            } 
        });
        
        const video = document.getElementById('video');
        video.srcObject = currentStream;
        
        document.getElementById('camera-preview').style.display = 'block';
        document.getElementById('capture-instructions').style.display = 'none';
        
        // Update capture button text
        document.getElementById('capture-btn').innerHTML = 
            `<i class="fas fa-camera"></i> Capture Face for ${currentStudentName}`;
        
    } catch (err) {
        console.error('Error accessing camera:', err);
        showAlert('Error accessing camera: ' + err.message, 'danger');
    }
}

// Stop camera
function stopCamera() {
    if (currentStream) {
        currentStream.getTracks().forEach(track => track.stop());
        currentStream = null;
    }
    
    document.getElementById('camera-preview').style.display = 'none';
    document.getElementById('capture-instructions').style.display = 'block';
    document.getElementById('capture-result').style.display = 'none';
    
    currentStudentId = null;
    currentStudentName = null;
    currentStudentLrn = null;
}

// Capture face
document.getElementById('capture-btn').addEventListener('click', function() {
    if (!currentStudentId) {
        showAlert('Please select a student first', 'warning');
        return;
    }
    
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');
    
    // Set canvas size to match video
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    
    // Draw video frame to canvas
    ctx.drawImage(video, 0, 0);
    
    // Convert to base64
    const imageData = canvas.toDataURL('image/jpeg', 0.8);
    
    // Send to server
    captureFace(imageData);
});

// Stop camera button
document.getElementById('stop-camera-btn').addEventListener('click', stopCamera);

// Capture face function
async function captureFace(imageData) {
    try {
        const formData = new FormData();
        formData.append('student_id', currentStudentId);
        formData.append('image_data', imageData);
        
        const response = await fetch('/face-recognition/capture-face', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert(result.message, 'success');
            
            // Update the student's status in the table
            updateStudentStatus(currentStudentId, true);
            
            // Stop camera
            stopCamera();
            
        } else {
            showAlert(result.error || 'Face capture failed', 'danger');
        }
        
    } catch (error) {
        console.error('Error capturing face:', error);
        showAlert('Error capturing face: ' + error.message, 'danger');
    }
}

// Update student status in table
function updateStudentStatus(studentId, hasFace) {
    const row = document.querySelector(`[data-student-id="${studentId}"]`).closest('tr');
    const statusCell = row.querySelector('td:nth-child(4)');
    const actionCell = row.querySelector('td:nth-child(5)');
    const button = actionCell.querySelector('button');
    
    if (hasFace) {
        statusCell.innerHTML = '<span class="badge badge-success"><i class="fas fa-check"></i> Captured</span>';
        button.innerHTML = '<i class="fas fa-camera"></i> Recapture';
        button.disabled = false;
    } else {
        statusCell.innerHTML = '<span class="badge badge-warning"><i class="fas fa-times"></i> Not Captured</span>';
        button.innerHTML = '<i class="fas fa-camera"></i> Capture';
        button.disabled = false;
    }
}

// Show alert
function showAlert(message, type) {
    const alertDiv = document.getElementById('capture-result');
    alertDiv.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `;
    alertDiv.style.display = 'block';
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        alertDiv.style.display = 'none';
    }, 5000);
}

// Cleanup on page unload
window.addEventListener('beforeunload', stopCamera);
</script>

<style>
.camera-container {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 20px;
    min-height: 300px;
}

#video {
    border-radius: 8px;
    border: 2px solid #007bff;
}

.table th {
    background-color: #343a40;
    color: white;
    border: none;
}

.table td {
    vertical-align: middle;
}

.capture-face-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.sticky-top {
    position: sticky;
    top: 0;
    z-index: 10;
}
</style>

</body>
</html>
