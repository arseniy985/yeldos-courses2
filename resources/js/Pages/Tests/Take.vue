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
          <Link v-if="test.course" :href="route('course.show', { id: test.course.id })" class="text-gray-400 hover:text-white transition">
            {{ test.course.title }}
          </Link>
          <template v-if="test.course">
            <span class="mx-2 text-gray-500">›</span>
            <Link :href="route('student.tests')" class="text-gray-400 hover:text-white transition">
              Все тесты
            </Link>
          </template>
          <span class="mx-2 text-gray-500">›</span>
          <span class="text-white">{{ test.title }}</span>
        </div>
        
        <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-gray-800 border-b border-gray-700">
            <!-- Предупреждение о том, что тест уже успешно пройден -->
            <div v-if="testAlreadyPassed" class="bg-green-600 bg-opacity-20 border border-green-600 rounded-lg p-6 mb-6">
              <div class="flex items-center mb-4">
                <div class="bg-green-600 rounded-full p-2 mr-3">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
                <h3 class="text-xl font-semibold text-green-300">Вы уже успешно прошли этот тест!</h3>
              </div>
              <p class="text-gray-300 mb-4">
                Вы уже прошли этот тест с хорошим результатом. Вы можете посмотреть свои результаты или вернуться к списку доступных тестов.
              </p>
              <div class="flex space-x-4">
                <Link :href="route('student.test.result', { id: existingResult.id })" 
                      class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                  Посмотреть мои результаты
                </Link>
                <Link :href="route('student.tests')" 
                      class="px-4 py-2 bg-gray-600 text-gray-200 rounded hover:bg-gray-500 transition">
                  К списку тестов
                </Link>
              </div>
            </div>
            
            <!-- Содержимое теста (показывается только если тест не был успешно пройден) -->
            <div v-if="!testAlreadyPassed">
              <!-- Заголовок и детали теста -->
              <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                  <h2 class="text-xl font-semibold text-white">{{ test.title }}</h2>
                  <div>
                    <span class="bg-indigo-600 text-xs font-medium rounded-full px-3 py-1 text-white">
                      {{ currentQuestionIndex + 1 }} из {{ test.questions.length }}
                    </span>
                  </div>
                </div>
                <p class="text-gray-300 text-sm" v-if="test.course">Курс: {{ test.course.title }}</p>
                <p class="text-gray-300 text-sm" v-else>Курс: Информация недоступна</p>
              </div>
              
              <!-- Прогресс бар -->
              <div class="w-full bg-gray-700 rounded-full h-2.5 mb-6">
                <div class="bg-indigo-600 h-2.5 rounded-full" 
                    :style="{ width: `${(currentQuestionIndex / test.questions.length) * 100}%` }"></div>
              </div>
              
              <!-- Вопрос -->
              <div v-if="!isTestCompleted && currentQuestion" class="mb-8">
                <h3 class="text-lg font-medium text-white mb-4">{{ currentQuestion.name }}</h3>
                
                <div class="space-y-3">
                  <div v-for="(answer, index) in currentQuestion.answers" :key="index"
                      class="flex items-center">
                    <input type="radio" 
                          :id="'answer-' + index" 
                          :value="answer.id" 
                          v-model="selectedAnswer"
                          class="form-radio h-5 w-5 text-indigo-600" />
                    <label :for="'answer-' + index" class="ml-3 text-gray-300 cursor-pointer">
                      {{ answer.name }}
                    </label>
                  </div>
                </div>
              </div>
              
              <!-- Результаты теста -->
              <div v-if="isTestCompleted" class="mb-8">
                <div class="bg-gray-700 rounded-lg p-6 text-center">
                  <h3 class="text-lg font-medium mb-4" 
                      :class="testResult.passed ? 'text-green-400' : 'text-red-400'">
                    {{ testResult.passed ? 'Тест успешно пройден!' : 'Тест не пройден' }}
                  </h3>
                  
                  <div class="mb-4">
                    <div class="inline-block bg-gray-600 px-4 py-2 rounded-lg">
                      <div class="text-2xl font-bold text-white">{{ testResult.percentage }}%</div>
                      <div class="text-sm text-gray-300">
                        Правильных ответов: {{ testResult.correct_answers }} из {{ testResult.total_questions }}
                      </div>
                    </div>
                  </div>
                  
                  <div class="text-gray-300 mb-6">
                    {{ testResult.passed 
                      ? 'Поздравляем! Вы успешно справились с тестом.' 
                      : 'К сожалению, тест не пройден. Вы можете попробовать пересдать его.' }}
                  </div>
                  
                  <div class="flex justify-center space-x-4">
                    <Link :href="route('student.tests')" 
                          class="px-4 py-2 bg-gray-600 text-gray-200 rounded hover:bg-gray-500 transition">
                      К списку тестов
                    </Link>
                    <Link v-if="!testResult.passed" :href="route('student.test.take', { id: test.id })" 
                          class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                      Пройти тест снова
                    </Link>
                    <Link v-else :href="route('student.test.result', { id: testResult.result_id })" 
                          class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                      Посмотреть детали
                    </Link>
                  </div>
                </div>
              </div>
              
              <!-- Кнопки навигации -->
              <div v-if="!isTestCompleted" class="flex justify-between">
                <button @click="previousQuestion" 
                        class="px-4 py-2 bg-gray-600 text-gray-200 rounded hover:bg-gray-500 transition"
                        :disabled="currentQuestionIndex === 0">
                  Назад
                </button>
                
                <button v-if="currentQuestionIndex < test.questions.length - 1"
                        @click="nextQuestion" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition"
                        :disabled="!selectedAnswer">
                  Следующий вопрос
                </button>
                
                <button v-else
                        @click="submitTest" 
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition"
                        :disabled="!selectedAnswer">
                  Завершить тест
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </NavLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import axios from 'axios';
import NavLayout from "@/Layouts/NavLayout.vue";

