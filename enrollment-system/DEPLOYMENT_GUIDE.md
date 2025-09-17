# 🌐 School Information System - Online Deployment Guide

This comprehensive guide will help you deploy your School Information System (SIS) to make it available online for students, teachers, and administrators.

## 📋 Project Overview

**Technology Stack:**
- **Backend**: PHP 8.1+ with CodeIgniter 4 framework
- **Database**: MySQL 8.0+ / MariaDB 10.4+
- **Frontend**: HTML, CSS, JavaScript (Vanilla)
- **Face Recognition**: Python 3.8+ with OpenCV and face-recognition libraries
- **File Storage**: Local file system (documents, uploads)
- **Authentication**: Session-based with role-based access control

**Key Features:**
- Multi-role authentication (Admin, Registrar, Teacher, Student)
- Student enrollment and document management
- Teacher assignment and grade management
- Face recognition attendance system
- Document upload and viewing
- Academic year and curriculum management

## 🚀 Deployment Options

### Option 1: Shared Hosting (Budget-Friendly)

**Best for**: Small schools, limited budget, simple setup

**Requirements:**
- PHP 8.1+ support
- MySQL database
- File upload capability
- SSL certificate (recommended)

**Popular Providers:**
- **Hostinger** ($1.99/month)
- **Bluehost** ($2.95/month)
- **SiteGround** ($3.99/month)
- **A2 Hosting** ($2.99/month)

**Setup Steps:**
1. Purchase shared hosting plan
2. Upload files via FTP/cPanel File Manager
3. Create MySQL database
4. Configure database connection
5. Set file permissions
6. Run migrations

### Option 2: VPS Hosting (Recommended)

**Best for**: Medium to large schools, better performance, more control

**Requirements:**
- Ubuntu/CentOS server
- Root access
- Domain name
- SSL certificate

**Popular Providers:**
- **DigitalOcean** ($5/month droplet)
- **Linode** ($5/month)
- **Vultr** ($3.50/month)
- **AWS EC2** (Pay-as-you-go)
- **Google Cloud Platform** (Pay-as-you-go)

**Setup Steps:**
1. Create VPS instance
2. Install LAMP stack
3. Configure domain and SSL
4. Deploy application
5. Set up monitoring

### Option 3: Cloud Platform (Enterprise)

**Best for**: Large institutions, high availability, scalability

**Platforms:**
- **AWS** (EC2 + RDS + S3)
- **Google Cloud** (Compute Engine + Cloud SQL + Storage)
- **Microsoft Azure** (Virtual Machines + Database)
- **DigitalOcean App Platform**

## 🛠️ Detailed Deployment Instructions

### Shared Hosting Deployment

#### Step 1: Prepare Your Application

**1.1 Optimize for Production**
```bash
# In your local project directory
composer install --no-dev --optimize-autoloader

# Remove development files
rm -rf tests/
rm -rf .git/
rm -rf face_recognition_app/venv/
```

**1.2 Create Production Environment File**
```bash
# Copy and modify environment file
cp env .env
```

Edit `.env`:
```env
#--------------------------------------------------------------------
# ENVIRONMENT
#--------------------------------------------------------------------
CI_ENVIRONMENT = production

#--------------------------------------------------------------------
# APP
#--------------------------------------------------------------------
app.baseURL = 'https://yourdomain.com/'
app.forceGlobalSecureRequests = true

#--------------------------------------------------------------------
# DATABASE
#--------------------------------------------------------------------
database.default.hostname = localhost
database.default.database = your_database_name
database.default.username = your_database_user
database.default.password = your_database_password
database.default.DBDriver = MySQLi
database.default.DBDebug = false
```

#### Step 2: Upload to Hosting

**2.1 Upload Files**
```bash
# Using FTP client (FileZilla, WinSCP)
# Upload entire enrollment-system folder to public_html/
```

**2.2 Set File Permissions**
```bash
# Via cPanel File Manager or SSH
chmod 755 public/
chmod 755 writable/
chmod 777 writable/uploads/
chmod 777 writable/logs/
chmod 777 writable/cache/
```

#### Step 3: Database Setup

**3.1 Create Database**
```sql
-- Via cPanel MySQL or phpMyAdmin
CREATE DATABASE sis_enrollment;
CREATE USER 'sis_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON sis_enrollment.* TO 'sis_user'@'localhost';
FLUSH PRIVILEGES;
```

