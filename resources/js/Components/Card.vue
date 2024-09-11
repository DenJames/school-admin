<script setup lang="ts"></script>

<template>
    <div class="w-full rounded-lg border border-gray-200 bg-white shadow dark:border-gray-700 dark:bg-gray-800">
        <div
            class="rounded-t-md bg-gray-700 p-1 text-center text-xl font-medium text-gray-500 dark:divide-gray-600 dark:text-gray-400">
            <slot name="header" />
        </div>
        <div class="border-t border-gray-200 dark:border-gray-600">
            <slot />
        </div>
    </div>
</template>

<style scoped></style>
