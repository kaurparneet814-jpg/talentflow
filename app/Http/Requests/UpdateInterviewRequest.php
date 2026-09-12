<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInterviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'interviewer_id' => ['sometimes', 'exists:users,id'],
            'scheduled_at' => ['sometimes', 'date', 'after:now'],
            'meeting_link' => ['sometimes', 'nullable', 'url'],
            'status' => ['sometimes', 'in:scheduled,completed,cancelled'],
        ];
    }
}
