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
        <main class="container mx-auto py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="md:col-span-2 bg-gray-800 rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4 text-white">
                        Course Overview
                    </h2>
                    <p
                        class="text-white mb-4 flex items-center justify-center text-justify"
                    >
                        {{ course.overview }}
                    </p>
                </div>
                <div class="bg-gray-800 rounded-lg p-6">
                    <img
                        :src="course.thumbnail"
                        alt="Course Image"
                        class="mb-4 rounded-lg"
                    />
                    <h2 class="text-xl font-semibold mb-4 text-white">
                        {{ course.title }}
                    </h2>
                    <div>
                        <img
                            class="rounded-full m-1.5 mt-2 flex items-baseline w-8 h-8"
                            :src="`https://picsum.photos/${
                                course.id + 190
                            }/300`"
                        />
                    </div>
                    <div class="px-1.5 text-white mt-1">
                        <div
                            class="text-[14px] text-gray-300 font-extrabold flex gap-1 items-center cursor-pointer"
                        >
                            {{ course.instructor }}
                        </div>
                        <div class="flex">
                            <Star fillColor="#FFD700" :size="17" />
                            <Star fillColor="#FFD700" :size="17" />
                            <Star fillColor="#FFD700" :size="17" />
                            <Star fillColor="#FFD700" :size="17" />
                            <StarHalfFull fillColor="#FFD700" :size="17" />
                        </div>
                        <div class="text-lg mb-1 mt-3 text-gray-300">
                            <span
                                class="text-gray-400 line-through"
                                v-if="course.orig_price > course.current_price"
                                >Php {{ course.orig_price }}</span
                            >
                            Php {{ course.current_price }}
                        </div>
                    </div>
                    <button
                        class="mt-3 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-800 duration-300"
                    >
                        Enroll Now
                    </button>
                </div>
                <div class="md:col-span-3 bg-gray-800 rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4 text-white">
                        Episodes List
                    </h2>
                    <ul class="space-y-4">
                        <div v-for="episode in episodes" :key="episode">
                            <Link
                                :href="
                                    route('course.watchepisode', {
                                        id: episode.id,
                                    })
                                "
                                class="flex items-center justify-center cursor-pointer"
                            >
                                <li
                                    class="w-full flex items-center space-x-4 hover:bg-gray-900 rounded-lg"
                                >
                                    <div class="flex-shrink-0">
                                        <img
                                            class="h-40 w-60 rounded-md"
                                            :src="episode.episode_thumbnail"
                                            alt="Video Thumbnail"
                                        />
                                    </div>
                                    <div class="flex-grow">
                                        <h3
                                            class="text-lg font-semibold text-white"
                                        >
                                            {{ episode.episode_title }}
                                        </h3>
                                        <p class="text-gray-400">
                                            {{
                                                trimLongText(
                                                    episode.episode_description,
                                                    150
                                                )
                                            }}
                                        </p>
                                    </div>
                                </li>
                            </Link>
                        </div>
                    </ul>
                </div>
                
                <!-- Секция с тестами курса -->
                <div v-if="tests && tests.length > 0" class="md:col-span-3 bg-gray-800 rounded-lg p-6 mt-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-white">
                            Тесты для курса
                        </h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="test in tests" :key="test.id" 
                            class="bg-gray-700 border border-gray-600 rounded-lg shadow-lg hover:shadow-2xl transition p-6">
                            <h3 class="text-lg font-semibold text-white mb-3">{{ test.title }}</h3>
                            
                            <div class="mt-2 text-sm text-gray-300">
                                Количество вопросов: {{ test.questions_count || 'не указано' }}
                            </div>
                            
                            <div class="mt-4 flex justify-end">
                                <Link :href="route('student.test.take', { id: test.id })" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                    Пройти тест
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </NavLayout>
</template>
