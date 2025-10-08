# Permission System Documentation

This application implements a comprehensive role-based access control (RBAC) system with granular permissions.

## User Roles

### Available Roles
- **Admin**: Full system access
- **Manager**: Project management and team oversight
- **Developer**: Code development tasks
- **Designer**: Design-related tasks
- **Tester**: Quality assurance tasks
- **HR**: Human resources functions
- **Accountant**: Financial functions
- **Client**: Limited access for clients
- **Member**: General member (backward compatibility)

## Permission System

### Available Permissions

#### User Management
- `view.users` - View user list (Admin, HR)
- `manage.users` - Create/edit/delete users (Admin only)

#### Project Management
- `manage.projects` - Create/edit projects (Admin, Manager)
- `delete.projects` - Delete projects (Admin only)
- `view.project` - View specific project (Admin, Manager, Members with tasks)
- `edit.project` - Edit specific project (Admin, Manager if owner)

#### Task Management
- `assign.tasks` - Create and assign tasks (Admin, Manager)
- `view.task` - View specific task (Admin, Manager, Assignee)
- `edit.task` - Edit specific task (Admin, Manager, Assignee)

#### Time Tracking
- `view.timetracking` - Access timesheet functionality
- `log.time.others` - Log time for other users (Admin, Manager)

#### Reports
- `view.reports` - View general reports (Admin, Manager, Accountant)
- `view.financial.reports` - View financial reports (Admin, Accountant)

#### Dashboard Access
- `access.admin.dashboard` - Access admin dashboard (Admin only)
- `access.manager.dashboard` - Access manager dashboard (Admin, Manager)

## Usage in Code

### Middleware Protection

#### Route-Level Protection
```php
// Protect routes with role-based middleware
Route::get('/users', [UserController::class, 'index'])
    ->middleware('role:admin,hr');

// Protect routes with permission-based middleware
Route::get('/users', [UserController::class, 'index'])
    ->middleware('permission:view.users');

// Protect routes with multiple permissions
Route::get('/project/{project}/edit', [ProjectController::class, 'edit'])
    ->middleware('permission:edit.project');
```

#### Group-Level Protection
```php
Route::prefix('admin')->middleware('permission:access.admin.dashboard')->group(function () {
    // Admin-only routes
});
```

### Controller Protection

#### Using Authorization Policies
```php
public function show(Project $project)
{
    $this->authorize('view', $project);
    // Controller logic...
}
```

#### Manual Permission Checks
```php
public function index(Request $request)
{
    if (!$request->user()->canViewUsers()) {
        abort(403);
    }
    // Controller logic...
}
```

### View Protection

#### Blade Directives

##### Permission-Based
```blade
@hasPermission('manage.users')
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        Create User
    </a>
@endhasPermission

@hasPermission('edit.project', $project)
    <a href="{{ route('projects.edit', $project) }}" class="btn btn-secondary">
        Edit Project
    </a>
@endhasPermission
```

##### Role-Based
```blade
@hasRole('admin')
    <div class="admin-panel">
        <!-- Admin content -->
    </div>
@endhasRole

@hasRole(['admin', 'manager'])
    <div class="management-panel">
        <!-- Management content -->
    </div>
@endhasRole

@isAdmin
    <div class="admin-only-content">
        <!-- Admin-only content -->
    </div>
@endisAdmin

@isManager
    <div class="manager-content">
        <!-- Manager content -->
    </div>
@endisManager

@canWorkOnTasks
    <div class="task-tools">
        <!-- Task management tools -->
    </div>
@endcanWorkOnTasks
```

##### Component-Based
```blade
<x-permission-check permission="manage.projects">
    <button class="btn btn-primary">Create Project</button>
</x-permission-check>

<x-permission-check permission="edit.task" :resource="$task">
    <button class="btn btn-secondary">Edit Task</button>
</x-permission-check>
```

### User Model Methods

#### Role Checks
```php
$user->isAdmin()           // Check if user is admin
$user->isManager()         // Check if user is manager
$user->isDeveloper()       // Check if user is developer
$user->isDesigner()        // Check if user is designer
$user->isTester()          // Check if user is tester
$user->isHR()             // Check if user is HR
$user->isAccountant()     // Check if user is accountant
$user->isClient()         // Check if user is client
$user->hasRole('admin')   // Check specific role
$user->hasAnyRole(['admin', 'manager']) // Check multiple roles
```

#### Permission Checks
```php
$user->canViewUsers()              // View user management
$user->canManageUsers()            // Create/edit users
$user->canViewReports()            // View reports
$user->canViewFinancialReports()   // View financial reports
$user->canManageProjects()         // Create/edit projects
$user->canDeleteProjects()         // Delete projects
$user->canAssignTasks()            // Create/assign tasks
$user->canViewTimeTracking()       // Access timesheet
$user->canLogTimeForOthers()       // Log time for others
$user->canWorkOnTasks()            // Work on tasks (not client/HR/accountant)
$user->canAccessAdminDashboard()   // Access admin dashboard
$user->canAccessManagerDashboard() // Access manager dashboard
```

#### Resource-Specific Checks
```php
$user->canViewProject($project)    // View specific project
$user->canEditProject($project)    // Edit specific project
$user->canViewTask($task)          // View specific task
$user->canEditTask($task)          // Edit specific task
```

## Security Best Practices

### 1. Defense in Depth
- Always use middleware for route protection
- Add controller-level checks for sensitive operations
- Use view-level protection to hide UI elements
- Implement model-level policies for data access

### 2. Fail Securely
- Default to denying access if permission is unclear
- Log permission failures for security monitoring
- Return 403 errors for insufficient permissions
- Redirect unauthenticated users to login

### 3. Principle of Least Privilege
- Grant minimum permissions necessary for each role
- Review and audit permissions regularly
- Remove unnecessary permissions promptly
- Use resource-specific permissions when possible

### 4. Input Validation
- Validate user input even with permission checks
- Don't rely solely on UI restrictions
- Implement server-side validation for all operations
- Sanitize and escape output appropriately

## Permission Matrix

| Role       | Projects | Tasks | Users | Reports | Time | Admin |
|------------|----------|--------|--------|---------|------|-------|
| Admin      | Full     | Full   | Full   | Full    | Full | Full  |
| Manager    | Manage   | Assign | View   | View    | View | No    |
| Developer  | View*    | Work   | No     | No      | Own  | No    |
| Designer   | View*    | Work   | No     | No      | Own  | No    |
| Tester     | View*    | Work   | No     | No      | Own  | No    |
| HR         | No       | No     | View   | No      | No   | No    |
| Accountant | No       | No     | No     | Finance | No   | No    |
| Client     | View*    | No     | No     | No      | No   | No    |

*View only for projects where they have assigned tasks or are the manager.

## Troubleshooting

### Common Issues

1. **403 Forbidden Errors**
   - Check if user has the required role
   - Verify permission middleware is correctly applied
   - Ensure policies are registered in `AuthServiceProvider`

2. **Navigation Items Not Showing**
   - Check Blade directive syntax
   - Verify permission method exists in User model
   - Clear view cache: `php artisan view:clear`

3. **Permission Checks Not Working**
   - Clear application cache: `php artisan cache:clear`
   - Check for typos in permission names
   - Verify middleware is registered in `Kernel.php`

### Testing Permissions

```php
// Test in Tinker
php artisan tinker

// Check user permissions
$user = User::find(1);
$user->canManageProjects();
$user->canViewProject(Project::find(1));

// Test middleware
// Visit protected routes and verify access control
```