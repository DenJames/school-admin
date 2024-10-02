<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import CreateGroupForm from "@/Pages/Groups/Partials/CreateGroupForm.vue";
</script>

<template>
    <AppLayout title="Opret Gruppe">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Opret Gruppe</h2>
        </template>

        <div>
            <div class="mx-auto max-w-7xl py-10 sm:px-6 lg:px-8">
                <CreateGroupForm />
            </div>
        </div>
    </AppLayout>
</template>
