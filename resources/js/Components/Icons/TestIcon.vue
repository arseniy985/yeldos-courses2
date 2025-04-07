<template>
    <svg
        :width="size"
        :height="size"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        :fill="fillColor"
    >
        <path d="M17 4v6h-2V7H9v10h4v2H7V5h8V2l4 2zm1 8h-1v1h-1v-1h-1v-1h1v-1h1v1h1v1zm2 4h-6v-1h6v1zm0 2h-6v-1h6v1z" />
    </svg>
</template>

<script setup>
defineProps({
    size: {
        type: Number,
        default: 24,
    },
    fillColor: {
        type: String,
        default: "currentColor",
    },
});
</script> 