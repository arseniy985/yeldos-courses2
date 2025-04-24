<template>
    <li
        :class="[!openSideNav ? 'justify-center' : 'flex items-center']"
        class="px-3 py-2.5 text-primary-700 text-sm font-medium rounded-lg hover:bg-primary-50 cursor-pointer transition-colors duration-200"
    >
        <div v-if="openSideNav" class="mr-3 flex-1">
            {{ iconString }}
        </div>
        <div class="flex items-center justify-center w-8 h-8 text-primary-600">
            <component :is="icon" />
        </div>
    </li>
</template>

<script setup>
import { defineProps, toRefs } from "vue";
import { IconComponents } from "@/Components/Icons";

const props = defineProps({
    openSideNav: Boolean,
    iconString: String,
});

const { openSideNav, iconString } = toRefs(props);

let icon = null;

if (iconString.value === "Home") icon = IconComponents.HomeOutlineIcon;
if (iconString.value === "Courses") icon = IconComponents.BookOpenIcon;
if (iconString.value === "My Library") icon = IconComponents.BookOpenIcon;
if (iconString.value === "Manage Students") icon = IconComponents.UsersIcon;
if (iconString.value === "Create Course") icon = IconComponents.PlusCircleIcon;
if (iconString.value === "Manage Courses") icon = IconComponents.CollectionIcon;
if (iconString.value === "Tests") icon = IconComponents.ClipboardCheckIcon;
</script>
