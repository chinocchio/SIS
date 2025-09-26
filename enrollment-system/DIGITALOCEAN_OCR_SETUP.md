# 🖥️ DigitalOcean OCR Setup Guide

This guide will help you set up OCR (Optical Character Recognition) functionality on DigitalOcean for your School Information System.

## 📋 OCR Overview

**What OCR Does in Your System:**
- **SF9 Document Processing**: Automatically extracts student information from Form 137 (SF9) documents
- **Data Extraction**: Pulls out LRN, full name, birth date, gender, grade level, and other details
- **Student Enrollment**: Streamlines the enrollment process by auto-filling forms

**Current Windows Configuration:**
```env
ocr.enabled = true
ocr.tesseract_path = "C:\\Program Files\\Tesseract-OCR\\tesseract.exe"
```

## 🚀 DigitalOcean OCR Setup

### Step 1: Create DigitalOcean Droplet

**1.1 Choose Droplet Configuration**
- **Size**: Basic Plan - $6/month (1GB RAM, 1 vCPU, 25GB SSD)
- **OS**: Ubuntu 22.04 LTS
- **Region**: Choose closest to your users
- **Authentication**: SSH Key (recommended) or Password

**1.2 Create Droplet**
```bash
# After creating droplet, connect via SSH
ssh root@your-droplet-ip
```

### Step 2: Install LAMP Stack

**2.1 Update System**
```bash
sudo apt update && sudo apt upgrade -y
```

**2.2 Install Apache**
```bash
sudo apt install apache2 -y
sudo systemctl enable apache2
sudo systemctl start apache2
```

**2.3 Install PHP 8.1**
```bash
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install php8.1 php8.1-cli php8.1-common php8.1-mysql php8.1-zip php8.1-gd php8.1-mbstring php8.1-curl php8.1-xml php8.1-bcmath php8.1-imagick -y
```

**2.4 Install MySQL**
```bash
sudo apt install mysql-server -y
sudo mysql_secure_installation
```

**2.5 Install Composer**
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### Step 3: Install Tesseract OCR

**3.1 Install Tesseract**
```bash
# Install Tesseract OCR engine
sudo apt install tesseract-ocr -y

# Install additional language packs (optional)
sudo apt install tesseract-ocr-eng tesseract-ocr-fil -y

# Install image processing libraries
sudo apt install imagemagick -y
```

**3.2 Verify Installation**
```bash
# Check Tesseract version
tesseract --version

# Test OCR functionality
echo "Testing OCR" | tesseract stdin stdout
```

**3.3 Find Tesseract Path**
```bash
# Find where Tesseract is installed
which tesseract
# Output: /usr/bin/tesseract
```

### Step 4: Deploy Your Application

**4.1 Clone/Upload Application**
```bash
# Option A: Git clone
cd /var/www/html
sudo git clone https://github.com/yourusername/sis.git
sudo chown -R www-data:www-data sis/

# Option B: Upload via SCP
# From your local machine:
scp -r enrollment-system root@your-droplet-ip:/var/www/html/sis
```

**4.2 Install Dependencies**
```bash
cd /var/www/html/sis
sudo -u www-data composer install --no-dev --optimize-autoloader
```

**4.3 Configure Environment**
```bash
sudo -u www-data cp env .env
sudo nano .env
```

**4.4 Update .env for DigitalOcean**
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
database.default.hostname = 'localhost'
database.default.database = 'sis_enrollment'
database.default.username = 'sis_user'
database.default.password = 'your_secure_password'
database.default.DBDriver = 'MySQLi'
database.default.DBDebug = false

#--------------------------------------------------------------------
# OCR Configuration (Updated for Linux)
#--------------------------------------------------------------------
ocr.enabled = true
ocr.tesseract_path = "/usr/bin/tesseract"
```

**4.5 Set Permissions**
```bash
sudo chown -R www-data:www-data /var/www/html/sis
sudo chmod -R 755 /var/www/html/sis
sudo chmod -R 777 /var/www/html/sis/writable
```

### Step 5: Configure Apache

**5.1 Enable Required Modules**
```bash
sudo a2enmod rewrite
sudo a2enmod ssl
sudo a2enmod headers
```

**5.2 Create Virtual Host**
```bash
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
    
    # OCR file upload limits
    LimitRequestBody 10485760  # 10MB limit
    
    ErrorLog ${APACHE_LOG_DIR}/sis_error.log
    CustomLog ${APACHE_LOG_DIR}/sis_access.log combined
