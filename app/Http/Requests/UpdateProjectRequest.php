<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $project = $this->route('project');
        $user = auth()->user();

        // Admin can update any project
        if ($user->isAdmin()) {
            return true;
        }

        // Manager can update their own projects
        return $user->isManager() && $project->manager_id === $user->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $project = $this->route('project');

        return [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('projects', 'title')->ignore($project->id)
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => [
                'required',
                'in:en_cours,terminé,annulé',
                function ($attribute, $value, $fail) use ($project) {
                    // Business rule validation for status transitions
                    $allowedTransitions = [
                        'en_cours' => ['terminé', 'annulé'],
                        'annulé' => ['en_cours'],
                        'terminé' => [], // Cannot change from completed
                    ];

                    if (!in_array($value, $allowedTransitions[$project->status] ?? [])) {
                        $fail('Transition de statut non autorisée.');
                    }

                    // Cannot complete project with pending tasks
                    if ($value === 'terminé') {
                        $pendingTasks = $project->tasks()->whereIn('status', ['à_faire', 'en_cours'])->count();
                        if ($pendingTasks > 0) {
                            $fail('Impossible de terminer le projet avec des tâches en cours.');
                        }
                    }
                },
            ],
            'start_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($project) {
                    // Cannot change start date if project has started
                    if ($project->status !== 'en_cours' || $project->start_date->isPast()) {
                        // Allow if new date is not before current start date
                        if (strtotime($value) < strtotime($project->start_date)) {
                            $fail('Impossible de modifier la date de début d\'un projet déjà commencé.');
                        }
                    }
                },
            ],
            'end_date' => [
                'required',
                'date',
                'after:start_date',
                function ($attribute, $value, $fail) use ($project) {
                    // Check if any tasks have due dates after the new end date
                    $tasksAfterEndDate = $project->tasks()
                        ->whereNotNull('due_date')
                        ->where('due_date', '>', $value)
                        ->count();

                    if ($tasksAfterEndDate > 0) {
                        $fail('Impossible de définir une date de fin antérieure aux échéances des tâches.');
                    }
                },
            ],
            'manager_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = \App\Models\User::find($value);
                    if ($user && !$user->isManager() && !$user->isAdmin()) {
                        $fail('Le manager sélectionné doit avoir le rôle manager ou admin.');
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
            'title.required' => 'Le titre du projet est obligatoire.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'title.unique' => 'Un projet avec ce titre existe déjà.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
            'status.required' => 'Le statut du projet est obligatoire.',
            'status.in' => 'Le statut sélectionné n\'est pas valide.',
            'start_date.required' => 'La date de début est obligatoire.',
            'start_date.date' => 'La date de début doit être une date valide.',
            'end_date.required' => 'La date de fin est obligatoire.',
            'end_date.date' => 'La date de fin doit être une date valide.',
            'end_date.after' => 'La date de fin doit être postérieure à la date de début.',
            'manager_id.required' => 'Le manager du projet est obligatoire.',
            'manager_id.exists' => 'Le manager sélectionné n\'existe pas.',
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
            'start_date' => 'date de début',
            'end_date' => 'date de fin',
            'manager_id' => 'manager',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Trim whitespace from title and description
        $this->merge([
            'title' => trim($this->title),
            'description' => trim($this->description),
        ]);
    }
}