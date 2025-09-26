# School Information System - Local Setup Guide

This guide will help you set up the School Information System (SIS) locally on a new machine.

## 📋 Prerequisites

Before starting, ensure you have the following installed:

### Required Software
- **PHP 8.1+** (with extensions: mysqli, curl, gd, mbstring, xml, zip)
- **MySQL 8.0+** or **MariaDB 10.4+**
- **Composer** (PHP dependency manager)
- **Git** (for cloning the repository)
- **Python 3.8+** (for face recognition features)
- **Web Server** (Apache/Nginx) or **PHP Built-in Server**

### Recommended Tools
- **VS Code** or **PhpStorm** (IDE)
- **MySQL Workbench** or **phpMyAdmin** (Database management)
- **Postman** (API testing)

## 🚀 Step-by-Step Setup

### 1. Clone the Repository
```bash
git clone <your-repository-url>
cd enrollment-system
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Database Setup

#### 3.1 Create Database
```sql
CREATE DATABASE sis_enrollment;
```

#### 3.2 Configure Database Connection
Edit `app/Config/Database.php`:
```php
public $default = [
    'DSN'      => '',
    'hostname' => 'localhost',
    'username' => 'your_mysql_username',
    'password' => 'your_mysql_password',
    'database' => 'sis_enrollment',
    'DBDriver' => 'MySQLi',
    'DBPrefix' => '',
    'pConnect' => false,
    'DBDebug'  => (ENVIRONMENT !== 'production'),
    'cacheOn'  => false,
    'cacheDir' => '',
    'charset'  => 'utf8',
    'DBCollat' => 'utf8_general_ci',
    'swapPre'  => '',
    'encrypt'  => false,
    'compress' => false,
    'strictOn' => false,
    'failover' => [],
    'port'     => 3306,
];
```

#### 3.3 Run Database Migrations
```bash
php spark migrate
```

#### 3.4 Seed Initial Data (Optional)
```bash
php spark db:seed CurriculumSeeder
php spark db:seed StrandSeeder
php spark db:seed SchoolYearSeeder
```

### 4. Configure Environment

#### 4.1 Copy Environment File
```bash
cp env .env
```

#### 4.2 Update Environment Variables
Edit `.env` file:
```env
#--------------------------------------------------------------------
# ENVIRONMENT
#--------------------------------------------------------------------
CI_ENVIRONMENT = development

#--------------------------------------------------------------------
# APP
#--------------------------------------------------------------------
app.baseURL = 'http://localhost:8080/'
app.forceGlobalSecureRequests = false

#--------------------------------------------------------------------
# DATABASE
#--------------------------------------------------------------------
database.default.hostname = localhost
database.default.database = sis_enrollment
database.default.username = your_mysql_username
database.default.password = your_mysql_password
database.default.DBDriver = MySQLi
database.default.port = 3306

#--------------------------------------------------------------------
# SESSION
#--------------------------------------------------------------------
session.driver = 'CodeIgniter\Session\Handlers\FileHandler'
session.cookieName = 'ci_session'
session.expiration = 7200
session.savePath = null
session.matchIP = false
session.timeToUpdate = 300
session.regenerateDestroySession = false
```

### 5. Set Up File Permissions

#### 5.1 Create Upload Directories
```bash
mkdir -p writable/uploads/documents
mkdir -p writable/uploads/student_documents
mkdir -p writable/logs
```

#### 5.2 Set Permissions (Linux/Mac)
```bash
chmod -R 755 writable/
chmod -R 755 public/
```

#### 5.3 Set Permissions (Windows)
Right-click folders → Properties → Security → Edit → Full Control

### 6. Start the Development Server

#### Option A: PHP Built-in Server (Recommended for Development)
```bash
php spark serve
```
Access: `http://localhost:8080`

#### Option B: Apache/Nginx
Configure virtual host pointing to `public/` directory

### 7. Face Recognition Setup (Optional)

The face recognition system allows teachers to take attendance using computer vision. It consists of two Python scripts:

#### 7.1 Install Python Dependencies

**Option A: Using requirements.txt (Recommended)**
```bash
cd face_recognition_app
pip install -r requirements.txt
```

**Option B: Manual Installation**
```bash
# Core dependencies
pip install opencv-python==4.8.1.78
pip install face-recognition==1.3.0
pip install dlib==19.24.2
pip install mysql-connector-python==8.1.0
pip install numpy==1.24.3
pip install Pillow==10.0.1
pip install requests==2.31.0

# For Windows users (if dlib installation fails)
pip install cmake
pip install dlib
```

#### 7.2 Configure Database Connection

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

#### 7.3 Test Face Recognition Setup

**Test Database Connection:**
```bash
cd face_recognition_app
python -c "import db; print('Database connection successful!')"
```

**Test Camera Access:**
```bash
python -c "import cv2; cap = cv2.VideoCapture(0); print('Camera access:', cap.isOpened()); cap.release()"
```

#### 7.4 Face Recognition Workflow

**Step 1: Capture Student Faces**
```bash
python capture_faces.py
```
- Enter student LRN when prompted
- Position student's face in camera view
- Press 'q' to capture and save face encoding
- Repeat for all students

**Step 2: Take Attendance**
```bash
python recognize_faces.py
```
- Enter subject code (e.g., "ENG7-01")
- System will recognize faces and record attendance
- Press 'q' to quit

#### 7.5 Troubleshooting Face Recognition

**Common Issues:**

