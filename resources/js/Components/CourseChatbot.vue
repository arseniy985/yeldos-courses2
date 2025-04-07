<script setup>
import { ref, nextTick, onMounted } from 'vue';
import axios from 'axios';
import { marked } from 'marked';

// Настраиваем marked для рендеринга markdown
marked.setOptions({
  breaks: true,        // Преобразовывать символы новой строки в <br>
  gfm: true,           // Использовать GitHub Flavored Markdown
  sanitize: false      // Разрешить HTML-теги (но мы используем дополнительную очистку на стороне сервера)
});

// Состояние компонента
const isOpen = ref(false); // Чат открыт или нет
const messages = ref([]); // История сообщений
const userInput = ref(''); // Текущий ввод пользователя
const isLoading = ref(false); // Индикатор загрузки
const chatContainer = ref(null); // Ссылка на контейнер чата для прокрутки

// Предустановленные вопросы для быстрого выбора
const suggestedQuestions = [
  'Я хочу изучать программирование, что можете посоветовать?',
  'Какие есть курсы по дизайну?',
  'Порекомендуйте курсы для начинающих',
  'Какие курсы самые популярные?'
];

// Инициализация чат-бота
onMounted(() => {
  // Добавляем приветственное сообщение при загрузке
  messages.value.push({
    id: Date.now(),
    text: 'Привет! Я ваш помощник по выбору курсов. Расскажите, что вас интересует, и я помогу подобрать подходящие курсы. Можете спросить о конкретной теме или уровне обучения.',
    isUser: false,
    timestamp: new Date()
  });
});

// Переключение видимости чата
const toggleChat = () => {
  isOpen.value = !isOpen.value;
};

// Отправка сообщения
const sendMessage = async () => {
  if (!userInput.value.trim() || isLoading.value) return;
  
  const messageText = userInput.value.trim();
  userInput.value = ''; // Очищаем поле ввода
  
  // Добавляем сообщение пользователя в историю
  messages.value.push({
    id: Date.now(),
    text: messageText,
    isUser: true,
    timestamp: new Date()
  });
  
  // Прокручиваем чат вниз после добавления сообщения
  await nextTick();
  scrollToBottom();
  
  // Устанавливаем состояние загрузки
  isLoading.value = true;
  
  try {
    // Отправляем запрос к API
    const response = await axios.post('/api/course-finder', {
      message: messageText
    });
    
    // Добавляем ответ бота в историю сообщений
    messages.value.push({
      id: Date.now(),
      text: response.data.message,
      isUser: false,
      timestamp: new Date()
    });
  } catch (error) {
    console.error('Ошибка при запросе к API:', error);
    
    // Добавляем сообщение об ошибке
    messages.value.push({
      id: Date.now(),
      text: 'Извините, возникла ошибка при обработке вашего запроса. Пожалуйста, попробуйте еще раз или задайте другой вопрос.',
      isUser: false,
      timestamp: new Date()
    });
  } finally {
    isLoading.value = false;
    
    // Прокручиваем чат вниз после получения ответа
    await nextTick();
    scrollToBottom();
  }
};

// Выбор предустановленного вопроса
const selectQuestion = (question) => {
  userInput.value = question;
  sendMessage();
};

// Отображение времени сообщения
const formatTime = (timestamp) => {
  return new Date(timestamp).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};

// Прокрутка чата к последнему сообщению
const scrollToBottom = () => {
  if (chatContainer.value) {
    chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
  }
};

// Рендеринг markdown в HTML
const renderMarkdown = (text) => {
  if (!text) return '';
  return marked(text);
};
</script>

