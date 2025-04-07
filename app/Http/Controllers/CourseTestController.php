<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTestRequest;
use App\Http\Requests\UpdateTestRequest;
use App\Models\Test;
use App\Models\TestQuestion;
use App\Models\TestQuestionAnswer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Psy\Util\Json;

class CourseTestController extends Controller
{
    /**
     * Получить список всех тестов для курса
     */
    public function index(Request $request, $courseId): JsonResponse
    {
        $tests = Test::where('courses_id', $courseId)->get();

        return response()->json([
            'success' => true,
            'data' => $tests
        ]);
    }

    /**
     * Получить детальную информацию о тесте со всеми вопросами и ответами
     */
    public function show($id): JsonResponse
    {
        $test = Test::with(['questions.answers'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $test
        ]);
    }

    /**
     * Создать новый тест с вопросами и ответами
     */
    public function store(CreateTestRequest $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();

        // Логирование для отладки
        Log::info('Пришедшие данные для создания теста: ', [
            'data' => $validated
        ]);

        try {
            DB::beginTransaction();

            // Создаем тест - используем title вместо name
            $test = Test::create([
                'title' => $validated['title'],
                'courses_id' => $validated['courses_id']
            ]);

            Log::info('Создан тест: ', ['test_id' => $test->id, 'test_data' => $test->toArray()]);

            // Создаем вопросы и ответы
            foreach ($validated['questions'] as $questionData) {
                $question = TestQuestion::create([
                    'name' => $questionData['name'],
                    'test_id' => $test->id
                ]);

                Log::info('Создан вопрос: ', ['question_id' => $question->id, 'question_data' => $question->toArray()]);

                foreach ($questionData['answers'] as $answerData) {
                    $answer = TestQuestionAnswer::create([
                        'name' => $answerData['name'],
                        'is_valid' => $answerData['is_valid'],
                        'test_question_id' => $question->id
                    ]);

                    Log::info('Создан ответ: ', ['answer_id' => $answer->id, 'answer_data' => $answer->toArray()]);
                }
            }

            DB::commit();

            // Для работы с Inertia
            if ($request->wantsJson()) {
                // Для API запросов возвращаем JSON
                return response()->json([
                    'success' => true,
                    'message' => 'Тест успешно создан',
                    'data' => Test::with(['questions.answers'])->find($test->id)
                ], 201);
            }

            // Для Inertia запросов возвращаем редирект
            return redirect()
                ->route('course.tests', ['courseId' => $validated['courses_id']])
                ->with('success', 'Тест успешно создан');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Ошибка при создании теста: ', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ошибка при создании теста: ' . $e->getMessage()
                ], 500);
            }

            // Для Inertia запросов возвращаем ошибку через сессию
            return redirect()
                ->back()
                ->withErrors(['error' => 'Ошибка при создании теста: ' . $e->getMessage()]);
        }
    }

    /**
     * Обновить тест
     */
    public function update(UpdateTestRequest $request, $id): JsonResponse
    {
        $validated = $request->validated();
        $test = Test::findOrFail($id);

        try {
            DB::beginTransaction();

            // Обновляем основные данные теста
            if (isset($validated['title'])) {
                $test->update(['title' => $validated['title']]);
            }

            // Обновляем вопросы и ответы, если они были предоставлены
            if (isset($validated['questions'])) {
                // Удаляем существующие вопросы и их ответы
                $test->questions()->each(function ($question) {
                    $question->answers()->delete();
                });
                $test->questions()->delete();

                // Создаем новые вопросы и ответы
                foreach ($validated['questions'] as $questionData) {
                    $question = TestQuestion::create([
                        'name' => $questionData['name'],
                        'test_id' => $test->id
                    ]);

                    foreach ($questionData['answers'] as $answerData) {
                        TestQuestionAnswer::create([
                            'name' => $answerData['name'],
                            'is_valid' => $answerData['is_valid'],
                            'test_question_id' => $question->id
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Тест успешно обновлен',
                'data' => Test::with(['questions.answers'])->find($test->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Ошибка при обновлении теста: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Удалить тест
     */
    public function destroy($id): JsonResponse
    {
        $test = Test::findOrFail($id);

        try {
            DB::beginTransaction();

            // Удаляем все вопросы и их ответы
            $test->questions()->each(function ($question) {
                $question->answers()->delete();
            });
            $test->questions()->delete();

            // Удаляем сам тест
            $test->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Тест успешно удален'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Ошибка при удалении теста: ' . $e->getMessage()
            ], 500);
        }
    }
}
