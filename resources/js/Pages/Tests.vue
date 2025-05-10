<script setup>
import { Head, Link } from "@inertiajs/vue3";
import NavLayout from "@/Layouts/NavLayout.vue";

defineProps({ tests: Array, courseId: Number, course: Object });
</script>

<template>
    <Head title="Tests" />
    <NavLayout>
        <main class="container mx-auto py-8">
            <!-- Page header -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-primary-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h1 class="text-2xl font-bold text-primary-900">
                            Тесты для курса: {{ course?.title || 'Загрузка...' }}
                        </h1>
                    </div>
                    <Link 
                        :href="route('test.create', { id: courseId })" 
                        class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors flex items-center"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Создать тест
                    </Link>
                </div>
            </div>

            <!-- Tests list -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="border-b border-primary-100 p-6 bg-primary-50">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-primary-900">Доступные тесты</h2>
                        <div class="text-sm text-primary-600">
                            {{ tests.length }} тестов всего
                        </div>
                    </div>
                </div>
                
                <div v-if="tests.length === 0" class="text-center py-12">
                    <svg class="w-16 h-16 text-primary-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-xl font-medium text-primary-700 mb-2">Нет доступных тестов</h3>
                    <p class="text-primary-500 mb-6">Создайте новый тест для этого курса</p>
                    <Link 
                        :href="route('test.create', { id: courseId })" 
                        class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors inline-flex items-center"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Создать первый тест
                    </Link>
                </div>
                
                <div v-else class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                    <div v-for="test in tests" :key="test.id" class="bg-white border border-primary-100 rounded-xl shadow-sm hover:shadow-md transition-shadow p-6">
                        <div class="flex items-start justify-between mb-4">
                            <h3 class="text-lg font-semibold text-primary-800">{{ test.test_name }}</h3>
                            <span class="px-2 py-1 bg-primary-100 text-primary-800 text-xs font-medium rounded-full">{{ test.questions_count }} вопросов</span>
                        </div>
                        <p class="text-primary-600 text-sm mb-6">
                            {{ test.description || 'Нет описания' }}
                        </p>
                        <div class="flex space-x-2">
                            <Link 
                                :href="route('test.show', { id: test.id })" 
                                class="px-3 py-2 bg-primary-600 text-white text-sm rounded-lg hover:bg-primary-700 transition-colors flex-1 text-center"
                            >
                                Пройти тест
                            </Link>
                            <Link 
                                :href="route('test.edit', { id: test.id })" 
                                class="px-3 py-2 bg-white border border-primary-300 text-primary-700 text-sm rounded-lg hover:bg-primary-50 transition-colors flex items-center justify-center"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </Link>
                            <Link 
                                :href="route('test.destroy', { id: test.id })" 
                                method="delete"
                                as="button"
                                class="px-3 py-2 bg-white border border-red-300 text-red-700 text-sm rounded-lg hover:bg-red-50 transition-colors flex items-center justify-center"
                                onclick="return confirm('Вы уверены, что хотите удалить этот тест?')"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </NavLayout>
</template> 