# 🧪 Face Recognition Local Test Guide

This guide will help you test the web-based face recognition system locally before deploying to production.

## 🚀 Quick Test Setup

### Step 1: Start Your Local Server
```bash
# Navigate to your project directory
cd enrollment-system

# Start CodeIgniter development server
php spark serve
```

Your server will be running at: `http://localhost:8080`

### Step 2: Login as Teacher
1. Go to `http://localhost:8080`
2. Click "Teacher Login"
3. Login with teacher credentials
4. You should see the Teacher Dashboard

### Step 3: Access Face Recognition
1. Click "📷 Face Recognition" button on Teacher Dashboard
2. You'll see the Face Recognition page with your assigned subjects

### Step 4: Test Camera Access
1. Click "🧪 Test Camera" button
2. This will open the test page
3. Click "📷 Start Camera"
4. Allow camera access when prompted
5. Click "📸 Capture Image" to test

## 🎯 What to Test

### ✅ Camera Access Test
- **Expected**: Browser asks for camera permission
- **Expected**: Camera feed appears in video element
- **Expected**: Image capture works
- **Expected**: Image is sent to server successfully

### ✅ API Endpoint Test
- **Expected**: Image upload to `/face-recognition/process` works
- **Expected**: Server responds with JSON
- **Expected**: Mock face recognition results are returned

### ✅ Subject Selection Test
- **Expected**: Teacher sees assigned subjects
- **Expected**: Can click "📷 Start Attendance" for each subject
- **Expected**: Attendance page loads with camera interface

### ✅ Student List Test
- **Expected**: Can view students for each subject
- **Expected**: Shows which students have face encodings

## 🔧 Test Scenarios

### Scenario 1: Basic Camera Test
1. Go to `/face-recognition/test`
2. Start camera
3. Capture image
4. Verify API response

### Scenario 2: Subject Attendance Test
1. Go to `/face-recognition`
2. Select a subject
3. Click "📷 Start Attendance"
4. Test camera access
5. Capture and process image

### Scenario 3: Student Management Test
1. Go to `/face-recognition`
2. Click "👥 View Students" for a subject
3. Verify student list loads
4. Check face encoding status

## 🐛 Troubleshooting

### Camera Not Working
**Problem**: Camera access denied
**Solutions**:
- Use HTTPS (required for camera access)
- Check browser permissions
- Try different browser (Chrome recommended)
- Ensure camera is not used by another application

**For HTTPS locally**:
```bash
# Install mkcert for local HTTPS
# Then access via https://localhost:8080
```

### API Endpoint Errors
**Problem**: 404 or 500 errors
**Solutions**:
- Check routes are properly configured
- Verify controller exists
- Check file permissions
- Review server logs

### No Subjects Showing
**Problem**: Teacher has no assigned subjects
**Solutions**:
- Login as admin
- Assign teacher to subjects
- Check school year is active
- Verify teacher assignments

## 📊 Expected Results

### Successful Test Results
```json
{
  "success": true,
  "message": "Face recognition processing completed",
  "recognized_faces": [
    {
      "student_id": 1,
      "name": "John Doe",
      "confidence": 0.95,
      "timestamp": "2025-01-17 10:30:00"
    }
  ],
  "total_students": 25
}
```

### Test Page Information
- **Image Size**: ~50-200 KB
- **Dimensions**: 640x480 pixels
- **Format**: JPEG
- **API Response**: Valid JSON

## 🎥 Browser Compatibility

### Supported Browsers
- ✅ **Chrome** (Recommended)
- ✅ **Firefox**
- ✅ **Safari**
- ✅ **Edge**
- ❌ **Internet Explorer** (Not supported)

### Mobile Testing
- ✅ **Android Chrome**
- ✅ **iOS Safari**
- ✅ **Mobile cameras**

## 🔒 Security Considerations

### Local Testing
- Camera access requires HTTPS in production
- Local HTTP works for development
- Browser security policies apply

### Production Deployment
- Must use HTTPS
- Camera permissions required
- Secure API endpoints

## 📝 Test Checklist

### Pre-Test Setup
- [ ] Local server running (`php spark serve`)
- [ ] Teacher account exists and logged in
- [ ] Teacher assigned to subjects
- [ ] Students exist with face encodings (optional for test)

### Camera Test
- [ ] Camera access granted
- [ ] Video feed displays
- [ ] Image capture works
- [ ] Image sent to server
- [ ] API response received

### Face Recognition Test
- [ ] Subject selection works
- [ ] Attendance page loads
- [ ] Camera interface functional
- [ ] Mock results displayed
- [ ] Attendance recording works

### Integration Test
- [ ] Teacher dashboard updated
- [ ] Face recognition button works
- [ ] Navigation between pages
- [ ] Error handling works

## 🚀 Next Steps After Testing

### If Tests Pass
1. **Deploy to DigitalOcean** with HTTPS
2. **Capture student faces** using Python scripts
3. **Train teachers** on web interface
4. **Monitor usage** and performance

### If Tests Fail
1. **Check browser console** for errors
2. **Review server logs** for issues
3. **Verify file permissions**
4. **Test with different browser**

## 📞 Support

### Common Issues
- **Camera not detected**: Check browser permissions
- **API errors**: Verify routes and controller
- **No subjects**: Assign teacher to subjects
- **HTTPS required**: Use HTTPS for production

### Debug Information
- Check browser developer tools
- Review CodeIgniter logs
- Test API endpoints directly
- Verify database connections

---

**Ready to test?** Start your local server and navigate to `http://localhost:8080` to begin testing! 🚀