**3.2 Run Migrations**
```bash
# Via cPanel Terminal or SSH
cd public_html/enrollment-system
php spark migrate
php spark db:seed UserSeeder
```

### VPS Deployment (Ubuntu 20.04+)

#### Step 1: Server Setup

**1.1 Update System**
```bash
sudo apt update && sudo apt upgrade -y
```

**1.2 Install LAMP Stack**
```bash
# Install Apache
sudo apt install apache2 -y

# Install PHP 8.1
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install php8.1 php8.1-cli php8.1-common php8.1-mysql php8.1-zip php8.1-gd php8.1-mbstring php8.1-curl php8.1-xml php8.1-bcmath -y

# Install MySQL
sudo apt install mysql-server -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

**1.3 Configure Apache**
```bash
# Enable required modules
sudo a2enmod rewrite
sudo a2enmod ssl
sudo a2enmod headers

# Create virtual host
sudo nano /etc/apache2/sites-available/sis.conf
```

Virtual host configuration:
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

<VirtualHost *:443>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/html/sis/public
    
    <Directory /var/www/html/sis/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/yourdomain.com/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/yourdomain.com/privkey.pem
    
    ErrorLog ${APACHE_LOG_DIR}/sis_ssl_error.log
    CustomLog ${APACHE_LOG_DIR}/sis_ssl_access.log combined
</VirtualHost>
```

**1.4 Enable Site**
```bash
sudo a2ensite sis.conf
sudo a2dissite 000-default.conf
sudo systemctl restart apache2
```

#### Step 2: Database Configuration

**2.1 Secure MySQL**
```bash
sudo mysql_secure_installation
```

**2.2 Create Database and User**
```sql
sudo mysql -u root -p

CREATE DATABASE sis_enrollment;
CREATE USER 'sis_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON sis_enrollment.* TO 'sis_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### Step 3: Deploy Application

**3.1 Clone/Upload Application**
```bash
# Option A: Git clone
cd /var/www/html
sudo git clone https://github.com/yourusername/sis.git
sudo chown -R www-data:www-data sis/

# Option B: Upload via SCP/SFTP
# Upload files to /var/www/html/sis/
```

**3.2 Install Dependencies**
```bash
cd /var/www/html/sis
sudo -u www-data composer install --no-dev --optimize-autoloader
```

**3.3 Configure Environment**
```bash
sudo -u www-data cp env .env
sudo nano .env
```

**3.4 Set Permissions**
```bash
sudo chown -R www-data:www-data /var/www/html/sis
sudo chmod -R 755 /var/www/html/sis
sudo chmod -R 777 /var/www/html/sis/writable
```

**3.5 Run Migrations**
```bash
sudo -u www-data php spark migrate
sudo -u www-data php spark db:seed UserSeeder
```

#### Step 4: SSL Certificate

**4.1 Install Certbot**
```bash
sudo apt install certbot python3-certbot-apache -y
```

**4.2 Obtain SSL Certificate**
```bash
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com
```

### Cloud Platform Deployment (AWS)

#### Step 1: Infrastructure Setup

**1.1 Launch EC2 Instance**
- Instance Type: t3.small (2 vCPU, 2 GB RAM)
- OS: Ubuntu Server 20.04 LTS
- Security Group: Allow HTTP (80), HTTPS (443), SSH (22)

**1.2 Create RDS Database**
- Engine: MySQL 8.0
- Instance Class: db.t3.micro
- Storage: 20 GB
- Security Group: Allow MySQL (3306) from EC2

**1.3 Create S3 Bucket (Optional)**
- For file storage and backups
- Enable versioning and encryption

#### Step 2: Application Deployment

**2.1 Connect to EC2**
```bash
ssh -i your-key.pem ubuntu@your-ec2-ip
```

**2.2 Install Dependencies**
```bash
# Same as VPS setup
sudo apt update && sudo apt upgrade -y
sudo apt install apache2 php8.1 mysql-client composer -y
```

**2.3 Deploy Application**
```bash
# Clone repository
cd /var/www/html
sudo git clone https://github.com/yourusername/sis.git
sudo chown -R www-data:www-data sis/
```

**2.4 Configure Database Connection**
```bash
# Edit .env file with RDS endpoint
sudo nano sis/.env
```

**2.5 Run Migrations**
```bash
cd sis
sudo -u www-data php spark migrate
```

## 🔧 Configuration for Online Deployment

### Environment Configuration

**Production .env File:**
```env
#--------------------------------------------------------------------
# ENVIRONMENT
#--------------------------------------------------------------------
CI_ENVIRONMENT = production

