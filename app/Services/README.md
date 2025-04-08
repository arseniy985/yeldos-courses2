# AI Сервисы

## Обзор

В проекте реализована система для работы с различными нейросетевыми сервисами через единый интерфейс. Текущая реализация поддерживает следующие сервисы:

- OpenRouter (с моделью qwen/qwq-32b:free) - **по умолчанию**
- Qwen AI
- Mistral AI

## Настройка

### 1. Получение API ключей

#### OpenRouter

1. Зарегистрируйтесь на сайте [OpenRouter](https://openrouter.ai/)
2. Перейдите в раздел API Keys
3. Создайте новый ключ
4. Скопируйте полученный ключ в переменную `OPENROUTER_API_KEY` в файле `.env`

### 2. Конфигурация в .env

```
# AI Services Configuration
OPENROUTER_API_KEY=ваш-ключ-от-openrouter
DEFAULT_AI_SERVICE=openrouter
```

Для переключения между сервисами измените значение `DEFAULT_AI_SERVICE`:

- `openrouter` - использовать OpenRouter с моделью qwen/qwq-32b:free (по умолчанию)
- `qwen` - использовать Qwen AI (требуется ключ в `QWEN_API_KEY`)
- `mistral` - использовать Mistral AI (требуется ключ в `MISTRAL_API_KEY`)

## Использование в контроллерах

```php
<?php

use App\Services\AIServiceInterface;

class YourController extends Controller
{
    private AIServiceInterface $aiService;

    public function __construct(AIServiceInterface $aiService)
    {
        $this->aiService = $aiService;
    }

    public function yourMethod()
    {
        $response = $this->aiService->query(
            "Системный промпт/контекст",
            "Запрос пользователя"
        );
        
        return response()->json([
            'response' => $response
        ]);
    }
}
```

## Тестирование OpenRouter с Qwen

Для тестирования работы OpenRouter с моделью qwen/qwq-32b:free используйте следующий эндпоинт:

```
POST /api/test-openrouter-qwen
```

Тело запроса:
```json
{
    "message": "Ваш вопрос",
    "context": "Системный промпт (опционально)"
}
```

## Добавление новых AI сервисов

Для добавления нового AI сервиса:

1. Создайте новый класс, реализующий интерфейс `AIServiceInterface`
2. Зарегистрируйте сервис в `AIServiceProvider`
3. Добавьте настройки в `config/services.php`
4. Обновите файл `.env.example` с новыми переменными окружения 