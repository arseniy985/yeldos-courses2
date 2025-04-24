<template>
  <NavLayout>
    <main class="container mx-auto py-8">
      <!-- Page header -->
      <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <div class="flex items-center">
          <svg class="w-8 h-8 text-primary-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
          </svg>
          <h1 class="text-2xl font-bold text-primary-900">Tests for Course: {{ course.title }}</h1>
        </div>
      </div>

      <!-- Breadcrumb navigation -->
      <div class="bg-white rounded-xl shadow-md p-4 mb-8">
        <div class="flex items-center text-sm">
          <Link :href="route('home')" class="text-primary-600 hover:text-primary-800 transition">
            Home
          </Link>
          <span class="mx-2 text-primary-400">›</span>
          <Link :href="route('manageCourses')" class="text-primary-600 hover:text-primary-800 transition">
            Manage Courses
          </Link>
          <span class="mx-2 text-primary-400">›</span>
          <span class="text-primary-900 font-medium">Tests for Course: {{ course.title }}</span>
        </div>
      </div>
      
      <!-- Main content -->
      <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 border-b border-primary-100">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-primary-900">Test List</h2>
            <Link :href="route('course.tests.create', { courseId: course.id })"
                  class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              Create Test
            </Link>
          </div>

          <div v-if="tests.length === 0" class="text-center py-12">
            <svg class="w-16 h-16 text-primary-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
            </svg>
            <h3 class="text-xl font-medium text-primary-700 mb-2">No tests available for this course</h3>
            <p class="text-primary-500 mb-6">Create your first test to check your students' knowledge</p>
            <Link :href="route('course.tests.create', { courseId: course.id })"
                  class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition inline-flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              Create First Test
            </Link>
          </div>

          <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="test in tests" :key="test.id"
                class="bg-white border border-primary-100 rounded-lg shadow-sm hover:shadow-md transition p-6">
              <div class="flex justify-between items-start">
                <h3 class="text-lg font-semibold text-primary-900">{{ test.title }}</h3>
                <div class="flex items-center space-x-2">
                  <Link :href="route('tests.edit', { id: test.id })" class="text-primary-600 hover:text-primary-900 p-1" title="Edit">
                    <PencilIcon :size="18" />
                  </Link>
                  <button @click="confirmDeleteTest(test)" class="text-red-600 hover:text-red-900 p-1" title="Delete">
                    <TrashIcon :size="18" />
                  </button>
                </div>
              </div>

              <div class="mt-2 text-sm text-primary-600">
                Questions: {{ test.questions_count || 0 }}
              </div>

              <div class="mt-4 flex justify-between items-center">
                <span class="text-xs text-primary-500">
                  Created: {{ new Date(test.created_at).toLocaleDateString() }}
                </span>
                <Link :href="route('tests.edit', { id: test.id })"
                      class="text-sm text-primary-600 hover:text-primary-900 flex items-center">
                  <span>Edit</span>
                  <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                  </svg>
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Delete confirmation modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 max-w-md w-full shadow-xl">
        <h3 class="text-lg font-semibold mb-4 text-primary-900">Confirm Deletion</h3>
        <p class="mb-6 text-primary-600">Are you sure you want to delete the test "{{ testToDelete?.title }}"? This action cannot be undone.</p>
        <div class="flex justify-end space-x-3">
          <button @click="showDeleteModal = false"
                  class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
            Cancel
          </button>
          <button @click="deleteTest"
                  class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
            Delete
          </button>
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
