<script setup lang="ts">
import { Input as ShadInput } from "../../../components/ui/input"

interface Props {
  modelValue: string | number;
  type?: string;
  placeholder?: string;
  disabled?: boolean;
  error?: boolean;
}

withDefaults(defineProps<Props>(), {
  modelValue: "",
  type: "text",
  placeholder: "",
  disabled: false,
  error: false
});

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void;
}>();
</script>

<template>
  <ShadInput
    :value="modelValue"
    :type="type"
    :placeholder="placeholder"
    :disabled="disabled"
    :class="{ 'border-red-500 focus-visible:ring-red-500': error }"
    @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
  />
</template> 