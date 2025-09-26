<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Face Recognition Attendance - <?= esc($subject['name']) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f6fb;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }
        
        .header h1 {
            color: #333;
            margin: 0;
        }
        
        .btn {
            background: #667eea;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-left: 10px;
        }
        
        .btn:hover {
            background: #5a6fd8;
        }
        
        .btn-secondary {
            background: #6c757d;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .btn-success {
            background: #28a745;
        }
        
        .btn-success:hover {
            background: #218838;
        }
        
        .btn-danger {
            background: #dc3545;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .btn-warning {
            background: #ffc107;
            color: #212529;
        }
        
        .btn-warning:hover {
            background: #e0a800;
        }
        
        .camera-container {
            position: relative;
            width: 100%;
            max-width: 640px;
            margin: 20px auto;
            background: #000;
            border-radius: 8px;
            overflow: hidden;
        }
        
        #video {
            width: 100%;
            height: auto;
            display: block;
        }
        
        #canvas {
            display: none;
        }
        
        .controls {
            text-align: center;
            margin: 20px 0;
        }
        
        .status {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            border-radius: 6px;
            font-weight: bold;
        }
        
        .status.waiting {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }
        
        .status.processing {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }
        
        .status.success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .status.error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        .results {
            margin-top: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        
        .results h3 {
            margin-top: 0;
            color: #495057;
        }
        
        .recognized-face {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            margin: 5px 0;
            background: white;
            border-radius: 4px;
            border-left: 4px solid #28a745;
        }
        
        .face-info {
            display: flex;
            align-items: center;
        }
        
        .face-info .name {
            font-weight: bold;
            margin-right: 10px;
        }
        
        .face-info .confidence {
            color: #6c757d;
            font-size: 0.9em;
        }
        
        .face-actions {
            display: flex;
            gap: 5px;
        }
        
        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }
        
        .subject-info {
            background: #e3f2fd;
            border: 1px solid #bbdefb;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .subject-info h3 {
            margin-top: 0;
            color: #1976d2;
        }
        
        .no-camera {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        
        .no-camera h3 {
            margin-bottom: 10px;
        }
        
        .instructions {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .instructions h4 {
            margin-top: 0;
            color: #495057;
        }
        
        .instructions ol {
            margin: 10px 0;
            padding-left: 20px;
        }
        
        .instructions li {
            margin: 5px 0;
        }
        
        @media (max-width: 768px) {
            .camera-container {
                max-width: 100%;
            }
            
            .controls {
                flex-direction: column;
            }
            
            .btn {
                margin: 5px 0;
                width: 100%;
            }
            
            .recognized-face {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .face-actions {
                margin-top: 10px;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📷 Face Recognition Attendance</h1>
            <div>
                <a href="/face-recognition" class="btn btn-secondary">← Back to Subjects</a>
                <a href="/teacher/dashboard" class="btn btn-secondary">🏠 Dashboard</a>
            </div>
        </div>
        
        <div class="subject-info">
            <h3>📚 Subject: <?= esc($subject['name']) ?></h3>
            <p><strong>Code:</strong> <?= esc($subject['code']) ?></p>
            <p><strong>Grade Level:</strong> <?= esc($subject['grade_level']) ?></p>
        </div>
        
        <div class="instructions">
            <h4>📋 Instructions:</h4>
            <ol>
                <li>Click "Start Camera" to begin face recognition</li>
                <li>Allow camera access when prompted by your browser</li>
                <li>Position students in front of the camera</li>
                <li>Click "Capture & Recognize" to process faces</li>
                <li>Review recognized students and record attendance</li>
            </ol>
        </div>
        
        <div class="camera-container">
            <video id="video" autoplay muted></video>
            <canvas id="canvas"></canvas>
        </div>
        
        <div class="controls">
            <button id="startBtn" class="btn btn-success">📷 Start Camera</button>
            <button id="captureBtn" class="btn btn-warning" disabled>📸 Capture & Recognize</button>
            <button id="stopBtn" class="btn btn-danger" disabled>⏹️ Stop Camera</button>
        </div>
        
        <div id="status" class="status waiting">
            Ready to start face recognition
        </div>
        
        <div id="results" class="results" style="display: none;">
            <h3>👥 Recognized Students</h3>
            <div id="recognizedList"></div>
        </div>
        
        <div id="noCamera" class="no-camera" style="display: none;">
            <h3>📷 Camera Not Available</h3>
            <p>Your browser doesn't support camera access or camera is not available.</p>
            <p>Please use a modern browser (Chrome, Firefox, Safari, Edge) with camera access.</p>
        </div>
    </div>

    <script>
        let video = document.getElementById('video');
        let canvas = document.getElementById('canvas');
        let ctx = canvas.getContext('2d');
        let stream = null;
        let isProcessing = false;
        
        const startBtn = document.getElementById('startBtn');
        const captureBtn = document.getElementById('captureBtn');
        const stopBtn = document.getElementById('stopBtn');
        const status = document.getElementById('status');
        const results = document.getElementById('results');
        const recognizedList = document.getElementById('recognizedList');
        const noCamera = document.getElementById('noCamera');
        
        const subjectId = <?= $subject['id'] ?>;
        
        startBtn.addEventListener('click', startCamera);
        captureBtn.addEventListener('click', captureAndRecognize);
        stopBtn.addEventListener('click', stopCamera);
        
        async function startCamera() {
            try {
                updateStatus('Requesting camera access...', 'processing');
                
                stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { 
                        width: { ideal: 640 },
                        height: { ideal: 480 }
                    } 
                });
                
                video.srcObject = stream;
                video.play();
                
                startBtn.disabled = true;
                captureBtn.disabled = false;
                stopBtn.disabled = false;
                
                updateStatus('Camera started successfully. Position students in front of the camera.', 'success');
                
            } catch (error) {
                console.error('Camera access error:', error);
                updateStatus('Camera access denied or not available.', 'error');
                noCamera.style.display = 'block';
            }
        }
        
        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
            
            video.srcObject = null;
            
            startBtn.disabled = false;
            captureBtn.disabled = true;
            stopBtn.disabled = true;
            
            updateStatus('Camera stopped.', 'waiting');
            results.style.display = 'none';
        }
        
        async function captureAndRecognize() {
            if (isProcessing) return;
            
            isProcessing = true;
            captureBtn.disabled = true;
            updateStatus('Capturing image and processing faces...', 'processing');
            
            try {
                // Set canvas size to match video
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                
                // Draw current video frame to canvas
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                
                // Convert canvas to blob
                const blob = await new Promise(resolve => {
                    canvas.toBlob(resolve, 'image/jpeg', 0.8);
                });
                
                // Create form data
                const formData = new FormData();
                formData.append('image', blob, 'face_recognition.jpg');
                formData.append('subject_id', subjectId);
                
                // Send to server for processing
                const response = await fetch('/face-recognition/process', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    displayResults(result.recognized_faces);
                    updateStatus(`Face recognition completed. Found ${result.recognized_faces.length} students.`, 'success');
                } else {
                    updateStatus(`Face recognition failed: ${result.error}`, 'error');
                }
                
            } catch (error) {
                console.error('Face recognition error:', error);
                updateStatus('Face recognition failed. Please try again.', 'error');
            } finally {
                isProcessing = false;
                captureBtn.disabled = false;
            }
        }
        
        function displayResults(recognizedFaces) {
            recognizedList.innerHTML = '';
            
            if (recognizedFaces.length === 0) {
                recognizedList.innerHTML = '<p>No faces recognized. Please ensure students are clearly visible in the camera.</p>';
            } else {
                recognizedFaces.forEach(face => {
                    const faceElement = document.createElement('div');
                    faceElement.className = 'recognized-face';
                    faceElement.innerHTML = `
                        <div class="face-info">
                            <span class="name">${face.name}</span>
                            <span class="confidence">(${Math.round(face.confidence * 100)}% confidence)</span>
                        </div>
                        <div class="face-actions">
                            <button class="btn btn-success btn-sm" onclick="recordAttendance(${face.student_id}, '${face.name}')">
                                ✅ Record Attendance
                            </button>
                        </div>
                    `;
                    recognizedList.appendChild(faceElement);
                });
            }
            
            results.style.display = 'block';
        }
        
        async function recordAttendance(studentId, studentName) {
            try {
                const response = await fetch('/face-recognition/record-attendance', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        student_id: studentId,
                        subject_id: subjectId
                    })
                });
                
                const result = await response.json();
                
                if (result.status === 'success') {
                    alert(`✅ Attendance recorded for ${studentName}`);
                } else if (result.status === 'exists') {
                    alert(`ℹ️ Attendance already recorded for ${studentName} today`);
                } else {
                    alert(`❌ Failed to record attendance for ${studentName}`);
                }
                
            } catch (error) {
                console.error('Attendance recording error:', error);
                alert(`❌ Error recording attendance for ${studentName}`);
            }
        }
        
        function updateStatus(message, type) {
            status.textContent = message;
            status.className = `status ${type}`;
        }
        
        // Clean up on page unload
        window.addEventListener('beforeunload', stopCamera);
    </script>
</body>
</html>
