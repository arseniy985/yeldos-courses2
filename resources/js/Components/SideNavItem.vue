<template>
    <li
        :class="[!openSideNav ? 'p-2' : 'flex items-center p-2']"
        class="text-white text-sm font-semibold text-center hover:bg-gray-900 rounded-lg cursor-pointer"
    >
        <div :class="[!openSideNav ? 'w-full flex justify-center' : '']">
            <component :is="icon" />
        </div>
        <div v-if="openSideNav" class="mt-1 ml-4">
            <span>{{ iconString }}</span>
        </div>
    </li>
</template>

<script setup>
import { defineProps, toRefs } from "vue";

import Home from "@/Components/Icons/HomeIcon.vue";
import SchoolIcon from "@/Components/Icons/SchoolIcon.vue";
import LibraryIcon from "@/Components/Icons/LibraryIcon.vue";
import BookPlusIcon from "@/Components/Icons/BookPlusIcon.vue";
import BookMultipleIcon from "@/Components/Icons/BookMultipleIcon.vue";
import TestIcon from "@/Components/Icons/TestIcon.vue";

const props = defineProps({
    openSideNav: Boolean,
    iconString: String,
});

const { openSideNav, iconString } = toRefs(props);

let icon = null;

if (iconString.value === "Home") icon = Home;
if (iconString.value === "Courses") icon = SchoolIcon;
if (iconString.value === "My Library") icon = LibraryIcon;
if (iconString.value === "Manage Students") icon = SchoolIcon;
if (iconString.value === "Create Course") icon = BookPlusIcon;
if (iconString.value === "Manage Courses") icon = BookMultipleIcon;
 if (iconString.value === "Tests") icon = TestIcon;
</script>
