<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskNoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();

        // Check if user can access this task
        if ($this->task_id) {
            $task = \App\Models\Task::find($this->task_id);

            if (! $task) {
                return false;
            }

            // Admin can add notes to any task
            if ($user->isAdmin()) {
                return true;
            }

            // Manager can add notes to tasks in their projects
            if ($user->isManager() && $task->project->manager_id === $user->id) {
                return true;
            }

            // Members can add notes to their assigned tasks
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
        return [
            'task_id' => [
                'required',
                'exists:tasks,id',
                function ($attribute, $value, $fail) {
                    $task = \App\Models\Task::find($value);

                    if ($task) {
                        // Cannot add notes to tasks in cancelled projects
                        if ($task->project->status === 'annulé') {
                            $fail('Impossible d\'ajouter des notes à une tâche d\'un projet annulé.');
                        }
                    }
                },
            ],
            'content' => [
                'required',
                'string',
                'max:1000',
                'min:1',
                function ($attribute, $value, $fail) {
                    $trimmedContent = trim($value);

                    // Content cannot be only whitespace
                    if (empty($trimmedContent)) {
                        $fail('Le contenu de la note ne peut pas être vide.');
                    }

                    // Check for potential spam (too many repeated characters)
                    if (preg_match('/(.)\1{20,}/', $trimmedContent)) {
                        $fail('Le contenu semble invalide (trop de caractères répétés).');
                    }

                    // Validate mentions format
                    $this->validateMentions($trimmedContent, $fail);
                },
            ],
        ];
    }

    /**
     * Validate @mentions in the content
     */
    private function validateMentions(string $content, $fail): void
    {
        // Extract mentions
        preg_match_all('/@(\w+)/', $content, $matches);

        if (! empty($matches[1])) {
            $mentionedNames = array_unique($matches[1]);

            // Check if mentioned users exist and have access to the task
            foreach ($mentionedNames as $name) {
                $user = \App\Models\User::where('name', $name)->first();

                if (! $user) {
                    $fail("L'utilisateur @{$name} n'existe pas.");

                    continue;
                }

                // Check if mentioned user has access to this task
                $task = \App\Models\Task::find($this->task_id);
                if ($task && ! $this->userCanAccessTask($user, $task)) {
                    $fail("L'utilisateur @{$name} n'a pas accès à cette tâche.");
                }
            }

            // Limit number of mentions per note
            if (count($mentionedNames) > 5) {
                $fail('Vous ne pouvez pas mentionner plus de 5 utilisateurs par note.');
            }
        }
    }

    /**
     * Check if user can access a task
     */
    private function userCanAccessTask(\App\Models\User $user, \App\Models\Task $task): bool
    {
        // Admin can access any task
        if ($user->isAdmin()) {
            return true;
        }

        // Manager can access tasks in their projects
        if ($user->isManager() && $task->project->manager_id === $user->id) {
            return true;
        }

        // Members can access their assigned tasks
        return $user->isMember() && $task->assigned_to === $user->id;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'task_id.required' => 'La tâche est obligatoire.',
            'task_id.exists' => 'La tâche sélectionnée n\'existe pas.',
            'content.required' => 'Le contenu de la note est obligatoire.',
            'content.string' => 'Le contenu doit être du texte.',
            'content.max' => 'Le contenu ne peut pas dépasser 1000 caractères.',
            'content.min' => 'Le contenu doit contenir au moins 1 caractère.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'task_id' => 'tâche',
            'content' => 'contenu',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Add user_id and trim content
        $this->merge([
            'user_id' => auth()->id(),
            'content' => trim($this->content),
        ]);
    }
}
