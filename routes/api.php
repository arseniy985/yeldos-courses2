<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CourseTestController;
use App\Http\Controllers\TestQuestionController;
use App\Http\Controllers\TestQuestionAnswerController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticationController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');

Route::middleware('auth:sanctum', 'throttle:60,1')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return response()->json([
        'message' => 'Тек авторизацияланған қолданушылар көре алады!',
        'user' => auth()->user(),
    ]);
});
Route::middleware(['auth:api'])->group(function () {
    Route::post('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'store']);
    Route::delete('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'destroy']);
});


Route::get('/test', function () {
    return response()->json([
        'message' => 'API жұмыс істеп тұр!'
    ]);
});

// API для работы с тестами курсов
// Временно убрана аутентификация для отладки
// Маршруты для тестов
Route::get('/courses/{courseId}/tests', [CourseTestController::class, 'index']);
Route::get('/tests/{id}', [CourseTestController::class, 'show']);
Route::post('/tests', [CourseTestController::class, 'store'])->name('api.tests.store');
Route::put('/tests/{id}', [CourseTestController::class, 'update'])->name('api.tests.update');
Route::delete('/tests/{id}', [CourseTestController::class, 'destroy']);

// Маршруты для вопросов теста
Route::get('/tests/{testId}/questions', [TestQuestionController::class, 'index']);
Route::get('/questions/{id}', [TestQuestionController::class, 'show']);
Route::post('/questions', [TestQuestionController::class, 'store']);
Route::put('/questions/{id}', [TestQuestionController::class, 'update']);
Route::delete('/questions/{id}', [TestQuestionController::class, 'destroy']);

// Маршруты для ответов на вопросы теста
Route::get('/questions/{questionId}/answers', [TestQuestionAnswerController::class, 'index']);
Route::get('/answers/{id}', [TestQuestionAnswerController::class, 'show']);
Route::post('/answers', [TestQuestionAnswerController::class, 'store']);
Route::put('/answers/{id}', [TestQuestionAnswerController::class, 'update']);
Route::delete('/answers/{id}', [TestQuestionAnswerController::class, 'destroy']);

// Маршруты для прохождения тестов пользователями
Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/student/tests', [App\Http\Controllers\API\TestController::class, 'index']);
    Route::get('/student/tests/{id}', [App\Http\Controllers\API\TestController::class, 'show']);
    Route::post('/student/submit-test', [App\Http\Controllers\API\TestController::class, 'submitAnswers']);
    Route::get('/student/test-results', [App\Http\Controllers\API\TestController::class, 'getUserTestResults']);
});

// Добавляем маршрут для отправки ответов на тест без аутентификации для отладки
Route::post('/tests/{id}/submit', [App\Http\Controllers\API\TestController::class, 'submitAnswers'])->name('api.tests.submit');

// Добавляем новый маршрут для чат-бота
Route::post('/test-chat', [App\Http\Controllers\API\TestController::class, 'chatWithTestAnalyst']);

// Добавляем маршрут для чат-бота поиска курсов
Route::post('/course-finder', [App\Http\Controllers\API\CourseFinderController::class, 'findCourse']);

// Тестовый маршрут для проверки работы с OpenRouter и моделью qwen/qwq-32b:free
Route::post('/test-openrouter-qwen', [App\Http\Controllers\API\OpenRouterTestController::class, 'testQwen']);

