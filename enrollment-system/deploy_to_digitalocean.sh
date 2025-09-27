#!/bin/bash

# DigitalOcean Deployment Script for SIS with Nginx
# Run this script on your DigitalOcean droplet after initial setup

echo "🚀 Starting SIS Deployment on DigitalOcean with Nginx..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    print_error "Please run as root (use sudo)"
    exit 1
fi

# Update system
print_status "Updating system packages..."
apt update && apt upgrade -y

# Add PHP repository
print_status "Adding PHP repository..."
apt install -y software-properties-common
add-apt-repository ppa:ondrej/php -y
apt update

# Install required packages
print_status "Installing required packages..."
apt install -y nginx mysql-server php8.1-fpm php8.1-mysql php8.1-curl php8.1-json php8.1-mbstring php8.1-xml php8.1-zip php8.1-gd php8.1-intl php8.1-bcmath php8.1-cli php8.1-common php8.1-opcache php8.1-readline php8.1-soap php8.1-xmlrpc php8.1-xsl php8.1-zip unzip git curl wget

# Install Python and face recognition dependencies
print_status "Installing Python and face recognition dependencies..."
apt install -y python3.10 python3.10-venv python3.10-dev python3-pip build-essential cmake pkg-config libjpeg-dev libtiff5-dev libpng-dev libavcodec-dev libavformat-dev libswscale-dev libv4l-dev libxvidcore-dev libx264-dev libgtk-3-dev libatlas-base-dev gfortran libhdf5-dev libhdf5-serial-dev libhdf5-103 libqtgui4 libqtwebkit4 libqt4-test python3-pyqt5 libdc1394-22-dev libopenblas-dev liblapack-dev

# Install Tesseract OCR
print_status "Installing Tesseract OCR..."
apt install -y tesseract-ocr tesseract-ocr-eng

# Start and enable services
print_status "Starting and enabling services..."
systemctl start nginx
systemctl start php8.1-fpm
systemctl start mysql
systemctl enable nginx
systemctl enable php8.1-fpm
systemctl enable mysql

# Configure PHP-FPM
print_status "Configuring PHP-FPM..."
sed -i 's/memory_limit = 128M/memory_limit = 256M/' /etc/php/8.1/fpm/php.ini
sed -i 's/max_execution_time = 30/max_execution_time = 300/' /etc/php/8.1/fpm/php.ini
sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 50M/' /etc/php/8.1/fpm/php.ini
sed -i 's/post_max_size = 8M/post_max_size = 50M/' /etc/php/8.1/fpm/php.ini

# Enable PHP OPcache
echo "opcache.enable=1" >> /etc/php/8.1/fpm/conf.d/10-opcache.ini
echo "opcache.memory_consumption=128" >> /etc/php/8.1/fpm/conf.d/10-opcache.ini

# Restart PHP-FPM
systemctl restart php8.1-fpm

# Configure firewall
print_status "Configuring firewall..."
ufw --force enable
ufw allow 22/tcp
ufw allow 80/tcp
ufw allow 443/tcp

# Create application directory
print_status "Setting up application directory..."
mkdir -p /var/www/html/sis
cd /var/www/html/sis

# Set permissions
chown -R www-data:www-data /var/www/html/sis
chmod -R 755 /var/www/html/sis

# Create Nginx site configuration
print_status "Creating Nginx site configuration..."
cat > /etc/nginx/sites-available/rntvs.site << 'EOF'
server {
    listen 80;
    server_name rntvs.site www.rntvs.site;
    root /var/www/html/sis/enrollment-system/public;
    index index.php index.html;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # Handle CodeIgniter routing
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP handling
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Deny access to hidden files
    location ~ /\. {
        deny all;
    }

    # Deny access to writable directory
    location ~ ^/(writable|app|system|tests)/ {
        deny all;
    }

    # Handle large file uploads
    client_max_body_size 50M;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/javascript application/xml+rss application/json;

    # Logging
    access_log /var/log/nginx/rntvs.site_access.log;
    error_log /var/log/nginx/rntvs.site_error.log;
}
EOF

# Enable site and remove default
ln -sf /etc/nginx/sites-available/rntvs.site /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

# Test Nginx configuration
nginx -t

# Reload Nginx
systemctl reload nginx

print_status "✅ Nginx server setup completed!"
print_warning "Next steps:"
echo "1. Upload your SIS files to /var/www/html/sis/ (upload enrollment-system folder contents)"
echo "2. Configure database (see DIGITALOCEAN_DEPLOYMENT_GUIDE.md)"
echo "3. Set up Python virtual environment for face recognition"
echo "4. Update .env file with: app.baseURL = 'https://rntvs.site/'"
echo "5. Set up SSL certificate with: certbot --nginx -d rntvs.site -d www.rntvs.site"

print_status "Deployment script completed! 🎉"

