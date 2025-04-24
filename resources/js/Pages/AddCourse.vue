<script setup>
import { ref } from "vue";
import { Head, router } from "@inertiajs/vue3";
import NavLayout from "@/Layouts/NavLayout.vue";

defineProps({ errors: Object });

let course_title = ref("");
let course_thumbnail = ref("");
let course_instructor = ref("");
let course_price = ref("");
let course_overview = ref("");

let error = ref({
    course_title: null,
    course_thumbnail: null,
    course_instructor: null,
    course_price: null,
    course_overview: null,
});

const episodes = ref([
    {
        thumbnail: null,
        video: null,
        title: "",
        description: "",
    },
]);

const addCourse = () => {
    let err = false;

    error.value.course_title = null;
    error.value.course_thumbnail = null;
    error.value.course_instructor = null;
    error.value.course_price = null;
    error.value.course_overview = null;

    if (!course_title.value) {
        error.value.course_title = "Please enter Course Title";
        err = true;
    }
    if (!course_thumbnail.value) {
        error.value.course_thumbnail = "Please select a thumbnail";
        err = true;
    }
    if (!course_instructor.value) {
        error.value.course_instructor = "Please enter Course Instructor";
        err = true;
    }

    if (!course_price.value) {
        error.value.course_price = "Please enter Course Price";
        err = true;
    }

    if (!course_overview.value) {
        error.value.course_overview = "Please enter Course Overview";
        err = true;
    }

    if (err) {
        return;
    }

    let data = new FormData();

    data.append("title", course_title.value);
    data.append("instructor", course_instructor.value);
    data.append("thumbnail", course_thumbnail.value);
    data.append("current_price", course_price.value);
    data.append("course_overview", course_overview.value);
    episodes.value = episodes.value.map((episode, index) => {
        data.append(`episodes[${index}][thumbnail]`, episode.thumbnail);
        data.append(`episodes[${index}][video]`, episode.video);
        data.append(`episodes[${index}][title]`, episode.title);
        data.append(`episodes[${index}][description]`, episode.description);
        return episode; // Return the modified episode to keep reactivity
    });

    router.post("/addcourse", data, {
        onSuccess: (page) => {
            // Redirect to create test page for newly created course
            if (page.props.course && page.props.course.id) {
                router.visit(route('course.tests.create', { courseId: page.props.course.id }));
            } else {
                router.visit(route('manageCourses'));
            }
        }
    });
};

const handleThumbnailChange = (index, e) => {
    const file = e.target.files[0];
    episodes.value[index].thumbnail = file;
};

const handleVideoChange = (index, e) => {
    const file = e.target.files[0];
    episodes.value[index].video = file;
};

const getImage = (e) => (course_thumbnail.value = e.target.files[0]);

const addEpisode = () => {
    episodes.value.push({
        thumbnail: null,
        video: null,
        title: "",
        description: "",
    });
};

const removeEpisode = (index) => {
    episodes.value.splice(index, 1);
};
</script>

