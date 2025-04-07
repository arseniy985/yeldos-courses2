<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitTestAnswersRequest extends FormRequest
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
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|integer|exists:test_questions,id',
            'answers.*.answer_id' => 'required|integer|exists:test_questions_answers,id',
        ];
    }
}
