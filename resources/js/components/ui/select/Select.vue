<template>
  <select v-model="selected" @change="$emit('update:modelValue', selected)" :class="cn(
      'placeholder:text-muted-foreground p-4 selection:bg-primary selection:text-primary-foreground dark:bg-input/30 border-input flex min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none  disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm',
      'focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]',
      'aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive',
      props.class,
    )">
      <option v-for="option in options" :key="option.value" :value="option.value">
        {{ option.label }}
      </option>
    </select>
</template>

<script setup>
import { ref, watch } from 'vue';
import cn from 'classnames';

const props = defineProps({
  modelValue: [String, Number],
  options: {
    type: Array,
    default: () => [],
  },
  
});

const emits = defineEmits(['update:modelValue']);

const selected = ref(props.modelValue);

// Watch for external changes to modelValue
watch(() => props.modelValue, (val) => {
  selected.value = val;
});
</script>