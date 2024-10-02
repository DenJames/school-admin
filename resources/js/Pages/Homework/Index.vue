<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import List from "../../Components/Homework/List.vue";
import Card from "../../Components/Card.vue";
import HomeworkData = App.Data.HomeworkData;

interface Props {
    homework: HomeworkData[];
}

defineProps<Props>();
</script>

<template>
    <AppLayout title="Lektier">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Lektier</h2>
        </template>

        <Card>
            <template #header>Lektier</template>

            <div class="w-full overflow-x-auto">
                <List :homework="homework" />
            </div>
        </Card>
    </AppLayout>
</template>
