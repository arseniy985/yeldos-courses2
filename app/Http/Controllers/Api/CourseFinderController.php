<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\MistralService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Courses;

class CourseFinderController extends Controller
{
    private MistralService $mistralService;

    /**
     * Промпт для поиска курсов
     */
    private static string $courseFinderPrompt = <<<PROMPT
    Ты — образовательный консультант, который помогает подобрать курсы из СТРОГО ОГРАНИЧЕННОГО списка доступных курсов.

    **ВАЖНЕЙШЕЕ ПРАВИЛО:**
    Ты НИКОГДА, НИ ПРИ КАКИХ ОБСТОЯТЕЛЬСТВАХ не должен придумывать, модифицировать или изменять информацию о курсах.

    **Формат данных о курсах:**
    Каждый курс имеет следующие поля:
    - id: уникальный идентификатор
    - name: ТОЧНОЕ название курса
    - description: ТОЧНОЕ описание курса
    - instructor: ТОЧНОЕ имя преподавателя
    - rating: рейтинг курса
    - price: цена курса
    - episodes: список эпизодов курса (при наличии)

    **АБСОЛЮТНО ОБЯЗАТЕЛЬНЫЕ ТРЕБОВАНИЯ:**
    1. ИСПОЛЬЗУЙ ТОЛЬКО ДАННЫЕ из предоставленного JSON-массива курсов
    2. КАТЕГОРИЧЕСКИ ЗАПРЕЩЕНО:
       - Придумывать названия курсов
       - Изменять имена преподавателей
       - Модифицировать описания курсов
       - Добавлять несуществующие курсы
       - Изменять цены, рейтинги или любые другие характеристики
    3. Если для запроса НЕТ подходящих курсов, честно скажи: "К сожалению, в нашей базе нет курсов, соответствующих вашему запросу"
    4. Рекомендуй ТОЛЬКО те курсы, которые ТОЧНО соответствуют запросу или тесно связаны с запрашиваемой темой
    5. ВСЕГДА КОПИРУЙ название, имя преподавателя и другие данные БУКВАЛЬНО, без изменений

    **Ответ пользователю:**
    - Приветствие и краткое введение
    - Список ТОЛЬКО РЕАЛЬНЫХ курсов из базы данных, подходящих к запросу
    - Для каждого курса:
      * ТОЧНОЕ название (скопированное из данных без изменений)
      * Преподаватель: ТОЧНОЕ имя (скопированное из данных без изменений)
      * Подробное описание: первые 100-150 символов из поля description (если оно большое)
      * Рейтинг: точное значение из поля rating
      * Цена: точное значение из поля price
    - Если нет подходящих курсов, сообщи об этом честно

    **КРИТИЧЕСКИЙ ЗАПРЕТ:**
    НИКОГДА НЕ ВЫДУМЫВАЙ КУРСЫ, КОТОРЫХ НЕТ В ПРЕДОСТАВЛЕННОМ JSON.
    Не предлагай "похожие" или "альтернативные" курсы, если их нет в данных.
    Не используй названия курсов, которые "звучат похоже" на запрос, но отсутствуют в данных.

    **Запрос пользователя:** {userQuery}

    КРИТИЧЕСКИ ВАЖНО: Следующим сообщением будет JSON-массив с информацией о доступных курсах.
    ТОЛЬКО ЭТИ КУРСЫ СУЩЕСТВУЮТ. Других курсов НЕТ и не может быть.
    ИСПОЛЬЗУЙ ТОЛЬКО ЭТИ ДАННЫЕ И НИЧЕГО БОЛЬШЕ.
    PROMPT;

    /**
     * Constructor with dependency injection
     */
    public function __construct(MistralService $mistralService)
    {
        $this->mistralService = $mistralService;
    }

    /**
     * Обработка запроса пользователя на поиск подходящего курса
     */
    public function findCourse(Request $request): JsonResponse
    {
        $userQuery = $request->input('message');

        if (empty($userQuery)) {
            return response()->json([
                'success' => false,
                'message' => 'Пожалуйста, укажите ваши интересы или цели обучения'
            ], 400);
        }

        // Получаем все доступные курсы с эпизодами
        $courses = Courses::with('episodes')->get();

        if ($courses->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'К сожалению, в системе пока нет доступных курсов'
            ], 404);
        }

        // Преобразуем данные в более четкий формат для нейросети
//        $formattedCourses = $courses->map(function ($course) {
//            return [
//                'id' => $course->id,
//                'name' => $course->name,
//                'description' => $course->description,
//                'instructor' => $course->instructor,
//                'rating' => $course->rating,
//                'price' => $course->price,
//                'episodes' => $course->episodes->map(function ($episode) {
//                    return [
//                        'id' => $episode->id,
//                        'name' => $episode->name,
//                        'description' => $episode->description,
//                    ];
//                })
//            ];
//        });

        // Формируем промпт с запросом пользователя
        $prompt = str_replace('{userQuery}', $userQuery, self::$courseFinderPrompt);

        // Получаем ответ от нейросети
        $response = $this->mistralService->query($prompt, json_encode($courses));

        return response()->json([
            'success' => true,
            'message' => urldecode(htmlspecialchars($response))
        ]);
    }
}
