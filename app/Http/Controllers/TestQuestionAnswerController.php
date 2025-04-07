<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTestAnswerRequest;
use App\Http\Requests\UpdateTestAnswerRequest;
use App\Models\TestQuestionAnswer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestQuestionAnswerController extends Controller
{
    /**
     * Получить список всех ответов для вопроса теста
     */
    public function index(Request $request, $questionId): JsonResponse
    {
        $answers = TestQuestionAnswer::where('test_question_id', $questionId)->get();
        
        return response()->json([
            'success' => true,
            'data' => $answers
        ]);
    }

    /**
     * Получить детальную информацию об ответе на вопрос теста
     */
    public function show($id): JsonResponse
    {
        $answer = TestQuestionAnswer::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $answer
        ]);
    }

    /**
     * Создать новый ответ на вопрос теста
     */
    public function store(CreateTestAnswerRequest $request): JsonResponse
    {
        $validated = $request->validated();
        
        try {
            $answer = TestQuestionAnswer::create([
                'name' => $validated['name'],
                'is_valid' => $validated['is_valid'],
                'test_question_id' => $validated['test_question_id']
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Ответ на вопрос теста успешно создан',
                'data' => $answer
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при создании ответа на вопрос теста: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Обновить ответ на вопрос теста
     */
    public function update(UpdateTestAnswerRequest $request, $id): JsonResponse
    {
        $validated = $request->validated();
        $answer = TestQuestionAnswer::findOrFail($id);
        
        try {
            $answer->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Ответ на вопрос теста успешно обновлен',
                'data' => $answer->fresh()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при обновлении ответа на вопрос теста: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Удалить ответ на вопрос теста
     */
    public function destroy($id): JsonResponse
    {
        $answer = TestQuestionAnswer::findOrFail($id);
        
        try {
            $answer->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Ответ на вопрос теста успешно удален'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при удалении ответа на вопрос теста: ' . $e->getMessage()
            ], 500);
        }
    }
}
