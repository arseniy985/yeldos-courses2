<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\OpenRouterService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OpenRouterTestController extends Controller
{
    private OpenRouterService $openRouterService;

    /**
     * Constructor with dependency injection
     */
    public function __construct(OpenRouterService $openRouterService)
    {
        $this->openRouterService = $openRouterService;
    }

    /**
     * Тестовый метод для проверки работы с Open Router и моделью qwen/qwq-32b:free
     */
    public function testQwen(Request $request): JsonResponse
    {
        $message = $request->input('message', 'Привет! Расскажи о себе.');
        $context = $request->input('context', 'Ты — полезный ассистент. ОЧЕНЬ ВАЖНО: отвечай ТОЛЬКО на русском языке, независимо от языка запроса.');
        
        // Явно указываем модель qwen/qwq-32b:free
        $response = $this->openRouterService->query(
            $context,
            $message,
            'qwen/qwq-32b:free'
        );

        return response()->json([
            'success' => true,
            'message' => $response,
            'model' => 'qwen/qwq-32b:free'
        ]);
    }
} 