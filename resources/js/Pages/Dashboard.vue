<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Card from "@/Components/Card.vue";
import Calendar from "@/Components/Lessons/Calendar.vue";
import { Link } from "@inertiajs/vue3";
import MessageData = App.Data.MessageData;
import ArticleData = App.Data.ArticleData;

interface Props {
    now: string;
    teams: string;
    receivedMessages: MessageData[];
    articles: ArticleData[];
}

defineProps<Props>();
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Dashboard</h2>
        </template>

        <div class="mb-2 grid grid-cols-2 gap-4 overflow-hidden sm:rounded-lg">
            <div class="flex flex-col gap-4">
                <Card>
                    <template #header> Nyheder </template>

                    <div class="max-h-[400px] min-h-[20px] overflow-y-auto">
                        <template
                            v-for="article in articles"
                            :key="article.id">
                            <Link
                                :href="route('articles.show', article.id)"
                                class="hover:bg-custom-primary flex items-center justify-between gap-4 border-b border-b-gray-600 p-2 text-sm text-white transition-all">
                                {{ article.title }}
                            </Link>
                        </template>
                    </div>
                </Card>

                <Card>
                    <template #header>Beskeder</template>

                    <div class="max-h-[400px] min-h-[20px] overflow-y-auto">
                        <template
                            v-for="message in receivedMessages"
                            :key="message.id">
                            <Link
                                :href="route('messages.show', message.id)"
                                class="hover:bg-custom-primary flex items-center justify-between gap-4 border-b border-b-gray-600 p-2 text-sm text-white transition-all">
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
    </AppLayout>
</template>
