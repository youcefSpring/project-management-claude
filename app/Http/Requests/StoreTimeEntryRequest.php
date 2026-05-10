<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreTimeEntryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();

        // Admin can create time entries for any task
        if ($user->isAdmin()) {
            return true;
        }

        // Check if user can log time for this task
        if ($this->task_id) {
            $task = \App\Models\Task::find($this->task_id);

            if (! $task) {
                return false;
            }

            // Manager can log time for tasks in their projects
            if ($user->isManager() && $task->project->manager_id === $user->id) {
                return true;
            }

            // Members can only log time for their assigned tasks
            return $user->isMember() && $task->assigned_to === $user->id;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = auth()->user();

        return [
            'task_id' => [
                'required',
                'exists:tasks,id',
                function ($attribute, $value, $fail) {
                    $task = \App\Models\Task::find($value);

                    if ($task) {
                        // Cannot log time for completed tasks (unless reopened)
                        if ($task->status === 'fait') {
                            $fail('Impossible d\'ajouter du temps à une tâche terminée.');
                        }

                        // Cannot log time for tasks in completed/cancelled projects
                        if (in_array($task->project->status, ['terminé', 'annulé'])) {
                            $fail('Impossible d\'ajouter du temps à une tâche d\'un projet terminé ou annulé.');
                        }
                    }
                },
            ],
            'start_time' => [
                'required',
                'date_format:Y-m-d H:i:s',
                function ($attribute, $value, $fail) {
                    $startTime = Carbon::parse($value);

                    // Cannot log time in the future
                    if ($startTime->isFuture()) {
                        $fail('L\'heure de début ne peut pas être dans le futur.');
                    }

                    // Cannot log time more than 30 days in the past
                    if ($startTime->lt(Carbon::now()->subDays(30))) {
                        $fail('L\'heure de début ne peut pas être antérieure à 30 jours.');
                    }

                    // Check if time is within project boundaries
                    if ($this->task_id) {
                        $task = \App\Models\Task::find($this->task_id);
                        if ($task && $task->project) {
                            if ($startTime->lt($task->project->start_date)) {
                                $fail('L\'heure de début ne peut pas être antérieure au début du projet.');
                            }
                        }
                    }
                },
            ],
            'end_time' => [
                'required',
                'date_format:Y-m-d H:i:s',
                'after:start_time',
                function ($attribute, $value, $fail) {
                    if ($this->start_time) {
                        $startTime = Carbon::parse($this->start_time);
                        $endTime = Carbon::parse($value);

                        // Check maximum duration (24 hours)
                        if ($startTime->diffInHours($endTime) > 24) {
                            $fail('La durée ne peut pas dépasser 24 heures.');
                        }

                        // Check minimum duration (1 minute)
                        $start = $startTime instanceof \Carbon\Carbon ? $startTime : \Carbon\Carbon::parse($startTime);
                        $end = $endTime instanceof \Carbon\Carbon ? $endTime : \Carbon\Carbon::parse($endTime);

                        if ($start->diffInMinutes($end) < 1) {
                            $fail('La durée minimale est de 1 minute.');
                        }

                        // Check for overlaps with existing time entries
                        $this->validateNoOverlaps($startTime, $endTime, $fail);
                    }
                },
            ],
            'comment' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Check for overlapping time entries
     */
    private function validateNoOverlaps(Carbon $startTime, Carbon $endTime, $fail): void
    {
        $user = auth()->user();

        $overlapping = \App\Models\TimeEntry::where('user_id', $user->id)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($subQuery) use ($startTime, $endTime) {
                        $subQuery->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })
            ->exists();

        if ($overlapping) {
            $fail('Cette entrée de temps chevauche avec une entrée existante.');
        }
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'task_id.required' => 'La tâche est obligatoire.',
            'task_id.exists' => 'La tâche sélectionnée n\'existe pas.',
            'start_time.required' => 'L\'heure de début est obligatoire.',
            'start_time.date_format' => 'L\'heure de début doit être au format Y-m-d H:i:s.',
            'end_time.required' => 'L\'heure de fin est obligatoire.',
            'end_time.date_format' => 'L\'heure de fin doit être au format Y-m-d H:i:s.',
            'end_time.after' => 'L\'heure de fin doit être postérieure à l\'heure de début.',
            'comment.max' => 'Le commentaire ne peut pas dépasser 500 caractères.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'task_id' => 'tâche',
            'start_time' => 'heure de début',
            'end_time' => 'heure de fin',
            'comment' => 'commentaire',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Add user_id for validation
        $this->merge([
            'user_id' => auth()->id(),
        ]);
    }
}
