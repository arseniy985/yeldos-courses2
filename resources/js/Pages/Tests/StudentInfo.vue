<template>
  <Head title="Student Details" />
    <NavLayout>
        <main class="container mx-auto py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-4 bg-gray-800 rounded-lg p-6 flex">
                    <img
                        class="rounded-full"
                        width="35"
                        src="/images/astronaut.png"
                    />
                    <h2 class="text-white ml-2 font-extrabold py-1">
                        Details of Student: {{ student.name }}
                    </h2>
                </div>
                <div class="md:col-span-2 bg-gray-800 rounded-lg p-6 flex">
                    <div class="mr-4">
                        <p class="text-white font-semibold">Name:</p>
                        <p class="text-white">{{ student.name }}</p>

                        <p class="text-white font-semibold mt-3">Email:</p>
                        <p class="text-white">{{ student.email }}</p>

                        <p class="text-white font-semibold mt-3">
                            Account Created:
                        </p>
                        <p class="text-white">{{ student.created_at }}</p>
                    </div>
                </div>
                <div class="md:col-span-2 bg-gray-800 rounded-lg p-6 flex">
                    <div class="mr-4">
                        <p class="text-white font-semibold">Account ID:</p>
                        <p class="text-white">{{ student.id }}</p>
                        <p class="text-white font-semibold mt-3">Role:</p>
                        <p class="text-white">{{ student.role }}</p>
                        <p class="text-white font-semibold mt-3">Auth Type:</p>
                        <p class="text-white">{{ student.auth_type }}</p>
                    </div>
                </div>
                <div class="md:col-span-4 bg-gray-800 rounded-lg p-6 flex">
                    <h4 class="text-white ml-2 font-extrabold py-1">
                        Courses Enrolled
                    </h4>
                </div>
            </div>
        </main>
    </NavLayout>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-gray-800 border-b border-gray-700">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-white">Информация о тестировании</h2>
            <Link :href="route('student.tests')" 
                  class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
              К списку тестов
            </Link>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Статистика -->
            <div class="bg-gray-700 rounded-lg shadow p-6">
              <h3 class="text-lg font-medium text-white mb-4">Ваша статистика</h3>
              
              <div class="space-y-4">
                <div class="flex justify-between items-center">
                  <span class="text-gray-300">Всего пройдено тестов:</span>
                  <span class="font-semibold text-white">{{ stats.total_tests_taken }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                  <span class="text-gray-300">Успешно пройдено:</span>
                  <span class="font-semibold text-green-400">{{ stats.tests_passed }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                  <span class="text-gray-300">Не пройдено:</span>
                  <span class="font-semibold text-red-400">{{ stats.tests_failed }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                  <span class="text-gray-300">Средний результат:</span>
                  <span class="font-semibold text-indigo-400">{{ stats.average_score }}%</span>
                </div>
              </div>
            </div>
            
            <!-- Последние результаты -->
            <div class="bg-gray-700 rounded-lg shadow p-6">
              <h3 class="text-lg font-medium text-white mb-4">Последние результаты</h3>
              
              <div v-if="stats.recent_results.length === 0" class="text-gray-300 text-center py-4">
                У вас еще нет пройденных тестов
              </div>
              
              <div v-else class="space-y-3">
                <div v-for="result in stats.recent_results" :key="result.id"
                     class="border-b border-gray-600 pb-3 last:border-0 last:pb-0">
                  <div class="flex justify-between">
                    <div>
                      <div class="text-white font-medium">{{ result.test_title }}</div>
                      <div class="text-sm text-gray-400">{{ formatDate(result.date) }}</div>
                    </div>
                    <div :class="result.passed ? 'text-green-400' : 'text-red-400'" class="font-semibold">
                      {{ result.score }}%
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Рекомендации -->
          <div class="bg-gray-700 rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-white mb-4">Рекомендуемые тесты</h3>
            
            <div v-if="recommendations.length === 0" class="text-gray-300 text-center py-4">
              Нет рекомендуемых тестов в данный момент
            </div>
            
            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              <div v-for="test in recommendations" :key="test.id"
                   class="bg-gray-600 border border-gray-500 rounded-lg p-4">
                <h4 class="text-white font-medium mb-2">{{ test.title }}</h4>
                <div class="text-sm text-gray-300 mb-3">{{ test.course_title }}</div>
                <div class="flex justify-end">
                  <Link :href="route('student.test.take', { id: test.id })"
                        class="px-3 py-1 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                    Пройти тест
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  stats: Object,
  recommendations: Array
});

// Форматирование даты
const formatDate = (dateString) => {
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('ru-RU', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date);
};
</script> 