<template>
    <div class="relative">
    <select 
      :value="modelValue" 
      @input="updateValue($event.target.value)">
      <option v-for="option in options" :key="option.value" :value="option.value">
        {{ option.label }}
      </option>
    </select>
    <div class="pointer-events-none absolute right-8 top-1/2 -translate-y-1/2">
      <svg width="17" height="11" viewBox="0 0 17 11" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M1 1L8.5 9L16 1" stroke="#F18700" stroke-width="2"/>
      </svg>
    </div>
  </div>
  <div v-if="error" class="text-red-600 absolute bottom-5 right-0 text-xxs">{{ error }}</div>
</template>

<script setup>
const props = defineProps({
  options: {
    type: [Array, Object],
    required: true,
  },
  modelValue: {
    type: String,
    default: '',
  },
  error: {
    type: String,
    default: '',
  },
});

const emit = defineEmits(['update:modelValue']);

const updateValue = (value) => {
  emit('update:modelValue', value);
};
</script>