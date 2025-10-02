<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();

        // Admin can create tasks anywhere
        if ($user->isAdmin()) {
            return true;
        }

        // Manager can create tasks in their projects
        if ($user->isManager() && $this->project_id) {
            $project = \App\Models\Project::find($this->project_id);
            return $project && $project->manager_id === $user->id;
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
        return [
            'project_id' => [
                'required',
                'exists:projects,id',
                function ($attribute, $value, $fail) {
                    $project = \App\Models\Project::find($value);

                    // Cannot add tasks to completed or cancelled projects
                    if ($project && in_array($project->status, ['terminé', 'annulé'])) {
                        $fail('Impossible d\'ajouter des tâches à un projet terminé ou annulé.');
                    }
                },
            ],
            'title' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    // Check for duplicate title within the same project
                    if ($this->project_id) {
                        $exists = \App\Models\Task::where('project_id', $this->project_id)
                            ->where('title', trim($value))
                            ->exists();

                        if ($exists) {
                            $fail('Une tâche avec ce titre existe déjà dans ce projet.');
                        }
                    }
                },
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'due_date' => [
                'nullable',
                'date',
                'after:today',
                function ($attribute, $value, $fail) {
                    if ($value && $this->project_id) {
                        $project = \App\Models\Project::find($this->project_id);

                        // Due date cannot be after project end date
                        if ($project && $project->end_date && strtotime($value) > strtotime($project->end_date)) {
                            $fail('L\'échéance ne peut pas être postérieure à la fin du projet.');
                        }
                    }
                },
            ],
            'assigned_to' => [
                'nullable',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $user = \App\Models\User::find($value);

                        // Only members can be assigned tasks
                        if ($user && !$user->isMember()) {
                            $fail('Seuls les membres peuvent être assignés à des tâches.');
                        }
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
            'project_id.required' => 'Le projet est obligatoire.',
            'project_id.exists' => 'Le projet sélectionné n\'existe pas.',
            'title.required' => 'Le titre de la tâche est obligatoire.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
            'due_date.date' => 'L\'échéance doit être une date valide.',
            'due_date.after' => 'L\'échéance ne peut pas être dans le passé.',
            'assigned_to.exists' => 'L\'utilisateur assigné n\'existe pas.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'project_id' => 'projet',
            'title' => 'titre',
            'description' => 'description',
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

    /**
     * Get the validated data from the request with additional processing.
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        // Set default status for new tasks
        if (!isset($validated['status'])) {
            $validated['status'] = 'à_faire';
        }

        return $validated;
    }
}