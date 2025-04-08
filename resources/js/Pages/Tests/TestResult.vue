<template>
  <NavLayout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Навигационные кнопки (хлебные крошки) -->
        <div class="flex items-center mb-4 text-sm">
          <Link :href="route('home')" class="text-gray-400 hover:text-white transition">
            Главная
          </Link>
          <span class="mx-2 text-gray-500">›</span>
          <Link v-if="result.test.course" :href="route('course.show', { id: result.test.course.id })" class="text-gray-400 hover:text-white transition">
            {{ result.test.course.title }}
          </Link>
          <span class="mx-2 text-gray-500">›</span>
          <Link :href="route('student.tests')" class="text-gray-400 hover:text-white transition">
            Все тесты
          </Link>
          <span class="mx-2 text-gray-500">›</span>
          <Link :href="route('student.test.take', { id: result.test_id })" class="text-gray-400 hover:text-white transition">
            {{ result.test.title }}
          </Link>
          <span class="mx-2 text-gray-500">›</span>
          <span class="text-white">Результаты</span>
        </div>

        <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-gray-800 border-b border-gray-700">
            <!-- Заголовок и основная информация о результате -->
            <div class="mb-8">
              <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-white">Результат теста: {{ result.test.title }}</h2>
                <div
                  :class="result.passed ? 'bg-green-600' : 'bg-red-600'"
                  class="px-4 py-2 rounded-lg text-white font-medium"
                >
                  {{ result.passed ? 'Тест пройден' : 'Тест не пройден' }}
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-gray-700 p-4 rounded-lg text-center">
                  <div class="text-sm text-gray-300 mb-1">Общий результат</div>
                  <div class="text-2xl font-bold text-white">{{ result.percentage }}%</div>
                </div>

                <div class="bg-gray-700 p-4 rounded-lg text-center">
                  <div class="text-sm text-gray-300 mb-1">Правильных ответов</div>
                  <div class="text-2xl font-bold text-white">{{ result.correct_answers }} из {{ result.total_questions }}</div>
                </div>

                <div class="bg-gray-700 p-4 rounded-lg text-center">
                  <div class="text-sm text-gray-300 mb-1">Дата прохождения</div>
                  <div class="text-lg font-medium text-white">
                    {{ new Date(result.created_at).toLocaleDateString() }}
                    {{ new Date(result.created_at).toLocaleTimeString() }}
                  </div>
                </div>
              </div>
            </div>

            <!-- Детальный список вопросов и ответов -->
            <div>
              <h3 class="text-lg font-medium text-white mb-4">Детальные результаты</h3>

              <div v-if="parsedAnswers && parsedAnswers.length > 0" class="space-y-6">
                <div
                  v-for="(answer, index) in parsedAnswers"
                  :key="index"
                  class="bg-gray-700 p-5 rounded-lg"
                >
                  <div class="flex justify-between items-start mb-4">
                    <h4 class="text-white text-lg font-medium">
                      {{ index + 1 }}. {{ answer.question_text }}
                    </h4>
                    <div
                      :class="answer.is_correct ? 'bg-green-600' : 'bg-red-600'"
                      class="px-3 py-1 rounded-full text-xs text-white font-medium"
                    >
                      {{ answer.is_correct ? 'Верно' : 'Неверно' }}
                    </div>
                  </div>

                  <div class="text-gray-300">
                    <div class="font-medium mb-1">Ваш ответ:</div>
                    <div class="pl-4 border-l-2 border-gray-600 ml-2">
                      {{ answer.answer_text }}
                    </div>
                  </div>

                  <div v-if="!answer.is_correct && correctAnswers[answer.question_id]" class="text-gray-300 mt-4">
                    <div class="font-medium mb-1 text-green-400">Правильный ответ:</div>
                    <div class="pl-4 border-l-2 border-green-600 ml-2">
                      {{ correctAnswers[answer.question_id] }}
                    </div>
                  </div>
                </div>
              </div>

              <div v-else class="text-center py-8 text-gray-300">
                Детальная информация о ваших ответах недоступна
              </div>
            </div>

            <!-- Аналитика от нейросети -->
            <div class="mt-10">
              <div v-if="loading" class="bg-gray-700 p-6 rounded-lg flex items-center justify-center">
                <div class="flex items-center">
                  <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <span class="text-gray-300">Загружаем анализ результатов...</span>
                </div>
              </div>

              <div v-else-if="error" class="bg-red-900 bg-opacity-30 border border-red-700 p-5 rounded-lg">
                <div class="text-red-300 mb-4">{{ error }}</div>
                <button
                  @click="loadAnalytics"
                  class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition"
                >
                  Попробовать еще раз
                </button>
              </div>

              <div v-else-if="analyticsData" class="bg-gray-700 p-6 rounded-lg overflow-auto">
                <div class="prose prose-invert max-w-none" v-html="renderMarkdown(analyticsData)"></div>
              </div>
              
              <div v-else class="bg-gray-700 p-6 rounded-lg text-center text-gray-300">
                Аналитика по результатам теста недоступна
              </div>
            </div>
            
            <!-- Чат-бот для помощи с анализом результатов -->
            <div class="mt-10">
              <h3 class="text-lg font-medium text-white mb-4">Задайте вопрос о результатах теста</h3>
              
              <!-- Открывающая кнопка для чата на мобильных устройствах -->
              <button 
                @click="toggleChatbot" 
                class="md:hidden mb-4 w-full py-2 px-4 bg-indigo-600 text-white rounded-lg shadow-lg hover:bg-indigo-700 transition flex items-center justify-center"
              >
                <span v-if="!isChatbotVisible">Показать помощника по результатам</span>
                <span v-else>Скрыть помощника</span>
              </button>
              
              <div 
                class="bg-gray-700 rounded-lg shadow-lg overflow-hidden"
                :class="{'hidden md:block': !isChatbotVisible}"
              >
                <!-- Заголовок чата -->
                <div class="bg-gray-800 p-4 flex justify-between items-center border-b border-gray-600">
                  <div class="flex items-center">
                    <div class="bg-indigo-500 h-8 w-8 rounded-full flex items-center justify-center mr-3">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12zm0-8a1 1 0 00-1 1v3a1 1 0 002 0V9a1 1 0 00-1-1zm0-2a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                      </svg>
                    </div>
                    <div>
                      <h4 class="text-white font-medium">Помощник по результатам</h4>
                      <p class="text-gray-400 text-xs">Задайте вопрос о вашем тесте</p>
                    </div>
                  </div>
                  <button @click="toggleChatbot" class="md:hidden text-gray-400 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                  </button>
                </div>
                
                <!-- История сообщений -->
                <div class="p-4 h-80 overflow-y-auto" ref="chatHistory">
                  <!-- Сообщение с подсказкой -->
                  <div v-if="chatMessages.length === 0" class="text-center text-gray-400 py-8">
                    <p>Задайте вопрос по результатам вашего теста, например:</p>
                    <div class="my-3 space-y-2">
                      <button 
                        @click="askQuestion('Почему я сделал ошибку в вопросе о SQL?')"
                        class="bg-gray-600 text-gray-200 px-3 py-1 rounded-lg text-sm hover:bg-gray-500 transition block w-full"
                      >
                        Почему я сделал ошибку в вопросе о SQL?
                      </button>
                      <button 
                        @click="askQuestion('Как мне улучшить мои знания в этой теме?')"
                        class="bg-gray-600 text-gray-200 px-3 py-1 rounded-lg text-sm hover:bg-gray-500 transition block w-full"
                      >
                        Как мне улучшить мои знания в этой теме?
                      </button>
                      <button 
                        @click="askQuestion('Объясни подробнее мои ошибки')"
                        class="bg-gray-600 text-gray-200 px-3 py-1 rounded-lg text-sm hover:bg-gray-500 transition block w-full"
                      >
                        Объясни подробнее мои ошибки
                      </button>
                    </div>
                  </div>
                  
                  <!-- Сообщения чата -->
                  <div v-for="(message, index) in chatMessages" :key="index" class="mb-4">
                    <div 
                      :class="message.isUser ? 'bg-indigo-600 text-white ml-auto' : 'bg-gray-600 text-gray-200 mr-auto'" 
                      class="px-4 py-3 rounded-lg max-w-3/4 inline-block"
                    >
                      <div v-if="message.isUser">
                        {{ message.text }}
                      </div>
                      <div v-else class="prose prose-invert prose-sm max-w-none break-words" v-html="renderMarkdown(message.text)"></div>
                    </div>
                    <div 
                      :class="message.isUser ? 'text-right' : 'text-left'" 
                      class="text-gray-400 text-xs mt-1"
                    >
                      {{ message.isUser ? 'Вы' : 'Помощник' }} • {{ formatTime(message.timestamp) }}
                    </div>
                  </div>
                  
                  <!-- Индикатор загрузки -->
                  <div v-if="isChatLoading" class="flex items-center space-x-2 mb-4">
                    <div class="bg-gray-600 text-gray-200 px-4 py-3 rounded-lg mr-auto inline-block">
                      <div class="flex items-center">
                        <span class="h-2 w-2 bg-gray-300 rounded-full animate-bounce"></span>
                        <span class="h-2 w-2 mx-1 bg-gray-300 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                        <span class="h-2 w-2 bg-gray-300 rounded-full animate-bounce" style="animation-delay: 0.4s"></span>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Форма для отправки сообщений -->
                <div class="border-t border-gray-600 p-4">
                  <form @submit.prevent="sendMessage" class="flex space-x-2">
                    <input 
                      v-model="userMessage" 
                      type="text" 
                      placeholder="Задайте вопрос о результатах теста..." 
                      class="flex-1 bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                      :disabled="isChatLoading"
                    />
                    <button 
                      type="submit" 
                      class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500"
                      :disabled="isChatLoading || !userMessage.trim()"
                    >
                      <svg v-if="!isChatLoading" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                      </svg>
                      <svg v-else class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                    </button>
                  </form>
                </div>
              </div>
            </div>

            <!-- Кнопки навигации -->
            <div class="mt-8 flex justify-between">
              <Link :href="route('student.tests')"
                    class="px-4 py-2 bg-gray-600 text-gray-200 rounded hover:bg-gray-500 transition">
                К списку тестов
              </Link>

              <Link :href="route('student.test.take', { id: result.test_id })"
                    class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                Пройти тест снова
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </NavLayout>
</template>

