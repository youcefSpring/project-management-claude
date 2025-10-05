<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTimeEntryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $timeEntry = $this->route('timeEntry');
        $user = auth()->user();

        // Admin can update any time entry
        if ($user->isAdmin()) {
            return true;
        }

        // Manager can update time entries for their projects
        if ($user->isManager() && $timeEntry->task->project->manager_id === $user->id) {
            return true;
        }

        // Users can update their own time entries
        return $timeEntry->user_id === $user->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $timeEntry = $this->route('timeEntry');

        return [
            'start_time' => [
                'required',
                'date_format:Y-m-d H:i:s',
                function ($attribute, $value, $fail) use ($timeEntry) {
                    $startTime = Carbon::parse($value);

                    // Cannot modify time entries older than 7 days (configurable)
                    $modificationDeadline = config('app.time_entry_modification_days', 7);
                    if ($timeEntry->created_at->diffInDays(Carbon::now()) > $modificationDeadline) {
                        $fail('Cette entrée de temps est trop ancienne pour être modifiée.');
                    }

                    // Cannot log time in the future
                    if ($startTime->isFuture()) {
                        $fail('L\'heure de début ne peut pas être dans le futur.');
                    }

                    // Cannot log time more than 30 days in the past
                    if ($startTime->lt(Carbon::now()->subDays(30))) {
                        $fail('L\'heure de début ne peut pas être antérieure à 30 jours.');
                    }

                    // Check if time is within project boundaries
                    if ($timeEntry->task && $timeEntry->task->project) {
                        if ($startTime->lt($timeEntry->task->project->start_date)) {
                            $fail('L\'heure de début ne peut pas être antérieure au début du projet.');
                        }
                    }
                },
            ],
            'end_time' => [
                'required',
                'date_format:Y-m-d H:i:s',
                'after:start_time',
                function ($attribute, $value, $fail) use ($timeEntry) {
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

                        // Check for overlaps with other time entries (excluding current)
                        $this->validateNoOverlaps($startTime, $endTime, $timeEntry->id, $fail);
                    }
                },
            ],
            'comment' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Check for overlapping time entries
     */
    private function validateNoOverlaps(Carbon $startTime, Carbon $endTime, int $exceptId, $fail): void
    {
        $timeEntry = $this->route('timeEntry');

        $overlapping = \App\Models\TimeEntry::where('user_id', $timeEntry->user_id)
            ->where('id', '!=', $exceptId)
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
            'start_time' => 'heure de début',
            'end_time' => 'heure de fin',
            'comment' => 'commentaire',
        ];
    }
}
