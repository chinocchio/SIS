<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - SIS</title>
    <?php include __DIR__ . '/../teacher/partials/sidebar_styles.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../teacher/partials/layout_start.php'; ?>
        <!-- <div class="page-header">
            <h1><i class="fas fa-camera"></i> Capture Student Faces</h1>
        </div> -->
        
        <div class="container-fluid">
            <div class="row flex-row d-flex" style="height: 90vh; min-height: 600px;">
                <div class="col-md-6 d-flex flex-column" style="height: 100%;">
                    <div class="card flex-fill d-flex flex-column" style="height: 100%;">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-users"></i> Students</h5>
                        </div>
                        <div class="card-body p-0 flex-fill" style="overflow-y: auto;">
                            <div class="table-responsive" style="height: 100%;">
                                <table class="table table-hover mb-0">
                                    <thead class="thead-light sticky-top">
                                        <tr>
                                            <th class="border-0">Name</th>
                                            <th class="border-0">LRN</th>
                                            <th class="border-0">Section</th>
                                            <th class="border-0">Status</th>
                                            <th class="border-0">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                <?php if (!empty($students)): ?>
                                    <?php foreach ($students as $student): ?>
                                        <tr class="align-middle">
                                            <td class="fw-bold"><?= esc($student['full_name']) ?></td>
                                            <td class="text-muted"><?= esc($student['lrn']) ?></td>
                                            <td>
                                                <span class="badge bg-info text-dark">
                                                    Grade <?= $student['grade_level'] ?> - <?= esc($student['section_name']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if (!empty($student['face_encoding'])): ?>
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle"></i> Captured
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-exclamation-circle"></i> Not Captured
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-primary btn-sm capture-face-btn" 
                                                        data-student-id="<?= $student['id'] ?>"
                                                        data-student-name="<?= esc($student['full_name']) ?>"
                                                        data-student-lrn="<?= esc($student['lrn']) ?>">
                                                    <i class="fas fa-camera"></i> 
                                                    <?= !empty($student['face_encoding']) ? 'Recapture' : 'Capture' ?>
                                                </button>
                                            </td>   
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="fas fa-info-circle fa-2x mb-2"></i><br>
                                            <strong>No students assigned to you</strong><br>
                                            <small>Contact your administrator to get student assignments.</small>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 d-flex flex-column" style="height: 100%;">
            <div class="card flex-fill d-flex flex-column" style="height: 100%;">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-video"></i> Camera</h5>
                </div>
                <div class="card-body flex-fill d-flex flex-column justify-content-center align-items-center">
                    <div class="camera-container w-100 h-100 d-flex flex-column align-items-center justify-content-center">
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
    <?php include __DIR__ . '/../teacher/partials/layout_end.php'; ?>

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
                width: 640, 
                height: 480,
                facingMode: 'user'
            } 
        });
        const video = document.getElementById('video');
        video.srcObject = currentStream;
        document.getElementById('camera-preview').style.display = 'block';
        document.getElementById('capture-instructions').style.display = 'none';
        // Enlarge camera interface
        document.querySelector('.camera-container').classList.add('camera-active');
        video.classList.add('video-active');
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
    // Remove enlarged camera interface
    document.querySelector('.camera-container').classList.remove('camera-active');
    document.getElementById('video').classList.remove('video-active');
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
    background: #f8f9fa;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    transition: all 0.3s cubic-bezier(.4,2,.6,1);
}

.camera-container.camera-active {
    min-height: 480px;
}

#video {
    border-radius: 8px;
    border: 2px solid #28a745;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    max-width: 100%;
    height: auto;
    transition: all 0.3s cubic-bezier(.4,2,.6,1);
}

#video.video-active {
    width: 480px !important;
    height: 360px !important;
    max-width: 100%;
}

#video {
    border-radius: 8px;
    border: 2px solid #28a745;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    max-width: 100%;
    height: auto;
}

.table th {
    background-color: #f8f9fa;
    color: #495057;
    border: none;
    font-weight: 600;
    font-size: 0.9rem;
}

.table td {
    vertical-align: middle;
    padding: 12px 8px;
}

.capture-face-btn {
    border-radius: 20px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.capture-face-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
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

.badge {
    font-size: 0.8rem;
    padding: 6px 10px;
}

.card-header {
    border-bottom: 1px solid rgba(0,0,0,0.125);
}

.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border: none;
}
</style>

</body>
</html>
