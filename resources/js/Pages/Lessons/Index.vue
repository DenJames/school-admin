<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Calendar from "@/Components/Lessons/Calendar.vue";
import TextInput from "@/Components/TextInput.vue";
import { ref } from "vue";

interface Props {
    now: string;
    uuid: string;
}

const props = defineProps<Props>();

const export_route = props.uuid ? route("lessons.export", props.uuid) : null;
const copySuccessful = ref(false);

function copyExportRouteUrl() {
    console.log(export_route);
    if (export_route) {
        if (navigator.clipboard.writeText(export_route)) {
            copySuccessful.value = true;
            setTimeout(() => {
                copySuccessful.value = false;
            }, 3000);
        }
    }
}
</script>

<template>
    <AppLayout title="Skema">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Skema</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <Calendar :now="now" />
                <p class="mt-2 dark:text-white">Eksporter kalender</p>
                <TextInput
                    v-if="export_route"
                    :model-value="export_route"
                    readonly
                    class="mt-1 w-full"
                    @click="copyExportRouteUrl"></TextInput>
                <p
                    v-if="copySuccessful"
                    class="mt-2 text-green-500">
                    Kopieret til udklipsholder
                </p>
            </div>
        </div>
    </AppLayout>
</template>
