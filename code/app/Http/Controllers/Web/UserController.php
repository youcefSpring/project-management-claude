<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $query = User::query();

        // Apply filters
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('name')->paginate(15);
        $roles = User::getRoles();
        $roleLabels = User::getRoleLabels();

        return view('users.index', compact('users', 'roles', 'roleLabels'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $this->authorize('create', User::class);

        $roles = User::getRoles();
        $roleLabels = User::getRoleLabels();

        return view('users.create', compact('roles', 'roleLabels'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(User::getRoles())],
            'language' => 'nullable|string|in:en,fr,ar',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'language' => $request->language ?? 'en',
        ]);

        return redirect()->route('users.index')
            ->with('success', __('User created successfully.'));
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        // Load user's related data
        $user->load([
            'managedProjects.tasks',
            'assignedTasks.project',
            'timeEntries.task.project',
            'taskNotes.task',
        ]);

        // Calculate user statistics
        $stats = [
            'total_projects_managed' => $user->managedProjects->count(),
            'total_tasks_assigned' => $user->assignedTasks->count(),
            'completed_tasks' => $user->assignedTasks->where('status', 'completed')->count(),
            'total_time_logged' => $user->timeEntries->sum('duration_hours'),
            'total_comments' => $user->taskNotes->count(),
        ];

        return view('users.show', compact('user', 'stats'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        $roles = User::getRoles();
        $roleLabels = User::getRoleLabels();

        return view('users.edit', compact('user', 'roles', 'roleLabels'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(User::getRoles())],
            'language' => 'nullable|string|in:en,fr,ar',
        ];

        // Only validate password if it's being changed
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $request->validate($rules);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'language' => $request->language ?? $user->language,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('users.show', $user)
            ->with('success', __('User updated successfully.'));
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        // Prevent deleting the last admin
        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return redirect()->route('users.index')
                ->with('error', __('app.messages.cannot_delete_last_admin'));
        }

        // Check if user has any associated data
        $hasProjects = $user->managedProjects()->count() > 0;
        $hasTasks = $user->assignedTasks()->count() > 0;
        $hasTimeEntries = $user->timeEntries()->count() > 0;

        if ($hasProjects || $hasTasks || $hasTimeEntries) {
            return redirect()->route('users.index')
                ->with('error', __('app.messages.cannot_delete_user_with_data'));
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', __('User deleted successfully.'));
    }

    /**
     * Update user role (quick action for admins)
     */
    public function updateRole(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $request->validate([
            'role' => ['required', Rule::in(User::getRoles())],
        ]);

        // Prevent removing the last admin
        if ($user->isAdmin() && $request->role !== 'admin' && User::where('role', 'admin')->count() <= 1) {
            return response()->json([
                'success' => false,
                'message' => __('Cannot remove the last administrator role.'),
            ], 400);
        }

        $user->update(['role' => $request->role]);

        return response()->json([
            'success' => true,
            'message' => __('User role updated successfully.'),
            'user' => [
                'id' => $user->id,
                'role' => $user->role,
                'role_label' => $user->getRoleLabel(),
            ],
        ]);
    }

    /**
     * Get user statistics for dashboard
     */
    public function getUserStats()
    {
        $this->authorize('viewAny', User::class);

        $stats = [
            'total_users' => User::count(),
            'users_by_role' => User::selectRaw('role, COUNT(*) as count')
                ->groupBy('role')
                ->pluck('count', 'role')
                ->toArray(),
            'recent_users' => User::latest()
                ->take(5)
                ->select('id', 'name', 'email', 'role', 'created_at')
                ->get(),
        ];

        return response()->json($stats);
    }
}
