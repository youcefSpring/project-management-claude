<?php

require __DIR__ . '/code/vendor/autoload.php';

// Laravel Application Bootstrap
$app = require_once __DIR__ . '/code/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

echo "=== PROJECT MANAGEMENT SYSTEM FUNCTIONALITY TEST ===\n\n";

// Test 1: User Authentication
echo "1. TESTING USER ROLES AND PERMISSIONS\n";
echo "=====================================\n";

$admin = User::where('email', 'admin@demo.com')->first();
$manager = User::where('email', 'manager@demo.com')->first();
$developer = User::where('role', 'developer')->first();
$client = User::where('role', 'client')->first();

echo "Admin User: {$admin->name} - {$admin->email} - Role: {$admin->role}\n";
echo "Manager User: {$manager->name} - {$manager->email} - Role: {$manager->role}\n";
echo "Developer User: " . ($developer ? "{$developer->name} - {$developer->email} - Role: {$developer->role}" : "Not found") . "\n";
echo "Client User: " . ($client ? "{$client->name} - {$client->email} - Role: {$client->role}" : "Not found") . "\n\n";

// Test permissions
echo "Admin permissions:\n";
echo "- Is Admin: " . ($admin->isAdmin() ? 'YES' : 'NO') . "\n";
echo "- Can Manage Projects: " . ($admin->canManageProjects() ? 'YES' : 'NO') . "\n";
echo "- Can Work on Tasks: " . ($admin->canWorkOnTasks() ? 'YES' : 'NO') . "\n\n";

echo "Manager permissions:\n";
echo "- Is Manager: " . ($manager->isManager() ? 'YES' : 'NO') . "\n";
echo "- Can Manage Projects: " . ($manager->canManageProjects() ? 'YES' : 'NO') . "\n";
echo "- Can Work on Tasks: " . ($manager->canWorkOnTasks() ? 'YES' : 'NO') . "\n\n";

if ($developer) {
    echo "Developer permissions:\n";
    echo "- Is Developer: " . ($developer->isDeveloper() ? 'YES' : 'NO') . "\n";
    echo "- Can Manage Projects: " . ($developer->canManageProjects() ? 'YES' : 'NO') . "\n";
    echo "- Can Work on Tasks: " . ($developer->canWorkOnTasks() ? 'YES' : 'NO') . "\n\n";
}

if ($client) {
    echo "Client permissions:\n";
    echo "- Is Client: " . ($client->isClient() ? 'YES' : 'NO') . "\n";
    echo "- Can Manage Projects: " . ($client->canManageProjects() ? 'YES' : 'NO') . "\n";
    echo "- Can Work on Tasks: " . ($client->canWorkOnTasks() ? 'YES' : 'NO') . "\n\n";
}

// Test 2: Projects
echo "2. TESTING PROJECT FUNCTIONALITY\n";
echo "=================================\n";

$totalProjects = Project::count();
echo "Total Projects in Database: {$totalProjects}\n";

if ($totalProjects > 0) {
    $sampleProject = Project::with('manager', 'tasks')->first();
    echo "Sample Project: {$sampleProject->title}\n";
    echo "- Description: {$sampleProject->description}\n";
    echo "- Status: {$sampleProject->status}\n";
    echo "- Manager: {$sampleProject->manager->name}\n";
    echo "- Start Date: {$sampleProject->start_date}\n";
    echo "- End Date: {$sampleProject->end_date}\n";
    echo "- Tasks Count: {$sampleProject->tasks->count()}\n";
    echo "- Completion: {$sampleProject->completion_percentage}%\n";
    echo "- Total Hours: {$sampleProject->total_hours}\n\n";

    // Test project accessibility for different users
    echo "Project Access Test:\n";
    $adminProjects = Project::accessibleBy($admin)->count();
    $managerProjects = Project::accessibleBy($manager)->count();
    echo "- Admin can access {$adminProjects} projects\n";
    echo "- Manager can access {$managerProjects} projects\n";

    if ($developer) {
        $developerProjects = Project::accessibleBy($developer)->count();
        echo "- Developer can access {$developerProjects} projects\n";
    }
} else {
    echo "No projects found in database.\n";
}
echo "\n";

// Test 3: Tasks
echo "3. TESTING TASK FUNCTIONALITY\n";
echo "==============================\n";

$totalTasks = Task::count();
echo "Total Tasks in Database: {$totalTasks}\n";

