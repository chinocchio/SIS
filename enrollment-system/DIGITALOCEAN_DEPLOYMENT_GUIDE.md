# 🚀 DigitalOcean Deployment Guide for SIS with Face Recognition

This guide will help you deploy your School Information System with face recognition capabilities on DigitalOcean.

## 📋 Prerequisites

- DigitalOcean account
- Domain name (optional but recommended)
- Basic knowledge of Linux commands
- Your local SIS project ready

## 🎯 Deployment Overview

### What We'll Deploy:
- ✅ **CodeIgniter 4 Web Application**
- ✅ **MySQL Database**
- ✅ **Python Face Recognition System**
- ✅ **Apache/Nginx Web Server**
- ✅ **SSL Certificate**
- ✅ **Domain Configuration**

## 🖥️ Step 1: Create DigitalOcean Droplet

### 1.1 Create New Droplet
1. **Login** to DigitalOcean
2. **Click** "Create" → "Droplets"
3. **Choose** Ubuntu 22.04 LTS (64-bit)
4. **Select** Basic Plan:
   - **CPU**: 4 vCPUs (minimum for face recognition)
   - **RAM**: 8 GB (minimum for face recognition)
   - **Storage**: 160 GB SSD
   - **Bandwidth**: 5 TB transfer
5. **Authentication**: SSH Key (recommended) or Password
6. **Hostname**: `sis-server` (or your preferred name)
7. **Click** "Create Droplet"

### 1.2 Connect to Your Server
```bash
ssh root@YOUR_SERVER_IP
```

## 🔧 Step 2: Server Setup

### 2.1 Update System
```bash
apt update && apt upgrade -y
```

### 2.2 Install Required Packages
```bash
# Install Apache, PHP, MySQL, and other essentials
apt install -y apache2 mysql-server php8.1 php8.1-mysql php8.1-curl php8.1-json php8.1-mbstring php8.1-xml php8.1-zip php8.1-gd php8.1-intl php8.1-bcmath php8.1-cli php8.1-common php8.1-opcache php8.1-readline php8.1-soap php8.1-xmlrpc php8.1-xsl php8.1-zip unzip git curl wget software-properties-common
```

### 2.3 Install Python and Face Recognition Dependencies
```bash
# Install Python 3.10 (recommended for face recognition)
apt install -y python3.10 python3.10-venv python3.10-dev python3-pip

# Install system dependencies for face recognition
apt install -y build-essential cmake pkg-config libjpeg-dev libtiff5-dev libpng-dev libavcodec-dev libavformat-dev libswscale-dev libv4l-dev libxvidcore-dev libx264-dev libgtk-3-dev libatlas-base-dev gfortran libhdf5-dev libhdf5-serial-dev libhdf5-103 libqtgui4 libqtwebkit4 libqt4-test python3-pyqt5 libdc1394-22-dev libopenblas-dev liblapack-dev

# Install Tesseract OCR
apt install -y tesseract-ocr tesseract-ocr-eng
```

## 🗄️ Step 3: Database Setup

### 3.1 Secure MySQL Installation
```bash
mysql_secure_installation
```
Follow the prompts:
- Set root password
- Remove anonymous users: Y
- Disallow root login remotely: Y
- Remove test database: Y
- Reload privilege tables: Y

### 3.2 Create Database and User
```bash
mysql -u root -p
```

```sql
CREATE DATABASE enrollment_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'sis_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON enrollment_db.* TO 'sis_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

## 📁 Step 4: Deploy Your Application

### 4.1 Clone Your Repository
```bash
cd /var/www/html
git clone https://github.com/yourusername/your-sis-repo.git sis
# OR upload your files via SCP/SFTP
```

### 4.2 Set Permissions
```bash
cd /var/www/html/sis
chown -R www-data:www-data .
chmod -R 755 .
chmod -R 777 writable/
```

### 4.3 Configure Environment
```bash
cp env .env
nano .env
```

Update `.env` file:
```env
#--------------------------------------------------------------------
# ENVIRONMENT
#--------------------------------------------------------------------
CI_ENVIRONMENT = production