#--------------------------------------------------------------------
# APP
#--------------------------------------------------------------------
app.baseURL = 'https://yourdomain.com/'
app.forceGlobalSecureRequests = true
app.indexPage = ''

#--------------------------------------------------------------------
# DATABASE
#--------------------------------------------------------------------
database.default.hostname = your_database_host
database.default.database = sis_enrollment
database.default.username = your_database_user
database.default.password = your_database_password
database.default.DBDriver = MySQLi
database.default.DBDebug = false
database.default.charset = utf8mb4
database.default.DBCollat = utf8mb4_general_ci

#--------------------------------------------------------------------
# SECURITY
#--------------------------------------------------------------------
encryption.key = your_32_character_encryption_key_here
session.driver = Database
session.cookieName = sis_session
session.expiration = 7200
session.regenerateDestroy = true

#--------------------------------------------------------------------
# LOGGING
#--------------------------------------------------------------------
logger.threshold = 4
```

### Security Hardening

**1. File Permissions**
```bash
# Secure file permissions
find /var/www/html/sis -type f -exec chmod 644 {} \;
find /var/www/html/sis -type d -exec chmod 755 {} \;
chmod -R 777 /var/www/html/sis/writable
chmod 600 /var/www/html/sis/.env
```

**2. Apache Security Headers**
```apache
# Add to virtual host
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
Header always set Referrer-Policy "strict-origin-when-cross-origin"
```

**3. PHP Security**
```bash
# Edit php.ini
sudo nano /etc/php/8.1/apache2/php.ini
```

Key settings:
```ini
expose_php = Off
display_errors = Off
log_errors = On
error_log = /var/log/php_errors.log
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 30
memory_limit = 256M
```

### Performance Optimization

**1. Enable Caching**
```bash
# Install Redis (optional)
sudo apt install redis-server -y
```

**2. Optimize Apache**
```apache
# Enable compression
LoadModule deflate_module modules/mod_deflate.so
<Location />
    SetOutputFilter DEFLATE
    SetEnvIfNoCase Request_URI \
        \.(?:gif|jpe?g|png)$ no-gzip dont-vary
    SetEnvIfNoCase Request_URI \
        \.(?:exe|t?gz|zip|bz2|sit|rar)$ no-gzip dont-vary
</Location>
```

**3. Database Optimization**
```sql
-- Add indexes for better performance
ALTER TABLE students ADD INDEX idx_lrn (lrn);
ALTER TABLE students ADD INDEX idx_status (status);
ALTER TABLE student_grades ADD INDEX idx_student_subject (student_id, subject_id);
ALTER TABLE attendance_records ADD INDEX idx_student_date (student_id, recorded_at);
```

## 📱 Face Recognition Deployment

### Option 1: Local Server Setup

**Requirements:**
- Webcam/camera access
- Python 3.8+ on server
- Sufficient RAM (4GB+)

**Setup:**
```bash
# Install Python dependencies
cd /var/www/html/sis/face_recognition_app
python3 -m venv .venv
source .venv/bin/activate
pip install -r requirements.txt

# Configure database connection
nano db.py
```

**Usage:**
```bash
# Capture faces (run once per student)
python3 capture_faces.py

# Take attendance (run during class)
python3 recognize_faces.py
```

### Option 2: Separate Face Recognition Server

**Architecture:**
- Main web server: PHP application
- Face recognition server: Python application
- Communication: REST API

**Setup:**
```bash
# On face recognition server
git clone https://github.com/yourusername/sis.git
cd sis/face_recognition_app
python3 -m venv .venv
source .venv/bin/activate
pip install -r requirements.txt

# Configure API endpoint
nano db.py  # Update API_BASE_URL
```

## 🔍 Monitoring and Maintenance

### Log Monitoring

**1. Application Logs**
```bash
# Monitor application logs
tail -f /var/www/html/sis/writable/logs/log-$(date +%Y-%m-%d).php

# Monitor Apache logs
tail -f /var/log/apache2/sis_error.log
tail -f /var/log/apache2/sis_access.log
```

**2. Database Monitoring**
```sql
-- Check database status
SHOW PROCESSLIST;
SHOW STATUS LIKE 'Threads_connected';
SHOW STATUS LIKE 'Slow_queries';
```

### Backup Strategy

**1. Database Backup**
```bash
#!/bin/bash
# Daily database backup script
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u root -p sis_enrollment > /backups/db_backup_$DATE.sql
gzip /backups/db_backup_$DATE.sql