<script setup>
import { computed, ref, onMounted, nextTick, watch } from 'vue';
import { Link } from '@inertiajs/vue3';
import NavLayout from "@/Layouts/NavLayout.vue";
import axios from 'axios';
import { marked } from 'marked';

// Настраиваем marked для безопасного рендеринга HTML
marked.setOptions({
  breaks: true,        // Преобразовывать символы новой строки в <br>
  gfm: true,           // Использовать GitHub Flavored Markdown
  sanitize: false      // Разрешить HTML-теги (но мы используем DOMPurify)
});

const props = defineProps({
  test: Object,
  user: Object,
  result: Object,
});

const loading = ref(false);
const analyticsData = ref(null);
const error = ref(null);

// Состояние для чат-бота
const isChatbotVisible = ref(window.innerWidth >= 768); // По умолчанию виден на десктопе
const userMessage = ref('');
const chatMessages = ref([]);
const isChatLoading = ref(false);
const chatHistory = ref(null);

// Функция для переключения видимости чат-бота на мобильных устройствах
const toggleChatbot = () => {
  isChatbotVisible.value = !isChatbotVisible.value;
};

// Форматирование времени для сообщений
const formatTime = (timestamp) => {
  const date = new Date(timestamp);
  return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};

// Отправка сообщения в чат
const sendMessage = async () => {
  if (!userMessage.value.trim() || isChatLoading.value) return;
  
  const message = userMessage.value.trim();
  userMessage.value = ''; // Очищаем поле ввода
  
  // Добавляем сообщение пользователя в историю
  chatMessages.value.push({
    text: message,
    isUser: true,
    timestamp: new Date()
  });
  
  // Прокручиваем чат вниз
  await nextTick();
  scrollChatToBottom();
  
  // Устанавливаем состояние загрузки
  isChatLoading.value = true;
  
  try {
    // Отправляем запрос на сервер
    const response = await axios.post('/api/test-chat', {
      result_id: props.result.id,
      message: message
    });
    
    // Добавляем ответ бота в историю
    chatMessages.value.push({
      text: response.data.message,
      isUser: false,
      timestamp: new Date()
    });
    
  } catch (err) {
    console.error('Ошибка при отправке сообщения:', err);
    
    // Определяем тип ошибки и формируем соответствующее сообщение
    let errorMessage = 'Произошла ошибка при обработке вашего вопроса. Пожалуйста, попробуйте еще раз.';
    
    // Проверяем, является ли ошибка таймаутом
    if (err.code === 'ECONNABORTED') {
      errorMessage = 'Превышено время ожидания ответа от сервера (50 секунд). Запрос к нейросети занимает слишком много времени. Пожалуйста, попробуйте задать более короткий или простой вопрос.';
    }
    
    // Добавляем сообщение об ошибке
    chatMessages.value.push({
      text: errorMessage,
      isUser: false,
      timestamp: new Date()
    });
  } finally {
    isChatLoading.value = false;
    
    // Прокручиваем чат вниз после получения ответа
    await nextTick();
    scrollChatToBottom();
  }
};