1. **Camera not detected:**
   ```bash
   # Check available cameras
   python -c "import cv2; print([i for i in range(10) if cv2.VideoCapture(i).isOpened()])"
   ```

2. **dlib installation fails (Windows):**
   ```bash
   # Install Visual C++ Build Tools first
   # Then install cmake and dlib
   pip install cmake
   pip install dlib
   ```

3. **Face not recognized:**
   - Ensure good lighting
   - Face should be clearly visible
   - Try capturing face again with better angle

4. **Database connection error:**
   - Verify MySQL credentials in `db.py`
   - Ensure MySQL service is running
   - Check if `attendance_records` table exists

#### 7.6 Face Recognition API Endpoints

The system provides API endpoints for attendance recording:

**Record Attendance:**
```bash
POST http://localhost:8080/api/attendance/record
Content-Type: application/json

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

#### 7.7 Security Considerations

- **Face Data Storage**: Face encodings are stored as binary data in the database
- **Privacy**: Only authorized teachers can access attendance records
- **Data Retention**: Consider implementing data retention policies
- **Access Control**: Ensure only teachers assigned to subjects can take attendance

#### 7.8 Performance Optimization

**For Large Student Populations:**
- Use face encoding caching
- Implement batch processing
- Consider using GPU acceleration for face recognition
- Optimize database queries for attendance records

**Memory Management:**
- Clear face encodings from memory after processing
- Use efficient image resizing
- Implement proper error handling for camera access

## 🔐 Default Admin Account

After running migrations, create an admin account:

### Option 1: Via Database
```sql
INSERT INTO users (username, password, role, first_name, last_name, email, is_active, created_at, updated_at) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'System', 'Administrator', 'admin@school.edu', 1, NOW(), NOW());
```
Password: `password`

### Option 2: Via CodeIgniter CLI
```bash
php spark make:controller AdminSetup
# Then create a setup method to create admin user
```

## 🧪 Testing the Setup

### 1. Access the Application
- **Main URL**: `http://localhost:8080`
- **Admin Login**: `http://localhost:8080/auth/login`

### 2. Test Basic Functionality
1. **Login** as admin
2. **Create** a school year
3. **Add** curriculum/strands
4. **Create** sections
5. **Add** students
6. **Test** face recognition (if enabled)

### 3. Test Face Recognition (Optional)
```bash
cd face_recognition_app
python capture_faces.py  # Capture student faces
python recognize_faces.py  # Take attendance
```

## 🐛 Troubleshooting

### Common Issues

#### 1. Database Connection Error
- Check MySQL service is running
- Verify credentials in `Database.php`
- Ensure database exists

#### 2. Permission Denied Errors
- Check file permissions on `writable/` directory
- Ensure web server has write access

#### 3. Face Recognition Issues
- **Windows**: Install Visual C++ Build Tools first, then cmake, then dlib
- **Camera Access**: Ensure camera permissions are granted
- **Database Connection**: Verify MySQL credentials in `face_recognition_app/db.py`
- **Face Recognition**: Ensure good lighting and clear face visibility
- **Virtual Environment**: Use `python -m venv .venv` for isolated Python packages

#### 4. Migration Errors
```bash
# Check migration status
php spark migrate:status

# Rollback if needed
php spark migrate:rollback

# Refresh migrations
php spark migrate:refresh
```

#### 5. Composer Issues
```bash
# Clear composer cache
composer clear-cache

# Reinstall dependencies
composer install --no-dev --optimize-autoloader
```

## 📁 Project Structure

```
enrollment-system/
├── app/
│   ├── Controllers/          # Application controllers
│   ├── Models/              # Database models
│   ├── Views/               # HTML templates
│   ├── Config/              # Configuration files
│   ├── Filters/             # Authentication filters
│   └── Libraries/           # Custom libraries
├── public/                  # Web root directory
├── writable/               # Logs, cache, uploads
├── face_recognition_app/    # Python face recognition
│   ├── capture_faces.py    # Capture student faces
│   ├── recognize_faces.py  # Take attendance
│   ├── db.py              # Database connection
│   └── requirements.txt   # Python dependencies
├── .env                    # Environment variables
└── composer.json           # PHP dependencies
```

## 🔧 Development Tips

### 1. Enable Debug Mode
In `.env`:
```env
CI_ENVIRONMENT = development
```

### 2. View Logs
```bash
tail -f writable/logs/log-$(date +%Y-%m-%d).php
```

### 3. Clear Cache
```bash
php spark cache:clear
```

### 4. Database Backup
```bash
mysqldump -u username -p sis_enrollment > backup.sql
```

## 🚀 Production Deployment

For production deployment:

1. **Set Environment**: `CI_ENVIRONMENT = production`
2. **Disable Debug**: `app.debug = false`
3. **Use HTTPS**: `app.forceGlobalSecureRequests = true`
4. **Optimize**: `composer install --no-dev --optimize-autoloader`
5. **Set Permissions**: Restrict write access to necessary directories only

## 📞 Support

If you encounter issues:
1. Check the logs in `writable/logs/`
2. Verify all prerequisites are installed
3. Ensure database connection is working
4. Check file permissions
5. For face recognition issues, see `FACE_RECOGNITION_SETUP.md`

## 📚 Additional Documentation

- **`QUICK_START.md`** - 5-minute setup guide
- **`FACE_RECOGNITION_SETUP.md`** - Detailed face recognition setup
- **`DATABASE_SCHEMA.md`** - Database structure documentation
- **`DEPLOYMENT_GUIDE.md`** - Complete online deployment guide

---

**Happy Coding! 🎉**
