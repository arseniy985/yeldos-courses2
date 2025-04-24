<template>
  <NavLayout>
    <main class="container mx-auto py-8">
      <!-- Page header -->
      <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <div class="flex items-center">
          <svg class="w-8 h-8 text-primary-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
          </svg>
          <h1 class="text-2xl font-bold text-primary-900">Test Results</h1>
        </div>
      </div>
      
      <!-- Breadcrumb navigation -->
      <div class="bg-white rounded-xl shadow-md p-4 mb-8">
        <div class="flex items-center text-sm">
          <Link :href="route('home')" class="text-primary-600 hover:text-primary-800 transition">
            Home
          </Link>
          <span class="mx-2 text-primary-400">›</span>
          <Link :href="route('student.tests')" class="text-primary-600 hover:text-primary-800 transition">
            Available Tests
          </Link>
          <span class="mx-2 text-primary-400">›</span>
          <span class="text-primary-900 font-medium">My Results</span>
        </div>
      </div>
      
      <!-- Main content -->
      <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 border-b border-primary-100">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-primary-900">Test Results</h2>
            <Link 
              :href="route('student.tests')" 
              class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition"
            >
              Back to Tests
            </Link>
          </div>
          
          <div v-if="results.length === 0" class="text-center py-16">
            <svg class="w-16 h-16 text-primary-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
            </svg>
            <h3 class="text-xl font-medium text-primary-700 mb-2">You haven't completed any tests yet</h3>
            <p class="text-primary-500 mb-6">Take tests to check your knowledge</p>
            <Link 
              :href="route('student.tests')" 
              class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition"
            >
              Browse Available Tests
            </Link>
          </div>
          
          <div v-else>
            <div class="grid grid-cols-1 gap-6">
              <div 
                v-for="result in results" 
                :key="result.id" 
                class="bg-white border border-primary-100 rounded-lg shadow-sm hover:shadow-md transition p-6"
              >
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                  <div>
                    <h3 class="text-lg font-semibold text-primary-900">{{ result.test.title }}</h3>
                    <p class="text-primary-600 text-sm mt-1">Course: {{ result.test.course.title }}</p>
                  </div>
                  <div 
                    :class="result.passed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" 
                    class="px-3 py-1 rounded-full text-sm font-medium mt-2 md:mt-0"
                  >
                    {{ result.passed ? 'Passed' : 'Failed' }}
                  </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                  <div class="bg-primary-50 p-4 rounded-lg text-center border border-primary-100">
                    <div class="text-sm text-primary-600 mb-1">Overall Result</div>
                    <div class="text-2xl font-bold text-primary-900">{{ result.percentage }}%</div>
                  </div>
                  
                  <div class="bg-primary-50 p-4 rounded-lg text-center border border-primary-100">
                    <div class="text-sm text-primary-600 mb-1">Correct Answers</div>
                    <div class="text-2xl font-bold text-primary-900">{{ result.correct_answers }} of {{ result.total_questions }}</div>
                  </div>
                  
                  <div class="bg-primary-50 p-4 rounded-lg text-center border border-primary-100">
                    <div class="text-sm text-primary-600 mb-1">Completion Date</div>
                    <div class="text-lg font-medium text-primary-900">
                      {{ new Date(result.created_at).toLocaleDateString() }}
                    </div>
                  </div>
                </div>
                
                <div class="flex justify-end mt-4">
                  <Link 
                    :href="route('student.test.result', { id: result.id })" 
                    class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition flex items-center"
                  >
                    <span>View Details</span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </NavLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import NavLayout from "@/Layouts/NavLayout.vue";

defineProps({
  results: Array
});
</script> 