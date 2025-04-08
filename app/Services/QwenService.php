<?php

namespace App\Services;

use Qwen\QwenClient;
use Qwen\Enums\Models;

class QwenService implements AIServiceInterface
{
    private QwenClient $qwenClient;
    private string $apiKey;

    /**
     * Конструктор сервиса Qwen AI
     * 
     * @param string $apiKey API ключ для доступа к Qwen AI
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->initClient();
    }

    /**
     * Инициализация клиента Qwen
     */
    private function initClient(): void
    {
        $this->qwenClient = QwenClient::build($this->apiKey);
    }

    /**
     * Отправляет запрос к нейросети Qwen и получает ответ
     *
     * @param string $context Контекст/системный промпт для нейросети
     * @param string $text Текст запроса пользователя
     * @param mixed $model Модель нейросети (по умолчанию QWEN_MAX)
     * @return string Ответ от нейросети
     */
    public function query(string $context, string $text, mixed $model = Models::QWEN_MAX): string
    {
        return QwenClient::build($this->apiKey)
            ->query($context, 'system')
            ->query($text, 'user')
            ->withModel($model)
            ->run();
    }
} 