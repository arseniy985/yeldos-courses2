<script setup>
import { Head, Link } from "@inertiajs/vue3";
import NavLayout from "@/Layouts/NavLayout.vue";

defineProps({ courses: Array });
</script>
<template>
    <Head title="Manage Courses" />
    <NavLayout>
        <main class="container mx-auto py-8">
            <!-- Page header -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8 flex items-center">
                <svg class="w-8 h-8 text-primary-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <h1 class="text-2xl font-bold text-primary-900">Manage Courses</h1>
                
                <Link :href="route('addCourse')" class="ml-auto bg-primary-600 text-white px-4 py-2 rounded-lg flex items-center hover:bg-primary-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Course
                </Link>
            </div>

            <!-- Course list -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="border-b border-primary-100 p-6 bg-primary-50">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-primary-900">All Courses</h2>
                        <div class="text-sm text-primary-600">
                            {{ courses.length }} courses total
                        </div>
                    </div>
                </div>
                
                <div v-if="courses.length === 0" class="text-center py-12">
                    <svg class="w-16 h-16 text-primary-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <h3 class="text-xl font-medium text-primary-700 mb-2">No courses available</h3>
                    <p class="text-primary-500 mb-4">Get started by creating your first course.</p>
                    <Link :href="route('addCourse')" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create Course
                    </Link>
                </div>

                <div v-else class="divide-y divide-primary-100">
                    <div v-for="course in courses" :key="course.id" class="p-6 hover:bg-primary-50 transition-colors">
                        <div class="flex flex-col md:flex-row items-start md:items-center">
                            <div class="md:w-1/5 mb-4 md:mb-0 mr-6">
                                <img 
                                    class="rounded-lg shadow-sm w-full object-cover h-32"
                                    :src="course.thumbnail || '/images/course-placeholder.jpg'"
                                    :alt="course.title"
                                />
                            </div>
                            
                            <div class="flex-1">
                                <h3 class="text-xl font-semibold text-primary-900 mb-2">{{ course.title }}</h3>
                                <div class="flex items-center text-primary-600 mb-2">
                                    <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span>{{ course.instructor }}</span>
                                </div>
                                <p class="text-primary-700 mb-4 line-clamp-2">
                                    {{ course.overview ? course.overview.substring(0, 150) + (course.overview.length > 150 ? '...' : '') : 'No description available' }}
                                </p>
                                
                                <div class="flex flex-wrap gap-2">
                                    <Link :href="route('course.show', { id: course.id })" class="inline-flex items-center px-3 py-1.5 bg-primary-100 text-primary-700 rounded-lg hover:bg-primary-200 transition-colors">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View
                                    </Link>
                                    
                                    <Link :href="route('course.tests', { courseId: course.id })" class="inline-flex items-center px-3 py-1.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                                        Tests
                                                    </Link>
                                    
                                                    <Link
                                        :href="route('course.destroy', { id: course.id })"
                                                        as="button"
                                                        method="delete"
                                        class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                                        onclick="return confirm('Are you sure you want to delete this course?')"
                                                    >
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                                        Delete
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
