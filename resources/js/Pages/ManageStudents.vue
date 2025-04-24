<script setup>
import { Head, Link } from "@inertiajs/vue3";
import NavLayout from "@/Layouts/NavLayout.vue";

defineProps({ students: Array });
</script>

<template>
    <Head title="Manage Students" />
    <NavLayout>
        <main class="container mx-auto py-8">
            <!-- Page Header -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-primary-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <h1 class="text-2xl font-bold text-primary-900">Manage Students</h1>
                    </div>
                    <Link 
                        href="/add-student" 
                        class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors flex items-center"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Student
                    </Link>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-primary-50 rounded-lg p-4 border border-primary-100">
                        <div class="text-primary-600 text-sm font-medium mb-1">Total Students</div>
                        <div class="text-3xl font-bold text-primary-900">{{ students.length }}</div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4 border border-green-100">
                        <div class="text-green-600 text-sm font-medium mb-1">Active Students</div>
                        <div class="text-3xl font-bold text-green-900">{{ students.filter(s => s.is_active).length }}</div>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                        <div class="text-blue-600 text-sm font-medium mb-1">Recent Registrations</div>
                        <div class="text-3xl font-bold text-blue-900">{{ students.filter(s => {
                            const date = new Date(s.created_at);
                            const now = new Date();
                            const diffTime = Math.abs(now - date);
                            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                            return diffDays <= 7;
                        }).length }}</div>
                    </div>
                </div>
            </div>

            <!-- Students Table -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="border-b border-primary-100 p-6 bg-primary-50">
                    <h2 class="text-lg font-semibold text-primary-900">All Students</h2>
                </div>

                <div v-if="students.length === 0" class="text-center py-16">
                    <svg class="w-20 h-20 text-primary-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <h3 class="text-xl font-medium text-primary-700 mb-2">No registered students</h3>
                    <p class="text-primary-500 mb-6">Add students so they can start learning</p>
                </div>

                <div v-else class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-primary-100">
                        <thead class="bg-primary-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">Student</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">Registration Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">Courses</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-primary-100">
                            <tr v-for="student in students" :key="student.id" class="hover:bg-primary-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center">
                                                <span class="text-primary-700 font-medium">{{ student.name ? student.name.charAt(0).toUpperCase() : 'U' }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-primary-900">{{ student.name }}</div>
                                            <div class="text-xs text-primary-500">ID: {{ student.id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-primary-900">{{ student.email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-primary-900">{{ new Date(student.created_at).toLocaleDateString() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-primary-900">{{ student.courses_count || 0 }} courses</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span 
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" 
                                        :class="student.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                                    >
                                        {{ student.is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <Link 
                                        :href="route('student.show', { id: student.id })" 
                                        class="text-primary-600 hover:text-primary-900 p-1"
                                        title="View"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </Link>
                                    <Link 
                                        :href="route('student.edit', { id: student.id })" 
                                        class="text-indigo-600 hover:text-indigo-900 p-1"
                                        title="Edit"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </Link>
                                    <Link 
                                        :href="route('student.destroy', { id: student.id })" 
                                        method="delete"
                                        as="button"
                                        class="text-red-600 hover:text-red-900 p-1"
                                        title="Delete"
                                        onclick="return confirm('Are you sure you want to delete this student?')"
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
