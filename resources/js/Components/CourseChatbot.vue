<script setup>
import { ref, nextTick, onMounted } from 'vue';
import axios from 'axios';
import { marked } from 'marked';

// Configure marked for markdown rendering
marked.setOptions({
  breaks: true,        // Convert newline characters to <br>
  gfm: true,           // Use GitHub Flavored Markdown
  sanitize: false      // Allow HTML tags (but we use additional server-side sanitization)
});

// Component state
const isOpen = ref(false); // Chat is open or not
const messages = ref([]); // Message history
const userInput = ref(''); // Current user input
const isLoading = ref(false); // Loading indicator
const chatContainer = ref(null); // Reference to chat container for scrolling

// Preset questions for quick selection
const suggestedQuestions = [
  'I want to learn programming, what do you recommend?',
  'What courses on design do you have?',
  'Recommend courses for beginners',
  'What are the most popular courses?'
];

// Initialize chatbot
onMounted(() => {
  // Add welcome message on load
  messages.value.push({
    id: Date.now(),
    text: 'Hi! I\'m your course selection assistant. Tell me what you\'re interested in, and I\'ll help you find suitable courses. Feel free to ask about a specific topic or skill level.',
    isUser: false,
    timestamp: new Date()
  });
});

// Toggle chat visibility
const toggleChat = () => {
  isOpen.value = !isOpen.value;
};

// Send message
const sendMessage = async () => {
  if (!userInput.value.trim() || isLoading.value) return;
  
  const messageText = userInput.value.trim();
  userInput.value = ''; // Clear input field
  
  // Add user message to history
  messages.value.push({
    id: Date.now(),
    text: messageText,
    isUser: true,
    timestamp: new Date()
  });
  
  // Scroll chat down after adding message
  await nextTick();
  scrollToBottom();
  
  // Set loading state
  isLoading.value = true;
  
  try {
    // Send request to API
    const response = await axios.post('/api/course-finder', {
      message: messageText
    });
    
    // Add bot response to message history
    messages.value.push({
      id: Date.now(),
      text: response.data.message,
      isUser: false,
      timestamp: new Date()
    });
  } catch (error) {
    console.error('Error querying API:', error);
    
    // Add error message
    let errorMessage = 'Sorry, there was an error processing your request. Please try again or ask a different question.';
    
    // Check if error is a timeout
    if (error.code === 'ECONNABORTED') {
      errorMessage = 'The server response timed out. Queries to the neural network may take some time. Please try a shorter or simpler question.';
    }
    
    messages.value.push({
      id: Date.now(),
      text: errorMessage,
      isUser: false,
      timestamp: new Date()
    });
  } finally {
    isLoading.value = false;
    
    // Scroll chat down after receiving response
    await nextTick();
    scrollToBottom();
  }
};

// Select preset question
const selectQuestion = (question) => {
  userInput.value = question;
  sendMessage();
};

// Display message time
const formatTime = (timestamp) => {
  return new Date(timestamp).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};

// Scroll chat to the last message
const scrollToBottom = () => {
  if (chatContainer.value) {
    chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
  }
};

// Render markdown to HTML
const renderMarkdown = (text) => {
  if (!text) return '';
  return marked(text);
};
</script>

<template>
  <div class="chatbot-widget">
    <!-- Button to open/close chat -->
    <button 
      @click="toggleChat" 
      class="chatbot-toggle-button"
      :class="{ 'chatbot-open': isOpen }"
    >
      <div v-if="!isOpen" class="flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
        <span>Course Assistant</span>
      </div>
      <div v-else>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </div>
    </button>
    
    <!-- Chat window -->
    <div class="chatbot-window" :class="{ 'chatbot-open': isOpen }">
      <!-- Chat header -->
      <div class="chatbot-header">
        <div class="flex items-center">
          <div class="chatbot-avatar">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
          </div>
          <div>
            <h3 class="chatbot-title">Course Assistant</h3>
            <p class="chatbot-subtitle">I'll help find the right course</p>
          </div>
        </div>
        <button @click="toggleChat" class="chatbot-close">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      
      <!-- Chat body with messages -->
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
        
        <!-- Loading indicator -->
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

      <!-- Quick questions -->
     
      
      <!-- Message input -->
      <div class="chatbot-footer">
        <form @submit.prevent="sendMessage" class="chatbot-input-container">
          <input 
            v-model="userInput" 
            type="text" 
            placeholder="Type your question..." 
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
  font-family: 'Figtree', sans-serif;
}

.chatbot-toggle-button {
  background-color: theme('colors.primary.600');
  color: white;
  border: none;
  border-radius: 50px;
  padding: 10px 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
  transition: all 0.3s ease;
}

.chatbot-toggle-button:hover {
  background-color: theme('colors.primary.700');
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(79, 70, 229, 0.4);
}

.chatbot-toggle-button.chatbot-open {
  width: 52px;
  height: 52px;
  min-width: unset;
  border-radius: 50%;
  padding: 0;
}

