<script setup lang="ts">
interface Props {
    value?: string;
}

defineProps<Props>();
</script>

<template>
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
