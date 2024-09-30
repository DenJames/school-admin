<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Calendar from "@/Components/Lessons/Calendar.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";

interface Props {
    now: string;
    uuid: string;
}

const props = defineProps<Props>();

const export_route = route("lessons.export", props.uuid);
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <Calendar :now="now" />
                <p class="mt-2 dark:text-white">Eksporter kalender</p>
                <TextInput
                    :model-value="export_route"
                    disabled
                    class="mt-1 w-full"></TextInput>
            </div>
        </div>
    </AppLayout>
</template>
