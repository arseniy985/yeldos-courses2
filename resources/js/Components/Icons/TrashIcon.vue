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
    class="icon icon-trash"
  >
    <path d="M3 6h18"></path>
    <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6"></path>
    <path d="M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2"></path>
    <line x1="10" y1="11" x2="10" y2="17"></line>
    <line x1="14" y1="11" x2="14" y2="17"></line>
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