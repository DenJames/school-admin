<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Card from "@/Components/Card.vue";
import Calendar from "@/Components/lesson/calendar.vue";

interface Props {
    now: string;
    lessons: any[];
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
                <div class="grid grid-cols-2 gap-2 overflow-hidden bg-white shadow-xl sm:rounded-lg dark:bg-gray-800">
                    <Card>
                        <template #header> Aktuelt </template>
                        <p class="p-2 text-white">Her kommer der til at være nyheder eller noget</p>
                    </Card>
                    <Card>
                        <template #header> Kommunikation </template>
                        <p class="p-2 text-white">Beskeder</p>
                    </Card>
                    <Card>
                        <template #header> Undervisning </template>
                        <p class="p-2 text-white">Foxy was here UwU</p>
                    </Card>
                    <Card>
                        <template #header> Skema </template>
                        <Calendar
                            :now="now"
                            :events="lessons"
                            initialView="listWeek" />
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