<template>
  <div class="chatbot-widget">
    <!-- Кнопка для открытия/закрытия чата -->
    <button 
      @click="toggleChat" 
      class="chatbot-toggle-button"
      :class="{ 'chatbot-open': isOpen }"
    >
      <div v-if="!isOpen" class="flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
        <span>Помощник по курсам</span>
      </div>
      <div v-else>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </div>
    </button>
    
    <!-- Окно чата -->
    <div class="chatbot-window" :class="{ 'chatbot-open': isOpen }">
      <!-- Заголовок чата -->
      <div class="chatbot-header">
        <div class="flex items-center">
          <div class="chatbot-avatar">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
          </div>
          <div>
            <h3 class="chatbot-title">Помощник по курсам</h3>
            <p class="chatbot-subtitle">Помогу найти подходящий курс</p>
          </div>
        </div>
        <button @click="toggleChat" class="chatbot-close">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      
      <!-- Тело чата с сообщениями -->
      <div class="chatbot-body" ref="chatContainer">
        <div v-for="message in messages" :key="message.id" class="chatbot-message-container">
          <div 
            :class="[
              'chatbot-message', 
              message.isUser ? 'chatbot-user-message' : 'chatbot-bot-message'
            ]"
          >
            <div v-if="message.isUser">{{ message.text }}</div>
            <div v-else class="prose prose-sm max-w-none" v-html="renderMarkdown(message.text)"></div>
          </div>
          <div 
            :class="[
              'chatbot-timestamp', 
              message.isUser ? 'text-right' : 'text-left'
            ]"
          >
            {{ formatTime(message.timestamp) }}
          </div>
        </div>
        
        <!-- Индикатор загрузки -->
        <div v-if="isLoading" class="chatbot-message-container">
          <div class="chatbot-message chatbot-bot-message">
            <div class="chatbot-typing-indicator">
              <span></span>
              <span></span>
              <span></span>
            </div>
          </div>
        </div>
      </div>

      <!-- Быстрые вопросы -->
      <div class="chatbot-suggestions" v-if="messages.length < 3">
        <p class="chatbot-suggestions-title">Примеры вопросов:</p>
        <div class="chatbot-suggestions-container">
          <button 
            v-for="question in suggestedQuestions" 
            :key="question" 
            @click="selectQuestion(question)"
            class="chatbot-suggestion-button"
          >
            {{ question }}
          </button>
        </div>
      </div>
      
      <!-- Ввод сообщения -->
      <div class="chatbot-footer">
        <form @submit.prevent="sendMessage" class="chatbot-input-container">
          <input 
            v-model="userInput" 
            type="text" 
            placeholder="Напишите ваш вопрос..." 
            class="chatbot-input"
            :disabled="isLoading"
          />
          <button 
            type="submit" 
            class="chatbot-send-button"
            :disabled="isLoading || !userInput.trim()"
          >
            <svg v-if="!isLoading" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
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
</template>

