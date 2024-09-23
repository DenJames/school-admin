<script setup lang="ts">
import AppLayout from "../../Layouts/AppLayout.vue";
import Card from "../../Components/Card.vue";
import { Link } from "@inertiajs/vue3";
import { route } from "../../../../vendor/tightenco/ziggy";
import Checkmark from "../../Components/Icons/Checkmark.vue";
import Pencil from "../../Components/Icons/Pencil.vue";
import MessageData = App.Data.MessageData;

interface Props {
    messages: MessageData[];
    receivedMessages: MessageData[];
}

const props = defineProps<Props>();
</script>

<template>
    <AppLayout title="Messages">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Messages</h2>
        </template>

        <div class="relative grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6">
            <Card>
                <template #header>
                    Sent Messages

                    <Link
                        :href="route('messages.create')"
                        class="absolute right-1 top-1 rounded-md p-1 transition-all hover:bg-gray-800">
                        <Pencil class="size-5" />
                    </Link>
                </template>

                <div class="max-h-[500px] overflow-y-auto">
                    <template
                        v-for="message in messages"
                        :key="message.id">
                        <Link
                            :href="route('messages.show', message.id)"
                            class="flex items-center justify-between gap-4 border-b border-b-gray-600 p-2 text-sm text-white transition-all hover:bg-gray-700">
                            <p class="flex items-center gap-2 truncate">
                                {{ message.subject }}
                                <span
                                    v-if="message.readAt"
                                    class="text-nowrap text-xs text-white/50">
                                    <Checkmark class="w-4" />
                                </span>
                            </p>

                            <div class="flex items-center gap-2">
                                <span
                                    v-if="message.receiver"
                                    class="text-nowrap text-xs text-white/50">
                                    Receiver: {{ message.receiver.name }}
                                </span>
                            </div>
                        </Link>
                    </template>
                </div>
            </Card>

            <Card>
                <template #header>Received messages</template>

                <div class="max-h-[500px] overflow-y-auto">
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
    </AppLayout>
</template>

<style scoped></style>
