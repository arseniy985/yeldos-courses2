<template>
  <svg 
    xmlns="http://www.w3.org/2000/svg" 
    :width="size" 
    :height="size" 
    viewBox="0 0 24 24" 
    :fill="fill" 
    stroke="currentColor" 
    stroke-width="2" 
    stroke-linecap="round" 
    stroke-linejoin="round" 
    class="icon icon-school"
  >
    <path d="M22 9L12 5 2 9l10 4 10-4zm0 0v6"></path>
    <path d="M6 10.6V16a6 6 0 0 0 12 0v-5.4"></path>
  </svg>
</template>

<script setup>
defineProps({
  size: {
    type: [Number, String],
    default: 24
  },
  fill: {
    type: String,
    default: 'none'
  }
});
</script> 