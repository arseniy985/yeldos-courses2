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
          <span class="text-white">Тесты для курса: {{ course.title }}</span>
        </div>
        
        <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-gray-800 border-b border-gray-700">
            <div class="flex justify-between items-center mb-6">
              <h2 class="text-xl font-semibold text-white">Тесты для курса: {{ course.title }}</h2>
              <Link :href="route('course.tests.create', { courseId: course.id })"
                    class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                Создать тест
              </Link>
            </div>

            <div v-if="tests.length === 0" class="text-center py-8">
              <div class="text-gray-300 mb-4">Для этого курса еще нет тестов</div>
              <Link :href="route('course.tests.create', { courseId: course.id })"
                    class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                Создать первый тест
              </Link>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div v-for="test in tests" :key="test.id"
                  class="bg-gray-700 border border-gray-600 rounded-lg shadow-md hover:shadow-lg transition p-6">
                <div class="flex justify-between items-start">
                  <h3 class="text-lg font-semibold text-white">{{ test.title }}</h3>
                  <div class="flex items-center space-x-2">
                    <Link :href="route('tests.edit', { id: test.id })" class="text-indigo-400 hover:text-indigo-300">
                      <PencilIcon :size="18" />
                    </Link>
                    <button @click="confirmDeleteTest(test)" class="text-red-400 hover:text-red-300">
                      <TrashIcon :size="18" />
                    </button>
                  </div>
                </div>

                <div class="mt-2 text-sm text-gray-300">
                  Количество вопросов: {{ test.questions_count || 0 }}
                </div>

                <div class="mt-4 flex justify-between items-center">
                  <span class="text-xs text-gray-400">
                    Создан: {{ new Date(test.created_at).toLocaleDateString() }}
                  </span>
                  <Link :href="route('tests.edit', { id: test.id })"
                        class="text-sm text-indigo-400 hover:text-indigo-300">
                    Редактировать
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Модальное окно подтверждения удаления -->
      <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-gray-800 rounded-lg p-6 max-w-md w-full border border-gray-700">
          <h3 class="text-lg font-semibold mb-4 text-white">Подтвердите удаление</h3>
          <p class="mb-6 text-gray-300">Вы действительно хотите удалить тест "{{ testToDelete?.title }}"? Это действие нельзя отменить.</p>
          <div class="flex justify-end space-x-3">
            <button @click="showDeleteModal = false"
                    class="px-4 py-2 bg-gray-600 text-gray-200 rounded hover:bg-gray-500 transition">
              Отмена
            </button>
            <button @click="deleteTest"
                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
              Удалить
            </button>
          </div>
        </div>
      </div>
    </div>
  </NavLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import PencilIcon from '@/Components/Icons/PencilIcon.vue';
import TrashIcon from '@/Components/Icons/TrashIcon.vue';
import NavLayout from "@/Layouts/NavLayout.vue";

const props = defineProps({
  course: Object,
  tests: Array
});

const showDeleteModal = ref(false);
const testToDelete = ref(null);

const confirmDeleteTest = (test) => {
  testToDelete.value = test;
  showDeleteModal.value = true;
};

const deleteTest = () => {
  router.delete(route('tests.destroy', { id: testToDelete.value.id }), {
    onSuccess: () => {
      showDeleteModal.value = false;
    },
  });
};
</script>
