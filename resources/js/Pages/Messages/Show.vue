<script setup lang="ts">
import AppLayout from "../../Layouts/AppLayout.vue";
import Card from "../../Components/Card.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import Reload from "../../Components/Icons/Reload.vue";
import ReplyEntry from "../../Components/Messages/ReplyEntry.vue";
import MessageData = App.Data.MessageData;
import ReplyData = App.Data.ReplyData;

interface Props {
    message: MessageData & { replies: ReplyData[] };
}

const props = defineProps<Props>();

const user = computed(() => usePage().props.auth.user);

const form = useForm({
    content: "",
});

function refreshMessages() {
    router.reload({ only: ["message"] });
}

function submit() {
    form.post(route("message.reply", props.message.id), {
        onFinish: () => {
            form.reset();
        },
    });
}
</script>

<template>
    <AppLayout title="Messages">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">{{ message.subject }}</h2>
        </template>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <Card>
                    <template #header>
                        {{ message.subject }}
                    </template>

                    <div class="max-h-[700px] overflow-y-auto p-2">
                        <div class="rounded-md bg-gray-700 p-2">
                            <p>
                                {{ message.content }}
                            </p>

                            <span
                                v-if="message.sender"
                                class="text-xs text-white/50">
                                Sent by: {{ message.sender.name }}
                            </span>
                        </div>
                    </div>
                </Card>
            </div>

            <div>
                <Card>
                    <template #header>
                        Replies

                        <button
                            class="absolute right-2 top-1 rounded-md p-1 transition-all hover:bg-gray-800"
                            @click="refreshMessages">
                            <Reload class="size-5" />
                        </button>
                    </template>

                    <div class="max-h-[500px] space-y-3 overflow-y-auto p-2">
                        <form
                            class="space-y-2"
                            @submit.prevent="submit">
                            <textarea
                                v-model="form.content"
                                class="w-full rounded-md bg-gray-700 p-2"
                                rows="3"
                                placeholder="Type your reply here"
                                required></textarea>

                            <button
                                type="submit"
                                class="mb-2 w-full rounded-md bg-gray-700 px-4 py-2 transition-all hover:bg-gray-600"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing">
                                Send
                            </button>
                        </form>

                        <template
                            v-for="reply in message.replies"
                            :key="reply.id">
                            <ReplyEntry :reply="reply" />
                        </template>
                    </div>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped></style>
