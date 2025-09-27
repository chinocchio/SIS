# ✅ DigitalOcean Deployment Checklist

Use this checklist to ensure a smooth deployment of your SIS with face recognition.

## 📋 Pre-Deployment Preparation

### Local Environment
- [ ] **Backup local database** using `mysqldump`
- [ ] **Export database** to SQL file
- [ ] **Test face recognition** locally (working)
- [ ] **Verify all features** working locally
- [ ] **Prepare domain name** (if using custom domain)
- [ ] **Gather credentials** (admin, teacher, student accounts)

### Files to Upload
- [ ] **Complete SIS codebase** (all files)
- [ ] **Database backup** (.sql file)
- [ ] **Face recognition Python scripts**
- [ ] **Requirements files** (composer.json, requirements.txt)
- [ ] **Environment configuration** (.env template)

## 🖥️ DigitalOcean Setup

### Droplet Creation
- [ ] **Create Ubuntu 22.04 LTS droplet**
- [ ] **Minimum specs**: 4 vCPUs, 8GB RAM, 160GB SSD
- [ ] **Configure SSH key** authentication
- [ ] **Set hostname** (e.g., sis-server)
- [ ] **Note server IP address**

### Initial Server Setup
- [ ] **Connect via SSH** to server
- [ ] **Update system packages** (`apt update && apt upgrade`)
- [ ] **Run deployment script** (`bash deploy_to_digitalocean.sh`)
- [ ] **Verify packages installed** correctly

## 🗄️ Database Setup

### MySQL Configuration
- [ ] **Secure MySQL installation** (`mysql_secure_installation`)
- [ ] **Create database** (`enrollment_db`)
- [ ] **Create database user** (`sis_user`)
- [ ] **Set proper permissions**
- [ ] **Import database backup**
- [ ] **Verify data imported** correctly

### Database Verification
- [ ] **Check all tables** exist
- [ ] **Verify record counts**
- [ ] **Test database connection** from PHP
- [ ] **Check face encodings** in students table

## 📁 Application Deployment

### File Upload
- [ ] **Upload SIS files** to `/var/www/html/sis/`
- [ ] **Set correct permissions** (`chown -R www-data:www-data`)
- [ ] **Set writable permissions** (`chmod -R 777 writable/`)
- [ ] **Configure .env file** with production settings

### Environment Configuration
- [ ] **Update baseURL** to production domain
- [ ] **Set CI_ENVIRONMENT** to production
- [ ] **Configure database credentials**
- [ ] **Set OCR path** to `/usr/bin/tesseract`
- [ ] **Enable HTTPS** settings

## 🐍 Python Face Recognition Setup

### Virtual Environment
- [ ] **Create Python virtual environment**
- [ ] **Activate virtual environment**
- [ ] **Install Python dependencies**
- [ ] **Install face recognition models**
- [ ] **Test Python scripts** manually

### Face Recognition Testing
- [ ] **Test face capture script**
- [ ] **Test face recognition script**
- [ ] **Verify web integration** works
- [ ] **Test camera access** from web

## 🌐 Web Server Configuration

### Apache Setup
- [ ] **Create virtual host** configuration
- [ ] **Enable site** (`a2ensite`)
- [ ] **Enable required modules**
- [ ] **Restart Apache** service
- [ ] **Test web server** responds

### SSL Certificate
- [ ] **Install Certbot**
- [ ] **Obtain SSL certificate**
- [ ] **Configure HTTPS redirect**
- [ ] **Test SSL certificate** validity

## 🔒 Security Configuration

### Firewall Setup
- [ ] **Enable UFW firewall**
- [ ] **Allow SSH (port 22)**
- [ ] **Allow HTTP (port 80)**
- [ ] **Allow HTTPS (port 443)**
- [ ] **Verify firewall status**

### File Permissions
- [ ] **Secure .env file** (`chmod 600`)
- [ ] **Set proper directory permissions**
- [ ] **Disable directory browsing**
- [ ] **Configure PHP security** settings

