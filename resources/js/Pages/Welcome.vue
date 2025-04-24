<script setup>
import { Head, Link } from "@inertiajs/vue3";
import NavLayout from "@/Layouts/NavLayout.vue";
import CourseCard from "@/Components/CourseCard.vue";
import CourseChatbot from "@/Components/CourseChatbot.vue";

defineProps({
    courses: Array,
});
</script>

<template>
    <Head title="Yeldos Courses - Home" />
    <NavLayout>
        <!-- Hero section -->
        <div class="mb-10">
            <div class="bg-gradient-to-r from-primary-500 to-primary-700 rounded-2xl shadow-lg overflow-hidden">
                <div class="max-w-6xl mx-auto px-6 py-12 md:py-16 flex flex-col md:flex-row items-center">
                    <div class="md:w-1/2 text-white md:pr-12 mb-8 md:mb-0">
                        <h1 class="text-3xl md:text-4xl font-bold mb-4">Welcome to Yeldos Courses</h1>
                        <p class="text-primary-100 text-lg mb-6">Find the best courses for your learning and development. Level up your skills with our experts.</p>
                    </div>
                  
                </div>
            </div>
        </div>
        
        <!-- All courses -->
        <div class="w-full max-w-7xl mx-auto p-6">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold text-primary-900">All Courses</h2>
            </div>
            
            <div v-if="courses.length === 0" class="text-center py-12 bg-white rounded-xl shadow">
                <svg class="w-16 h-16 text-primary-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <h3 class="text-xl font-medium text-primary-700 mb-2">No courses available</h3>
                <p class="text-primary-500">Courses will appear here when they are published.</p>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <Link 
                    v-for="course in courses" 
                    :key="course.id" 
                    :href="route('course.show', { id: course.id })"
                    class="no-underline"
                >
                    <CourseCard 
                        :title="course.title"
                        :instructor="course.instructor"
                        :image="course.instructor_image"
                        :thumbnail="course.thumbnail"
                        :rating="course.rating"
                        :overview="course.overview"
                        :current_price="course.current_price"
                        :orig_price="course.original_price || course.current_price"
                    />
                </Link>
            </div>
        </div>

        <!-- Chatbot for course assistance -->
        <CourseChatbot />
    </NavLayout>
</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(99,102,241,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
