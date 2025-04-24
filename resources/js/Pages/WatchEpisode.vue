<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import NavLayout from "@/Layouts/NavLayout.vue";
import Play from "@/Components/Icons/PlayIcon.vue";

defineProps({
    episode_details: Object,
    other_episodes: Array,
});

const page = usePage();

const trimLongText = (string, length) => {
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
    <Head title="Watch Episode" />
    <NavLayout>
        <main class="container mx-auto py-8">
            <!-- Page header -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-primary-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h1 class="text-2xl font-bold text-primary-900">Watch Episode</h1>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8">
                <!-- Video player -->
                <div class="bg-white rounded-xl shadow-md p-6 flex justify-center items-center">
                    <video
                        :src="episode_details.episode_video"
                        class="w-full max-w-[1000px] rounded-lg"
                        controls
                        autoplay
                    />
                </div>

                <!-- Episode information -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-primary-900 mb-3">
                        {{ episode_details.episode_title }}
                    </h2>
                    <div class="flex items-center mb-4 pb-4 border-b border-primary-100">
                        <img
                            class="rounded-full w-8 h-8 mr-2"
                            :src="`https://picsum.photos/${episode_details.course_id + 190}/300`"
                            alt="Instructor"
                        />
                        <div class="text-primary-700 font-medium">
                            {{ episode_details.instructor }}
                        </div>
                    </div>
                    <p class="text-primary-600 text-justify">
                        {{ episode_details.episode_description }}
                    </p>
                </div>

                <!-- All episodes list -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-primary-900 mb-6 pb-2 border-b border-primary-100">
                        All Episodes
                    </h2>
                    
                    <ul class="space-y-6">
                        <div v-for="(other, index) in other_episodes" :key="other.id">
                            <Link
                                :href="route('course.watchepisode', { id: other.id })"
                                class="block"
                            >
                                <li
                                    class="flex items-start space-x-4 rounded-lg p-3 transition-colors"
                                    :class="page.url == '/watch/' + other.id ? 'bg-primary-50 border border-primary-100' : 'hover:bg-gray-50'"
                                >
                                    <div class="flex-shrink-0">
                                        <img
                                            class="h-32 w-56 rounded-md object-cover"
                                            :src="other.episode_thumbnail"
                                            alt="Video Thumbnail"
                                        />
                                    </div>
                                    <div class="flex-grow">
                                        <h3 class="text-lg font-medium text-primary-900 mb-1">
                                            {{ other.episode_title }}
                                        </h3>
                                        <p class="text-primary-600 text-sm mb-2">
                                            {{ trimLongText(other.episode_description, 150) }}
                                        </p>
                                        <div
                                            v-if="page.url == '/watch/' + other.id"
                                            class="text-primary-600 font-medium flex items-center mt-2"
                                        >
                                            <Play fillColor="#4F46E5" :size="20" />
                                            <span class="ml-1">Now Playing</span>
                                        </div>
                                    </div>
                                </li>
                            </Link>
                        </div>
                    </ul>
                </div>
            </div>
        </main>
    </NavLayout>
</template>
