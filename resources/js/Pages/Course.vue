<script setup>
import { Head, Link } from "@inertiajs/vue3";
import NavLayout from "@/Layouts/NavLayout.vue";
import Star from "@/Components/Icons/StarIcon.vue";
import StarHalfFull from "@/Components/Icons/StarHalfIcon.vue";

defineProps({
    course: Object,
    episodes: Array,
    tests: Array,
});

const trimLongText = (string, length) => {
    // Check if the string exists
    if (!string) {
        return ""; // Return empty string if value is undefined, null or empty
    }
    
    if (string.length <= length) {
        return string;
    } else {
        const trimmedString = string.slice(0, length).trim();
        const trimmedStringWithEllipsis = trimmedString + "...";

        return trimmedStringWithEllipsis;
    }
};
</script>

<template>
    <Head :title="course.title" />
    <NavLayout>
        <!-- Hero section with course information -->
        <div class="bg-gradient-to-r from-primary-50 to-primary-100 py-8 px-4 rounded-xl mb-8">
            <div class="container mx-auto">
                <div class="flex flex-col md:flex-row items-center gap-8">
                    <!-- Course image -->
                    <div class="md:w-1/3">
                        <img 
                            :src="course.thumbnail" 
                            :alt="course.title" 
                            class="w-full h-auto rounded-xl shadow-lg object-cover"
                        />
                    </div>
                    
                    <!-- Course information -->
                    <div class="md:w-2/3">
                        <h1 class="text-3xl md:text-4xl font-bold text-primary-900 mb-3">{{ course.title }}</h1>
                        
                        <div class="flex items-center text-primary-700 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                            </svg>
                            <span class="font-medium">{{ course.instructor }}</span>
                        </div>
                        
                        <div class="flex flex-wrap gap-4 mb-6">
                            <div class="bg-white px-4 py-2 rounded-lg shadow-sm">
                                <div class="text-xs text-primary-500 mb-1">Duration</div>
                                <div class="flex items-center text-primary-800 font-semibold">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    {{ episodes?.length || 0 }} lessons
                                </div>
                            </div>
                            
                            <div class="bg-white px-4 py-2 rounded-lg shadow-sm">
                                <div class="text-xs text-primary-500 mb-1">Tests</div>
                                <div class="flex items-center text-primary-800 font-semibold">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                                    </svg>
                                    {{ tests?.length || 0 }} tests
                                </div>
                            </div>
                            
                            <div class="bg-white px-4 py-2 rounded-lg shadow-sm">
                                <div class="text-xs text-primary-500 mb-1">Price</div>
                                <div class="text-primary-800 font-bold">
                                    {{ course.current_price }} ₸
                                </div>
                            </div>
                        </div>
                        
                        <button class="bg-primary-600 text-white px-8 py-3 rounded-lg font-medium hover:bg-primary-700 transition-colors shadow-md flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                            </svg>
                            Enroll in the course
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Course content -->
        <div class="container mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main content (episodes) -->
                <div class="lg:col-span-2">
                    <!-- About the course -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                        <h2 class="text-xl font-semibold text-primary-900 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            About the course
                        </h2>
                        <p class="text-primary-700 leading-relaxed">{{ course.overview }}</p>
                    </div>
                    
                    <!-- Course episodes -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-xl font-semibold text-primary-900 mb-6 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-600" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M7 4a3 3 0 016 0v4a3 3 0 11-6 0V4z" />
                                <path d="M5.5 9.643a.5.5 0 01-.5.5H3a.5.5 0 01-.5-.5V9a4 4 0 014-4V4a5 5 0 00-5 5v.643a1.5 1.5 0 001.5 1.5H4l6 6 3-3-4.5-4.5v-1a3 3 0 016 0v1a.5.5 0 01-.5.5H13a.5.5 0 01-.5-.5V9a4 4 0 01-4-4h-.5a.5.5 0 01-.5.5zm14 0a.5.5 0 01-.5.5H17a.5.5 0 01-.5-.5V9a4 4 0 00-1-2.646V11.5a.5.5 0 01-.5.5H14a.5.5 0 01-.5-.5v-7a.5.5 0 01.5-.5h3a.5.5 0 01.5.5v.5a5 5 0 012 4v.643z" />
                            </svg>
                            Course content ({{ episodes?.length || 0 }} lessons)
                        </h2>
                        
                        <div class="space-y-4">
                            <div v-for="(episode, index) in episodes" :key="episode.id">
                                <Link :href="route('course.watchepisode', { id: episode.id })" 
                                      class="block hover:bg-primary-50 rounded-xl transition-colors border border-primary-100 overflow-hidden">
                                    <div class="flex items-start">
                                        <!-- Episode number -->
                                        <div class="bg-primary-600 text-white font-semibold h-full flex items-center justify-center p-4 md:p-6">
                                            <span class="text-lg md:text-xl">{{ index + 1 }}</span>
                                        </div>
                                        
                                        <!-- Episode information -->
                                        <div class="flex-1 p-4 md:p-6">
                                            <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                                                <h3 class="font-medium text-primary-900 text-lg">{{ episode.title || 'Episode ' + (index + 1) }}</h3>
                                                <div class="mt-2 md:mt-0 flex items-center text-primary-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="text-sm">{{ episode.duration || '15:00' }}</span>
                                                </div>
                                            </div>
                                            <p class="text-sm text-primary-600 mt-2" v-if="episode.description">{{ trimLongText(episode.description, 150) }}</p>
                                            <p class="text-sm text-primary-500 mt-2" v-else>Watch this episode to gain new knowledge about the course.</p>
                                            
                                            <div class="mt-3 flex items-center">
                                                <div class="bg-primary-100 text-primary-600 text-xs px-2 py-1 rounded-full font-medium">
                                                    {{ episode.video_type || 'Video lesson' }}
                                                </div>
                                                <div class="ml-2 flex items-center text-primary-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="text-xs">Watch</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </Link>
                            </div>
                            
                            <div v-if="!episodes || !episodes.length" class="py-8 text-primary-500 text-center">
                                <svg class="w-16 h-16 mx-auto mb-4 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-primary-700 mb-2">No episodes available yet</h3>
                                <p class="text-primary-500">Lessons will be added soon.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Tests -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                        <h2 class="text-xl font-semibold text-primary-900 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-600" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                            </svg>
                            Course tests
                        </h2>
                        
                        <div class="space-y-3">
                            <div v-for="(test, index) in tests" :key="test.id" 
                                 class="p-4 border border-primary-100 rounded-lg hover:bg-primary-50 transition-colors">
                                <div class="flex justify-between items-center">
                                    <span class="text-primary-800 font-medium">{{ test.title }}</span>
                                    <Link :href="route('student.test.take', { id: test.id })" 
                                          class="text-primary-50 bg-primary-600 hover:bg-primary-700 px-3 py-1 rounded-md text-sm font-medium transition-colors">
                                        Start test
                                    </Link>
                                </div>
                            </div>
                            
                            <div v-if="!tests.length" class="py-4 text-primary-500 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2 text-primary-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p>Tests for this course are not available yet</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </NavLayout>
</template>