</VirtualHost>
```

**5.3 Enable Site**
```bash
sudo a2ensite sis.conf
sudo a2dissite 000-default.conf
sudo systemctl restart apache2
```

### Step 6: Database Setup

**6.1 Create Database**
```sql
sudo mysql -u root -p

CREATE DATABASE sis_enrollment;
CREATE USER 'sis_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON sis_enrollment.* TO 'sis_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

**6.2 Run Migrations**
```bash
cd /var/www/html/sis
sudo -u www-data php spark migrate
sudo -u www-data php spark db:seed UserSeeder
```

### Step 7: SSL Certificate

**7.1 Install Certbot**
```bash
sudo apt install certbot python3-certbot-apache -y
```

**7.2 Obtain SSL Certificate**
```bash
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com
```

## 🔧 OCR Configuration Details

### Updated OcrService for Linux

The existing `OcrService.php` will work on Linux with minimal changes. Here's what happens:

**1. Path Detection**
```php
// The autoDetectTesseractPath() method will find:
// /usr/bin/tesseract (Linux)
// Instead of: C:\Program Files\Tesseract-OCR\tesseract.exe (Windows)
```

**2. Command Execution**
```php
// Linux command:
$command = '/usr/bin/tesseract "' . $filePath . '" stdout 2>&1';

// Windows command (your current):
$command = '"C:\Program Files\Tesseract-OCR\tesseract.exe" "' . $filePath . '" stdout 2>&1';
```

### OCR Testing

**Test OCR Functionality**
```bash
# Create a test image
cd /var/www/html/sis
sudo -u www-data php spark

# In CodeIgniter CLI:
$ocrService = new \App\Libraries\OcrService();
$result = $ocrService->checkTesseractAvailability();
print_r($result);
```

**Test with Sample Document**
```bash
# Upload a test SF9 document via admin panel
# Check logs for OCR processing
tail -f /var/www/html/sis/writable/logs/log-$(date +%Y-%m-%d).php
```

## 📁 File Upload Configuration

### Apache Configuration for Large Files

**Update Apache Configuration**
```bash
sudo nano /etc/apache2/apache2.conf
```

Add these settings:
```apache
# Increase upload limits for OCR documents
LimitRequestBody 10485760  # 10MB
```

**Update PHP Configuration**
```bash
sudo nano /etc/php/8.1/apache2/php.ini
```

Key settings:
```ini
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 60
memory_limit = 256M
max_input_time = 60
```

**Restart Services**
```bash
sudo systemctl restart apache2
sudo systemctl restart php8.1-fpm
```

## 🔍 OCR Troubleshooting

### Common Issues

**1. Tesseract Not Found**
```bash
# Check if Tesseract is installed
which tesseract
tesseract --version

# If not found, reinstall
sudo apt install tesseract-ocr -y
```

**2. Permission Denied**
```bash
# Check file permissions
ls -la /var/www/html/sis/writable/uploads/
sudo chown -R www-data:www-data /var/www/html/sis/writable/
sudo chmod -R 777 /var/www/html/sis/writable/uploads/
```

**3. OCR Processing Fails**
```bash
# Check Apache error logs
sudo tail -f /var/log/apache2/sis_error.log

# Check application logs
sudo tail -f /var/www/html/sis/writable/logs/log-$(date +%Y-%m-%d).php
```

**4. File Upload Issues**
```bash
# Check PHP error logs
sudo tail -f /var/log/php8.1-fpm.log

# Test file upload limits
php -i | grep upload_max_filesize
php -i | grep post_max_size
```

### OCR Testing Commands

**Test OCR with Sample Image**
```bash
# Create a test image with text
echo "Student Name: Juan Dela Cruz" > test.txt
convert -size 400x100 xc:white -pointsize 16 -fill black -annotate +10+50 @test.txt test_image.png

# Test OCR
tesseract test_image.png stdout
```

