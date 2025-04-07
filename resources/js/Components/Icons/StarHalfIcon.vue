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
    class="icon icon-star-half"
  >
    <path d="M12 17.8L5.8 21 7 14.1 2 9.3 8.9 8.3 12 2"></path>
    <path d="M12 2L15.1 8.3 22 9.3 17 14.1 18.2 21 12 17.8"></path>
    <line x1="12" y1="2" x2="12" y2="17.8"></line>
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
    default: 'currentColor'
  }
});
</script> 