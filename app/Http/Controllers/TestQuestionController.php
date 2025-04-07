<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTestQuestionRequest;
use App\Http\Requests\UpdateTestQuestionRequest;
use App\Models\TestQuestion;
use App\Models\TestQuestionAnswer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestQuestionController extends Controller
{
    /**
     * Получить список всех вопросов для теста
     */
    public function index(Request $request, $testId): JsonResponse
    {
        $questions = TestQuestion::with('answers')
            ->where('test_id', $testId)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $questions
        ]);
    }

    /**
     * Получить детальную информацию о вопросе теста
     */
    public function show($id): JsonResponse
    {
        $question = TestQuestion::with('answers')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $question
        ]);
    }

    /**
     * Создать новый вопрос теста с ответами
     */
    public function store(CreateTestQuestionRequest $request): JsonResponse
    {
        $validated = $request->validated();
        
        try {
            DB::beginTransaction();
            
            // Создаем вопрос
            $question = TestQuestion::create([
                'name' => $validated['name'],
                'test_id' => $validated['test_id']
            ]);
            
            // Создаем ответы
            foreach ($validated['answers'] as $answerData) {
                TestQuestionAnswer::create([
                    'name' => $answerData['name'],
                    'is_valid' => $answerData['is_valid'],
                    'test_question_id' => $question->id
                ]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Вопрос теста успешно создан',
                'data' => TestQuestion::with('answers')->find($question->id)
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при создании вопроса теста: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Обновить вопрос теста
     */
    public function update(Request $request, $id): JsonResponse
    {
        $question = TestQuestion::findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            // Обновляем основные данные вопроса
            if ($request->has('name')) {
                $question->update(['name' => $request->name]);
            }
            
            // Обновляем ответы, если они были предоставлены
            if ($request->has('answers')) {
                // Удаляем существующие ответы
                $question->answers()->delete();
                
                // Создаем новые ответы
                foreach ($request->answers as $answerData) {
                    TestQuestionAnswer::create([
                        'name' => $answerData['name'],
                        'is_valid' => $answerData['is_valid'],
                        'test_question_id' => $question->id
                    ]);
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Вопрос теста успешно обновлен',
                'data' => TestQuestion::with('answers')->find($question->id)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при обновлении вопроса теста: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Удалить вопрос теста
     */
    public function destroy($id): JsonResponse
    {
        $question = TestQuestion::findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            // Удаляем все ответы
            $question->answers()->delete();
            
            // Удаляем сам вопрос
            $question->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Вопрос теста успешно удален'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при удалении вопроса теста: ' . $e->getMessage()
            ], 500);
        }
    }
}
