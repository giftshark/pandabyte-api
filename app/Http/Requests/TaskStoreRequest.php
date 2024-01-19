<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'max:100'],
            'description' => 'required',
            'due_date' => ['sometimes','nullable', 'date'],
            'completed_at' =>  ['sometimes','nullable', 'date'],
        ];
    }
}
