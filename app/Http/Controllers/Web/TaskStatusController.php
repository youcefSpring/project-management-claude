<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskStatusController extends Controller
{
    /**
     * List the task statuses of the current organization.
     */
    public function index(Request $request)
    {
        $this->authorizeOrganizationAdmin($request);

        $organizationId = $request->user()->organization_id;

        $statuses = TaskStatus::forOrganization($organizationId);

        // A fresh organization has no status yet: seed the default set once.
        if ($statuses->isEmpty()) {
            TaskStatus::seedDefaultsFor($organizationId);
            $statuses = TaskStatus::forOrganization($organizationId);
        }

        $usage = $statuses->mapWithKeys(fn (TaskStatus $status) => [$status->id => $status->tasksCount()]);

        return view('task-statuses.index', compact('statuses', 'usage'));
    }

    /**
     * Create a status for the current organization.
     */
    public function store(Request $request)
    {
        $this->authorizeOrganizationAdmin($request);

        $organizationId = $request->user()->organization_id;

        $data = $request->validate([
            'name' => [
                'required', 'string', 'max:100',
                Rule::unique('task_statuses', 'name')->where('organization_id', $organizationId),
            ],
            'color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_default' => ['nullable', 'boolean'],
            'is_final' => ['nullable', 'boolean'],
        ]);

        $status = TaskStatus::create([
            'organization_id' => $organizationId,
            'slug' => TaskStatus::makeSlug($data['name'], $organizationId),
            'name' => $data['name'],
            'color' => $data['color'],
            'sort_order' => $data['sort_order'] ?? (TaskStatus::forOrg($organizationId)->max('sort_order') + 1),
            'is_default' => $request->boolean('is_default'),
            'is_final' => $request->boolean('is_final'),
        ]);

        $this->syncDefault($status);

        return redirect()->route('task-statuses.index')
            ->with('success', __('Status created successfully.'));
    }

    /**
     * Update a status of the current organization.
     */
    public function update(Request $request, TaskStatus $taskStatus)
    {
        $this->authorizeOrganizationAdmin($request);
        $this->authorizeSameOrganization($request, $taskStatus);

        $organizationId = $request->user()->organization_id;

        $data = $request->validate([
            'name' => [
                'required', 'string', 'max:100',
                Rule::unique('task_statuses', 'name')
                    ->where('organization_id', $organizationId)
                    ->ignore($taskStatus->id),
            ],
            'color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_default' => ['nullable', 'boolean'],
            'is_final' => ['nullable', 'boolean'],
        ]);

        // The slug is kept stable while tasks reference it
        $taskStatus->update([
            'name' => $data['name'],
            'color' => $data['color'],
            'sort_order' => $data['sort_order'] ?? $taskStatus->sort_order,
            'is_default' => $request->boolean('is_default'),
            'is_final' => $request->boolean('is_final'),
        ]);

        $this->syncDefault($taskStatus);

        return redirect()->route('task-statuses.index')
            ->with('success', __('Status updated successfully.'));
    }

    /**
     * Delete a status when no task uses it.
     */
    public function destroy(Request $request, TaskStatus $taskStatus)
    {
        $this->authorizeOrganizationAdmin($request);
        $this->authorizeSameOrganization($request, $taskStatus);

        $used = $taskStatus->tasksCount();

        if ($used > 0) {
            return redirect()->route('task-statuses.index')
                ->with('error', __('This status is used by :count task(s) and cannot be deleted.', ['count' => $used]));
        }

        if (! $taskStatus->canBeDeleted()) {
            return redirect()->route('task-statuses.index')
                ->with('error', __('An organization must keep at least one task status.'));
        }

        $wasDefault = $taskStatus->is_default;
        $organizationId = $taskStatus->organization_id;
        $taskStatus->delete();

        if ($wasDefault) {
            $first = TaskStatus::forOrganization($organizationId)->first();
            $first?->update(['is_default' => true]);
        }

        return redirect()->route('task-statuses.index')
            ->with('success', __('Status deleted successfully.'));
    }

    /**
     * Only one default status per organization.
     */
    private function syncDefault(TaskStatus $status): void
    {
        if (! $status->is_default) {
            // Guarantee that one default always exists
            $hasDefault = TaskStatus::forOrg($status->organization_id)->where('is_default', true)->exists();

            if (! $hasDefault) {
                $status->update(['is_default' => true]);
            }

            return;
        }

        TaskStatus::forOrg($status->organization_id)
            ->where('id', '!=', $status->id)
            ->update(['is_default' => false]);
    }

    private function authorizeOrganizationAdmin(Request $request): void
    {
        $user = $request->user();

        abort_unless($user?->isAdmin() || $user?->isSuperAdmin(), 403);
        abort_if($user->organization_id === null, 403, __('Your account is not linked to an organization.'));
    }

    private function authorizeSameOrganization(Request $request, TaskStatus $taskStatus): void
    {
        abort_unless((int) $taskStatus->organization_id === (int) $request->user()->organization_id, 403);
    }
}
