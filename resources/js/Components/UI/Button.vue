<script setup lang="ts">
import { Button as ShadButton } from "../../../components/ui/button"

interface Props {
  variant?: "default" | "destructive" | "outline" | "secondary" | "ghost" | "link"
  size?: "default" | "sm" | "lg" | "icon"
  type?: "button" | "submit" | "reset"
  disabled?: boolean
}

withDefaults(defineProps<Props>(), {
  variant: "default",
  size: "default",
  type: "button",
  disabled: false
})
</script>

<template>
  <ShadButton
    :variant="variant"
    :size="size"
    :type="type"
    :disabled="disabled"
    class="font-semibold"
  >
    <slot />
  </ShadButton>
</template> 