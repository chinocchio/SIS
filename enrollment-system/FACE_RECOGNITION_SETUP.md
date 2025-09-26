# 🎭 Face Recognition Setup Guide

This guide provides detailed instructions for setting up the face recognition attendance system.

## 📋 Prerequisites

### Required Software
- **Python 3.8+** (with pip)
- **Webcam/Camera** (USB or built-in)
- **MySQL Database** (already configured for main app)
- **Visual C++ Build Tools** (Windows only)

### Hardware Requirements
- **Camera**: Any USB webcam or built-in camera
- **RAM**: Minimum 4GB (8GB recommended)
- **CPU**: Modern multi-core processor
- **Storage**: 2GB free space for Python packages

## 🚀 Installation Steps

### 1. Create Virtual Environment (Recommended)
```bash
cd face_recognition_app
python -m venv .venv

# Activate virtual environment
# Windows:
.venv\Scripts\activate
# Linux/Mac:
source .venv/bin/activate
```

### 2. Install Dependencies

**Option A: Using requirements.txt**
```bash
pip install -r requirements.txt
```

**Option B: Manual Installation**
```bash
# Core packages
pip install opencv-python==4.8.1.78
pip install face-recognition==1.3.0
pip install mysql-connector-python==8.1.0
pip install numpy==1.24.3
pip install Pillow==10.0.1
pip install requests==2.31.0

# Face recognition library
pip install dlib==19.24.2
```

### 3. Windows-Specific Setup

**Install Visual C++ Build Tools:**
1. Download from: https://visualstudio.microsoft.com/visual-cpp-build-tools/
2. Install "C++ build tools" workload
3. Restart command prompt

**Install cmake:**
```bash
pip install cmake
pip install dlib
```

### 4. Configure Database Connection

Edit `face_recognition_app/db.py`:
```python
DB_CONFIG = {
    'host': 'localhost',
    'user': 'your_mysql_username',
    'password': 'your_mysql_password',
    'database': 'sis_enrollment',
    'port': 3306
}
```

## 🧪 Testing Setup

### 1. Test Database Connection
```bash
python -c "import db; print('Database connection successful!')"
```

### 2. Test Camera Access
```bash
python -c "import cv2; cap = cv2.VideoCapture(0); print('Camera access:', cap.isOpened()); cap.release()"
```

### 3. Test Face Recognition
```bash
python -c "import face_recognition; print('Face recognition library loaded successfully!')"
```

## 📸 Using Face Recognition

### Step 1: Capture Student Faces

**Run the capture script:**
```bash
python capture_faces.py
```

**Process:**
1. Enter student LRN when prompted
2. Position student's face in camera view
3. Ensure good lighting and clear visibility
4. Press 'q' to capture and save face encoding
5. Repeat for all students

**Tips for Better Results:**
- Use natural lighting when possible
- Position face directly facing camera
- Ensure face fills most of the frame
- Avoid shadows and glare
- Capture multiple angles if needed

### Step 2: Take Attendance

**Run the recognition script:**
```bash
python recognize_faces.py
```

**Process:**
1. Enter subject code (e.g., "ENG7-01")
2. System loads face encodings for students in that subject
3. Camera starts recognizing faces automatically
4. Attendance is recorded when face is recognized
5. Press 'q' to quit

**Features:**
- Real-time face recognition
- Automatic attendance recording
- Duplicate prevention (one record per student per subject per day)
- Visual feedback with recognized names

## 🔧 Troubleshooting

### Common Issues

#### 1. Camera Not Detected
```bash
# Check available cameras
python -c "import cv2; print([i for i in range(10) if cv2.VideoCapture(i).isOpened()])"
```

**Solutions:**
- Check camera permissions
- Try different camera index (0, 1, 2, etc.)
- Restart camera application
- Check USB connection

#### 2. dlib Installation Fails

**Windows:**
```bash
# Install Visual C++ Build Tools first
# Then install cmake and dlib
pip install cmake
pip install dlib
```

