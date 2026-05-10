<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $task = $this->route('task');
        $user = auth()->user();

        // Admin can update any task status
        if ($user->isAdmin()) {
            return true;
        }

        // Manager can update tasks in their projects
        if ($user->isManager() && $task->project->manager_id === $user->id) {
            return true;
        }

        // Members can update status of their assigned tasks
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

        return [
            'status' => [
                'required',
                'in:à_faire,en_cours,fait',
                function ($attribute, $value, $fail) use ($task, $user) {
                    // Check allowed transitions
                    $allowedTransitions = [
                        'à_faire' => ['en_cours'],
                        'en_cours' => ['à_faire', 'fait'],
                        'fait' => ['en_cours'],
                    ];

                    if (! in_array($value, $allowedTransitions[$task->status] ?? [])) {
                        $fail('Transition de statut non autorisée.');
                    }

                    // Business rule: only assigned user or manager can change status
                    if ($user->isMember() && $task->assigned_to !== $user->id) {
                        $fail('Vous ne pouvez modifier que le statut de vos propres tâches.');
                    }

                    // Business rule: task must be assigned to move to in_progress
                    if ($value === 'en_cours' && ! $task->assigned_to) {
                        $fail('La tâche doit être assignée avant d\'être mise en cours.');
                    }

                    // Cannot modify tasks in completed/cancelled projects
                    if (in_array($task->project->status, ['terminé', 'annulé']) && $value !== $task->status) {
                        $fail('Impossible de modifier une tâche dans un projet terminé ou annulé.');
                    }

                    // Cannot start task if due date is in the past
                    if ($value === 'en_cours' && $task->due_date && $task->due_date->isPast()) {
                        $fail('Impossible de démarrer une tâche dont l\'échéance est dépassée.');
                    }
                },
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Le statut est obligatoire.',
            'status.in' => 'Le statut sélectionné n\'est pas valide.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'status' => 'statut',
        ];
    }
}