// Функция для прокрутки чата вниз
const scrollChatToBottom = () => {
  if (chatHistory.value) {
    chatHistory.value.scrollTop = chatHistory.value.scrollHeight;
  }
};

// Вспомогательная функция для предварительно заданных вопросов
const askQuestion = (question) => {
  userMessage.value = question;
  sendMessage();
};

// Загрузка аналитики при монтировании компонента
onMounted(() => {
  loadAnalytics();
  
  // Слушаем изменение размера окна для определения видимости чат-бота
  window.addEventListener('resize', () => {
    if (window.innerWidth >= 768) {
      isChatbotVisible.value = true;
    }
  });
});

// Функция для загрузки аналитики по результатам теста
const loadAnalytics = async () => {
  loading.value = true;
  error.value = null;
  analyticsData.value = null;
  
  try {
    const response = await axios.get('/test/result', {
      params: { result_id: props.result.id }
    });
    
    console.log('Получены данные аналитики:', response.data);
    analyticsData.value = response.data;
  } catch (err) {
    console.error('Ошибка загрузки аналитики:', err);
    
    // Формируем сообщение об ошибке в зависимости от типа ошибки
    let errorMessage = err.response?.data?.message || 'Ошибка загрузки аналитики. Попробуйте позже.';
    
    // Проверяем, является ли ошибка таймаутом
    if (err.code === 'ECONNABORTED') {
      errorMessage = 'Превышено время ожидания ответа от сервера (50 секунд). Генерация аналитики теста занимает слишком много времени. Пожалуйста, попробуйте обновить страницу или вернитесь позже.';
    }
    
    error.value = errorMessage;
  } finally {
    loading.value = false;
  }
};

