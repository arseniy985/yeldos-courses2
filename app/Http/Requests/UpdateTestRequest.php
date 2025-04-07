<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTestRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'questions' => 'sometimes|required|array|min:1',
            'questions.*.name' => 'required|string|max:255',
            'questions.*.answers' => 'required|array|min:2',
            'questions.*.answers.*.name' => 'required|string|max:255',
            'questions.*.answers.*.is_valid' => 'required|boolean',
        ];
    }
}
