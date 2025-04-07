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
          <span class="text-white">Доступные тесты</span>
        </div>
        
        <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-gray-800 border-b border-gray-700">
            <div class="flex justify-between items-center mb-6">
              <h2 class="text-xl font-semibold text-white">Доступные тесты</h2>
              <Link :href="route('student.test-results')" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                Мои результаты
              </Link>
            </div>
            
            <div v-if="tests.length === 0" class="text-center py-8">
              <div class="text-gray-300 mb-4">Нет доступных тестов</div>
              <Link :href="route('courses')" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                Просмотреть курсы
              </Link>
            </div>
            
            <div v-else class="space-y-6">
              <div v-for="(courseTests, course) in groupedTests" :key="course" class="bg-gray-700 rounded-lg overflow-hidden">
                <div class="bg-gray-600 px-6 py-3">
                  <h3 class="font-medium text-white">{{ course }}</h3>
                </div>
                
                <div class="p-6 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                  <div v-for="test in courseTests" :key="test.id" 
                      class="bg-gray-600 border border-gray-500 rounded-lg shadow-md hover:shadow-lg transition p-4">
                    <div class="mb-2">
                      <h4 class="text-lg font-medium text-white">{{ test.title }}</h4>
                      <div class="mt-1 text-sm text-gray-300">
                        Вопросов: {{ test.questions_count || 0 }}
                      </div>
                    </div>
                    
                    <div v-if="test.result" class="mb-3 p-2 rounded" 
                        :class="test.result.passed ? 'bg-green-700 bg-opacity-30' : 'bg-red-700 bg-opacity-30'">
                      <div class="text-xs font-medium" :class="test.result.passed ? 'text-green-300' : 'text-red-300'">
                        {{ test.result.passed ? 'Тест пройден' : 'Тест не пройден' }}
                      </div>
                      <div class="text-sm text-gray-300">
                        Результат: {{ test.result.percentage }}% ({{ test.result.correct_answers }}/{{ test.result.total_questions }})
                      </div>
                    </div>
                    
                    <div class="flex justify-end">
                      <Link v-if="test.result && test.result.passed" 
                            :href="route('student.test.result', { id: test.result.id })"
                            class="px-3 py-1 text-sm bg-green-600 text-white rounded hover:bg-green-700 transition">
                        Посмотреть результаты
                      </Link>
                      <Link v-else
                            :href="route('student.test.take', { id: test.id })"
                            class="px-3 py-1 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                        {{ test.result ? 'Пересдать тест' : 'Пройти тест' }}
                      </Link>
                    </div>
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
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import NavLayout from "@/Layouts/NavLayout.vue";

const props = defineProps({
  tests: Array
});

// Группировка тестов по курсам
const groupedTests = computed(() => {
  const grouped = {};
  
  props.tests.forEach(test => {
    const courseName = test.course ? test.course.title : 'Без курса';
    
    if (!grouped[courseName]) {
      grouped[courseName] = [];
    }
    
    grouped[courseName].push(test);
  });
  
  return grouped;
});
</script> 