# Keep only last 7 days
find /backups -name "db_backup_*.sql.gz" -mtime +7 -delete
```

**2. File Backup**
```bash
#!/bin/bash
# Daily file backup script
DATE=$(date +%Y%m%d_%H%M%S)
tar -czf /backups/files_backup_$DATE.tar.gz /var/www/html/sis/writable/uploads/
tar -czf /backups/app_backup_$DATE.tar.gz /var/www/html/sis/

# Upload to S3 (optional)
aws s3 cp /backups/db_backup_$DATE.sql.gz s3://your-backup-bucket/
aws s3 cp /backups/files_backup_$DATE.tar.gz s3://your-backup-bucket/
```

### Performance Monitoring

**1. Server Monitoring**
```bash
# Install monitoring tools
sudo apt install htop iotop nethogs -y

# Monitor system resources
htop
iotop
nethogs
```

**2. Application Monitoring**
```bash
# Monitor PHP processes
ps aux | grep php
top -p $(pgrep php)
```

## 🚨 Troubleshooting

### Common Issues

**1. Database Connection Errors**
```bash
# Check MySQL status
sudo systemctl status mysql
sudo systemctl restart mysql

# Test connection
mysql -u sis_user -p -h localhost sis_enrollment
```

**2. File Permission Issues**
```bash
# Fix permissions
sudo chown -R www-data:www-data /var/www/html/sis
sudo chmod -R 755 /var/www/html/sis
sudo chmod -R 777 /var/www/html/sis/writable
```

**3. SSL Certificate Issues**
```bash
# Renew certificate
sudo certbot renew --dry-run
sudo certbot renew
sudo systemctl reload apache2
```

**4. Performance Issues**
```bash
# Check Apache status
sudo systemctl status apache2
sudo apache2ctl -M  # Check loaded modules

# Monitor memory usage
free -h
df -h
```

### Support and Maintenance

**1. Regular Updates**
```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Update PHP packages
composer update --no-dev
```

**2. Security Updates**
```bash
# Check for security updates
sudo apt list --upgradable
sudo unattended-upgrades
```

**3. Log Rotation**
```bash
# Configure log rotation
sudo nano /etc/logrotate.d/sis
```

Log rotation configuration:
```
/var/www/html/sis/writable/logs/*.php {
    daily
    missingok
    rotate 30
    compress
    delaycompress
    notifempty
    create 644 www-data www-data
}
```

## 📊 Cost Estimation

### Shared Hosting
- **Hosting**: $2-5/month
- **Domain**: $10-15/year
- **SSL**: Usually included
- **Total**: $35-75/year

### VPS Hosting
- **VPS**: $5-20/month
- **Domain**: $10-15/year
- **SSL**: Free (Let's Encrypt)
- **Total**: $70-255/year

### Cloud Platform
- **EC2**: $10-50/month
- **RDS**: $15-100/month
- **S3**: $1-10/month
- **Domain**: $10-15/year
- **Total**: $432-900/year

## 🎯 Go-Live Checklist

### Pre-Deployment
- [ ] Test all functionality locally
- [ ] Optimize database queries
- [ ] Set up monitoring
- [ ] Create backup strategy
- [ ] Configure SSL certificate
- [ ] Test file uploads
- [ ] Verify email functionality

### Deployment Day
- [ ] Upload application files
- [ ] Configure database
- [ ] Run migrations
- [ ] Set file permissions
- [ ] Test all user roles
- [ ] Verify face recognition (if applicable)
- [ ] Test document uploads
- [ ] Check mobile responsiveness

### Post-Deployment
- [ ] Monitor logs for errors
- [ ] Test backup procedures
- [ ] Set up automated monitoring
- [ ] Train users on new system
- [ ] Document any customizations
- [ ] Plan maintenance schedule

## 📚 Additional Documentation

- **`QUICK_START.md`** - 5-minute setup guide
- **`FACE_RECOGNITION_SETUP.md`** - Detailed face recognition setup
- **`DATABASE_SCHEMA.md`** - Database structure documentation
- **`DIGITALOCEAN_OCR_SETUP.md`** - OCR setup on DigitalOcean

---

**Need help with deployment?** Check the troubleshooting section or refer to the main `SETUP_GUIDE.md` for additional configuration details! 🚀
