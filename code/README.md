# Laravel Project Management Application

A comprehensive project management system built with Laravel 11, featuring multilingual support (French/English/Arabic), role-based access control, time tracking, and real-time collaboration tools.

## 📋 Table of Contents

- [Features](#-features)
- [Requirements](#-requirements)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Database Setup](#-database-setup)
- [Running the Application](#-running-the-application)
- [User Roles & Permissions](#-user-roles--permissions)
- [API Documentation](#-api-documentation)
- [Troubleshooting](#-troubleshooting)
- [Contributing](#-contributing)
- [License](#-license)

## 🚀 Features

### Core Functionality
- **Project Management**: Create, manage, and track projects with detailed statistics
- **Task Management**: Kanban board and list views with drag-and-drop functionality
- **Time Tracking**: Log work hours with overlap validation and reporting
- **Team Collaboration**: Task notes with @mentions and real-time notifications
- **Advanced Reporting**: Project, user, and time tracking reports with export (PDF/Excel)

### Technical Features
- **Multilingual Support**: French, English, and Arabic with RTL layout
- **Role-Based Access**: Admin, Manager, Member, and Client roles
- **Responsive Design**: Mobile-first Bootstrap 5 interface
- **Real-time Updates**: Ajax-powered interface with live notifications
- **REST API**: Complete API for mobile/external integrations
- **Security**: CSRF protection, input validation, and authorization gates

## 📦 Requirements

### System Requirements
- **PHP**: >= 8.1
- **Composer**: >= 2.0
- **Node.js**: >= 16.0
- **NPM**: >= 8.0

### PHP Extensions
```bash
php-openssl
php-pdo
php-mbstring
php-tokenizer
php-xml
php-ctype
php-json
php-bcmath
php-fileinfo
php-mysql
```

### Database
- **MySQL**: >= 8.0 (recommended)
- **PostgreSQL**: >= 13.0 (alternative)
- **SQLite**: >= 3.8 (development only)

## 🛠 Installation

### Step 1: Clone or Setup Project

**Option A: If you have the project files**
```bash
cd /path/to/your/project
```

**Option B: Create fresh Laravel installation and copy files**
```bash
# Create new Laravel project
composer create-project laravel/laravel project-management
cd project-management

# Copy the custom application files to the Laravel structure
# (Copy all app/, database/, resources/, routes/ folders from the provided code)
```

### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### Step 3: Create Environment File

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

## ⚙️ Configuration

### Environment Variables

Edit `.env` file with your configuration:

```env
# Application
APP_NAME="Project Management"
APP_ENV=local
APP_KEY=base64:your-generated-key
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=project_management
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# Cache & Sessions
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Broadcasting (Optional)
BROADCAST_DRIVER=log
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

# Application Specific
APP_TIMEZONE=Europe/Paris
APP_LOCALE=fr
APP_FALLBACK_LOCALE=en
APP_SUPPORTED_LOCALES=fr,en,ar

# File Upload Limits
MAX_FILE_SIZE=10240
ALLOWED_FILE_TYPES=jpg,jpeg,png,pdf,doc,docx,xls,xlsx

# Time Tracking
TIME_ENTRY_MODIFICATION_DAYS=7
NOTE_MODIFICATION_HOURS=24
```

### Additional Configuration Files

**Create missing service classes:**

```bash
# If these files are missing, create them:
php artisan make:service DashboardService
php artisan make:service TaskNoteService
php artisan make:middleware RoleMiddleware
php artisan make:middleware LanguageMiddleware
```

## 🗄️ Database Setup

### Step 1: Create Database

```bash
# MySQL
mysql -u root -p
CREATE DATABASE project_management;
EXIT;

# Or use your preferred database management tool
```

### Step 2: Run Migrations

```bash
# Run all migrations
php artisan migrate

# If you need to reset (⚠️ WARNING: This will delete all data)
php artisan migrate:fresh
```

### Step 3: Seed Sample Data

```bash
# Create sample data for development
php artisan db:seed

# Or seed specific seeders
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=ProjectSeeder
php artisan db:seed --class=TaskSeeder
```

**Sample Users Created:**
- **Admin**: admin@demo.com / password
- **Manager**: manager@demo.com / password
- **Member**: member@demo.com / password
- **Client**: client@demo.com / password

## 🏃 Running the Application

### Development Server

```bash
# Start Laravel development server
php artisan serve

# In a separate terminal, compile assets
npm run dev

# For asset watching (auto-compile on changes)
npm run watch
```

**Access the application:**
- **Web Interface**: http://localhost:8000
- **API Endpoints**: http://localhost:8000/api/

### Production Deployment

```bash
# Install production dependencies only
composer install --optimize-autoloader --no-dev

# Build assets for production
npm run build

# Clear and cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### Web Server Configuration

**Nginx Configuration Example:**
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/your/project/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 👥 User Roles & Permissions

### Role Hierarchy

1. **Admin**
   - Full system access
   - User management
   - System configuration
   - All project and task operations

2. **Manager**
   - Create and manage projects
   - Assign tasks to team members
   - View team reports
   - Manage project settings

3. **Member**
   - View assigned projects and tasks
   - Log time entries
   - Update task status
   - Add task notes

4. **Client**
   - View project progress
   - Read-only access to assigned projects
   - Basic reporting

### Permission System

The application uses Laravel's built-in authorization system:

```php
// Check user role
auth()->user()->isAdmin()
auth()->user()->isManager()
auth()->user()->isMember()
auth()->user()->isClient()

// Check specific permissions
$user->can('view', $project)
$user->can('update', $task)
```

## 📚 API Documentation

### Authentication

```bash
# Login
POST /api/login
{
    "email": "user@example.com",
    "password": "password"
}

# Register
POST /api/register
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password"
}
```

### Main Endpoints

```bash
# Projects
GET    /api/ajax/projects
POST   /api/ajax/projects
GET    /api/ajax/projects/{id}
PUT    /api/ajax/projects/{id}
DELETE /api/ajax/projects/{id}

# Tasks
GET    /api/ajax/tasks
POST   /api/ajax/tasks
GET    /api/ajax/tasks/{id}
PUT    /api/ajax/tasks/{id}
PATCH  /api/ajax/tasks/{id}/status

# Time Entries
GET    /api/ajax/time-entries
POST   /api/ajax/time-entries
PUT    /api/ajax/time-entries/{id}
DELETE /api/ajax/time-entries/{id}

# Reports
GET    /api/ajax/reports/projects
GET    /api/ajax/reports/users
GET    /api/ajax/reports/time-tracking
POST   /api/ajax/reports/export
```

### API Response Format

```json
{
    "success": true,
    "data": {...},
    "message": "Operation successful",
    "meta": {
        "current_page": 1,
        "total": 100
    }
}
```

## 🐛 Troubleshooting

### Common Issues

**1. Permission Denied Errors**
```bash
# Fix storage permissions
sudo chown -R www-data:www-data storage/
sudo chown -R www-data:www-data bootstrap/cache/
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

**2. Asset Compilation Issues**
```bash
# Clear npm cache
npm cache clean --force

# Remove node_modules and reinstall
rm -rf node_modules/
npm install

# Try different build command
npm run production
```

**3. Database Connection Issues**
```bash
# Check database configuration
php artisan config:clear
php artisan cache:clear

# Test database connection
php artisan tinker
DB::connection()->getPdo();
```

**4. Migration Issues**
```bash
# Reset migrations (⚠️ WARNING: Deletes all data)
php artisan migrate:fresh --seed

# Check migration status
php artisan migrate:status

# Rollback last migration
php artisan migrate:rollback
```

**5. Class Not Found Errors**
```bash
# Regenerate autoloader
composer dump-autoload

# Clear all caches
php artisan optimize:clear
```

### Performance Optimization

```bash
# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Queue optimization (if using queues)
php artisan queue:restart
```

### Development Tools

```bash
# Enable query logging
DB_LOG_QUERIES=true

# Debug mode
APP_DEBUG=true

# Use Laravel Telescope (optional)
composer require laravel/telescope
php artisan telescope:install
```

## 🧪 Testing

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Run with coverage
php artisan test --coverage
```

### Test Database

```bash
# Use separate test database
cp .env .env.testing

# Edit .env.testing
DB_DATABASE=project_management_test

# Create test database
php artisan migrate --env=testing
```

## 🔧 Maintenance

### Regular Tasks

```bash
# Clear application cache
php artisan cache:clear

# Clear compiled views
php artisan view:clear

# Clear configuration cache
php artisan config:clear

# Optimize application
php artisan optimize

# Check application health
php artisan about
```

### Backup Strategy

```bash
# Backup database
mysqldump -u username -p project_management > backup.sql

# Backup files
tar -czf app_backup.tar.gz /path/to/application

# Automated backup (add to cron)
0 2 * * * /path/to/backup-script.sh
```

## 📈 Monitoring

### Log Files

```bash
# Application logs
tail -f storage/logs/laravel.log

# Web server logs
tail -f /var/log/nginx/access.log
tail -f /var/log/nginx/error.log
```

### Health Checks

```bash
# Application health
curl http://localhost:8000/health

# API health
curl http://localhost:8000/api/health
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines

- Follow PSR-12 coding standards
- Write tests for new features
- Update documentation
- Use conventional commits

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 📞 Support

For support and questions:
- **Documentation**: [Project Wiki](link-to-wiki)
- **Issues**: [GitHub Issues](link-to-issues)
- **Email**: support@yourproject.com

---

**Built with ❤️ using Laravel, Bootstrap, and modern web technologies.**

## 🎯 Quick Start Checklist

- [ ] Clone/download project files
- [ ] Install Composer dependencies (`composer install`)
- [ ] Install NPM dependencies (`npm install`)
- [ ] Copy `.env.example` to `.env`
- [ ] Generate application key (`php artisan key:generate`)
- [ ] Configure database connection in `.env`
- [ ] Run migrations (`php artisan migrate`)
- [ ] Seed sample data (`php artisan db:seed`)
- [ ] Build assets (`npm run dev`)
- [ ] Start development server (`php artisan serve`)
- [ ] Access application at `http://localhost:8000`
- [ ] Login with demo credentials (admin@demo.com / password)

**Estimated setup time: 15-30 minutes** ⏱️