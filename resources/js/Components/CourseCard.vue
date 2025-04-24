<template>
    <div class="course-card h-full transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
        <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-primary-100 h-full flex flex-col">
            <!-- Изображение курса -->
            <div class="relative overflow-hidden aspect-video">
                <img
                    :src="thumbnail || ''"
                    alt="Course thumbnail"
                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                />
                <!-- Ценовая метка -->
                <div class="absolute bottom-0 right-0 bg-primary-600 text-white px-3 py-1 text-sm font-semibold rounded-tl-lg">
                    <template v-if="Number(orig_price) > Number(current_price)">
                        <div class="flex items-center">
                            <span class="text-primary-200 line-through mr-2 text-xs">
                                {{ orig_price }} ₸
                            </span>
                            <span>{{ current_price }} ₸</span>
                            <span class="ml-2 bg-yellow-500 text-white text-xs px-1.5 py-0.5 rounded-md">
                                -{{ Math.round((Number(orig_price) - Number(current_price)) / Number(orig_price) * 100) }}%
                            </span>
                        </div>
                    </template>
                    <template v-else>
                        <span>{{ current_price }} ₸</span>
                    </template>
                </div>
            </div>
            
            <!-- Информация о курсе -->
            <div class="p-4 flex-1 flex flex-col">
                <h3 class="text-lg font-bold text-primary-900 line-clamp-2 mb-2">
                    {{ title }}
                </h3>
                
                <!-- Информация о преподавателе -->
                <div class="flex items-center mt-auto pt-3 border-t border-primary-100">
                    
                    <div>
                        <p class="text-sm text-primary-700">{{ instructor }}</p>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, toRefs } from "vue";
import Star from "@/Components/Icons/StarIcon.vue";
import StarHalfFull from "@/Components/Icons/StarHalfIcon.vue";

const props = defineProps({
    title: String,
    instructor: String,
    image: String,
    thumbnail: String,
    rating: String,
    overview: String,
    current_price: String,
    orig_price: String,
});

const {
    title,
    instructor,
    image,
    thumbnail,
    rating,
    current_price,
    orig_price,
} = toRefs(props);
</script>

<style scoped>
.course-card {
    backface-visibility: hidden;
}
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
