<template>
  <NavLayout>
    <main class="container mx-auto py-8">
      <!-- Page header -->
      <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <div class="flex items-center">
          <svg class="w-8 h-8 text-primary-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
          </svg>
          <h1 class="text-2xl font-bold text-primary-900">Test Result</h1>
        </div>
      </div>

      <!-- Breadcrumb navigation -->
      <div class="bg-white rounded-xl shadow-md p-4 mb-8">
        <div class="flex items-center text-sm">
          <Link :href="route('home')" class="text-primary-600 hover:text-primary-800 transition">
            Home
          </Link>
          <span class="mx-2 text-primary-400">›</span>
          <Link v-if="result.test.course" :href="route('course.show', { id: result.test.course.id })" class="text-primary-600 hover:text-primary-800 transition">
            {{ result.test.course.title }}
          </Link>
          <span class="mx-2 text-primary-400">›</span>
          <Link :href="route('student.tests')" class="text-primary-600 hover:text-primary-800 transition">
            All Tests
          </Link>
          <span class="mx-2 text-primary-400">›</span>
          <Link :href="route('student.test.take', { id: result.test_id })" class="text-primary-600 hover:text-primary-800 transition">
            {{ result.test.title }}
          </Link>
          <span class="mx-2 text-primary-400">›</span>
          <span class="text-primary-900 font-medium">Results</span>
        </div>
      </div>

      <!-- Main content -->
      <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 border-b border-primary-100">
          <!-- Header and main result information -->
          <div class="mb-8">
            <div class="flex justify-between items-center mb-4">
              <h2 class="text-xl font-semibold text-primary-900">Test Result: {{ result.test.title }}</h2>
              <div
                :class="result.passed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                class="px-4 py-2 rounded-lg font-medium"
              >
                {{ result.passed ? 'Test Passed' : 'Test Failed' }}
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
              <div class="bg-primary-50 p-4 rounded-lg text-center border border-primary-100">
                <div class="text-sm text-primary-600 mb-1">Overall Result</div>
                <div class="text-2xl font-bold text-primary-900">{{ result.percentage }}%</div>
              </div>

              <div class="bg-primary-50 p-4 rounded-lg text-center border border-primary-100">
                <div class="text-sm text-primary-600 mb-1">Correct Answers</div>
                <div class="text-2xl font-bold text-primary-900">{{ result.correct_answers }} of {{ result.total_questions }}</div>
              </div>

              <div class="bg-primary-50 p-4 rounded-lg text-center border border-primary-100">
                <div class="text-sm text-primary-600 mb-1">Completion Date</div>
                <div class="text-lg font-medium text-primary-900">
                  {{ new Date(result.created_at).toLocaleDateString() }}
                  {{ new Date(result.created_at).toLocaleTimeString() }}
                </div>
              </div>
            </div>
          </div>

          <!-- Detailed list of questions and answers -->
          <div>
            <h3 class="text-lg font-medium text-primary-900 mb-4">Detailed Results</h3>

            <div v-if="parsedAnswers && parsedAnswers.length > 0" class="space-y-6">
              <div
                v-for="(answer, index) in parsedAnswers"
                :key="index"
                class="bg-primary-50 p-5 rounded-lg border border-primary-100"
              >
                <div class="flex justify-between items-start mb-4">
                  <h4 class="text-primary-900 text-lg font-medium">
                    {{ index + 1 }}. {{ answer.question_text }}
                  </h4>
                  <div
                    :class="answer.is_correct ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                    class="px-3 py-1 rounded-full text-xs font-medium"
                  >
                    {{ answer.is_correct ? 'Correct' : 'Incorrect' }}
                  </div>
                </div>

                <div class="text-primary-700">
                  <div class="font-medium mb-1">Your answer:</div>
                  <div class="pl-4 border-l-2 border-primary-200 ml-2">
                    {{ answer.answer_text }}
                  </div>
                </div>

                <div v-if="!answer.is_correct && correctAnswers[answer.question_id]" class="text-primary-700 mt-4">
                  <div class="font-medium mb-1 text-green-600">Correct answer:</div>
                  <div class="pl-4 border-l-2 border-green-300 ml-2">
                    {{ correctAnswers[answer.question_id] }}
                  </div>
                </div>
              </div>
            </div>

            <div v-else class="text-center py-8 text-primary-600">
              Detailed information about your answers is unavailable
            </div>
          </div>

          <!-- Neural network analytics -->
          <div class="mt-10">
            <h3 class="text-lg font-medium text-primary-900 mb-4">Result Analysis</h3>
            
            <div v-if="loading" class="bg-primary-50 p-6 rounded-lg flex items-center justify-center border border-primary-100">
              <div class="flex items-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-primary-600">Loading results analysis...</span>
              </div>
            </div>

            <div v-else-if="error" class="bg-red-50 border border-red-200 p-5 rounded-lg">
              <div class="text-red-600 mb-4">{{ error }}</div>
              <button
                @click="loadAnalytics"
                class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700 transition"
              >
                Try again
              </button>
            </div>

            <div v-else-if="analyticsData" class="bg-primary-50 p-6 rounded-lg overflow-auto border border-primary-100">
              <div class="prose max-w-none" v-html="renderMarkdown(analyticsData)"></div>
            </div>
            
            <div v-else class="bg-primary-50 p-6 rounded-lg text-center text-primary-600 border border-primary-100">
              Analytics for test results is unavailable
            </div>
          </div>
          
          <!-- Chatbot for help with results analysis -->
          <div class="mt-10">
            <h3 class="text-lg font-medium text-primary-900 mb-4">Ask a question about your test results</h3>
            
            <!-- Opening button for chat on mobile devices -->
            <button 
              @click="toggleChatbot" 
              class="md:hidden mb-4 w-full py-2 px-4 bg-primary-600 text-white rounded-lg shadow-lg hover:bg-primary-700 transition flex items-center justify-center"
            >
              <span v-if="!isChatbotVisible">Show Results Assistant</span>
              <span v-else>Hide Assistant</span>
            </button>
            
            <div 
              class="bg-primary-50 rounded-lg shadow-lg overflow-hidden border border-primary-100"
              :class="{'hidden md:block': !isChatbotVisible}"
            >
              <!-- Chat header -->
              <div class="bg-primary-100 p-4 flex justify-between items-center border-b border-primary-200">
                <div class="flex items-center">
                  <div class="bg-primary-500 h-8 w-8 rounded-full flex items-center justify-center mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12zm0-8a1 1 0 00-1 1v3a1 1 0 002 0V9a1 1 0 00-1-1zm0-2a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  <div>
                    <h4 class="text-primary-900 font-medium">Results Assistant</h4>
                    <p class="text-primary-600 text-xs">Ask a question about your test</p>
                  </div>
                </div>
                <button @click="toggleChatbot" class="md:hidden text-primary-600 hover:text-primary-800">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </button>
              </div>
              
              <!-- Message history -->
              <div class="p-4 h-80 overflow-y-auto bg-white" ref="chatHistory">
                <!-- Hint message -->
                <div v-if="chatMessages.length === 0" class="text-center text-primary-600 py-8">
                  <p>Ask a question about your test results, for example:</p>
                  <div class="my-3 space-y-2">
                    <button 
                      @click="askQuestion('Why did I make a mistake in the SQL question?')"
                      class="bg-primary-50 text-primary-800 px-3 py-1 rounded-lg text-sm hover:bg-primary-100 transition block w-full"
                    >
                      Why did I make a mistake in the SQL question?
                    </button>
                    <button 
                      @click="askQuestion('How can I improve my knowledge on this topic?')"
                      class="bg-primary-50 text-primary-800 px-3 py-1 rounded-lg text-sm hover:bg-primary-100 transition block w-full"
                    >
                      How can I improve my knowledge on this topic?
                    </button>
                    <button 
                      @click="askQuestion('Explain my mistakes in detail')"
                      class="bg-primary-50 text-primary-800 px-3 py-1 rounded-lg text-sm hover:bg-primary-100 transition block w-full"
                    >
                      Explain my mistakes in detail
                    </button>
                  </div>
                </div>
                
                <!-- Chat messages -->
                <div v-for="(message, index) in chatMessages" :key="index" class="mb-4">
                  <div 
                    :class="message.isUser ? 'bg-primary-600 text-white ml-auto' : 'bg-primary-100 text-primary-800 mr-auto'" 
                    class="px-4 py-3 rounded-lg max-w-3/4 inline-block"
                  >
                    <div v-if="message.isUser">
                      {{ message.text }}
                    </div>
                    <div v-else class="prose prose-sm max-w-none break-words" v-html="renderMarkdown(message.text)"></div>
                  </div>
                  <div 
                    :class="message.isUser ? 'text-right' : 'text-left'" 
                    class="text-primary-500 text-xs mt-1"
                  >
                    {{ message.isUser ? 'You' : 'Assistant' }} • {{ formatTime(message.timestamp) }}
                  </div>
                </div>
                
                <!-- Loading indicator -->
                <div v-if="isChatLoading" class="flex items-center space-x-2 mb-4">
                  <div class="bg-primary-100 text-primary-800 px-4 py-3 rounded-lg mr-auto inline-block">
                    <div class="flex items-center">
                      <span class="h-2 w-2 bg-primary-400 rounded-full animate-bounce"></span>
                      <span class="h-2 w-2 mx-1 bg-primary-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                      <span class="h-2 w-2 bg-primary-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></span>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Message form -->
              <div class="border-t border-primary-200 p-4 bg-white">
                <form @submit.prevent="sendMessage" class="flex space-x-2">
                  <input 
                    v-model="userMessage" 
                    type="text" 
                    placeholder="Ask a question about your test results..." 
                    class="flex-1 bg-primary-50 border border-primary-200 rounded-lg px-4 py-2 text-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-300"
                    :disabled="isChatLoading"
                  />
                  <button 
                    type="submit" 
                    class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500"
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

          <!-- Navigation buttons -->
          <div class="mt-8 flex justify-between">
            <Link :href="route('student.tests')"
                  class="px-4 py-2 bg-primary-100 text-primary-800 rounded-lg hover:bg-primary-200 transition">
              Back to Tests List
            </Link>

            <Link :href="route('student.test.take', { id: result.test_id })"
                  class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
              Take Test Again
            </Link>
          </div>
        </div>
      </div>
    </main>
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
/* Styles for Markdown content */
.prose {
  line-height: 1.6;
  font-size: 1rem;
}
.prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
  color: #1e293b; /* primary-900 equivalent */
  margin-top: 1.5em;
  margin-bottom: 0.5em;
  font-weight: 600;
}
.prose h1 {
  font-size: 1.8rem;
  margin-top: 0;
  border-bottom: 1px solid #e2e8f0; /* primary-200 equivalent */
  padding-bottom: 0.5rem;
}
.prose h2 {
  font-size: 1.5rem;
  border-bottom: 1px solid #e2e8f0; /* primary-200 equivalent */
  padding-bottom: 0.25rem;
}
.prose h3 {
  font-size: 1.25rem;
  color: #3b82f6; /* blue-500 for better visibility */
  margin-top: 1.75em;
}
.prose p {
  margin-top: 0.75em;
  margin-bottom: 1em;
}
.prose strong, .prose b {
  color: #1e293b; /* primary-900 equivalent */
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
  color: #3b82f6; /* blue-500 for better visibility */
}
.prose li > ul, .prose li > ol {
  margin-top: 0.5em;
  margin-bottom: 0.5em;
}
.prose code {
  background-color: rgba(226, 232, 240, 0.5); /* primary-200 with opacity */
  border-radius: 0.25rem;
  padding: 0.125rem 0.25rem;
  font-family: monospace;
  font-size: 0.875em;
}
.prose pre {
  background-color: #f1f5f9; /* primary-100 equivalent */
  border-radius: 0.25rem;
  padding: 1rem;
  overflow-x: auto;
  margin: 1em 0;
}
.prose blockquote {
  border-left: 4px solid #e2e8f0; /* primary-200 equivalent */
  padding-left: 1rem;
  font-style: italic;
  margin: 1em 0;
  color: #64748b; /* primary-500 equivalent */
}
.prose a {
  color: #3b82f6; /* blue-500 */
  text-decoration: underline;
}
.prose hr {
  border: 0;
  border-top: 1px solid #e2e8f0; /* primary-200 equivalent */
  margin: 2em 0;
}
.prose table {
  border-collapse: collapse;
  width: 100%;
  margin: 1.5em 0;
  font-size: 0.9em;
}
.prose thead {
  background-color: #f1f5f9; /* primary-100 equivalent */
}
.prose th, .prose td {
  border: 1px solid #e2e8f0; /* primary-200 equivalent */
  padding: 0.5rem;
  text-align: left;
}

/* Chatbot styles */
.v-enter-active,
.v-leave-active {
  transition: opacity 0.3s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}

.prose-sm {
  font-size: 0.875rem;
}
.max-w-3\/4 {
  max-width: 75%;
}
</style>