**Linux:**
```bash
# Install system dependencies
sudo apt-get install build-essential cmake
sudo apt-get install libopenblas-dev liblapack-dev
pip install dlib
```

**Mac:**
```bash
# Install Xcode command line tools
xcode-select --install
pip install dlib
```

#### 3. Face Not Recognized

**Possible Causes:**
- Poor lighting conditions
- Face not clearly visible
- Camera angle issues
- Face encoding quality

**Solutions:**
- Improve lighting
- Reposition camera
- Recapture face with better angle
- Ensure face fills frame properly

#### 4. Database Connection Error

**Check:**
- MySQL service is running
- Credentials in `db.py` are correct
- Database `sis_enrollment` exists
- `attendance_records` table exists

**Test Connection:**
```bash
python -c "
import mysql.connector
from db import DB_CONFIG
try:
    conn = mysql.connector.connect(**DB_CONFIG)
    print('Database connection successful!')
    conn.close()
except Exception as e:
    print('Database connection failed:', e)
"
```

#### 5. Performance Issues

**Optimization Tips:**
- Use smaller image resolution
- Reduce face encoding tolerance
- Close other applications
- Use SSD storage
- Increase RAM if possible

## 🔒 Security Considerations

### Data Privacy
- **Face Encodings**: Stored as binary data in database
- **Access Control**: Only authorized teachers can access
- **Data Retention**: Consider implementing retention policies
- **Encryption**: Consider encrypting face data at rest

### Best Practices
- Regular backup of face encodings
- Secure database access
- Monitor attendance records
- Implement audit logging
- Regular security updates

## 📊 API Integration

### Attendance Recording API

**Endpoint:** `POST /api/attendance/record`

**Request:**
```json
{
    "student_id": 1,
    "subject_id": 5
}
```

**Response:**
```json
{
    "status": "success",
    "message": "Attendance recorded."
}
```

**Error Response:**
```json
{
    "status": "exists",
    "message": "Attendance already recorded for today."
}
```

### Testing API
```bash
curl -X POST http://localhost:8080/api/attendance/record \
  -H "Content-Type: application/json" \
  -d '{"student_id": 1, "subject_id": 5}'
```

## 🚀 Performance Optimization

### For Large Student Populations

**Batch Processing:**
- Process multiple students at once
- Use threading for parallel processing
- Implement caching for face encodings

**Memory Management:**
- Clear face encodings after processing
- Use efficient image resizing
- Implement proper error handling

**Database Optimization:**
- Index attendance records properly
- Use connection pooling
- Implement query optimization

### Hardware Recommendations

**Minimum:**
- CPU: Intel i3 or AMD Ryzen 3
- RAM: 4GB
- Storage: 2GB free space

**Recommended:**
- CPU: Intel i5 or AMD Ryzen 5
- RAM: 8GB
- Storage: 5GB free space
- GPU: NVIDIA GTX 1050+ (for faster processing)

## 📝 Maintenance

### Regular Tasks

**Weekly:**
- Test camera functionality
- Check database connectivity
- Review attendance records
- Update Python packages

**Monthly:**
- Backup face encodings
- Clean up old attendance records
- Performance monitoring
- Security updates

**Yearly:**
- Full system backup
- Hardware maintenance
- Software updates
- Security audit

## 🆘 Support

### Getting Help

1. **Check Logs**: Review error messages in console
2. **Test Components**: Verify each component individually
3. **Documentation**: Refer to this guide and main setup guide
4. **Community**: Check online forums for similar issues

### Common Error Messages

**"Camera not found":**
- Check camera permissions and connections

**"Database connection failed":**
- Verify MySQL credentials and service status

**"Face not recognized":**
- Improve lighting and camera positioning

**"Module not found":**
- Install missing Python packages

---

**Need more help?** Check the main `SETUP_GUIDE.md` for additional troubleshooting tips! 🆘