<template>
    <Head title="Add Course" />

    <NavLayout>
        <div class="max-w-5xl mx-auto">
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <h2 class="text-2xl font-bold text-primary-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 text-primary-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add New Course
                </h2>
                
                    <form class="w-full" @submit.prevent="addCourse">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-primary-900 text-sm font-medium mb-2" for="course-title">
                                Course Title <span class="text-red-600">*</span>
                                </label>
                                <input
                                class="w-full px-4 py-2 border border-primary-200 rounded-lg focus:ring focus:ring-primary-200 focus:border-primary-500"
                                id="course-title"
                                    type="text"
                                placeholder="Enter course title"
                                    v-model="course_title"
                                />
                            <p v-if="error.course_title" class="text-red-500 text-xs mt-1">
                                    {{ error.course_title }}
                                </p>
                            </div>
                        
                        <div>
                            <label class="block text-primary-900 text-sm font-medium mb-2" for="course-instructor">
                                Instructor <span class="text-red-600">*</span>
                                </label>
                                <input
                                class="w-full px-4 py-2 border border-primary-200 rounded-lg focus:ring focus:ring-primary-200 focus:border-primary-500"
                                id="course-instructor"
                                    type="text"
                                placeholder="Instructor name"
                                    v-model="course_instructor"
                                />
                            <p v-if="error.course_instructor" class="text-red-500 text-xs mt-1">
                                    {{ error.course_instructor }}
                                </p>
                            </div>
                        </div>
                    
                    <div class="mb-6">
                        <label class="block text-primary-900 text-sm font-medium mb-2" for="course-overview">
                            Course Overview <span class="text-red-600">*</span>
                                </label>
                                <textarea
                                    v-model="course_overview"
                            id="course-overview"
                            rows="4"
                            class="w-full px-4 py-2 border border-primary-200 rounded-lg focus:ring focus:ring-primary-200 focus:border-primary-500"
                            placeholder="Enter course description"
                        ></textarea>
                        <p v-if="error.course_overview" class="text-red-500 text-xs mt-1">
                                    {{ error.course_overview }}
                                </p>
                            </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-primary-900 text-sm font-medium mb-2" for="course-price">
                                Course Price <span class="text-red-600">*</span>
                                </label>
                                <input
                                class="w-full px-4 py-2 border border-primary-200 rounded-lg focus:ring focus:ring-primary-200 focus:border-primary-500"
                                id="course-price"
                                    type="number"
                                    min="0.1"
                                    step="0.1"
                                placeholder="Course price"
                                    v-model="course_price"
                                />
                            <p v-if="error.course_price" class="text-red-500 text-xs mt-1">
                                    {{ error.course_price }}
                                </p>
                            </div>
                        
                        <div>
                            <label class="block text-primary-900 text-sm font-medium mb-2" for="course-thumbnail">
                                Course Thumbnail <span class="text-red-600">*</span>
                            </label>
                            <div class="flex items-center">
                                <label class="flex items-center justify-center px-4 py-2 bg-primary-50 text-primary-700 rounded-lg border border-primary-200 cursor-pointer hover:bg-primary-100 transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    Choose File
                                    <input
                                        @change="
                                            course_thumbnail = $event.target.files[0]
                                        "
                                        class="hidden"
                                        id="course-thumbnail"
                                        type="file"
                                        accept="image/*"
                                    />
                                </label>
                                <span v-if="course_thumbnail" class="ml-2 text-sm text-primary-700">
                                    {{ course_thumbnail.name }}
                                </span>
                            </div>
                            <p v-if="error.course_thumbnail" class="text-red-500 text-xs mt-1">
                                    {{ error.course_thumbnail }}
                                </p>
                            </div>
                        </div>
                    
                    <!-- Course episodes -->
                    <div class="mt-8 mb-6">
                        <h3 class="text-xl font-semibold text-primary-900 mb-4">Course Episodes</h3>
                        
                        <div v-for="(episode, index) in episodes" :key="index" class="bg-primary-50 p-5 rounded-lg mb-4 border border-primary-100">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="font-medium text-primary-800">Episode {{ index + 1 }}</h4>
                                <button
                                    v-if="index > 0" 
                                    type="button"
                                    @click="episodes.splice(index, 1)" 
                                    class="text-red-500 hover:text-red-700"
                                >
                                    Remove
                                </button>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-primary-900 text-sm font-medium mb-2">
                                        Episode Title
                                    </label>
                                                <input
                                        v-model="episode.title"
                                                    type="text"
                                        class="w-full px-4 py-2 border border-primary-200 rounded-lg focus:ring focus:ring-primary-200 focus:border-primary-500"
                                        placeholder="Episode title"
                                    />
                                </div>
                                
                                <div>
                                    <label class="block text-primary-900 text-sm font-medium mb-2">
                                        Episode Video
                                    </label>
                                    <div class="flex items-center">
                                        <label class="flex items-center justify-center px-4 py-2 bg-white text-primary-700 rounded-lg border border-primary-200 cursor-pointer hover:bg-primary-50 transition-colors">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                            Select Video
                                                <input
                                                @change="
                                                    episode.video = $event.target.files[0]
                                                "
                                                class="hidden"
                                                type="file"
                                                accept="video/*"
                                            />
                                        </label>
                                        <span v-if="episode.video" class="ml-2 text-sm text-primary-700">
                                            {{ episode.video.name }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-primary-900 text-sm font-medium mb-2">
                                    Episode Description
                                </label>
                                <textarea
                                    v-model="episode.description"
                                    rows="3"
                                    class="w-full px-4 py-2 border border-primary-200 rounded-lg focus:ring focus:ring-primary-200 focus:border-primary-500"
                                    placeholder="Episode description"
                                ></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-primary-900 text-sm font-medium mb-2">
                                    Episode Thumbnail
                                </label>
                                <div class="flex items-center">
                                    <label class="flex items-center justify-center px-4 py-2 bg-white text-primary-700 rounded-lg border border-primary-200 cursor-pointer hover:bg-primary-50 transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Select Image
                                                <input
                                                    @change="
                                                episode.thumbnail = $event.target.files[0]
                                                    "
                                            class="hidden"
                                            type="file"
                                                    accept="image/*"
                                                />
                                    </label>
                                    <span v-if="episode.thumbnail" class="ml-2 text-sm text-primary-700">
                                        {{ episode.thumbnail.name }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                            <button
                            type="button"
                            @click="episodes.push({
                                thumbnail: null,
                                video: null,
                                title: '',
                                description: '',
                            })"
                            class="flex items-center text-primary-600 hover:text-primary-700 mt-2"
                        >
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Episode
                        </button>
                    </div>
                    
                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 transition-colors">
                            Create Course
                            </button>
                        </div>
                    </form>
                </div>
            </div>
    </NavLayout>
</template>
