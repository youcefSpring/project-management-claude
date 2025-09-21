# Laravel Project Management - Ubuntu Setup Guide

Complete step-by-step installation guide for Ubuntu 20.04/22.04/24.04 LTS

## 📋 Table of Contents

- [System Requirements](#-system-requirements)
- [Step 1: System Update](#-step-1-system-update)
- [Step 2: Install PHP & Extensions](#-step-2-install-php--extensions)
- [Step 3: Install Composer](#-step-3-install-composer)
- [Step 4: Install Node.js & NPM](#-step-4-install-nodejs--npm)
- [Step 5: Install MySQL](#-step-5-install-mysql)
- [Step 6: Install Web Server](#-step-6-install-web-server-nginx)
- [Step 7: Setup Project](#-step-7-setup-project)
- [Step 8: Configure Database](#-step-8-configure-database)
- [Step 9: Configure Web Server](#-step-9-configure-web-server)
- [Step 10: SSL & Security](#-step-10-ssl--security)
- [Troubleshooting](#-troubleshooting)
- [Production Optimization](#-production-optimization)

## 🖥️ System Requirements

### Supported Ubuntu Versions
- Ubuntu 24.04 LTS (Noble Numbat) ✅ **Recommended**
- Ubuntu 22.04 LTS (Jammy Jellyfish) ✅
- Ubuntu 20.04 LTS (Focal Fossa) ✅

### Minimum Hardware
- **CPU**: 2 cores (4 cores recommended)
- **RAM**: 2GB (4GB+ recommended)
- **Storage**: 10GB free space (20GB+ recommended)
- **Network**: Internet connection for packages

## 🔄 Step 1: System Update

```bash
# Update package lists
sudo apt update

# Upgrade existing packages
sudo apt upgrade -y

# Install essential tools
sudo apt install -y curl wget git unzip software-properties-common apt-transport-https ca-certificates gnupg lsb-release
```

## 🐘 Step 2: Install PHP & Extensions

### Add PHP Repository (for latest PHP 8.3)

```bash
# Add Ondrej's PHP repository
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
```

### Install PHP 8.3 and Required Extensions

```bash
# Install PHP 8.3 and core extensions
sudo apt install -y php8.3 php8.3-fpm php8.3-cli php8.3-common

# Install Laravel required extensions
sudo apt install -y \
    php8.3-mysql \
    php8.3-pdo \
    php8.3-mbstring \
    php8.3-tokenizer \
    php8.3-xml \
    php8.3-ctype \
    php8.3-json \
    php8.3-bcmath \
    php8.3-fileinfo \
    php8.3-openssl \
    php8.3-zip \
    php8.3-curl \
    php8.3-gd \
    php8.3-imagick \
    php8.3-intl \
    php8.3-redis \
    php8.3-xdebug

# Verify PHP installation
php -v
php -m | grep -E "(mysql|mbstring|openssl|tokenizer)"
```

### Configure PHP

```bash
# Edit PHP configuration
sudo nano /etc/php/8.3/fpm/php.ini

# Key settings to modify:
# memory_limit = 256M
# upload_max_filesize = 50M
# post_max_size = 50M
# max_execution_time = 300
# max_input_vars = 3000
# date.timezone = Europe/Paris

# Edit PHP-FPM pool configuration
sudo nano /etc/php/8.3/fpm/pool.d/www.conf

# Ensure these settings:
# user = www-data
# group = www-data
# listen = /var/run/php/php8.3-fpm.sock
# listen.owner = www-data
# listen.group = www-data
# listen.mode = 0660

# Restart PHP-FPM
sudo systemctl restart php8.3-fpm
sudo systemctl enable php8.3-fpm
```

## 🎼 Step 3: Install Composer

```bash
# Download Composer installer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"

# Verify installer (optional but recommended)
php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"

# Install Composer globally
php composer-setup.php --install-dir=/usr/local/bin --filename=composer

# Clean up installer
php -r "unlink('composer-setup.php');"

# Verify installation
composer --version

# Add Composer to PATH (if needed)
echo 'export PATH="$HOME/.composer/vendor/bin:$PATH"' >> ~/.bashrc
source ~/.bashrc
```

## 📦 Step 4: Install Node.js & NPM

### Option A: Using NodeSource Repository (Recommended)

```bash
# Add NodeSource repository for Node.js 20.x LTS
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -

# Install Node.js and NPM
sudo apt install -y nodejs

# Verify installation
node --version  # Should show v20.x.x
npm --version   # Should show 10.x.x
```

### Option B: Using Ubuntu Package Manager

```bash
# Install Node.js and NPM from Ubuntu repos
sudo apt install -y nodejs npm

# Update NPM to latest version
sudo npm install -g npm@latest
```

### Install Global Packages

```bash
# Install useful global packages
sudo npm install -g yarn pm2

# Verify installations
yarn --version
pm2 --version
```

## 🗄️ Step 5: Install MySQL

### Install MySQL Server

```bash
# Install MySQL Server 8.0
sudo apt install -y mysql-server mysql-client

# Secure MySQL installation
sudo mysql_secure_installation

# Follow the prompts:
# - Set root password: YES (choose a strong password)
# - Remove anonymous users: YES
# - Disallow root login remotely: YES
# - Remove test database: YES
# - Reload privilege tables: YES
```

### Configure MySQL

```bash
# Login to MySQL as root
sudo mysql -u root -p

# Create database and user for the application
CREATE DATABASE project_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'pmapp_user'@'localhost' IDENTIFIED BY 'secure_password_here';
GRANT ALL PRIVILEGES ON project_management.* TO 'pmapp_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Test database connection
mysql -u pmapp_user -p project_management
```

### Optimize MySQL Configuration

```bash
# Edit MySQL configuration
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf

# Add/modify these settings:
[mysqld]
innodb_buffer_pool_size = 256M
innodb_log_file_size = 64M
innodb_file_per_table = 1
innodb_flush_log_at_trx_commit = 2
query_cache_type = 1
query_cache_size = 32M
max_connections = 200
wait_timeout = 300

# Restart MySQL
sudo systemctl restart mysql
sudo systemctl enable mysql
```

## 🌐 Step 6: Install Web Server (Nginx)

```bash
# Install Nginx
sudo apt install -y nginx

# Start and enable Nginx
sudo systemctl start nginx
sudo systemctl enable nginx

# Check Nginx status
sudo systemctl status nginx

# Test Nginx installation (should show Nginx welcome page)
curl -I http://localhost
```

### Configure Firewall

```bash
# Install UFW if not already installed
sudo apt install -y ufw

# Allow SSH (important!)
sudo ufw allow ssh

# Allow HTTP and HTTPS
sudo ufw allow 'Nginx Full'

# Enable firewall
sudo ufw enable

# Check firewall status
sudo ufw status
```

## 📁 Step 7: Setup Project

### Clone or Download Project

```bash
# Navigate to web directory
cd /var/www

# Option A: Clone from Git (if you have a repository)
sudo git clone https://github.com/yourusername/project-management.git
sudo mv project-management laravel-pm

# Option B: Upload your project files
# Upload your project files to /var/www/laravel-pm

# Set ownership
sudo chown -R www-data:www-data /var/www/laravel-pm
sudo chmod -R 755 /var/www/laravel-pm

# Navigate to project directory
cd /var/www/laravel-pm
```

### Install Dependencies

```bash
# Install PHP dependencies
sudo -u www-data composer install --optimize-autoloader --no-dev

# Install JavaScript dependencies
sudo -u www-data npm install

# Build assets for production
sudo -u www-data npm run build
```

### Set Up Environment

```bash
# Copy environment file
sudo -u www-data cp .env.example .env

# Generate application key
sudo -u www-data php artisan key:generate

# Edit environment file
sudo nano .env
```

### Configure .env File

```env
APP_NAME="Project Management"
APP_ENV=production
APP_KEY=base64:your-generated-key-here
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=project_management
DB_USERNAME=pmapp_user
DB_PASSWORD=secure_password_here

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

# Application Specific
APP_TIMEZONE=Europe/Paris
APP_LOCALE=fr
APP_FALLBACK_LOCALE=en
APP_SUPPORTED_LOCALES=fr,en,ar
```

### Set Permissions

```bash
# Set correct ownership
sudo chown -R www-data:www-data /var/www/laravel-pm

# Set directory permissions
sudo find /var/www/laravel-pm -type d -exec chmod 755 {} \;

# Set file permissions
sudo find /var/www/laravel-pm -type f -exec chmod 644 {} \;

# Set special permissions for storage and bootstrap/cache
sudo chmod -R 775 /var/www/laravel-pm/storage
sudo chmod -R 775 /var/www/laravel-pm/bootstrap/cache

# Make artisan executable
sudo chmod +x /var/www/laravel-pm/artisan
```

## 🗃️ Step 8: Configure Database

```bash
# Navigate to project directory
cd /var/www/laravel-pm

# Run migrations
sudo -u www-data php artisan migrate --force

# Seed sample data (optional for production)
sudo -u www-data php artisan db:seed

# Cache configurations for production
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
```

## ⚙️ Step 9: Configure Web Server

### Create Nginx Virtual Host

```bash
# Create Nginx configuration file
sudo nano /etc/nginx/sites-available/laravel-pm
```

### Nginx Configuration

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/laravel-pm/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    # Security headers
    add_header X-XSS-Protection "1; mode=block";
    add_header Referrer-Policy "strict-origin-when-cross-origin";
    add_header Permissions-Policy "geolocation=(), microphone=(), camera=()";

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/javascript application/xml+rss application/json;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico {
        access_log off;
        log_not_found off;
        expires 30d;
        add_header Cache-Control "public, immutable";
    }

    location = /robots.txt  {
        access_log off;
        log_not_found off;
    }

    # Static assets caching
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    # PHP handling
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;

        # Security
        fastcgi_hide_header X-Powered-By;

        # Increase timeouts for long-running scripts
        fastcgi_read_timeout 300;
        fastcgi_connect_timeout 300;
        fastcgi_send_timeout 300;
    }

    # Deny access to hidden files
    location ~ /\. {
        deny all;
        access_log off;
        log_not_found off;
    }

    # Deny access to Laravel specific files
    location ~ /(storage|vendor|node_modules|tests|database|\.env) {
        deny all;
        access_log off;
        log_not_found off;
    }

    # Logging
    access_log /var/log/nginx/laravel-pm.access.log;
    error_log /var/log/nginx/laravel-pm.error.log;
}
```

### Enable Site and Test Configuration

```bash
# Enable the site
sudo ln -s /etc/nginx/sites-available/laravel-pm /etc/nginx/sites-enabled/

# Remove default site (optional)
sudo rm -f /etc/nginx/sites-enabled/default

# Test Nginx configuration
sudo nginx -t

# Reload Nginx
sudo systemctl reload nginx

# Check Nginx status
sudo systemctl status nginx
```

## 🔒 Step 10: SSL & Security

### Install Certbot for Let's Encrypt SSL

```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-nginx

# Obtain SSL certificate
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Test automatic renewal
sudo certbot renew --dry-run

# Set up automatic renewal (cron job)
sudo crontab -e

# Add this line to run renewal check twice daily:
0 12 * * * /usr/bin/certbot renew --quiet
```

### Additional Security Measures

```bash
# Install fail2ban for brute force protection
sudo apt install -y fail2ban

# Configure fail2ban
sudo nano /etc/fail2ban/jail.local
```

### Fail2ban Configuration

```ini
[DEFAULT]
bantime = 3600
findtime = 600
maxretry = 3

[sshd]
enabled = true

[nginx-http-auth]
enabled = true

[nginx-limit-req]
enabled = true
filter = nginx-limit-req
action = iptables-multiport[name=ReqLimit, port="http,https", protocol=tcp]
logpath = /var/log/nginx/*error.log
findtime = 600
bantime = 7200
maxretry = 10
```

```bash
# Start and enable fail2ban
sudo systemctl start fail2ban
sudo systemctl enable fail2ban

# Check fail2ban status
sudo fail2ban-client status
```

### Create Deployment Script

```bash
# Create deployment script
sudo nano /var/www/laravel-pm/deploy.sh
```

```bash
#!/bin/bash

# Laravel Project Deployment Script

echo "🚀 Starting deployment..."

# Navigate to project directory
cd /var/www/laravel-pm

# Put application in maintenance mode
sudo -u www-data php artisan down

# Pull latest changes (if using Git)
# sudo -u www-data git pull origin main

# Install/update Composer dependencies
sudo -u www-data composer install --optimize-autoloader --no-dev

# Install/update NPM dependencies
sudo -u www-data npm ci

# Build assets
sudo -u www-data npm run build

# Run database migrations
sudo -u www-data php artisan migrate --force

# Clear caches
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache

# Set permissions
sudo chown -R www-data:www-data /var/www/laravel-pm
sudo chmod -R 755 /var/www/laravel-pm
sudo chmod -R 775 /var/www/laravel-pm/storage
sudo chmod -R 775 /var/www/laravel-pm/bootstrap/cache

# Restart services
sudo systemctl reload php8.3-fpm
sudo systemctl reload nginx

# Bring application back online
sudo -u www-data php artisan up

echo "✅ Deployment completed successfully!"
```

```bash
# Make script executable
sudo chmod +x /var/www/laravel-pm/deploy.sh
```

## 🐛 Troubleshooting

### Common Issues and Solutions

#### 1. Permission Denied Errors

```bash
# Fix ownership
sudo chown -R www-data:www-data /var/www/laravel-pm

# Fix permissions
sudo chmod -R 755 /var/www/laravel-pm
sudo chmod -R 775 /var/www/laravel-pm/storage
sudo chmod -R 775 /var/www/laravel-pm/bootstrap/cache

# Check SELinux (if enabled)
sudo setsebool -P httpd_can_network_connect 1
sudo setsebool -P httpd_unified 1
```

#### 2. 500 Internal Server Error

```bash
# Check error logs
sudo tail -f /var/log/nginx/laravel-pm.error.log
sudo tail -f /var/www/laravel-pm/storage/logs/laravel.log

# Clear all caches
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan route:clear
sudo -u www-data php artisan view:clear

# Regenerate autoloader
sudo -u www-data composer dump-autoload
```

#### 3. Database Connection Issues

```bash
# Test database connection
mysql -u pmapp_user -p project_management

# Check MySQL service
sudo systemctl status mysql
sudo systemctl restart mysql

# Verify database credentials in .env file
cat /var/www/laravel-pm/.env | grep DB_
```

#### 4. Asset Loading Issues

```bash
# Rebuild assets
cd /var/www/laravel-pm
sudo -u www-data npm run build

# Check asset permissions
sudo chmod -R 755 /var/www/laravel-pm/public

# Verify Nginx is serving static files
curl -I http://yourdomain.com/css/app.css
```

#### 5. PHP-FPM Issues

```bash
# Check PHP-FPM status
sudo systemctl status php8.3-fpm

# Check PHP-FPM logs
sudo tail -f /var/log/php8.3-fpm.log

# Restart PHP-FPM
sudo systemctl restart php8.3-fpm

# Test PHP configuration
php -v
php --ini
```

### Performance Monitoring

```bash
# Monitor system resources
htop
df -h
free -m

# Monitor web server
sudo tail -f /var/log/nginx/access.log
sudo nginx -s reload

# Monitor database
sudo mysqladmin -u root -p processlist
sudo mysqladmin -u root -p status
```

## 🚀 Production Optimization

### Enable OPcache

```bash
# Edit PHP configuration
sudo nano /etc/php/8.3/fpm/conf.d/10-opcache.ini
```

```ini
; OPcache settings
opcache.enable=1
opcache.enable_cli=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=12
opcache.max_accelerated_files=4000
opcache.revalidate_freq=60
opcache.save_comments=1
opcache.fast_shutdown=1
```

### Setup Redis (Optional)

```bash
# Install Redis
sudo apt install -y redis-server

# Configure Redis
sudo nano /etc/redis/redis.conf

# Key settings:
# maxmemory 256mb
# maxmemory-policy allkeys-lru

# Start Redis
sudo systemctl start redis-server
sudo systemctl enable redis-server

# Update Laravel .env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### Setup Queue Workers

```bash
# Create systemd service for queue worker
sudo nano /etc/systemd/system/laravel-worker.service
```

```ini
[Unit]
Description=Laravel queue worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/laravel-pm/artisan queue:work --sleep=3 --tries=3 --max-time=3600
StandardOutput=syslog
StandardError=syslog
SyslogIdentifier=laravel-queue

[Install]
WantedBy=multi-user.target
```

```bash
# Enable and start queue worker
sudo systemctl enable laravel-worker
sudo systemctl start laravel-worker
sudo systemctl status laravel-worker
```

### Setup Log Rotation

```bash
# Create log rotation configuration
sudo nano /etc/logrotate.d/laravel-pm
```

```
/var/www/laravel-pm/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    notifempty
    create 644 www-data www-data
    postrotate
        /bin/systemctl reload php8.3-fpm > /dev/null 2>&1 || true
    endscript
}
```

### Monitoring and Backup

```bash
# Create backup script
sudo nano /usr/local/bin/backup-laravel.sh
```

```bash
#!/bin/bash

BACKUP_DIR="/home/backup/laravel-pm"
DATE=$(date +%Y%m%d_%H%M%S)

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u pmapp_user -p'secure_password_here' project_management > $BACKUP_DIR/database_$DATE.sql

# Backup application files
tar -czf $BACKUP_DIR/files_$DATE.tar.gz -C /var/www laravel-pm --exclude=node_modules --exclude=vendor

# Remove backups older than 7 days
find $BACKUP_DIR -name "*.sql" -type f -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -type f -mtime +7 -delete

echo "Backup completed: $DATE"
```

```bash
# Make script executable
sudo chmod +x /usr/local/bin/backup-laravel.sh

# Add to crontab for daily backup at 2 AM
sudo crontab -e
0 2 * * * /usr/local/bin/backup-laravel.sh >> /var/log/backup.log 2>&1
```

## ✅ Final Verification

### Test Application

```bash
# Check all services are running
sudo systemctl status nginx
sudo systemctl status php8.3-fpm
sudo systemctl status mysql

# Test application URLs
curl -I http://yourdomain.com
curl -I https://yourdomain.com

# Check logs for errors
sudo tail -f /var/log/nginx/laravel-pm.error.log
sudo tail -f /var/www/laravel-pm/storage/logs/laravel.log
```

### Performance Test

```bash
# Install Apache Bench for basic testing
sudo apt install -y apache2-utils

# Test performance
ab -n 100 -c 10 https://yourdomain.com/

# Monitor during test
htop
```

### Security Verification

```bash
# Check SSL configuration
curl -I https://yourdomain.com

# Verify firewall rules
sudo ufw status

# Check fail2ban status
sudo fail2ban-client status

# Test security headers
curl -I https://yourdomain.com | grep -E "(X-Frame-Options|X-Content-Type-Options|X-XSS-Protection)"
```

## 🎯 Quick Reference Commands

### Daily Operations

```bash
# Check application status
sudo systemctl status nginx php8.3-fpm mysql

# View live logs
sudo tail -f /var/log/nginx/laravel-pm.error.log
sudo tail -f /var/www/laravel-pm/storage/logs/laravel.log

# Clear application cache
cd /var/www/laravel-pm
sudo -u www-data php artisan cache:clear

# Deploy updates
sudo /var/www/laravel-pm/deploy.sh

# Check disk space
df -h

# Monitor processes
htop
```

### Emergency Commands

```bash
# Restart all services
sudo systemctl restart nginx php8.3-fpm mysql

# Emergency application maintenance
cd /var/www/laravel-pm
sudo -u www-data php artisan down

# Restore from backup
mysql -u pmapp_user -p project_management < /home/backup/laravel-pm/database_YYYYMMDD_HHMMSS.sql
```

---

**🎉 Your Laravel Project Management Application is now ready for production on Ubuntu!**

**Estimated setup time: 45-60 minutes**

<function_calls>
<invoke name="TodoWrite">
<parameter name="todos">[{"content": "Créer un guide d'installation complet pour Ubuntu", "status": "completed", "activeForm": "Créant guide Ubuntu"}, {"content": "Ajouter les commandes spécifiques à Ubuntu/Linux", "status": "completed", "activeForm": "Ajoutant commandes Ubuntu"}, {"content": "Inclure la configuration du serveur web", "status": "completed", "activeForm": "Configurant serveur web"}, {"content": "Documenter la sécurité et les permissions", "status": "completed", "activeForm": "Documentant sécurité"}]