// Функция для рендеринга markdown в HTML
const renderMarkdown = (text) => {
  if (!text) return '';
  // Очищаем текст и декодируем unicode-escape последовательности
  const cleanedText = cleanAndDecodeText(text);
  return marked(cleanedText);
};

// Функция для очистки текста от частей промпта и декодирования unicode
const cleanAndDecodeText = (text) => {
  if (!text) return '';
  
  // Декодируем unicode-escape последовательности
  let decodedText = text.replace(/\\u([0-9a-fA-F]{4})/g, (match, hex) => {
    return String.fromCharCode(parseInt(hex, 16));
  });
  
  // Удаляем возможные части промпта, которые могли быть включены в ответ
  const phrasesToRemove = [
    'Детально проанализируй ошибки:',
    'Выдели тематики, где ошибки повторяются',
    'Определи типы вопросов, вызвавших сложности',
    'Объясни причины возможных ошибок',
    'Твои задачи:',
    'Структура ответа:',
    'Требования:',
    'здесь опиши',
    'здесь детально опиши',
    'здесь для каждого',
    'здесь предложи'
  ];
  
  phrasesToRemove.forEach(phrase => {
    decodedText = decodedText.replace(new RegExp(phrase, 'g'), '');
  });
  
  return decodedText;
};

// Парсим JSON строку с ответами
const parsedAnswers = computed(() => {
  if (!props.result.answers) return [];

  try {
    return typeof props.result.answers === 'string'
      ? JSON.parse(props.result.answers)
      : props.result.answers;
  } catch (e) {
    console.error('Ошибка при парсинге ответов:', e);
    return [];
  }
});

