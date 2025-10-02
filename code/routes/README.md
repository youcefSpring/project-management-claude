# Routes Documentation

This directory contains all route definitions for the Project Management Application.

## Route Files Overview

### Core Route Files

#### `web.php` - Web Application Routes
Main routes for the web interface including:
- **Authentication**: Login, register, logout
- **Dashboard**: Main application dashboard
- **Projects**: Project management pages (index, show, create, edit)
- **Tasks**: Task management interface
- **Timesheet**: Time tracking interface
- **Reports**: Report generation pages
- **Profile**: User profile and settings
- **Admin**: Administrative interface (admin-only)
- **Search**: Global search functionality
- **Language**: Language switching
- **Downloads**: File download endpoints

#### `api.php` - API Routes
RESTful API endpoints for Ajax calls:
- **Authentication**: API login/logout/register
- **Ajax Endpoints**: All CRUD operations with `/ajax` prefix
  - Projects CRUD
  - Tasks CRUD + status updates
  - Time entries CRUD
  - Task notes (nested resource)
  - Reports (projects, users, time-tracking, export)
  - Dashboard (stats, activity, notifications)
  - Language & translations management
- **Health Check**: API status endpoint

### Specialized Route Files

#### `auth.php` - Authentication Routes
Dedicated authentication routes including:
- Login/Register/Logout
- Password reset (future implementation)
- Email verification (future implementation)
- Profile management
- Password changes

#### `admin.php` - Admin-Only Routes
Administrative routes with `role:admin` middleware:
- Admin dashboard
- User management (CRUD, role/status updates)
- Translation management (import/export/sync)
- System settings and configuration
- Project analytics and archiving
- System reports and audit logs
- Cache management
- Maintenance mode controls

#### `channels.php` - Broadcasting Channels
Real-time broadcasting channel definitions:
- User private channels for notifications
- Project-specific channels
- Task-specific channels for updates
- Team collaboration channels
- Global admin notifications
- Time tracking presence

#### `console.php` - Artisan Commands
Custom console commands and scheduled tasks:
- Project cleanup and archiving
- Overdue task notifications
- Time calculation updates
- Translation synchronization
- Daily report generation
- Cache warming
- Scheduled task definitions

## Route Structure & Conventions

### Web Routes Pattern
```
/{resource}              # Index page
/{resource}/create       # Create form
/{resource}/{id}         # Show individual resource
/{resource}/{id}/edit    # Edit form
```

### API Routes Pattern
```
/api/ajax/{resource}           # RESTful API endpoints
/api/ajax/{resource}/{id}      # Individual resource operations
/api/ajax/reports/{type}       # Report generation
/api/ajax/dashboard/{section}  # Dashboard data
```

### Middleware Groups
- **`guest`**: Unauthenticated users only (login/register)
- **`auth`**: Authenticated users required
- **`role:admin`**: Admin role required
- **`role:admin,manager`**: Admin or manager role required

### Named Routes Convention
- Web routes: `{resource}.{action}` (e.g., `projects.index`)
- API routes: `api.{resource}.{action}` (e.g., `api.projects.store`)
- Admin routes: `admin.{resource}.{action}` (e.g., `admin.users.create`)

## Security & Access Control

### Role-Based Access
- **Admin**: Full system access including user management
- **Manager**: Project management, team oversight, reports
- **Member**: Task execution, time tracking, own data

### Route Protection
- All authenticated routes require `auth` middleware
- Role-specific routes use `role:` middleware
- API routes use `auth:sanctum` for token-based auth
- Form requests handle detailed authorization logic

### CSRF Protection
- All POST/PUT/PATCH/DELETE web routes include CSRF protection
- API routes use Sanctum token authentication
- Ajax calls must include CSRF token

## API Response Format

### Success Response
```json
{
    "success": true,
    "data": {...},
    "message": "Operation successful"
}
```

### Error Response
```json
{
    "success": false,
    "message": "Error description",
    "errors": {...},
    "code": 422
}
```

### Pagination Response
```json
{
    "data": [...],
    "meta": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 15,
        "total": 75
    }
}
```

## Future Enhancements

### Planned Route Additions
- **File Management**: Upload/download task attachments
- **Notifications**: Real-time notification system
- **API Versioning**: Versioned API endpoints
- **Webhooks**: External system integrations
- **Mobile API**: Mobile-specific endpoints
- **Public API**: External developer access

### Broadcasting Implementation
- Real-time task updates
- Live collaboration features
- Instant notifications
- Presence indicators
- Team chat integration

This route structure provides a solid foundation for the project management application with clear separation of concerns, proper security, and room for future expansion.