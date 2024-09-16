<script setup lang="ts">
import AppLayout from "../../Layouts/AppLayout.vue";
import Card from "../../Components/Card.vue";
import { Link, router, useForm, usePage } from "@inertiajs/vue3";
import { route } from "../../../../vendor/tightenco/ziggy";
import Checkmark from "../../Components/Icons/Checkmark.vue";
import { computed, onMounted } from "vue";
import Reload from "../../Components/Icons/Reload.vue";
import MessageData = App.Data.MessageData;
import ReplyData = App.Data.ReplyData;

interface Props {
    message: MessageData & { replies: ReplyData[] };
}

const props = defineProps<Props>();

const user = computed(() => usePage().props.auth.user);

const form = useForm({
    subject: props.message.subject,
    body: props.message.content,
    receiver: props.message.receiver,
    readAt: props.message.readAt,
});

function refreshMessages() {
    console.log("refresh");

    router.reload({ only: ["message"] });
}

function send() {
    console.log("send");

    refreshMessages();
}

onMounted(() => {
    console.log(user.value, "user");
});
</script>

<template>
    <AppLayout title="Messages">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">{{ message.subject }}</h2>
        </template>

        <Card>
            <template #header>
                Message

                <button
                    class="absolute right-2 top-1 rounded-md p-1 transition-all hover:bg-gray-800"
                    @click="refreshMessages">
                    <Reload class="size-5" />
                </button>
            </template>

            <div class="max-h-[500px] overflow-y-auto">
                <Link
                    :href="route('messages.index')"
                    class="flex items-center justify-between gap-4 border-b p-2 text-sm text-white transition-all hover:bg-gray-700">
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
            </div>
        </Card>

        <Card content-classes="mt-4">
            <template #header>
                Replies

                <button
                    class="absolute right-2 top-1 rounded-md p-1 transition-all hover:bg-gray-800"
                    @click="refreshMessages">
                    <Reload class="size-5" />
                </button>
            </template>

            <div class="max-h-[500px] overflow-y-auto">
                <template
                    v-for="reply in message.replies"
                    :key="reply.id">
                    <div class="flex items-center justify-between gap-4 border-b p-2 text-sm text-white">
                        <p class="flex items-center gap-2 truncate">
                            {{ reply.content }}
                        </p>

                        <div class="flex items-center gap-2">
                            <span
                                v-if="reply.user"
                                class="text-nowrap text-xs text-white/50">
                                Sender: {{ reply.user.name }}
                            </span>
                        </div>
                    </div>
                </template>
            </div>
        </Card>
    </AppLayout>
</template>

<style scoped></style>
