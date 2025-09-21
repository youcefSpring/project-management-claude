<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class UpdateTaskNoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $taskNote = $this->route('taskNote');
        $user = auth()->user();

        // Admin can update any task note
        if ($user->isAdmin()) {
            return true;
        }

        // Manager can update notes in their projects
        if ($user->isManager() && $taskNote->task->project->manager_id === $user->id) {
            return true;
        }

        // Users can only update their own notes within time limit
        if ($taskNote->user_id === $user->id) {
            // Check modification time limit (24 hours)
            $modificationDeadline = config('app.note_modification_hours', 24);
            return $taskNote->created_at->diffInHours(Carbon::now()) <= $modificationDeadline;
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
        $taskNote = $this->route('taskNote');

        return [
            'content' => [
                'required',
                'string',
                'max:1000',
                'min:1',
                function ($attribute, $value, $fail) use ($taskNote) {
                    $trimmedContent = trim($value);

                    // Content cannot be only whitespace
                    if (empty($trimmedContent)) {
                        $fail('Le contenu de la note ne peut pas être vide.');
                    }

                    // Check for potential spam (too many repeated characters)
                    if (preg_match('/(.)\\1{20,}/', $trimmedContent)) {
                        $fail('Le contenu semble invalide (trop de caractères répétés).');
                    }

                    // Cannot modify notes in cancelled projects
                    if ($taskNote->task->project->status === 'annulé') {
                        $fail('Impossible de modifier des notes dans un projet annulé.');
                    }

                    // Check modification time limit for non-admins/non-managers
                    $user = auth()->user();
                    if ($user->isMember() && $taskNote->user_id === $user->id) {
                        $modificationDeadline = config('app.note_modification_hours', 24);
                        if ($taskNote->created_at->diffInHours(Carbon::now()) > $modificationDeadline) {
                            $fail('Cette note est trop ancienne pour être modifiée.');
                        }
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

        if (!empty($matches[1])) {
            $mentionedNames = array_unique($matches[1]);
            $taskNote = $this->route('taskNote');

            // Check if mentioned users exist and have access to the task
            foreach ($mentionedNames as $name) {
                $user = \App\Models\User::where('name', $name)->first();

                if (!$user) {
                    $fail("L'utilisateur @{$name} n'existe pas.");
                    continue;
                }

                // Check if mentioned user has access to this task
                if (!$this->userCanAccessTask($user, $taskNote->task)) {
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
            'content' => 'contenu',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Trim content
        $this->merge([
            'content' => trim($this->content),
        ]);
    }
}