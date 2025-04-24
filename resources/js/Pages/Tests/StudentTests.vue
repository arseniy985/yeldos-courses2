<template>
  <NavLayout>
    <div class="py-8">
      <div class="max-w-7xl mx-auto">
        <!-- Breadcrumbs navigation -->
        <div class="flex items-center mb-6 text-sm">
          <Link :href="route('home')" class="text-primary-600 hover:text-primary-800 transition">
            Home
          </Link>
          <span class="mx-2 text-primary-400">›</span>
          <span class="text-primary-800 font-medium">Available Tests</span>
        </div>

        <!-- Page header -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8 flex justify-between items-center">
          <div class="flex items-center">
            <svg class="w-8 h-8 text-primary-600 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <h1 class="text-2xl font-bold text-primary-900">Available Tests</h1>
          </div>
          <Link :href="route('student.test-results')" 
                class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition flex items-center">
            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            My Results
          </Link>
        </div>
        
        <!-- No tests message -->
        <div v-if="tests.length === 0" class="bg-white rounded-xl shadow-sm p-12 text-center">
          <svg class="w-16 h-16 text-primary-300 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <h2 class="text-xl font-semibold text-primary-700 mb-4">No available tests</h2>
          <p class="text-primary-500 mb-6">Enroll in courses to access their tests</p>
          <Link :href="route('home')" 
                class="px-5 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-medium">
            Browse Courses
          </Link>
        </div>
        
        <!-- Tests by course -->
        <div v-else class="space-y-8">
          <div v-for="(courseTests, course) in groupedTests" :key="course" class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="bg-primary-50 border-b border-primary-100 px-6 py-4">
              <h3 class="font-semibold text-primary-900 text-lg">{{ course }}</h3>
            </div>
            
            <div class="p-6 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
              <div v-for="test in courseTests" :key="test.id" 
                  class="bg-white border border-primary-100 rounded-xl shadow-sm hover:shadow-md transition p-5">
                <div class="mb-4">
                  <h4 class="text-lg font-medium text-primary-900 mb-2">{{ test.title }}</h4>
                  <div class="flex items-center text-primary-600">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ test.questions_count || 0 }} questions</span>
                  </div>
                </div>
                
                <div v-if="test.result" class="mb-4 p-3 rounded-lg" 
                    :class="test.result.passed ? 'bg-green-50 border border-green-100' : 'bg-red-50 border border-red-100'">
                  <div class="flex items-center mb-1" :class="test.result.passed ? 'text-green-700' : 'text-red-700'">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" v-if="test.result.passed">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" v-else>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-medium">{{ test.result.passed ? 'Test passed' : 'Test failed' }}</span>
                  </div>
                  <div class="text-sm text-primary-700">
                    Score: {{ test.result.percentage }}% ({{ test.result.correct_answers }}/{{ test.result.total_questions }})
                  </div>
                </div>
                
                <div class="flex justify-end">
                  <Link v-if="test.result && test.result.passed" 
                        :href="route('student.test.result', { id: test.result.id })"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center">
                    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    View Results
                  </Link>
                  <Link v-else
                        :href="route('student.test.take', { id: test.id })"
                        class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition flex items-center">
                    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                    </svg>
                    {{ test.result ? 'Retake Test' : 'Take Test' }}
                  </Link>
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

// Group tests by courses
const groupedTests = computed(() => {
  const grouped = {};
  
  props.tests.forEach(test => {
    const courseName = test.course ? test.course.title : 'No Course';
    
    if (!grouped[courseName]) {
      grouped[courseName] = [];
    }
    
    grouped[courseName].push(test);
  });
  
  return grouped;
});
</script> 