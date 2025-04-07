<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTestQuestionRequest extends FormRequest
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
            'test_id' => 'required|integer|exists:tests,id',
            'name' => 'required|string|max:255',
            'answers' => 'required|array|min:2',
            'answers.*.name' => 'required|string|max:255',
            'answers.*.is_valid' => 'required|boolean',
        ];
    }
}
