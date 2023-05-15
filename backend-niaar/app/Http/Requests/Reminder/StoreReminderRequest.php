<?php

namespace App\Http\Requests\Reminder;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReminderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id' => ['required', Rule::exists('users', 'id')],
            'inquiry_id' => ['nullable', Rule::exists('inquiries', 'id')],
            'title' => ['nullable', 'string', 'min:3'],
            'body' => ['required', 'string', 'min:3'],
            'reminder_date' => ['nullable', 'date']
        ];
    }
}
