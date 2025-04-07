<template>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <h2 class="text-2xl font-semibold text-gray-800 mb-6">Доступные тесты</h2>
          
          <div v-if="!hasTests" class="text-center py-8">
            <div class="text-gray-500 mb-4">Для вас еще нет доступных тестов</div>
            <Link :href="route('home')" 
                  class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
              Вернуться на главную
            </Link>
          </div>
          
          <div v-else>
            <div v-for="course in courses" :key="course.id" class="mb-8">
              <h3 class="text-xl font-medium text-gray-700 mb-4 border-b pb-2">{{ course.title }}</h3>
              
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="test in course.tests" :key="test.id" 
                     class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition p-6">
                  <h4 class="text-lg font-semibold text-gray-800 mb-2">{{ test.title }}</h4>
                  
                  <div class="mt-2 text-sm text-gray-600 mb-4">
                    Количество вопросов: {{ test.questions_count || 'не указано' }}
                  </div>
                  
                  <div class="mt-4 flex justify-end">
                    <Link :href="route('student.tests.take', { id: test.id })" 
                          class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                      Пройти тест
                    </Link>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="mt-8 flex justify-between">
              <Link :href="route('home')" 
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
                Вернуться на главную
              </Link>
              <Link :href="route('student.test-results')" 
                    class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded hover:bg-indigo-200 transition">
                История тестов
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  courses: Array
});

// Проверяем, есть ли тесты в курсах
const hasTests = computed(() => {
  if (!props.courses || props.courses.length === 0) {
    return false;
  }
  
  return props.courses.some(course => 
    course.tests && course.tests.length > 0
  );
});
</script> 