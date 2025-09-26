<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Face Recognition Test - SIS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f6fb;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .header {
            text-align: center;
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
            margin: 5px;
        }
        
        .btn:hover {
            background: #5a6fd8;
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
        
        .no-camera {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        
        .no-camera h3 {
            margin-bottom: 10px;
        }
        
        .test-info {
            background: #e3f2fd;
            border: 1px solid #bbdefb;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .test-info h3 {
            margin-top: 0;
            color: #1976d2;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📷 Face Recognition Test</h1>
            <p>Test camera access and face recognition functionality</p>
        </div>
        
        <div class="test-info">
            <h3>🧪 Test Information</h3>
            <p>This page tests the web-based face recognition system locally.</p>
            <p><strong>What this tests:</strong></p>
            <ul>
                <li>Camera access through web browser</li>
                <li>Image capture and processing</li>
                <li>API endpoint communication</li>
                <li>Mock face recognition results</li>
            </ul>
        </div>
        
        <div class="camera-container">
            <video id="video" autoplay muted></video>
            <canvas id="canvas"></canvas>
        </div>
        
        <div class="controls">
            <button id="startBtn" class="btn btn-success">📷 Start Camera</button>
            <button id="captureBtn" class="btn btn-success" disabled>📸 Capture Image</button>
            <button id="stopBtn" class="btn btn-danger" disabled>⏹️ Stop Camera</button>
        </div>
        
        <div id="status" class="status waiting">
            Ready to test camera access
        </div>
        
        <div id="results" class="results" style="display: none;">
            <h3>📊 Test Results</h3>
            <div id="testResults"></div>
        </div>
        
        <div id="noCamera" class="no-camera" style="display: none;">
            <h3>📷 Camera Not Available</h3>
            <p>Your browser doesn't support camera access or camera is not available.</p>
            <p>Please use a modern browser (Chrome, Firefox, Safari, Edge) with camera access.</p>
        </div>
        
        <div class="controls">
            <a href="/face-recognition" class="btn">← Back to Face Recognition</a>
            <a href="/teacher/dashboard" class="btn">🏠 Teacher Dashboard</a>
        </div>
    </div>

    <script>
        let video = document.getElementById('video');
        let canvas = document.getElementById('canvas');
        let ctx = canvas.getContext('2d');
        let stream = null;
        
        const startBtn = document.getElementById('startBtn');
        const captureBtn = document.getElementById('captureBtn');
        const stopBtn = document.getElementById('stopBtn');
        const status = document.getElementById('status');
        const results = document.getElementById('results');
        const testResults = document.getElementById('testResults');
        const noCamera = document.getElementById('noCamera');
        
        startBtn.addEventListener('click', startCamera);
        captureBtn.addEventListener('click', captureImage);
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
                
                updateStatus('Camera started successfully! You can now capture an image.', 'success');
                
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
        
        async function captureImage() {
            updateStatus('Capturing image...', 'processing');
            
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
                
                // Test API endpoint
                const formData = new FormData();
                formData.append('image', blob, 'test_image.jpg');
                formData.append('subject_id', 1); // Test with subject ID 1
                
                updateStatus('Sending image to server for processing...', 'processing');
                
                const response = await fetch('/face-recognition/process', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                displayTestResults(result, blob);
                
                if (result.success) {
                    updateStatus('Image processed successfully! Check results below.', 'success');
                } else {
                    updateStatus(`Processing failed: ${result.error}`, 'error');
                }
                
            } catch (error) {
                console.error('Image processing error:', error);
                updateStatus('Image processing failed. Please try again.', 'error');
            }
        }
        
        function displayTestResults(result, blob) {
            const imageSize = (blob.size / 1024).toFixed(2);
            const imageDimensions = `${canvas.width}x${canvas.height}`;
            
            testResults.innerHTML = `
                <div style="margin-bottom: 20px;">
                    <h4>📸 Image Information</h4>
                    <p><strong>Size:</strong> ${imageSize} KB</p>
                    <p><strong>Dimensions:</strong> ${imageDimensions} pixels</p>
                    <p><strong>Format:</strong> JPEG</p>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <h4>🔧 API Response</h4>
                    <pre style="background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto;">${JSON.stringify(result, null, 2)}</pre>
                </div>
                
                <div>
                    <h4>📊 Test Summary</h4>
                    <p><strong>API Endpoint:</strong> ✅ Working</p>
                    <p><strong>Image Upload:</strong> ✅ Working</p>
                    <p><strong>Response Format:</strong> ✅ Valid JSON</p>
                    <p><strong>Face Recognition:</strong> ${result.success ? '✅ Mock Results' : '❌ Failed'}</p>
                </div>
            `;
            
            results.style.display = 'block';
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
