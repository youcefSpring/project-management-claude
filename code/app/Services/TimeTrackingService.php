<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Task;
use App\Models\TimeEntry;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class TimeTrackingService
{
    /**
     * Create a new time entry
     */
    public function create(array $data, User $user): TimeEntry
    {
        // Validate business rules
        $this->validateTimeEntryData($data, $user);

        // Ensure task exists and user can log time for it
        $task = Task::findOrFail($data['task_id']);
        if (! $this->canUserLogTimeForTask($user, $task)) {
            throw new \UnauthorizedHttpException('You are not authorized to log time for this task.');
        }

        // Parse times
        $startTime = Carbon::parse($data['start_time']);
        $endTime = Carbon::parse($data['end_time']);

        // Create time entry
        $timeEntry = TimeEntry::create([
            'task_id' => $data['task_id'],
            'user_id' => $user->id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'comment' => $data['comment'] ?? null,
        ]);

        // Log activity
        \Log::info('Time entry created', [
            'time_entry_id' => $timeEntry->id,
            'task_id' => $timeEntry->task_id,
            'user_id' => $user->id,
            'duration' => $timeEntry->duration,
        ]);

        return $timeEntry;
    }

    /**
     * Create time entry from duration
     */
    public function createFromDuration(int $taskId, float $duration, User $user, ?string $comment = null, ?Carbon $date = null): TimeEntry
    {
        $date = $date ?? Carbon::today();
        $startTime = $date->copy()->setTime(9, 0); // Default start time
        $endTime = $startTime->copy()->addHours($duration);

        return $this->create([
            'task_id' => $taskId,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'comment' => $comment,
        ], $user);
    }

    /**
     * Update time entry
     */
    public function update(TimeEntry $timeEntry, array $data, User $user): TimeEntry
    {
        // Check permissions
        if (! $timeEntry->canBeEditedBy($user)) {
            throw new \UnauthorizedHttpException('You are not authorized to edit this time entry.');
        }

        // Check if time entry can be modified
        if (! $timeEntry->canBeModified()) {
            throw ValidationException::withMessages([
                'time_entry' => ['This time entry is too old to be modified.'],
            ]);
        }

        // Validate updated data
        $this->validateTimeEntryData($data, $user, $timeEntry->id);

        // Parse times if provided
        $updateData = [];
        if (isset($data['start_time'])) {
            $updateData['start_time'] = Carbon::parse($data['start_time']);
        }
        if (isset($data['end_time'])) {
            $updateData['end_time'] = Carbon::parse($data['end_time']);
        }
        if (isset($data['comment'])) {
            $updateData['comment'] = $data['comment'];
        }

        // Update time entry
        $timeEntry->update($updateData);

        // Log activity
        \Log::info('Time entry updated', [
            'time_entry_id' => $timeEntry->id,
            'updated_by' => $user->id,
            'changes' => $updateData,
        ]);

        return $timeEntry->fresh();
    }

    /**
     * Delete time entry
     */
    public function delete(TimeEntry $timeEntry, User $user): bool
    {
        // Check permissions
        if (! $timeEntry->canBeDeletedBy($user)) {
            throw new \UnauthorizedHttpException('You are not authorized to delete this time entry.');
        }

        // Check if time entry can be modified
        if (! $timeEntry->canBeModified()) {
            throw ValidationException::withMessages([
                'time_entry' => ['This time entry is too old to be deleted.'],
            ]);
        }

        // Log before deletion
        \Log::info('Time entry deleted', [
            'time_entry_id' => $timeEntry->id,
            'task_id' => $timeEntry->task_id,
            'user_id' => $timeEntry->user_id,
            'duration' => $timeEntry->duration,
            'deleted_by' => $user->id,
        ]);

        return $timeEntry->delete();
    }

    /**
     * Get time entries for user
     */
    public function getUserTimeEntries(User $user, array $filters = []): Collection
    {
        $query = TimeEntry::accessibleBy($user);

        // Apply filters
        if (! empty($filters['task_id'])) {
            $query->forTask($filters['task_id']);
        }

        if (! empty($filters['project_id'])) {
            $query->forProject($filters['project_id']);
        }

        if (! empty($filters['start_date']) && ! empty($filters['end_date'])) {
            $query->dateRange(
                Carbon::parse($filters['start_date']),
                Carbon::parse($filters['end_date'])
            );
        }

        if (! empty($filters['user_id'])) {
            $query->forUser($filters['user_id']);
        }

        return $query->with(['task.project', 'user'])
            ->orderBy('start_time', 'desc')
            ->get();
    }

    /**
     * Get time entries with pagination (alias for getUserTimeEntries)
     */
    public function getTimeEntries(array $filters, User $user, int $perPage = 15)
    {
        $query = TimeEntry::accessibleBy($user);

        // Apply filters
        if (! empty($filters['task_id'])) {
            $query->forTask($filters['task_id']);
        }

        if (! empty($filters['project_id'])) {
            $query->forProject($filters['project_id']);
        }

        if (! empty($filters['start_date']) && ! empty($filters['end_date'])) {
            $query->dateRange(
                Carbon::parse($filters['start_date']),
                Carbon::parse($filters['end_date'])
            );
        }

        if (! empty($filters['user_id'])) {
            $query->forUser($filters['user_id']);
        }

        return $query->with(['task.project', 'user'])
            ->orderBy('start_time', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get time tracking dashboard for user
     */
    public function getUserDashboard(User $user): array
    {
        $today = Carbon::today();
        $thisWeek = [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
        $thisMonth = [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];

        return [
            'today_hours' => $this->getTotalHoursForPeriod($user, $today, $today),
            'week_hours' => $this->getTotalHoursForPeriod($user, $thisWeek[0], $thisWeek[1]),
            'month_hours' => $this->getTotalHoursForPeriod($user, $thisMonth[0], $thisMonth[1]),
            'active_tasks' => $this->getActiveTasksForUser($user),
            'recent_entries' => $this->getRecentTimeEntries($user, 5),
            'weekly_breakdown' => $this->getWeeklyBreakdown($user),
        ];
    }

    /**
     * Get project time tracking summary
     */
    public function getProjectSummary(Project $project, User $user): array
    {
        if (! $project->canBeViewedBy($user)) {
            throw new \UnauthorizedHttpException('You are not authorized to view this project.');
        }

        $timeEntries = TimeEntry::forProject($project->id)->get();

        return [
            'total_hours' => $timeEntries->sum('duration_hours'),
            'total_entries' => $timeEntries->count(),
            'team_members_count' => $timeEntries->pluck('user_id')->unique()->count(),
            'tasks_with_time' => $timeEntries->pluck('task_id')->unique()->count(),
            'average_daily_hours' => $this->getAverageDailyHours($timeEntries),
            'time_by_user' => $this->getTimeByUser($timeEntries),
            'time_by_task' => $this->getTimeByTask($timeEntries),
        ];
    }

    /**
     * Get timesheet for user and period
     */
    public function getTimesheet(User $user, Carbon $startDate, Carbon $endDate): array
    {
        $timeEntries = TimeEntry::forUser($user->id)
            ->dateRange($startDate, $endDate)
            ->with(['task.project'])
            ->get();

        $groupedByDate = $timeEntries->groupBy(function ($entry) {
            return $entry->start_time->format('Y-m-d');
        });

        $timesheet = [];
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $dateStr = $currentDate->format('Y-m-d');
            $dayEntries = $groupedByDate->get($dateStr, collect());

            $timesheet[] = [
                'date' => $currentDate->copy(),
                'entries' => $dayEntries,
                'total_hours' => $dayEntries->sum('duration_hours'),
                'is_weekend' => $currentDate->isWeekend(),
            ];

            $currentDate->addDay();
        }

        return [
            'timesheet' => $timesheet,
            'period_total' => $timeEntries->sum('duration_hours'),
            'average_daily' => $timeEntries->sum('duration_hours') / max(1, $startDate->diffInDays($endDate) + 1),
            'working_days' => collect($timesheet)->where('is_weekend', false)->count(),
        ];
    }

    /**
     * Start time tracking (for timer functionality)
     */
    public function startTimer(int $taskId, User $user): array
    {
        $task = Task::findOrFail($taskId);

        if (! $this->canUserLogTimeForTask($user, $task)) {
            throw new \UnauthorizedHttpException('You are not authorized to track time for this task.');
        }

        // Check if user already has an active timer
        $activeTimer = $this->getActiveTimer($user);
        if ($activeTimer) {
            throw ValidationException::withMessages([
                'timer' => ['You already have an active timer running.'],
            ]);
        }

        $startTime = Carbon::now();

        // Store timer in session or cache
        session()->put('active_timer', [
            'task_id' => $taskId,
            'user_id' => $user->id,
            'start_time' => $startTime->toISOString(),
        ]);

        return [
            'task_id' => $taskId,
            'start_time' => $startTime,
            'task_title' => $task->title,
        ];
    }

    /**
     * Stop time tracking and create time entry
     */
    public function stopTimer(User $user, ?string $comment = null): TimeEntry
    {
        $activeTimer = $this->getActiveTimer($user);

        if (! $activeTimer) {
            throw ValidationException::withMessages([
                'timer' => ['No active timer found.'],
            ]);
        }

        $startTime = Carbon::parse($activeTimer['start_time']);
        $endTime = Carbon::now();

        // Create time entry
        $timeEntry = $this->create([
            'task_id' => $activeTimer['task_id'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'comment' => $comment,
        ], $user);

        // Clear timer
        session()->forget('active_timer');

        return $timeEntry;
    }

    /**
     * Get active timer for user
     */
    public function getActiveTimer(User $user): ?array
    {
        $timer = session()->get('active_timer');

        if ($timer && $timer['user_id'] === $user->id) {
            return $timer;
        }

        return null;
    }

    /**
     * Validate time entry data
     */
    private function validateTimeEntryData(array $data, User $user, ?int $exceptId = null): void
    {
        // Validate time range
        if (isset($data['start_time'], $data['end_time'])) {
            $startTime = Carbon::parse($data['start_time']);
            $endTime = Carbon::parse($data['end_time']);

            if ($endTime->lte($startTime)) {
                throw ValidationException::withMessages([
                    'end_time' => ['End time must be after start time.'],
                ]);
            }

            if ($startTime->gt(Carbon::now())) {
                throw ValidationException::withMessages([
                    'start_time' => ['Start time cannot be in the future.'],
                ]);
            }

            // Check duration (max 24 hours)
            $duration = $startTime->diffInHours($endTime);
            if ($duration > 24) {
                throw ValidationException::withMessages([
                    'duration' => ['Time entry cannot exceed 24 hours.'],
                ]);
            }

            // Check for overlaps
            $this->validateNoOverlaps($data['task_id'] ?? null, $user, $startTime, $endTime, $exceptId);
        }
    }

    /**
     * Validate no overlapping time entries
     */
    private function validateNoOverlaps(?int $taskId, User $user, Carbon $startTime, Carbon $endTime, ?int $exceptId = null): void
    {
        $query = TimeEntry::where('user_id', $user->id)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($subQ) use ($startTime, $endTime) {
                        $subQ->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            });

        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'time_overlap' => ['This time entry overlaps with an existing entry.'],
            ]);
        }
    }

    /**
     * Check if user can log time for task
     */
    private function canUserLogTimeForTask(User $user, Task $task): bool
    {
        // Admin can log time for any task
        if ($user->isAdmin()) {
            return true;
        }

        // Manager can log time for tasks in their projects
        if ($user->isManager() && $task->project->manager_id === $user->id) {
            return true;
        }

        // Members can only log time for their assigned tasks
        if ($user->isMember() && $task->assigned_to === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Get total hours for period
     */
    private function getTotalHoursForPeriod(User $user, Carbon $start, Carbon $end): float
    {
        return TimeEntry::forUser($user->id)
            ->dateRange($start, $end)
            ->sum('duration_hours') ?? 0;
    }

    /**
     * Get active tasks for user
     */
    private function getActiveTasksForUser(User $user): Collection
    {
        return Task::where('assigned_to', $user->id)
            ->whereIn('status', [Task::STATUS_TODO, Task::STATUS_IN_PROGRESS])
            ->with('project')
            ->get();
    }

    /**
     * Get recent time entries
     */
    private function getRecentTimeEntries(User $user, int $limit = 10): Collection
    {
        return TimeEntry::forUser($user->id)
            ->with(['task.project'])
            ->orderBy('start_time', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get weekly breakdown
     */
    private function getWeeklyBreakdown(User $user): array
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $breakdown = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $hours = $this->getTotalHoursForPeriod($user, $date, $date);

            $breakdown[] = [
                'date' => $date,
                'day_name' => $date->format('l'),
                'hours' => $hours,
            ];
        }

        return $breakdown;
    }

    /**
     * Get average daily hours
     */
    private function getAverageDailyHours(Collection $timeEntries): float
    {
        if ($timeEntries->isEmpty()) {
            return 0;
        }

        $uniqueDays = $timeEntries->pluck('start_time')
            ->map(fn ($date) => Carbon::parse($date)->format('Y-m-d'))
            ->unique()
            ->count();

        return $uniqueDays > 0 ? round($timeEntries->sum('duration_hours') / $uniqueDays, 2) : 0;
    }

    /**
     * Get time by user
     */
    private function getTimeByUser(Collection $timeEntries): array
    {
        return $timeEntries->groupBy('user_id')
            ->map(function ($entries) {
                return [
                    'user' => $entries->first()->user,
                    'total_hours' => $entries->sum('duration_hours'),
                    'entry_count' => $entries->count(),
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * Get time by task
     */
    private function getTimeByTask(Collection $timeEntries): array
    {
        return $timeEntries->groupBy('task_id')
            ->map(function ($entries) {
                return [
                    'task' => $entries->first()->task,
                    'total_hours' => $entries->sum('duration_hours'),
                    'entry_count' => $entries->count(),
                ];
            })
            ->values()
            ->toArray();
    }
}
