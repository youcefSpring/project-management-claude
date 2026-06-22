<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrashController extends Controller
{
    /** Allowed trash types → model + the column used as a human label. */
    private const TYPES = [
        'project' => [Project::class, 'title', 'bi-folder'],
        'task' => [Task::class, 'title', 'bi-check2-square'],
        'time_entry' => [TimeEntry::class, 'comment', 'bi-clock'],
        'user' => [User::class, 'name', 'bi-person'],
    ];

    /** Recycle bin: the items the current user deleted. */
    public function index(Request $request)
    {
        $items = collect();
        foreach (self::TYPES as $type => [$model, $label, $icon]) {
            $rows = $model::onlyTrashed()
                ->where('deleted_by', $request->user()->id)
                ->latest('deleted_at')
                ->limit(100)
                ->get();

            foreach ($rows as $row) {
                $items->push([
                    'type' => $type,
                    'type_label' => __('app.trash.types.'.$type),
                    'icon' => $icon,
                    'id' => $row->getKey(),
                    'label' => $row->{$label} ?: ('#'.$row->getKey()),
                    'deleted_at' => optional($row->deleted_at)->diffForHumans(),
                ]);
            }
        }

        $items = $items->sortByDesc(fn ($i) => $i['deleted_at'])->values();

        return view('trash.index', ['items' => $items]);
    }

    public function restore(Request $request, string $type, int $id): JsonResponse
    {
        $model = $this->resolve($type, $id, $request);
        $model->restore();

        return $this->ajaxSuccess(__('app.trash.restored'), route('trash.index'));
    }

    public function forceDelete(Request $request, string $type, int $id): JsonResponse
    {
        $model = $this->resolve($type, $id, $request);
        $model->forceDelete();

        return $this->ajaxSuccess(__('app.trash.permanently_deleted'), route('trash.index'));
    }

    /** Find a trashed record the current user is allowed to act on (one they deleted). */
    private function resolve(string $type, int $id, Request $request)
    {
        abort_unless(isset(self::TYPES[$type]), 404);
        [$modelClass] = self::TYPES[$type];

        $model = $modelClass::onlyTrashed()->findOrFail($id);
        abort_unless((int) $model->deleted_by === (int) $request->user()->id, 403);

        return $model;
    }
}
