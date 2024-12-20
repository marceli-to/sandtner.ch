<template>
  <div class="w-full flex flex-col space-y-15 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-10 md:gap-y-20 lg:gap-x-30 lg:gap-y-20">
    <button
      type="button"
      v-for="time in times"
      :key="time"
      @click="selectTime(time)"
      :class="[
        'w-full flex justify-between font-bold uppercase tracking-wider border border-graphite text-graphite px-15 py-5 hover:!border-graphite hover:text-white hover:bg-graphite transition-all',
        modelValue === time
          ? '!border-tangerine bg-tangerine text-white'
          : ''
      ]"
    >
      {{ time }} Uhr
    </button>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue'

const times = ['11:30', '14:30', '15:15', '16:45']

const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['update:modelValue'])

const selectTime = (time) => {
  emit('update:modelValue', time)
}
</script>