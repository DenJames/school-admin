<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Calendar from "@/Components/lesson/calendar.vue";

interface Props {
    now: string;
}

defineProps<Props>();
</script>

<template>
    <AppLayout title="Lessons">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Lessons</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <Calendar :now="now" />
            </div>
        </div>
    </AppLayout>
</template>