if ($totalTasks > 0) {
    $sampleTask = Task::with('project', 'assignedUser')->first();
    echo "Sample Task: {$sampleTask->title}\n";
    echo "- Description: {$sampleTask->description}\n";
    echo "- Status: {$sampleTask->status}\n";
    echo "- Project: {$sampleTask->project->title}\n";
    echo "- Assigned to: " . ($sampleTask->assignedUser ? $sampleTask->assignedUser->name : 'Unassigned') . "\n";
    echo "- Due Date: {$sampleTask->due_date}\n";
    echo "- Is Overdue: " . ($sampleTask->isOverdue() ? 'YES' : 'NO') . "\n";
    echo "- Total Hours: {$sampleTask->total_hours}\n\n";

    // Test task status transitions
    echo "Task Status Transitions:\n";
    $allowedTransitions = Task::getAllowedTransitions();
    foreach ($allowedTransitions as $status => $transitions) {
        echo "- From '{$status}' can transition to: " . implode(', ', $transitions) . "\n";
    }
    echo "\n";

    // Test task accessibility for different users
    echo "Task Access Test:\n";
    $adminTasks = Task::accessibleBy($admin)->count();
    $managerTasks = Task::accessibleBy($manager)->count();
    echo "- Admin can access {$adminTasks} tasks\n";
    echo "- Manager can access {$managerTasks} tasks\n";

    if ($developer) {
        $developerTasks = Task::accessibleBy($developer)->count();
        echo "- Developer can access {$developerTasks} tasks\n";
    }
} else {
    echo "No tasks found in database.\n";
}
echo "\n";

// Test 4: Admin Specific Functions
echo "4. TESTING ADMIN SPECIFIC FUNCTIONALITY\n";
echo "========================================\n";

// Test user management capabilities
$totalUsers = User::count();
echo "Total Users: {$totalUsers}\n";

$roleDistribution = [];
foreach (User::getRoles() as $role) {
    $count = User::where('role', $role)->count();
    $roleDistribution[$role] = $count;
}

echo "Role Distribution:\n";
foreach ($roleDistribution as $role => $count) {
    $label = User::getRoleLabels()[$role] ?? ucfirst($role);
    echo "- {$label}: {$count} users\n";
}
echo "\n";

// Test 5: Database Relationships
echo "5. TESTING DATABASE RELATIONSHIPS\n";
echo "==================================\n";

// Test user relationships
echo "User Relationships Test:\n";
if ($manager->managedProjects()->count() > 0) {
    echo "- Manager manages {$manager->managedProjects()->count()} projects\n";
}

$userWithTasks = User::whereHas('assignedTasks')->first();
if ($userWithTasks) {
    echo "- User '{$userWithTasks->name}' has {$userWithTasks->assignedTasks()->count()} assigned tasks\n";
}

$userWithTimeEntries = User::whereHas('timeEntries')->first();
if ($userWithTimeEntries) {
    echo "- User '{$userWithTimeEntries->name}' has {$userWithTimeEntries->timeEntries()->count()} time entries\n";
}
echo "\n";

// Test 6: Business Logic
echo "6. TESTING BUSINESS LOGIC\n";
echo "==========================\n";

// Test project completion calculation
if ($totalProjects > 0) {
    $activeProjects = Project::where('status', Project::STATUS_ACTIVE)->count();
    $completedProjects = Project::where('status', Project::STATUS_COMPLETED)->count();
    echo "Project Status Distribution:\n";
    echo "- Active: {$activeProjects}\n";
    echo "- Completed: {$completedProjects}\n";
    echo "- Others: " . ($totalProjects - $activeProjects - $completedProjects) . "\n\n";
}

// Test task status distribution
if ($totalTasks > 0) {
    $pendingTasks = Task::where('status', Task::STATUS_PENDING)->count();
    $inProgressTasks = Task::where('status', Task::STATUS_IN_PROGRESS)->count();
    $completedTasks = Task::where('status', Task::STATUS_COMPLETED)->count();
    echo "Task Status Distribution:\n";
    echo "- Pending: {$pendingTasks}\n";
    echo "- In Progress: {$inProgressTasks}\n";
    echo "- Completed: {$completedTasks}\n";
    echo "- Others: " . ($totalTasks - $pendingTasks - $inProgressTasks - $completedTasks) . "\n\n";
}

echo "=== FUNCTIONALITY TEST COMPLETED ===\n";