#--------------------------------------------------------------------
# APP
#--------------------------------------------------------------------
app.baseURL = 'https://yourdomain.com/'
app.indexPage = 'index.php'
app.forceGlobalSecureRequests = true

#--------------------------------------------------------------------
# DATABASE
#--------------------------------------------------------------------
database.default.hostname = 'localhost'
database.default.database = 'enrollment_db'
database.default.username = 'sis_user'
database.default.password = 'your_secure_password'
database.default.DBDriver = 'MySQLi'
database.default.port = 3306

#--------------------------------------------------------------------
# OCR Configuration
#--------------------------------------------------------------------
ocr.enabled = true
ocr.tesseract_path = "/usr/bin/tesseract"
```

### 4.4 Import Database
```bash
mysql -u sis_user -p enrollment_db < database_backup.sql
```

## 🐍 Step 5: Python Face Recognition Setup

### 5.1 Create Virtual Environment
```bash
cd /var/www/html/sis/face_recognition_app
python3.10 -m venv venv
source venv/bin/activate
```

### 5.2 Install Python Dependencies
```bash
pip install --upgrade pip
pip install opencv-python face-recognition dlib numpy requests mysql-connector-python setuptools
pip install git+https://github.com/ageitgey/face_recognition_models
```

### 5.3 Test Face Recognition
```bash
python capture_faces_web.py
```

## 🌐 Step 6: Web Server Configuration

### 6.1 Configure Apache Virtual Host
```bash
nano /etc/apache2/sites-available/sis.conf
```

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/html/sis/public
    
    <Directory /var/www/html/sis/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/sis_error.log
    CustomLog ${APACHE_LOG_DIR}/sis_access.log combined
</VirtualHost>
```

### 6.2 Enable Site and Modules
```bash
a2ensite sis.conf
a2enmod rewrite
a2enmod ssl
systemctl restart apache2
```

## 🔒 Step 7: SSL Certificate (Let's Encrypt)

### 7.1 Install Certbot
```bash
apt install -y certbot python3-certbot-apache
```

### 7.2 Get SSL Certificate
```bash
certbot --apache -d yourdomain.com -d www.yourdomain.com
```

## 🔧 Step 8: Configure Face Recognition Permissions

### 8.1 Set Python Script Permissions
```bash
chmod +x /var/www/html/sis/face_recognition_app/*.py
chown www-data:www-data /var/www/html/sis/face_recognition_app/*.py
```

### 8.2 Test Web-based Face Recognition
```bash
# Test if Python can be executed from web
sudo -u www-data python3.10 /var/www/html/sis/face_recognition_app/capture_faces_web.py
```

## 🚀 Step 9: Final Configuration

### 9.1 Update Base URL in CodeIgniter
```bash
nano /var/www/html/sis/app/Config/App.php
```

Update:
```php
public $baseURL = 'https://yourdomain.com/';
```

### 9.2 Set Production Environment
```bash
nano /var/www/html/sis/.env
```

Set:
```env
CI_ENVIRONMENT = production
```

### 9.3 Configure Logging
```bash
nano /var/www/html/sis/app/Config/Logger.php
```

Set log level to `error` for production:
```php
public $threshold = 4; // ERROR level
```

## 🔍 Step 10: Testing Your Deployment

### 10.1 Test Web Application
1. **Visit**: `https://yourdomain.com`
2. **Test**: Admin login, teacher login, student login
3. **Verify**: All features working

### 10.2 Test Face Recognition
1. **Login** as teacher
2. **Go to**: Face Recognition → Capture Faces
3. **Test**: Face capture functionality
4. **Test**: Face recognition for attendance

### 10.3 Test Database
```bash
mysql -u sis_user -p enrollment_db -e "SHOW TABLES;"
```

## 📊 Step 11: Performance Optimization

### 11.1 Enable Apache Mods
```bash
a2enmod deflate
a2enmod expires
a2enmod headers
systemctl restart apache2
```

### 11.2 Configure PHP Performance
```bash
nano /etc/php/8.1/apache2/php.ini
```