// Получаем правильные ответы для каждого вопроса
const correctAnswers = computed(() => {
  if (!props.result.test || !props.result.test.questions) return {};

  const correct = {};

  props.result.test.questions.forEach(question => {
    const correctAnswer = question.answers.find(answer => answer.is_valid);
    if (correctAnswer) {
      correct[question.id] = correctAnswer.name;
    }
  });

  return correct;
});
</script>

<style>
/* Стили для Markdown-контента */
.prose {
  line-height: 1.6;
  font-size: 1rem;
}
.prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
  color: white;
  margin-top: 1.5em;
  margin-bottom: 0.5em;
  font-weight: 600;
}
.prose h1 {
  font-size: 1.8rem;
  margin-top: 0;
  border-bottom: 1px solid #4a5568;
  padding-bottom: 0.5rem;
}
.prose h2 {
  font-size: 1.5rem;
  border-bottom: 1px solid #4a5568;
  padding-bottom: 0.25rem;
}
.prose h3 {
  font-size: 1.25rem;
  color: #a3bffa; /* Light indigo for better visibility */
  margin-top: 1.75em;
}
.prose p {
  margin-top: 0.75em;
  margin-bottom: 1em;
}
.prose strong, .prose b {
  color: #ebf4ff; /* Light color for better visibility */
  font-weight: 600;
}
.prose ul, .prose ol {
  margin-top: 0.75em;
  margin-bottom: 1.25em;
  padding-left: 1.5em;
}
.prose li {
  margin-bottom: 0.5em;
  line-height: 1.5;
}
.prose li::marker {
  color: #a3bffa; /* Light indigo for better visibility */
}
.prose li > ul, .prose li > ol {
  margin-top: 0.5em;
  margin-bottom: 0.5em;
}
.prose code {
  background-color: rgba(45, 55, 72, 0.5);
  border-radius: 0.25rem;
  padding: 0.125rem 0.25rem;
  font-family: monospace;
  font-size: 0.875em;
}
.prose pre {
  background-color: #1a202c;
  border-radius: 0.25rem;
  padding: 1rem;
  overflow-x: auto;
  margin: 1em 0;
}
.prose blockquote {
  border-left: 4px solid #4a5568;
  padding-left: 1rem;
  font-style: italic;
  margin: 1em 0;
  color: #a0aec0;
}
.prose a {
  color: #90cdf4;
  text-decoration: underline;
}
.prose hr {
  border: 0;
  border-top: 1px solid #4a5568;
  margin: 2em 0;
}
.prose table {
  border-collapse: collapse;
  width: 100%;
  margin: 1.5em 0;
  font-size: 0.9em;
}
.prose thead {
  background-color: #2d3748;
}
.prose th, .prose td {
  border: 1px solid #4a5568;
  padding: 0.5rem;
  text-align: left;
}
.prose-invert {
  color: #e2e8f0;
}

/* Стили для чат-бота */
.prose-sm {
  font-size: 0.875rem;
}
.max-w-3\/4 {
  max-width: 75%;
}
</style>