<style scoped>
.chatbot-widget {
  position: fixed;
  bottom: 20px;
  right: 20px;
  z-index: 1000;
  font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.chatbot-toggle-button {
  display: flex;
  align-items: center;
  justify-content: center;
  width: auto;
  min-width: 52px;
  height: 52px;
  border-radius: 26px;
  background-color: #4f46e5;
  color: white;
  border: none;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  cursor: pointer;
  transition: all 0.3s ease;
  padding: 0 16px;
  font-weight: 500;
}

.chatbot-toggle-button:hover {
  background-color: #4338ca;
  box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
}

.chatbot-toggle-button.chatbot-open {
  width: 52px;
  min-width: unset;
  border-radius: 50%;
}

.chatbot-window {
  position: absolute;
  bottom: 70px;
  right: 0;
  width: 380px;
  height: 500px;
  background-color: #1e293b;
  border-radius: 12px;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
  display: flex;
  flex-direction: column;
  overflow: hidden;
  opacity: 0;
  transform: translateY(20px) scale(0.95);
  pointer-events: none;
  transition: all 0.3s ease;
}

.chatbot-window.chatbot-open {
  opacity: 1;
  transform: translateY(0) scale(1);
  pointer-events: all;
}

.chatbot-header {
  background-color: #2d3748;
  color: white;
  padding: 12px 16px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #4a5568;
}

.chatbot-avatar {
  width: 36px;
  height: 36px;
  background-color: #4f46e5;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 12px;
}

.chatbot-title {
  font-weight: 600;
  font-size: 16px;
}

.chatbot-subtitle {
  font-size: 12px;
  color: #a0aec0;
}

.chatbot-close {
  background: none;
  border: none;
  color: #a0aec0;
  cursor: pointer;
}

.chatbot-close:hover {
  color: white;
}

.chatbot-body {
  flex: 1;
  padding: 16px;
  overflow-y: auto;
  background-color: #1e293b;
}

.chatbot-message-container {
  margin-bottom: 12px;
}

.chatbot-message {
  max-width: 80%;
  padding: 10px 14px;
  border-radius: 12px;
  line-height: 1.4;
  word-break: break-word;
}

.chatbot-user-message {
  background-color: #4f46e5;
  color: white;
  margin-left: auto;
  border-bottom-right-radius: 4px;
}

.chatbot-bot-message {
  background-color: #2d3748;
  color: #e2e8f0;
  margin-right: auto;
  border-bottom-left-radius: 4px;
}

.chatbot-timestamp {
  margin-top: 4px;
  font-size: 10px;
  color: #64748b;
}

.chatbot-footer {
  padding: 12px 16px;
  background-color: #2d3748;
  border-top: 1px solid #4a5568;
}

.chatbot-input-container {
  display: flex;
  align-items: center;
  background-color: #1e293b;
  border-radius: 8px;
  overflow: hidden;
}

.chatbot-input {
  flex: 1;
  padding: 10px 14px;
  border: none;
  background-color: transparent;
  color: white;
  outline: none;
}

.chatbot-input::placeholder {
  color: #64748b;
}

.chatbot-send-button {
  background-color: #4f46e5;
  color: white;
  width: 42px;
  height: 42px;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background-color 0.2s ease;
}

.chatbot-send-button:hover:not(:disabled) {
  background-color: #4338ca;
}

.chatbot-send-button:disabled {
  background-color: #4f46e5;
  opacity: 0.5;
  cursor: not-allowed;
}

.chatbot-typing-indicator {
  display: flex;
  align-items: center;
}

.chatbot-typing-indicator span {
  width: 8px;
  height: 8px;
  background-color: #a0aec0;
  border-radius: 50%;
  margin: 0 2px;
  animation: typingBounce 1.4s infinite ease-in-out;
}

.chatbot-typing-indicator span:nth-child(1) {
  animation-delay: 0s;
}

.chatbot-typing-indicator span:nth-child(2) {
  animation-delay: 0.2s;
}

.chatbot-typing-indicator span:nth-child(3) {
  animation-delay: 0.4s;
}

@keyframes typingBounce {
  0%, 80%, 100% {
    transform: translateY(0);
  }
  40% {
    transform: translateY(-6px);
  }
}

.chatbot-suggestions {
  padding: 8px 16px;
  background-color: #1e293b;
  border-top: 1px solid #4a5568;
}

.chatbot-suggestions-title {
  font-size: 12px;
  color: #a0aec0;
  margin-bottom: 8px;
}

.chatbot-suggestions-container {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.chatbot-suggestion-button {
  background-color: #2d3748;
  color: #e2e8f0;
  border: 1px solid #4a5568;
  border-radius: 16px;
  padding: 6px 12px;
  font-size: 12px;
  cursor: pointer;
  transition: all 0.2s ease;
  white-space: nowrap;
  text-overflow: ellipsis;
  overflow: hidden;
  max-width: 100%;
}

.chatbot-suggestion-button:hover {
  background-color: #374151;
  border-color: #6b7280;
}

/* Стили для Markdown контента */
:deep(.prose) {
  color: #e2e8f0;
  font-size: 14px;
}

:deep(.prose a) {
  color: #93c5fd;
  text-decoration: underline;
}

:deep(.prose strong) {
  color: white;
  font-weight: 600;
}

:deep(.prose h1),
:deep(.prose h2),
:deep(.prose h3) {
  color: white;
  margin-top: 1em;
  margin-bottom: 0.5em;
}

:deep(.prose p) {
  margin-top: 0.5em;
  margin-bottom: 0.5em;
}

:deep(.prose ul),
:deep(.prose ol) {
  margin-top: 0.5em;
  margin-bottom: 0.5em;
  padding-left: 1.5em;
}

:deep(.prose li) {
  margin-top: 0.25em;
  margin-bottom: 0.25em;
}

:deep(.prose code) {
  background-color: rgba(71, 85, 105, 0.5);
  padding: 0.125rem 0.25rem;
  border-radius: 0.25rem;
}

/* Адаптивные стили для мобильных устройств */
@media (max-width: 640px) {
  .chatbot-window {
    width: calc(100vw - 40px);
    height: 450px;
    bottom: 80px;
  }
}
</style> 