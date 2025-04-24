<template>
  <NavLayout>
    <main class="container mx-auto py-8">
      <!-- Page header -->
      <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <div class="flex items-center">
          <svg class="w-8 h-8 text-primary-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
          </svg>
          <h1 class="text-2xl font-bold text-primary-900">Create Test</h1>
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
          <Link :href="route('course.tests', { courseId: course.id })" class="text-primary-600 hover:text-primary-800 transition">
            Tests for Course: {{ course.title }}
          </Link>
          <span class="mx-2 text-primary-400">›</span>
          <span class="text-primary-900 font-medium">Create Test</span>
        </div>
      </div>

      <!-- Main content -->
      <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 border-b border-primary-100">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-primary-900">Create Test for Course: {{ course.title }}</h2>
            <Link :href="route('course.tests', { courseId: course.id })" 
                  class="px-4 py-2 bg-primary-100 text-primary-800 rounded-lg hover:bg-primary-200 transition">
              Back to Test List
            </Link>
          </div>
          
          <form @submit.prevent="submitForm">
            <!-- Basic test data -->
            <div class="mb-6">
              <label for="title" class="block text-sm font-medium text-primary-700 mb-1">Test Title</label>
              <input type="text" id="title" v-model="form.title" 
                     class="w-full px-3 py-2 border border-primary-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 bg-white text-primary-900" 
                     required />
              <div v-if="form.errors.title" class="text-red-600 text-sm mt-1">{{ form.errors.title }}</div>
            </div>
            
            <!-- Questions section -->
            <div class="mb-6">
              <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-primary-900">Questions</h3>
                <button type="button" @click="addQuestion" 
                        class="px-3 py-1 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                  Add Question
                </button>
              </div>
              
              <div v-if="form.questions.length === 0" class="text-center py-12 bg-primary-50 rounded-lg border border-primary-100">
                <svg class="w-16 h-16 text-primary-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <div class="text-primary-600 mb-4">No questions for this test yet</div>
                <button type="button" @click="addQuestion" 
                        class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                  Add First Question
                </button>
              </div>
              
              <!-- Questions list -->
              <div v-for="(question, qIndex) in form.questions" :key="qIndex" class="mb-6 p-5 border border-primary-200 rounded-lg bg-primary-50">
                <div class="flex justify-between items-start mb-4">
                  <div class="w-full">
                    <label :for="'question-'+qIndex" class="block text-sm font-medium text-primary-700 mb-1">
                      Question {{ qIndex + 1 }}
                    </label>
                    <input type="text" :id="'question-'+qIndex" v-model="question.name" 
                          class="w-full px-3 py-2 border border-primary-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 bg-white text-primary-900" 
                          required />
                  </div>
                  <button type="button" @click="removeQuestion(qIndex)" 
                          class="ml-2 text-red-600 hover:text-red-800">
                    <TrashIcon :size="18" />
                  </button>
                </div>
                
                <!-- Answers for question -->
                <div class="ml-4">
                  <div class="flex justify-between items-center mb-2">
                    <h4 class="text-sm font-medium text-primary-700">Answer Options</h4>
                    <button type="button" @click="addAnswer(qIndex)" 
                            class="px-2 py-1 text-xs bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                      Add Answer
                    </button>
                  </div>
                  
                  <div v-if="question.answers.length === 0" class="text-center py-3 bg-white rounded-lg text-sm border border-primary-100">
                    <div class="text-primary-600">Add answer options</div>
                  </div>
                  
                  <div v-for="(answer, aIndex) in question.answers" :key="aIndex" 
                       class="flex items-center mb-2 p-2 border border-primary-200 rounded-md bg-white">
                    <input type="radio" :name="'correct-answer-'+qIndex" :id="'answer-'+qIndex+'-'+aIndex" 
                          :checked="answer.is_valid" @change="setCorrectAnswer(qIndex, aIndex)" 
                          class="mr-2 text-primary-600 focus:ring-primary-500" />
                    <input type="text" v-model="answer.name" 
                          class="flex-grow px-3 py-1 border border-primary-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 text-sm bg-white text-primary-900" 
                          required />
                    <button type="button" @click="removeAnswer(qIndex, aIndex)" 
                            class="ml-2 text-red-600 hover:text-red-800">
                      <TrashIcon :size="16" />
                    </button>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Form buttons -->
            <div class="flex justify-end space-x-3">
              <Link :href="route('course.tests', { courseId: course.id })" 
                    class="px-4 py-2 bg-primary-100 text-primary-800 rounded-lg hover:bg-primary-200 transition">
                Cancel
              </Link>
              <button type="submit" 
                      class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition"
                      :disabled="form.processing">
                Create Test
              </button>
            </div>
          </form>
        </div>
      </div>
    </main>
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

// Add a new question
const addQuestion = () => {
  form.questions.push({
    name: '',
    answers: []
  });
};

// Remove a question
const removeQuestion = (index) => {
  form.questions.splice(index, 1);
};

// Add a new answer option
const addAnswer = (questionIndex) => {
  form.questions[questionIndex].answers.push({
    name: '',
    is_valid: form.questions[questionIndex].answers.length === 0 // First answer is correct by default
  });
};

// Remove an answer option
const removeAnswer = (questionIndex, answerIndex) => {
  // If removing the correct answer and there are other answers, make the first one correct
  const isRemovingCorrect = form.questions[questionIndex].answers[answerIndex].is_valid;
  form.questions[questionIndex].answers.splice(answerIndex, 1);
  
  if (isRemovingCorrect && form.questions[questionIndex].answers.length > 0) {
    form.questions[questionIndex].answers[0].is_valid = true;
  }
};

// Set the correct answer
const setCorrectAnswer = (questionIndex, answerIndex) => {
  // First make all answers incorrect
  form.questions[questionIndex].answers.forEach((answer, idx) => {
    form.questions[questionIndex].answers[idx].is_valid = false;
  });
  
  // Then mark the selected answer as correct
  form.questions[questionIndex].answers[answerIndex].is_valid = true;
};

// Submit the form
const submitForm = () => {
  // Validate the form on client side before submission
  let isValid = true;
  
  // Check if there are questions
  if (form.questions.length === 0) {
    alert('Please add at least one question');
    isValid = false;
    return;
  }
  
  // Check if each question has at least two answers
  for (let i = 0; i < form.questions.length; i++) {
    if (form.questions[i].answers.length < 2) {
      alert(`Question ${i+1} must have at least 2 answer options`);
      isValid = false;
      return;
    }
    
    // Check if the question has at least one correct answer
    const hasCorrectAnswer = form.questions[i].answers.some(answer => answer.is_valid);
    if (!hasCorrectAnswer) {
      alert(`Question ${i+1} must have at least one correct answer`);
      isValid = false;
      return;
    }
  }
  
  if (isValid) {
    console.log('Submitting form:', form);
    form.post(route('tests.store'), {
      onSuccess: (page) => {
        // On successful server response, redirect to the test list page
        if (page.props.flash.success) {
          router.visit(route('course.tests', { courseId: props.course.id }));
        }
      },
      onError: (errors) => {
        console.error('Errors while saving:', errors);
      }
    });
  }
};
</script> 