**Test OCR Service**
```bash
cd /var/www/html/sis
sudo -u www-data php spark

# In CodeIgniter CLI:
$ocrService = new \App\Libraries\OcrService();
$result = $ocrService->extractText('/path/to/test_image.png');
print_r($result);
```

## 📊 Performance Optimization

### OCR Performance Tips

**1. Image Preprocessing**
```bash
# Install additional image processing tools
sudo apt install imagemagick libmagickwand-dev -y
```

**2. Tesseract Optimization**
```bash
# Use specific language models for better accuracy
tesseract image.png output -l eng+fil
```

**3. File Processing**
```bash
# Optimize images before OCR
convert input.pdf -density 300 -quality 100 output.png
```

### Server Optimization

**1. Increase PHP Memory**
```ini
# In php.ini
memory_limit = 512M
max_execution_time = 120
```

**2. Enable Caching**
```bash
# Install Redis for caching
sudo apt install redis-server -y
sudo systemctl enable redis-server
sudo systemctl start redis-server
```

## 🔒 Security Considerations

### File Upload Security

**1. File Validation**
```php
// The application already validates:
// - File extensions (jpg, jpeg, png, pdf)
// - File size (max 5MB)
// - File type validation
```

**2. Directory Permissions**
```bash
# Secure upload directory
sudo chmod 755 /var/www/html/sis/writable/uploads/
sudo chmod 644 /var/www/html/sis/writable/uploads/*
```

**3. Process Isolation**
```bash
# Run OCR in isolated environment
sudo chroot /var/www/html/sis/writable/uploads/ tesseract input.png output
```

## 📈 Monitoring OCR Performance

### Log Monitoring

**1. OCR Processing Logs**
```bash
# Monitor OCR processing
sudo tail -f /var/www/html/sis/writable/logs/log-$(date +%Y-%m-%d).php | grep OCR
```

**2. System Resource Monitoring**
```bash
# Monitor CPU and memory usage during OCR
htop
iotop
```

**3. File Upload Monitoring**
```bash
# Monitor upload directory
watch -n 1 'ls -la /var/www/html/sis/writable/uploads/'
```

## 💰 Cost Breakdown

### DigitalOcean Droplet Costs

**Basic Setup ($6/month)**
- 1GB RAM, 1 vCPU, 25GB SSD
- Sufficient for small to medium schools
- Can handle 50-100 concurrent users

**Recommended Setup ($12/month)**
- 2GB RAM, 1 vCPU, 50GB SSD
- Better performance for OCR processing
- Can handle 100-200 concurrent users

**High Performance ($24/month)**
- 4GB RAM, 2 vCPU, 80GB SSD
- Optimal for large schools
- Can handle 200+ concurrent users

### Additional Costs
- **Domain**: $10-15/year
- **SSL Certificate**: Free (Let's Encrypt)
- **Backup Storage**: $1-5/month (optional)

## 🎯 Go-Live Checklist

### Pre-Deployment
- [ ] Test OCR with sample SF9 documents
- [ ] Verify file upload limits
- [ ] Test all user roles and permissions
- [ ] Check database connectivity
- [ ] Verify SSL certificate

### Deployment Day
- [ ] Upload application files
- [ ] Configure environment variables
- [ ] Run database migrations
- [ ] Test OCR functionality
- [ ] Verify file uploads
- [ ] Test SF9 document processing

### Post-Deployment
- [ ] Monitor OCR processing logs
- [ ] Test with real SF9 documents
- [ ] Train users on OCR features
- [ ] Set up automated backups
- [ ] Monitor server performance

## 🆘 Support and Maintenance

### Regular Maintenance

**Weekly Tasks**
- Monitor OCR processing logs
- Check disk space usage
- Review error logs
- Test backup procedures

**Monthly Tasks**
- Update system packages
- Review OCR accuracy
- Optimize database queries
- Security updates

**Yearly Tasks**
- Full system backup
- Performance review
- Security audit
- Capacity planning

---

**Need help with OCR setup?** The OCR functionality will work seamlessly on DigitalOcean with the Linux Tesseract installation! 🚀
