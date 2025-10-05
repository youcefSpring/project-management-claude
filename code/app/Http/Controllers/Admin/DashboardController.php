<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\TimeEntry;
use App\Models\User;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(Request $request)
    {
        $user = $request->user();

        // Admin-specific stats
        $stats = [
            'courses' => 0, // This would come from a courses table if it exists
            'projects' => Project::count(),
            'publications' => 0, // This would come from a publications table if it exists
            'blog_posts' => 0, // This would come from a blog_posts table if it exists
            'unread_messages' => 0, // This would come from a messages table if it exists
            'total_messages' => 0, // This would come from a messages table if it exists
            'profile_completion' => 85,
            'response_rate' => 95,
            'total_users' => User::count(),
            'active_projects' => Project::where('status', 'active')->count(),
            'completed_tasks' => Task::where('status', 'completed')->count(),
            'total_time_logged' => TimeEntry::sum('duration_hours'),
        ];

        // Recent activity (admin view of all system activity)
        $recentActivity = $this->getAdminRecentActivity();

        return view('admin.dashboard', compact('stats', 'recentActivity'));
    }

    private function getAdminRecentActivity()
    {
        $activities = [];

        // Recent projects
        $recentProjects = Project::orderBy('created_at', 'desc')->take(3)->get();
        foreach ($recentProjects as $project) {
            $activities[] = [
                'type' => 'project',
                'action' => 'Project Created',
                'title' => $project->title,
                'date' => $project->created_at->diffForHumans(),
            ];
        }

        // Recent tasks
        $recentTasks = Task::orderBy('created_at', 'desc')->take(3)->get();
        foreach ($recentTasks as $task) {
            $activities[] = [
                'type' => 'task',
                'action' => 'Task Created',
                'title' => $task->title,
                'date' => $task->created_at->diffForHumans(),
            ];
        }

        // Recent users
        $recentUsers = User::orderBy('created_at', 'desc')->take(2)->get();
        foreach ($recentUsers as $user) {
            $activities[] = [
                'type' => 'user',
                'action' => 'User Registered',
                'title' => $user->name,
                'date' => $user->created_at->diffForHumans(),
            ];
        }

        // Sort by most recent
        usort($activities, function ($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        return array_slice($activities, 0, 8);
    }
}
