<script setup>
import { Head, Link } from "@inertiajs/vue3";
import NavLayout from "@/Layouts/NavLayout.vue";

defineProps({ courses: Array });
</script>

<template>
    <Head title="Manage Courses" />
    <NavLayout>
        <main class="container mx-auto py-8">
            <!-- Page Header -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-primary-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <h1 class="text-2xl font-bold text-primary-900">Manage Courses</h1>
                    </div>
                    <Link 
                        href="/add-course" 
                        class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors flex items-center"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Course
                    </Link>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-primary-50 rounded-lg p-4 border border-primary-100">
                        <div class="text-primary-600 text-sm font-medium mb-1">Total Courses</div>
                        <div class="text-3xl font-bold text-primary-900">{{ courses.length }}</div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4 border border-green-100">
                        <div class="text-green-600 text-sm font-medium mb-1">Active Courses</div>
                        <div class="text-3xl font-bold text-green-900">{{ courses.filter(c => c.is_published).length }}</div>
                    </div>
                    <div class="bg-orange-50 rounded-lg p-4 border border-orange-100">
                        <div class="text-orange-600 text-sm font-medium mb-1">In Development</div>
                        <div class="text-3xl font-bold text-orange-900">{{ courses.filter(c => !c.is_published).length }}</div>
                    </div>
                </div>
            </div>

            <!-- Courses Table -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="border-b border-primary-100 p-6 bg-primary-50">
                    <h2 class="text-lg font-semibold text-primary-900">All Courses</h2>
                </div>

                <div v-if="courses.length === 0" class="text-center py-16">
                    <svg class="w-20 h-20 text-primary-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <h3 class="text-xl font-medium text-primary-700 mb-2">No courses available</h3>
                    <p class="text-primary-500 mb-6">Create your first course to start teaching</p>
                    <Link 
                        href="/add-course" 
                        class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors inline-flex items-center"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create First Course
                    </Link>
                </div>

                <div v-else class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-primary-100">
                        <thead class="bg-primary-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">Preview</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">Title</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">Instructor</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">Episodes</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">Tests</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-primary-100">
                            <tr v-for="course in courses" :key="course.id" class="hover:bg-primary-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="w-16 h-12 bg-primary-100 rounded-md overflow-hidden">
                                        <img 
                                            :src="course.thumbnail ? `/storage/${course.thumbnail}` : '/images/placeholder-course.png'" 
                                            :alt="course.title"
                                            class="w-full h-full object-cover"
                                        />
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-primary-900">{{ course.title }}</div>
                                    <div class="text-xs text-primary-500">ID: {{ course.id }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-primary-900">{{ course.instructor }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-primary-900">{{ course.episodes_count || 0 }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span 
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" 
                                        :class="course.is_published ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800'"
                                    >
                                        {{ course.is_published ? 'Published' : 'In Development' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <Link 
                                        :href="route('tests', { courseId: course.id })"
                                        class="text-sm text-primary-600 hover:text-primary-900 flex items-center"
                                    >
                                        <span>{{ course.tests_count || 0 }} tests</span>
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </Link>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <Link 
                                        :href="route('course.show', { id: course.id })" 
                                        class="text-primary-600 hover:text-primary-900 p-1"
                                        title="View"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </Link>
                                    <Link 
                                        :href="route('course.edit', { id: course.id })" 
                                        class="text-indigo-600 hover:text-indigo-900 p-1"
                                        title="Edit"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </Link>
                                    <Link 
                                        :href="route('course.destroy', { id: course.id })" 
                                        method="delete"
                                        as="button"
                                        class="text-red-600 hover:text-red-900 p-1"
                                        title="Delete"
                                        onclick="return confirm('Are you sure you want to delete this course?')"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </NavLayout>
</template> 