## 🧪 Testing Phase

### Web Application Testing
- [ ] **Test homepage** loads
- [ ] **Test admin login**
- [ ] **Test teacher login**
- [ ] **Test student login**
- [ ] **Test all major features**

### Face Recognition Testing
- [ ] **Test face capture** functionality
- [ ] **Test face recognition** for attendance
- [ ] **Verify camera access** works
- [ ] **Test attendance recording**

### Database Testing
- [ ] **Test data retrieval**
- [ ] **Test data insertion**
- [ ] **Test file uploads**
- [ ] **Test document viewing**

## 📊 Performance Optimization

### PHP Optimization
- [ ] **Configure OPcache**
- [ ] **Set memory limits**
- [ ] **Optimize execution time**
- [ ] **Configure file upload limits**

### Apache Optimization
- [ ] **Enable compression**
- [ ] **Configure caching**
- [ ] **Set security headers**
- [ ] **Optimize KeepAlive**

### Database Optimization
- [ ] **Create necessary indexes**
- [ ] **Optimize table structures**
- [ ] **Configure query cache**
- [ ] **Set proper buffer sizes**

## 🔍 Final Verification

### System Health
- [ ] **Check server resources** (CPU, RAM, disk)
- [ ] **Monitor error logs**
- [ ] **Test backup procedures**
- [ ] **Verify monitoring** setup

### Application Health
- [ ] **Test all user roles**
- [ ] **Verify face recognition** accuracy
- [ ] **Test file uploads/downloads**
- [ ] **Check email functionality**

### Security Verification
- [ ] **Test HTTPS enforcement**
- [ ] **Verify firewall** configuration
- [ ] **Check file permissions**
- [ ] **Test SQL injection** protection

## 📱 Domain Configuration

### DNS Setup
- [ ] **Configure A records**
- [ ] **Set up CNAME records**
- [ ] **Configure subdomains** (if needed)
- [ ] **Test DNS propagation**

### Domain Testing
- [ ] **Test domain resolution**
- [ ] **Verify SSL certificate** for domain
- [ ] **Test HTTPS redirect**
- [ ] **Check mobile compatibility**

## 🚀 Go Live Checklist

### Pre-Launch
- [ ] **Final backup** of local system
- [ ] **Document all credentials**
- [ ] **Prepare user documentation**
- [ ] **Set up monitoring** alerts

### Launch Day
- [ ] **Announce maintenance** window
- [ ] **Deploy to production**
- [ ] **Test all critical functions**
- [ ] **Monitor for issues**

### Post-Launch
- [ ] **Monitor system** for 24 hours
- [ ] **Gather user feedback**
- [ ] **Document any issues**
- [ ] **Plan maintenance** schedule

## 📞 Support Information

### Important Files
- **Application Logs**: `/var/www/html/sis/writable/logs/`
- **Apache Logs**: `/var/log/apache2/`
- **MySQL Logs**: `/var/log/mysql/`
- **System Logs**: `/var/log/syslog`

### Useful Commands
```bash
# Check system status
systemctl status apache2 mysql

# View logs
tail -f /var/log/apache2/sis_error.log
tail -f /var/www/html/sis/writable/logs/log-$(date +%Y-%m-%d).log

# Check resources
htop
df -h
free -h

# Test face recognition
sudo -u www-data python3.10 /var/www/html/sis/face_recognition_app/capture_faces_web.py
```

## 🎉 Success Criteria

Your deployment is successful when:
- ✅ **Website loads** at your domain
- ✅ **All user roles** can login
- ✅ **Face recognition** works accurately
- ✅ **File uploads/downloads** work
- ✅ **SSL certificate** is valid
- ✅ **Performance** is acceptable
- ✅ **No critical errors** in logs

---

**Congratulations! Your SIS is now live on DigitalOcean!** 🚀

