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
          <Link :href="route('student.tests')" class="text-gray-400 hover:text-white transition">
            Доступные тесты
          </Link>
          <span class="mx-2 text-gray-500">›</span>
          <span class="text-white">Мои результаты</span>
        </div>
        
        <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-gray-800 border-b border-gray-700">
            <div class="flex justify-between items-center mb-6">
              <h2 class="text-xl font-semibold text-white">Результаты тестов</h2>
              <Link :href="route('student.tests')" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                К списку тестов
              </Link>
            </div>
            
            <div v-if="results.length === 0" class="text-center py-8">
              <div class="text-gray-300 mb-4">У вас еще нет пройденных тестов</div>
              <Link :href="route('student.tests')" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                Пройти тесты
              </Link>
            </div>
            
            <div v-else>
              <div class="grid grid-cols-1 gap-4">
                <div v-for="result in results" :key="result.id" 
                    class="bg-gray-700 border border-gray-600 rounded-lg shadow-md hover:shadow-lg transition p-6">
                  <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                    <div>
                      <h3 class="text-lg font-semibold text-white">{{ result.test.title }}</h3>
                      <p class="text-gray-300 text-sm mt-1">Курс: {{ result.test.course.title }}</p>
                    </div>
                    <div :class="result.passed ? 'bg-green-600' : 'bg-red-600'" 
                        class="px-3 py-1 text-white rounded-full text-sm font-medium mt-2 md:mt-0">
                      {{ result.passed ? 'Пройден' : 'Не пройден' }}
                    </div>
                  </div>
                  
                  <div class="flex flex-col md:flex-row justify-between text-gray-300 mb-4">
                    <div class="mb-2 md:mb-0">
                      <span class="font-medium">Правильных ответов:</span> {{ result.correct_answers }} из {{ result.total_questions }}
                    </div>
                    <div>
                      <span class="font-medium">Результат:</span> {{ result.percentage }}%
                    </div>
                  </div>
                  
                  <div class="flex justify-between items-center mt-4">
                    <div class="text-xs text-gray-400">
                      Пройден: {{ new Date(result.created_at).toLocaleDateString() }} {{ new Date(result.created_at).toLocaleTimeString() }}
                    </div>
                    <Link :href="route('student.test.result', { id: result.id })" 
                          class="px-3 py-1 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                      Подробнее
                    </Link>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </NavLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import NavLayout from "@/Layouts/NavLayout.vue";

defineProps({
  results: Array
});
</script> 