const props = defineProps({
  test: Object
});

const currentQuestionIndex = ref(0);
const selectedAnswers = ref({});
const selectedAnswer = ref(null);
const isTestCompleted = ref(false);
const testResult = ref(null);
const testAlreadyPassed = ref(false);
const existingResult = ref(null);

// Текущий вопрос
const currentQuestion = computed(() => {
  if (props.test.questions && props.test.questions.length > 0) {
    return props.test.questions[currentQuestionIndex.value];
  }
  return null;
});

// Проверяем при загрузке компонента, был ли тест успешно пройден
onMounted(() => {
  checkTestResult();
});

const checkTestResult = () => {
  // Проверяем, есть ли у теста результат для текущего пользователя
  if (props.test.results && props.test.results.length > 0) {
    // Находим результат, который помечен как успешно пройденный
    const passedResult = props.test.results.find(result => result.passed);
    if (passedResult) {
      testAlreadyPassed.value = true;
      existingResult.value = passedResult;
    }
  }
};

// При изменении текущего вопроса, загружаем ранее выбранный ответ, если есть
const updateSelectedAnswer = () => {
  const questionId = currentQuestion.value?.id;
  selectedAnswer.value = questionId ? selectedAnswers.value[questionId] : null;
};

// Следующий вопрос
const nextQuestion = () => {
  if (currentQuestionIndex.value < props.test.questions.length - 1) {
    // Сохраняем выбранный ответ
    const questionId = currentQuestion.value.id;
    selectedAnswers.value[questionId] = selectedAnswer.value;
    
    // Переходим к следующему вопросу
    currentQuestionIndex.value++;
    
    // Загружаем ранее выбранный ответ для следующего вопроса, если есть
    updateSelectedAnswer();
  }
};

// Предыдущий вопрос
const previousQuestion = () => {
  if (currentQuestionIndex.value > 0) {
    // Сохраняем текущий выбор
    const questionId = currentQuestion.value.id;
    selectedAnswers.value[questionId] = selectedAnswer.value;
    
    // Переходим к предыдущему вопросу
    currentQuestionIndex.value--;
    
    // Загружаем ранее выбранный ответ для предыдущего вопроса
    updateSelectedAnswer();
  }
};

// Отправка теста на проверку
const submitTest = () => {
  // Сохраняем ответ на последний вопрос
  const questionId = currentQuestion.value.id;
  selectedAnswers.value[questionId] = selectedAnswer.value;
  
  // Формируем данные для отправки
  const answers = [];
  Object.keys(selectedAnswers.value).forEach(questionId => {
    answers.push({
      question_id: questionId,
      answer_id: selectedAnswers.value[questionId]
    });
  });
  
  // Отправляем данные через axios напрямую вместо Inertia
  axios.post(`/student/submit-test`, { test_id: props.test.id, answers })
    .then(response => {
      if (response.data.success) {
        // Если успешно, перенаправляем на страницу результата теста
        const resultId = response.data.data.result_id;
        if (resultId) {
          router.visit(route('student.test.result', { id: resultId }));
        } else {
          // Если ID результата не вернулся, показываем встроенный результат
          isTestCompleted.value = true;
          testResult.value = response.data.data;
        }
      } else {
        console.error('Ошибка при отправке результатов:', response.data.message);
      }
    })
    .catch(error => {
      console.error('Ошибка при отправке результатов:', error);
    });
};
</script> 