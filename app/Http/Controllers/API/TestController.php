<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\AIServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TestResult;
use App\Models\Test;
use Illuminate\Http\JsonResponse;

class TestController extends Controller
{
    private AIServiceInterface $aiService;

    private static string $analysis = <<<PROMPT
        Ты — опытный преподаватель. Проанализируй результаты теста и дай подробный персонализированный анализ.

        ВАЖНО: Не включай части данной инструкции в свои ответы. Не цитируй эти задачи и требования в своем ответе.

        **Твои задачи:**
        1. Детально проанализируй ошибки:
           - Выдели тематики, где ошибки повторяются
           - Определи типы вопросов, вызвавших сложности
           - Объясни причины возможных ошибок

        2. Предоставь правильные ответы:
           - Для каждого вопроса, на который был дан неверный ответ, предоставь подробное объяснение правильного ответа
           - Объясни, почему другие варианты ответов были неверными (если это понятно из контекста)

        3. Сформулируй рекомендации:
           - Конкретные темы для повторения с объяснением их важности
           - Методики для лучшего усвоения сложных тем
           - Предложи подход к дальнейшему обучению

        **Структура ответа - используй только эти заголовки:**
        ### Сильные стороны
        (здесь опиши, что получилось лучше всего, с примерами конкретных вопросов)

        ### Области для улучшения
        (здесь детально опиши темы/типы вопросов, которые вызвали сложности)

        ### Разбор ошибок
        (здесь для каждого ошибочного ответа предоставь объяснение правильного ответа)

        ### Рекомендации
        (здесь предложи конкретные шаги для улучшения, с обоснованием каждой рекомендации)

        **Требования:**
        - Давай развернутые объяснения, не ограничивайся маркированными списками
        - Используй примеры из конкретных вопросов теста
        - При разборе ошибок цитируй только текст вопроса и ответ пользователя, не включай инструкции
        - Используй данные из поля "question_text" и "answer_text" для конкретики
        - ВАЖНО: Не используй escape-последовательности для кириллических символов
        - Если видишь в вопросе или ответе закодированный текст в формате \uXXXX, предположи, что это русский текст, и переведи его
        - Не добавляй заголовки, которых нет в структуре выше

        **Контекст:** В следующем сообщении ты получишь результаты теста пользователя в формате JSON. Отвечай непосредственно по этим данным.
    PROMPT;



    public function __construct(AIServiceInterface $aiService)
    {
        $this->aiService = $aiService;
    }

    public function getResultAnalytics(Request $request)
    {
        $resultId = $request->query('result_id');

        if (!$resultId) {
            return response()->json([
                'success' => false,
                'message' => 'ID результата теста не указан'
            ], 400);
        }

        // Получаем результат теста со всеми связанными данными для более глубокого анализа
        $testResult = TestResult::with([
            'test.questions.answers',
            'user',
            'test.course'
        ])->findOrFail($resultId);

        // Декодируем детальные ответы для удобства анализа
        $answers = json_decode($testResult->answers, true);
        if (!empty($answers)) {
            $testResult->answersData = $answers;
        }

        // Используем AIService для получения аналитики с использованием подготовленного промпта
        $analysis = $this->aiService->query(self::$analysis, json_encode($testResult));

        return response()->json($analysis);
    }

