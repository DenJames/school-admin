<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Calendar from "@/Components/Lessons/Calendar.vue";

interface Props {
    now: string;
}

defineProps<Props>();
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <Calendar :now="now" />
            </div>
        </div>
    </AppLayout>
</template>
