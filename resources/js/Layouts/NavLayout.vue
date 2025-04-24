<script setup>
import { ref, onMounted } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import SideNavItem from "../Components/SideNavItem.vue";
import MenuIcon from "@/Components/Icons/MenuIcon.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";

let openSideNav = ref(true);
let openSideNavOverlay = ref(false);
let sideNavOverlay = ref(null);
let width = ref(document.documentElement.clientWidth);

onMounted(() => {
    resize();
    sideNavOverlay.value.classList.value =
        sideNavOverlay.value.classList.value += " hidden";
    window.addEventListener("resize", () => {
        width.value = document.documentElement.clientWidth;
        resize();
    });
});

const resize = () => {
    if (width.value < 1280 && openSideNav.value) {
        openSideNav.value = false;
    }
    if (width.value > 1279 && !openSideNav.value) {
        openSideNav.value = true;
    }
};

const isNavOverlay = () => {
    if (width.value < 640) {
        openSideNavOverlay.value = !openSideNavOverlay.value;
    } else {
        openSideNav.value = !openSideNav.value;
    }
};
</script>

<template>
    <div class="relative bg-surface-50 min-h-screen">
        <!-- Horizontal navigation -->
        <div
            id="TopNav"
            class="w-full h-[70px] fixed bg-gradient-to-r from-primary-600 to-primary-800 z-20 flex items-center justify-between px-6 shadow-md"
        >
            <div class="flex items-center">
                <button
                    @click="isNavOverlay()"
                    class="p-2 rounded-full hover:bg-primary-700 inline-block cursor-pointer mr-3 lg:hidden transition-colors"
                >
                    <MenuIcon fillColor="#FFFFFF" :size="26" />
                </button>
                <Link
                    :href="route('home')"
                    class="flex items-center justify-center cursor-pointer"
                >
                    <div class="font-bold text-2xl text-white flex items-center">
                        <span class="mr-1">Yeldos</span>
                        <span class="text-primary-200">Courses</span>
                    </div>
                </Link>
            </div>

            <div class="flex items-center">
                <!-- Навигационные ссылки в десктопной версии -->
                <div class="hidden md:flex items-center space-x-6 mr-8">
                    <Link :href="route('home')" class="text-white hover:text-primary-200 transition-colors font-medium">
                        Home
                    </Link>
                    <Link :href="route('student.tests')" class="text-white hover:text-primary-200 transition-colors font-medium">
                        Tests
                    </Link>
                    <Link v-if="$page.props.auth.user.role == 'admin'" :href="route('manageCourses')" class="text-white hover:text-primary-200 transition-colors font-medium">
                        Manage
                    </Link>
                </div>
                
                <!-- User dropdown menu -->
                <Dropdown align="right" width="48">
                    <template #trigger>
                        <button 
                            class="flex items-center text-white bg-primary-700 hover:bg-primary-800 transition-colors rounded-full py-1.5 pl-3 pr-2"
                        >
                            <span class="mr-2 font-medium">{{ $page.props.auth.user.name }}</span>
                            <div class="w-8 h-8 rounded-full bg-primary-200 text-primary-800 flex items-center justify-center uppercase font-bold">
                                {{ $page.props.auth.user.name.charAt(0) }}
                            </div>
                        </button>
                    </template>

                    <template #content>
                        <DropdownLink :href="route('profile.edit')">
                            Profile
                        </DropdownLink>
                        <DropdownLink :href="route('logout')" method="post" as="button">
                            Log Out
                        </DropdownLink>
                    </template>
                </Dropdown>
            </div>
        </div>

        <!-- Side navigation (visible on large screens) -->
        <div class="hidden lg:block fixed top-0 left-0 h-full z-10">
            <div
                id="SideNav"
                class="h-full w-[240px] bg-white shadow-md"
            >
                <div class="h-[70px] flex items-center justify-center bg-primary-50">
                    <div class="font-bold text-xl text-primary-600">Yeldos<span class="text-primary-400">Courses</span></div>
                </div>
                <ul class="mt-6 p-4 space-y-2">
                    <Link :href="route('home')">
                        <SideNavItem
                            :openSideNav="true"
                            iconString="Home"
                        />
                    </Link>

                    <div v-if="$page.props.auth.user.role == 'admin'" class="pt-2">
                        <div class="border-b border-primary-100 my-3"></div>
                        <Link :href="route('addCourse')">
                            <SideNavItem
                                :openSideNav="true"
                                iconString="Create Course"
                            />
                        </Link>
                        <Link :href="route('manageCourses')">
                            <SideNavItem
                                :openSideNav="true"
                                iconString="Manage Courses"
                            />
                        </Link>
                        <Link :href="route('manageStudents')">
                            <SideNavItem
                                :openSideNav="true"
                                iconString="Manage Students"
                            />
                        </Link>
                    </div>
                    <div class="border-b border-primary-100 my-3"></div>
                    <Link :href="route('student.tests')">
                        <SideNavItem
                            :openSideNav="true"
                            iconString="Tests"
                        />
                    </Link>
                </ul>
            </div>
        </div>

        <!-- Mobile menu -->
        <div
            @click="openSideNavOverlay = false"
            class="bg-primary-800 bg-opacity-70 fixed z-50 w-full h-screen"
            :class="
                openSideNavOverlay
                    ? 'animate__animated animate__fadeIn animate__faster'
                    : 'hidden z-[-1]'
            "
        />
        <div
            id="SideNavOverlay"
            ref="sideNavOverlay"
            class="h-full fixed z-50 bg-white shadow-lg mt-[9px] w-[280px]"
            :class="
                openSideNavOverlay
                    ? 'animate__animated animate__slideInLeft animate__faster'
                    : 'animate__animated animate__slideOutLeft animate__faster'
            "
        >
            <div class="flex items-center p-4 border-b border-primary-100">
                <button
                    @click="isNavOverlay()"
                    class="p-2 rounded-full hover:bg-primary-100 cursor-pointer mr-3 transition-colors"
                >
                    <MenuIcon fillColor="#4338CA" :size="26" />
                </button>
                <Link
                    :href="route('home')"
                    class="flex items-center justify-center cursor-pointer"
                >
                    <div class="font-bold text-xl text-primary-700">Yeldos<span class="text-primary-500">Courses</span></div>
                </Link>
            </div>
            
            <ul class="p-4 space-y-1">
                <Link :href="route('home')">
                    <SideNavItem :openSideNav="true" iconString="Home" />
                </Link>
                <div v-if="$page.props.auth.user.role == 'admin'">
                    <div class="border-b border-primary-100 my-3"></div>
                    <Link :href="route('addCourse')">
                        <SideNavItem
                            :openSideNav="true"
                            iconString="Create Course"
                        />
                    </Link>
                    <Link :href="route('manageCourses')">
                        <SideNavItem
                            :openSideNav="true"
                            iconString="Manage Courses"
                        />
                    </Link>
                    <Link :href="route('manageStudents')">
                        <SideNavItem
                            :openSideNav="true"
                            iconString="Manage Students"
                        />
                    </Link>
                </div>
                <div class="border-b border-primary-100 my-3"></div>
                <Link :href="route('student.tests')">
                    <SideNavItem
                        :openSideNav="true"
                        iconString="Tests"
                    />
                </Link>
            </ul>
        </div>

        <!-- Main content -->
        <div
            class="pt-[70px] lg:pl-[240px]"
        >
            <div class="max-w-7xl mx-auto p-4">
                <slot />
            </div>
        </div>
    </div>
</template>
