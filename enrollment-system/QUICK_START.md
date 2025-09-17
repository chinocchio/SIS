# 🚀 Quick Start Guide

## ⚡ 5-Minute Setup

### 1. Prerequisites Check
```bash
# Check PHP version (8.1+)
php --version

# Check MySQL
mysql --version

# Check Composer
composer --version
```

### 2. Clone & Install
```bash
git clone <your-repo-url>
cd enrollment-system
composer install
```

### 3. Database Setup
```bash
# Create database
mysql -u root -p -e "CREATE DATABASE sis_enrollment;"

# Update database config in app/Config/Database.php
# Run migrations
php spark migrate
```

### 4. Start Server
```bash
php spark serve
```

### 5. Access Application
- **URL**: `http://localhost:8080`
- **Admin Login**: 
  - Username: `admin`
  - Password: `password`

## 🎯 First Steps After Login

1. **Create School Year** (Admin → School Years)
2. **Add Curriculum/Strands** (Admin → Curriculums/Strands)
3. **Create Sections** (Admin → Sections)
4. **Add Students** (Admin → Students)
5. **Assign Teachers** (Admin → Teachers → Assign)

## 🔧 Face Recognition (Optional)

```bash
cd face_recognition_app
pip install -r requirements.txt
python capture_faces.py  # Capture student faces
python recognize_faces.py  # Start attendance
```

**Note:** See `FACE_RECOGNITION_SETUP.md` for detailed setup instructions.

## 📚 Full Documentation

See `SETUP_GUIDE.md` for detailed instructions.

---

**Need Help?** Check the troubleshooting section in `SETUP_GUIDE.md` 🆘