Update:
```ini
memory_limit = 256M
max_execution_time = 300
upload_max_filesize = 50M
post_max_size = 50M
opcache.enable = 1
opcache.memory_consumption = 128
```

### 11.3 Restart Services
```bash
systemctl restart apache2
systemctl restart mysql
```

## 🔐 Step 12: Security Hardening

### 12.1 Configure Firewall
```bash
ufw enable
ufw allow 22/tcp
ufw allow 80/tcp
ufw allow 443/tcp
ufw status
```

### 12.2 Secure File Permissions
```bash
chmod 600 /var/www/html/sis/.env
chmod 644 /var/www/html/sis/app/Config/*.php
chmod -R 755 /var/www/html/sis/public
```

### 12.3 Disable Directory Browsing
```bash
nano /etc/apache2/apache2.conf
```

Add:
```apache
<Directory /var/www/html/sis>
    Options -Indexes
</Directory>
```

## 📱 Step 13: Domain Configuration

### 13.1 Update DNS Records
In your domain registrar's DNS settings:
- **A Record**: `@` → `YOUR_SERVER_IP`
- **A Record**: `www` → `YOUR_SERVER_IP`

### 13.2 Test Domain
```bash
nslookup yourdomain.com
ping yourdomain.com
```

## 🎯 Step 14: Final Checklist

### ✅ Verify Everything Works:
- [ ] **Web application** loads correctly
- [ ] **Database** connection working
- [ ] **Admin login** functional
- [ ] **Teacher login** functional
- [ ] **Student login** functional
- [ ] **Face capture** working
- [ ] **Face recognition** for attendance working
- [ ] **SSL certificate** active
- [ ] **File uploads** working
- [ ] **Email functionality** (if configured)

## 🚨 Troubleshooting

### Common Issues:

#### 1. Face Recognition Not Working
```bash
# Check Python path in PHP
sudo -u www-data which python3.10

# Test face recognition manually
sudo -u www-data python3.10 /var/www/html/sis/face_recognition_app/capture_faces_web.py
```

#### 2. Database Connection Issues
```bash
# Check MySQL status
systemctl status mysql

# Test database connection
mysql -u sis_user -p enrollment_db -e "SELECT 1;"
```

#### 3. File Permission Issues
```bash
# Fix permissions
chown -R www-data:www-data /var/www/html/sis
chmod -R 755 /var/www/html/sis
chmod -R 777 /var/www/html/sis/writable
```

#### 4. SSL Certificate Issues
```bash
# Check certificate status
certbot certificates

# Renew certificate
certbot renew --dry-run
```

## 📈 Monitoring and Maintenance

### 14.1 Set Up Monitoring
```bash
# Install monitoring tools
apt install -y htop iotop nethogs

# Check system resources
htop
df -h
free -h
```

### 14.2 Regular Backups
```bash
# Create backup script
nano /root/backup_sis.sh
```

```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/root/backups"
mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u sis_user -p enrollment_db > $BACKUP_DIR/database_$DATE.sql

# Files backup
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/html/sis

# Keep only last 7 days of backups
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete
```

```bash
chmod +x /root/backup_sis.sh

# Add to crontab for daily backups
crontab -e
# Add: 0 2 * * * /root/backup_sis.sh
```

## 🎉 Congratulations!

Your School Information System with face recognition is now deployed on DigitalOcean!

### 🌐 Access Your System:
- **URL**: `https://yourdomain.com`
- **Admin**: Use your admin credentials
- **Teachers**: Can capture faces and take attendance
- **Students**: Can view grades and submit documents

### 📞 Support:
- **Logs**: `/var/log/apache2/sis_error.log`
- **Application Logs**: `/var/www/html/sis/writable/logs/`
- **System Resources**: `htop`, `df -h`, `free -h`

### 🔄 Updates:
- **Code Updates**: `git pull` in `/var/www/html/sis/`
- **Dependencies**: `composer install` for PHP, `pip install -r requirements.txt` for Python
- **Database**: Run migrations if needed

---

**Your SIS is now live and ready for production use!** 🚀