    /**
     * Отправить ответы на тест и получить результат
     */
    public function submitAnswers(Request $request, $id = null): JsonResponse
    {
        // Если id передан как параметр маршрута, используем его, иначе берем из запроса
        $testId = $id ?: $request->input('test_id');

        // Проверяем обязательные поля
        if (empty($testId)) {
            return response()->json([
                'success' => false,
                'message' => 'ID теста не указан'
            ], 400);
        }

        if (!$request->has('answers') || !is_array($request->input('answers'))) {
            return response()->json([
                'success' => false,
                'message' => 'Необходимо указать ответы на вопросы'
            ], 400);
        }

        $userAnswers = $request->input('answers');

        // Получаем тест с вопросами и ответами
        $test = Test::with(['questions.answers'])->findOrFail($testId);
        $totalQuestions = $test->questions->count();

        if ($totalQuestions === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Тест не содержит вопросов'
            ], 400);
        }

        // Подсчитываем количество правильных ответов
        $correctAnswers = 0;
        $detailedAnswers = [];

        foreach ($userAnswers as $userAnswer) {
            $questionId = $userAnswer['question_id'];
            $answerId = $userAnswer['answer_id'];

            // Проверяем, что ответ принадлежит вопросу и является правильным
            $question = $test->questions->firstWhere('id', $questionId);

            if ($question) {
                $answer = $question->answers->firstWhere('id', $answerId);

                if ($answer) {
                    $isCorrect = $answer->is_valid;
                    if ($isCorrect) {
                        $correctAnswers++;
                    }

                    // Сохраняем детальную информацию о каждом ответе
                    $detailedAnswers[] = [
                        'question_id' => $questionId,
                        'question_text' => $question->name,
                        'answer_id' => $answerId,
                        'answer_text' => $answer->name,
                        'is_correct' => $isCorrect
                    ];
                }
            }
        }

        // Вычисляем процент правильных ответов
        $percentage = ($correctAnswers / $totalQuestions) * 100;
        $passed = $percentage >= 70; // Проходной балл 70%

        // Для не аутентифицированных пользователей не сохраняем результат
        $userId = Auth::check() ? Auth::id() : null;
        $testResultId = null;

        if ($userId) {
            // Сохраняем результат в базу данных
            $testResult = TestResult::create([
                'user_id' => $userId,
                'test_id' => $testId,
                'total_questions' => $totalQuestions,
                'correct_answers' => $correctAnswers,
                'percentage' => $percentage,
                'passed' => $passed,
                'answers' => json_encode($detailedAnswers)
            ]);

            $testResultId = $testResult->id;
        }

        $result = [
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers,
            'percentage' => round($percentage, 2),
            'passed' => $passed,
            'result_id' => $testResultId
        ];

        // Всегда возвращаем JSON ответ
        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Обработка вопроса пользователя о результатах теста через чат-бот
     */
    public function chatWithTestAnalyst(Request $request): JsonResponse
    {
        $resultId = $request->input('result_id');
        $userQuestion = $request->input('message');

        if (!$resultId) {
            return response()->json([
                'success' => false,
                'message' => 'ID результата теста не указан'
            ], 400);
        }

        if (empty($userQuestion)) {
            return response()->json([
                'success' => false,
                'message' => 'Вопрос не может быть пустым'
            ], 400);
        }

        // Получаем результат теста со всеми связанными данными
        $testResult = TestResult::with([
            'test.questions.answers',
            'user',
            'test.course'
        ])->findOrFail($resultId);

        // Декодируем детальные ответы для удобства анализа
        $answers = json_decode($testResult->answers, true);
        if (!empty($answers)) {
            $testResult->answersData = $answers;
        }

        // Формируем промпт для чат-бота
        $chatPrompt = $this->generateChatPrompt($testResult, $userQuestion);

        // Используем AIService для получения ответа на вопрос
        $response = $this->aiService->query($chatPrompt, json_encode($testResult));

        return response()->json([
            'success' => true,
            'message' => $response
        ]);
    }

    /**
     * Генерирует промпт для чат-бота на основе результатов теста и вопроса пользователя
     */
    private function generateChatPrompt($testResult, $userQuestion): string
    {
        $isPassed = $testResult->passed ? 'Да' : 'Нет';

        return <<<PROMPT
        Ты — опытный преподаватель и аналитик образовательных данных. Тебе нужно ответить на вопрос студента о его результатах теста.

        **Данные о тесте и его результатах:**
        - Название теста: {$testResult->test->title}
        - Общий результат: {$testResult->percentage}%
        - Правильных ответов: {$testResult->correct_answers} из {$testResult->total_questions}
        - Тест пройден: {$isPassed}

        **Твоя роль:**
        1. Дать подробный и понятный ответ на вопрос студента
        2. При необходимости проанализировать ответы студента и объяснить, где именно были допущены ошибки
        3. Предложить способы улучшения понимания темы, учитывая конкретные ошибки

        **Требования:**
        - Отвечай информативно, но дружелюбно
        - Используй конкретные примеры из теста, где это возможно
        - Фокусируйся на объяснении и помощи, а не на критике
        - Не используй escape-последовательности для кириллических символов
        - Отвечай ТОЛЬКО на русском языке

        **Вопрос студента:** {$userQuestion}

        Ответь так, как будто ты ведешь диалог со студентом. Отвечай в неформальном, но профессиональном тоне.
        PROMPT;
    }
}

