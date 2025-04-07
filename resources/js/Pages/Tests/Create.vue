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
          <Link :href="route('manageCourses')" class="text-gray-400 hover:text-white transition">
            Управление курсами
          </Link>
          <span class="mx-2 text-gray-500">›</span>
          <Link :href="route('course.tests', { courseId: course.id })" class="text-gray-400 hover:text-white transition">
            Тесты для курса: {{ course.title }}
          </Link>
          <span class="mx-2 text-gray-500">›</span>
          <span class="text-white">Создание теста</span>
        </div>
        
        <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-gray-800 border-b border-gray-700">
            <div class="flex justify-between items-center mb-6">
              <h2 class="text-xl font-semibold text-white">Создание теста для курса: {{ course.title }}</h2>
              <Link :href="route('course.tests', { courseId: course.id })" 
                    class="px-4 py-2 bg-gray-600 text-gray-200 rounded hover:bg-gray-500 transition">
                Назад к списку тестов
              </Link>
            </div>
            
            <form @submit.prevent="submitForm">
              <!-- Основные данные теста -->
              <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-300 mb-1">Название теста</label>
                <input type="text" id="title" v-model="form.title" 
                       class="w-full px-3 py-2 border border-gray-600 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-gray-700 text-white" 
                       required />
                <div v-if="form.errors.title" class="text-red-400 text-sm mt-1">{{ form.errors.title }}</div>
              </div>
              
              <!-- Секция для вопросов -->
              <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                  <h3 class="text-lg font-medium text-gray-300">Вопросы</h3>
                  <button type="button" @click="addQuestion" 
                          class="px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                    Добавить вопрос
                  </button>
                </div>
                
                <div v-if="form.questions.length === 0" class="text-center py-4 bg-gray-700 rounded">
                  <div class="text-gray-300">У теста еще нет вопросов</div>
                  <button type="button" @click="addQuestion" 
                          class="mt-2 px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                    Добавить первый вопрос
                  </button>
                </div>
                
                <!-- Список вопросов -->
                <div v-for="(question, qIndex) in form.questions" :key="qIndex" class="mb-6 p-4 border border-gray-600 rounded-lg bg-gray-700">
                  <div class="flex justify-between items-start mb-4">
                    <div class="w-full">
                      <label :for="'question-'+qIndex" class="block text-sm font-medium text-gray-300 mb-1">
                        Текст вопроса {{ qIndex + 1 }}
                      </label>
                      <input type="text" :id="'question-'+qIndex" v-model="question.name" 
                            class="w-full px-3 py-2 border border-gray-600 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-gray-700 text-white" 
                            required />
                    </div>
                    <button type="button" @click="removeQuestion(qIndex)" 
                            class="ml-2 text-red-400 hover:text-red-300">
                      <TrashIcon :size="18" />
                    </button>
                  </div>
                  
                  <!-- Ответы на вопрос -->
                  <div class="ml-4">
                    <div class="flex justify-between items-center mb-2">
                      <h4 class="text-sm font-medium text-gray-300">Варианты ответов</h4>
                      <button type="button" @click="addAnswer(qIndex)" 
                              class="px-2 py-1 text-xs bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                        Добавить ответ
                      </button>
                    </div>
                    
                    <div v-if="question.answers.length === 0" class="text-center py-3 bg-gray-600 rounded text-sm">
                      <div class="text-gray-300">Добавьте варианты ответов</div>
                    </div>
                    
                    <div v-for="(answer, aIndex) in question.answers" :key="aIndex" 
                         class="flex items-center mb-2 p-2 border border-gray-600 rounded-md bg-gray-600">
                      <input type="radio" :name="'correct-answer-'+qIndex" :id="'answer-'+qIndex+'-'+aIndex" 
                            :checked="answer.is_valid" @change="setCorrectAnswer(qIndex, aIndex)" 
                            class="mr-2" />
                      <input type="text" v-model="answer.name" 
                            class="flex-grow px-3 py-1 border border-gray-600 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm bg-gray-700 text-white" 
                            required />
                      <button type="button" @click="removeAnswer(qIndex, aIndex)" 
                              class="ml-2 text-red-400 hover:text-red-300">
                        <TrashIcon :size="16" />
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Кнопки формы -->
              <div class="flex justify-end space-x-3">
                <Link :href="route('course.tests', { courseId: course.id })" 
                      class="px-4 py-2 bg-gray-600 text-gray-200 rounded hover:bg-gray-500 transition">
                  Отмена
                </Link>
                <button type="submit" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition"
                        :disabled="form.processing">
                  Создать тест
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </NavLayout>
</template>

<script setup>
import { reactive } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import TrashIcon from '@/Components/Icons/TrashIcon.vue';
import NavLayout from "@/Layouts/NavLayout.vue";

const props = defineProps({
  course: Object
});

const form = useForm({
  courses_id: props.course.id,
  title: '',
  questions: []
});

// Добавление нового вопроса
const addQuestion = () => {
  form.questions.push({
    name: '',
    answers: []
  });
};

// Удаление вопроса
const removeQuestion = (index) => {
  form.questions.splice(index, 1);
};

// Добавление нового варианта ответа
const addAnswer = (questionIndex) => {
  form.questions[questionIndex].answers.push({
    name: '',
    is_valid: form.questions[questionIndex].answers.length === 0 // Первый ответ делаем правильным по умолчанию
  });
};

// Удаление варианта ответа
const removeAnswer = (questionIndex, answerIndex) => {
  // Если удаляем правильный ответ и есть другие ответы, делаем первый правильным
  const isRemovingCorrect = form.questions[questionIndex].answers[answerIndex].is_valid;
  form.questions[questionIndex].answers.splice(answerIndex, 1);
  
  if (isRemovingCorrect && form.questions[questionIndex].answers.length > 0) {
    form.questions[questionIndex].answers[0].is_valid = true;
  }
};

// Установка правильного ответа
const setCorrectAnswer = (questionIndex, answerIndex) => {
  // Сначала все ответы делаем неправильными
  form.questions[questionIndex].answers.forEach((answer, idx) => {
    form.questions[questionIndex].answers[idx].is_valid = false;
  });
  
  // Затем отмечаем выбранный ответ как правильный
  form.questions[questionIndex].answers[answerIndex].is_valid = true;
};

// Отправка формы
const submitForm = () => {
  // Валидация формы на клиенте перед отправкой
  let isValid = true;
  
  // Проверяем заполнение вопросов
  if (form.questions.length === 0) {
    alert('Добавьте хотя бы один вопрос');
    isValid = false;
    return;
  }
  
  // Проверяем, что у каждого вопроса есть хотя бы два ответа
  for (let i = 0; i < form.questions.length; i++) {
    if (form.questions[i].answers.length < 2) {
      alert(`Вопрос ${i+1} должен иметь как минимум 2 варианта ответа`);
      isValid = false;
      return;
    }
    
    // Проверяем, что у вопроса есть хотя бы один правильный ответ
    const hasCorrectAnswer = form.questions[i].answers.some(answer => answer.is_valid);
    if (!hasCorrectAnswer) {
      alert(`Вопрос ${i+1} должен иметь хотя бы один правильный ответ`);
      isValid = false;
      return;
    }
  }
  
  if (isValid) {
    console.log('Отправка формы:', form);
    form.post(route('tests.store'), {
      onSuccess: (page) => {
        // При успешном ответе сервера перенаправляемся на страницу списка тестов
        if (page.props.flash.success) {
          router.visit(route('course.tests', { courseId: props.course.id }));
        }
      },
      onError: (errors) => {
        console.error('Ошибки при сохранении:', errors);
      }
    });
  }
};
</script> 