<template>
  <div class="relative">
    <Error :error="error" />
    <textarea
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      @focus="$emit('update:error', '')"
      :placeholder="placeholder"
      v-bind="$attrs"
      :class="[
        $attrs.class,
        { '!border-red-600': error }
      ]">
    </textarea>
  </div>
</template>

<script setup>
import Error from './error.vue';

defineOptions({
  inheritAttrs: false
})

const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  },
  placeholder: {
    type: String,
    default: ''
  },
  error: {
    type: String,
    default: ''
  }
});

defineEmits(['update:modelValue', 'update:error']);
</script>