<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $task = $this->route('task');
        $user = auth()->user();

        // Admin can update any task
        if ($user->isAdmin()) {
            return true;
        }

        // Manager can update tasks in their projects
        if ($user->isManager() && $task->project->manager_id === $user->id) {
            return true;
        }

        // Members can update their assigned tasks (limited fields)
        return $user->isMember() && $task->assigned_to === $user->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $task = $this->route('task');
        $user = auth()->user();

        $rules = [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tasks', 'title')
                    ->where('project_id', $task->project_id)
                    ->ignore($task->id)
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ];

        // Status validation with business rules
        $rules['status'] = [
            'required',
            'in:à_faire,en_cours,fait',
            function ($attribute, $value, $fail) use ($task, $user) {
                // Check allowed transitions
                $allowedTransitions = [
                    'à_faire' => ['en_cours'],
                    'en_cours' => ['à_faire', 'fait'],
                    'fait' => ['en_cours'],
                ];

                if (!in_array($value, $allowedTransitions[$task->status] ?? [])) {
                    $fail('Transition de statut non autorisée.');
                }

                // Business rule: only assigned user or manager can change status
                if ($user->isMember() && $task->assigned_to !== $user->id) {
                    $fail('Vous ne pouvez modifier que le statut de vos propres tâches.');
                }

                // Business rule: task must be assigned to move to in_progress
                if ($value === 'en_cours' && !$task->assigned_to) {
                    $fail('La tâche doit être assignée avant d\'être mise en cours.');
                }

                // Cannot modify completed tasks in completed projects
                if ($task->project->status === 'terminé' && $value !== 'fait') {
                    $fail('Impossible de modifier une tâche dans un projet terminé.');
                }
            },
        ];

        // Only managers and admins can change assignment and due date
        if ($user->isAdmin() || ($user->isManager() && $task->project->manager_id === $user->id)) {
            $rules['due_date'] = [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($task) {
                    if ($value) {
                        // Due date cannot be after project end date
                        if ($task->project->end_date && strtotime($value) > strtotime($task->project->end_date)) {
                            $fail('L\'échéance ne peut pas être postérieure à la fin du projet.');
                        }

                        // Cannot set past due date for incomplete tasks
                        if (strtotime($value) < strtotime('today') && $task->status !== 'fait') {
                            $fail('L\'échéance ne peut pas être dans le passé pour une tâche non terminée.');
                        }
                    }
                },
            ];

            $rules['assigned_to'] = [
                'nullable',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $assignee = \App\Models\User::find($value);

                        // Only members can be assigned tasks
                        if ($assignee && !$assignee->isMember()) {
                            $fail('Seuls les membres peuvent être assignés à des tâches.');
                        }
                    }
                },
            ];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Le titre de la tâche est obligatoire.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'title.unique' => 'Une tâche avec ce titre existe déjà dans ce projet.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
            'status.required' => 'Le statut de la tâche est obligatoire.',
            'status.in' => 'Le statut sélectionné n\'est pas valide.',
            'due_date.date' => 'L\'échéance doit être une date valide.',
            'assigned_to.exists' => 'L\'utilisateur assigné n\'existe pas.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'titre',
            'description' => 'description',
            'status' => 'statut',
            'due_date' => 'échéance',
            'assigned_to' => 'assigné à',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => trim($this->title),
            'description' => trim($this->description),
        ]);
    }
}