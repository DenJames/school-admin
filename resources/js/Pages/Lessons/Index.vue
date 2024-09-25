<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Card from "@/Components/Card.vue";

interface Props {
    lesson: object;
}

const props = defineProps<Props>();
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">{{ lesson.name }}</h2>
        </template>
        <Card>
            <template #header> Lesson name </template>
            <div class="p-2">
                <h1>{{ lesson.name }}</h1>
            </div>
        </Card>
    </AppLayout>
</template>
