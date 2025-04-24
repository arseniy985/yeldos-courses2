<script setup>
import { Head } from "@inertiajs/vue3";
import NavLayout from "@/Layouts/NavLayout.vue";

defineProps({ student: Array });
</script>
<template>
    <Head title="Student Information" />
    <NavLayout>
        <main class="container mx-auto py-8">
            <!-- Page header -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-primary-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <h1 class="text-2xl font-bold text-primary-900">Student Information: {{ student.name }}</h1>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Basic information -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-semibold text-primary-900 mb-4 border-b border-primary-100 pb-2">Basic Information</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-primary-500 mb-1">Name:</p>
                            <p class="text-primary-900 font-medium">{{ student.name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-primary-500 mb-1">Email:</p>
                            <p class="text-primary-900 font-medium">{{ student.email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-primary-500 mb-1">Registration Date:</p>
                            <p class="text-primary-900 font-medium">{{ student.created_at }}</p>
                        </div>
                    </div>
                </div>

                <!-- Additional information -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-semibold text-primary-900 mb-4 border-b border-primary-100 pb-2">Additional Information</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-primary-500 mb-1">Account ID:</p>
                            <p class="text-primary-900 font-medium">{{ student.id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-primary-500 mb-1">Role:</p>
                            <p class="text-primary-900 font-medium">{{ student.role || 'Student' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-primary-500 mb-1">Auth Type:</p>
                            <p class="text-primary-900 font-medium">{{ student.auth_type || 'Standard' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student's courses -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-lg font-semibold text-primary-900 mb-4 border-b border-primary-100 pb-2">
                    Enrolled Courses
                </h2>
                <div v-if="student.courses && student.courses.length > 0">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div v-for="course in student.courses" :key="course.id" class="bg-primary-50 rounded-lg p-4 border border-primary-100">
                            <h3 class="font-medium text-primary-900">{{ course.title }}</h3>
                            <p class="text-sm text-primary-600">Progress: {{ course.progress || '0%' }}</p>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-8">
                    <svg class="w-12 h-12 text-primary-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <p class="text-primary-500">Student is not enrolled in any courses</p>
                </div>
            </div>
        </main>
    </NavLayout>
</template>
