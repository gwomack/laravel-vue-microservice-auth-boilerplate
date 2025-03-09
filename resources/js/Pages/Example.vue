<script setup lang="ts">
import Button from '@/Components/UI/Button.vue'
import Input from '@/Components/UI/Input.vue'
import { ref } from 'vue'

const username = ref('')
const password = ref('')

const handleSubmit = () => {
  // Handle form submission
  console.log('Form submitted:', { username: username.value, password: password.value })
}
</script>

<template>
  <div class="max-w-md mx-auto mt-8 p-6 bg-card rounded-lg shadow-sm">
    <form @submit.prevent="handleSubmit" class="space-y-4">
      <div class="space-y-2">
        <label for="username" class="text-sm font-medium">Username</label>
        <Input
          id="username"
          v-model="username"
          placeholder="Enter your username"
        />
      </div>

      <div class="space-y-2">
        <label for="password" class="text-sm font-medium">Password</label>
        <Input
          id="password"
          v-model="password"
          type="password"
          placeholder="Enter your password"
        />
      </div>

      <div class="flex gap-4">
        <Button type="submit">
          Submit
        </Button>

        <Button variant="secondary">
          Cancel
        </Button>

        <Button variant="destructive">
          Delete
        </Button>
      </div>

      <!-- Example of different button variants -->
      <div class="flex flex-wrap gap-4 mt-8">
        <Button variant="outline">
          Outline
        </Button>

        <Button variant="ghost">
          Ghost
        </Button>

        <Button variant="link">
          Link
        </Button>

        <Button size="sm">
          Small
        </Button>

        <Button size="lg">
          Large
        </Button>
      </div>
    </form>
  </div>
</template> 