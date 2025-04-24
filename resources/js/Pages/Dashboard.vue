<script setup>
import NavLayout from "@/Layouts/NavLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Dashboard" />
    <NavLayout>
        <main class="container mx-auto py-8">
            <!-- Page header -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-primary-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <h1 class="text-2xl font-bold text-primary-900">Dashboard</h1>
                </div>
            </div>

            <!-- Main content -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <!-- Information card -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-semibold text-primary-900 mb-4 border-b border-primary-100 pb-2">Welcome!</h2>
                    <p class="text-primary-600">You are successfully logged in. Statistics and important information will be displayed here.</p>
                </div>

                <!-- Activity card -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-semibold text-primary-900 mb-4 border-b border-primary-100 pb-2">Recent Activity</h2>
                    <p class="text-primary-600">Information about recent actions and events will be displayed here.</p>
                </div>

                <!-- Notifications card -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-semibold text-primary-900 mb-4 border-b border-primary-100 pb-2">Notifications</h2>
                    <p class="text-primary-600">You have no new notifications.</p>
                </div>
            </div>

            <!-- Detailed information section -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-lg font-semibold text-primary-900 mb-4 border-b border-primary-100 pb-2">Detailed Information</h2>
                <p class="text-primary-600">Detailed information about courses, students, and other aspects of the platform will be displayed here.</p>
            </div>
        </main>
    </NavLayout>
</template>