.chatbot-window {
  position: fixed;
  bottom: 90px;
  right: 20px;
  width: 380px;
  height: 550px;
  background-color: white;
  border-radius: 16px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  display: flex;
  flex-direction: column;
  transform: scale(0);
  opacity: 0;
  transform-origin: bottom right;
  transition: all 0.3s cubic-bezier(0.68, -0.55, 0.27, 1.55);
}

.chatbot-window.chatbot-open {
  transform: scale(1);
  opacity: 1;
}

.chatbot-header {
  background: linear-gradient(to right, theme('colors.primary.600'), theme('colors.primary.500'));
  color: white;
  padding: 18px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-top-left-radius: 16px;
  border-top-right-radius: 16px;
}

.chatbot-avatar {
  background-color: theme('colors.primary.400');
  border-radius: 50%;
  width: 42px;
  height: 42px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.chatbot-title {
  font-weight: 600;
  font-size: 18px;
  margin: 0;
}

.chatbot-subtitle {
  font-size: 13px;
  margin: 0;
  opacity: 0.9;
}

.chatbot-close {
  background: rgba(255, 255, 255, 0.2);
  border: none;
  border-radius: 50%;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  cursor: pointer;
  transition: background-color 0.2s;
}

.chatbot-close:hover {
  background: rgba(255, 255, 255, 0.3);
}

.chatbot-body {
  flex-grow: 1;
  padding: 16px;
  overflow-y: auto;
  background-color: theme('colors.surface.50');
  scroll-behavior: smooth;
}

.chatbot-message-container {
  margin-bottom: 16px;
}

.chatbot-message {
  padding: 12px 16px;
  border-radius: 18px;
  max-width: 85%;
  word-wrap: break-word;
  line-height: 1.5;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.chatbot-bot-message {
  background-color: white;
  color: theme('colors.primary.900');
  border-top-left-radius: 4px;
  margin-right: auto;
  border: 1px solid theme('colors.primary.100');
}

.chatbot-user-message {
  background: linear-gradient(to right, theme('colors.primary.500'), theme('colors.primary.600'));
  color: white;
  border-top-right-radius: 4px;
  margin-left: auto;
}

.chatbot-timestamp {
  font-size: 10px;
  color: theme('colors.surface.400');
  margin-top: 5px;
}

.chatbot-footer {
  padding: 16px;
  background-color: white;
  border-top: 1px solid theme('colors.surface.200');
}

.chatbot-input-container {
  display: flex;
  align-items: center;
}

.chatbot-input {
  flex-grow: 1;
  border: 1px solid theme('colors.surface.300');
  border-radius: 24px;
  padding: 10px 18px;
  outline: none;
  transition: all 0.3s;
  font-size: 14px;
}

.chatbot-input:focus {
  border-color: theme('colors.primary.500');
  box-shadow: 0 0 0 2px theme('colors.primary.100');
}

.chatbot-send-button {
  background: linear-gradient(to right, theme('colors.primary.500'), theme('colors.primary.600'));
  color: white;
  border: none;
  border-radius: 50%;
  width: 44px;
  height: 44px;
  margin-left: 12px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s;
  box-shadow: 0 2px 6px rgba(79, 70, 229, 0.2);
}

.chatbot-send-button:hover {
  background: linear-gradient(to right, theme('colors.primary.600'), theme('colors.primary.700'));
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(79, 70, 229, 0.3);
}

.chatbot-typing-indicator {
  display: flex;
  align-items: center;
  padding: 0 8px;
}

.chatbot-typing-indicator span {
  height: 8px;
  width: 8px;
  margin: 0 3px;
  background-color: theme('colors.primary.400');
  border-radius: 50%;
  display: inline-block;
  animation: bounce 1.3s linear infinite;
}

.chatbot-typing-indicator span:nth-child(2) {
  animation-delay: 0.15s;
}

.chatbot-typing-indicator span:nth-child(3) {
  animation-delay: 0.3s;
}

.chatbot-suggestions {
  padding: 16px;
  background-color: white;
  border-top: 1px solid theme('colors.primary.100');
}

.chatbot-suggestions-title {
  font-size: 12px;
  color: theme('colors.primary.600');
  margin-bottom: 12px;
  font-weight: 600;
}

.chatbot-suggestions-container {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.chatbot-suggestion-button {
  background-color: theme('colors.primary.50');
  color: theme('colors.primary.700');
  border: 1px solid theme('colors.primary.200');
  border-radius: 16px;
  padding: 8px 14px;
  font-size: 13px;
  cursor: pointer;
  transition: all 0.2s;
  font-weight: 500;
}

.chatbot-suggestion-button:hover {
  background-color: theme('colors.primary.100');
  border-color: theme('colors.primary.300');
}

@keyframes bounce {
  0%, 100% {
    transform: translateY(0);
  }
  25% {
    transform: translateY(-3px);
  }
  50% {
    transform: translateY(0);
  }
  75% {
    transform: translateY(3px);
  }
}

@media (max-width: 640px) {
  .chatbot-window {
    width: calc(100% - 40px);
    height: 500px;
    bottom: 80px;
  }
}
</style> 