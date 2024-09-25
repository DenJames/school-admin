<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Card from "@/Components/Card.vue";
import Calendar from "@/Components/Lessons/Calendar.vue";
import { Link } from "@inertiajs/vue3";
import MessageData = App.Data.MessageData;

interface Props {
    now: string;
    teams: string;
    receivedMessages: MessageData[];
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
                <div class="mb-2 grid grid-cols-2 gap-2 overflow-hidden sm:rounded-lg">
                    <div class="flex flex-col gap-2">
                        <Card>
                            <template #header> Aktuelt </template>
                            <p class="p-2 text-white">Her kommer der til at være nyheder eller noget</p>
                        </Card>
                        <Card>
                            <template #header>Received messages</template>

                            <div class="max-h-[500px] min-h-[20px] overflow-y-auto">
                                <template
                                    v-for="message in receivedMessages"
                                    :key="message.id">
                                    <Link
                                        :href="route('messages.show', message.id)"
                                        class="flex items-center justify-between gap-4 border-b border-b-gray-600 p-2 text-sm text-white transition-all hover:bg-gray-700">
                                        <p class="truncate">{{ message.subject }}</p>

                                        <span
                                            v-if="message.sender"
                                            class="text-nowrap text-xs text-white/50">
                                            Sender: {{ message.sender.name }}
                                        </span>
                                    </Link>
                                </template>
                            </div>
                        </Card>
                    </div>
                    <Card>
                        <template #header> Skema </template>
                        <div class="p-2">
                            <Calendar
                                :now="now"
                                initial-view="listWeek" />
                        </div>
                    </Card>
                </div>
                <Card>
                    <template #header> Teams and Groupes </template>
                    <p class="p-2 text-white"><span class="bold">Teams: </span>{{ teams }}</p>
                    <p class="p-2 text-white"><span class="bold">Groupes: </span>None</p>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
