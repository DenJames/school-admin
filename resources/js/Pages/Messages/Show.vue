<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Card from "@/Components/Card.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import Reload from "@/Components/Icons/Reload.vue";
import ReplyEntry from "@/Components/Messages/ReplyEntry.vue";
import Trash from "../../Components/Icons/Trash.vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import DangerButton from "../../Components/DangerButton.vue";
import DialogModal from "../../Components/DialogModal.vue";
import InputError from "../../Components/InputError.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import Pencil from "../../Components/Icons/Pencil.vue";
import InputLabel from "../../Components/InputLabel.vue";
import MessageData = App.Data.MessageData;
import ReplyData = App.Data.ReplyData;

interface Props {
    message: MessageData & { replies: ReplyData[] };
}

const props = defineProps<Props>();

const user = computed(() => usePage().props.auth.user);

const confirmingMessageDeletion = ref(false);
const confirmingMessageEdit = ref(false);
const selectedMessage = ref(null);

const messageForm = useForm({
    subject: props.message.subject,
    content: props.message.content,
});

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

function submitEdit() {
    messageForm.put(route("messages.update", props.message), {
        onError: () => {
            messageForm.reset();
        },
        onFinish: () => {
            messageForm.reset();
            selectedMessage.value = null;
            closeModal();
        },
    });
}

function confirmMessageDeletion(message: MessageData) {
    confirmingMessageDeletion.value = true;

    selectMessage(message);
}

function confirmMessageEdit(message: MessageData) {
    confirmingMessageEdit.value = true;

    selectMessage(message);
}

function selectMessage(message: MessageData) {
    selectedMessage.value = message;
}

function deleteMessage() {
    router.delete(route("messages.destroy", selectedMessage.value), {
        preserveScroll: true,
        onFinish: () => {
            closeModal();
            selectedMessage.value = null;
        },
    });
}

const closeModal = () => {
    confirmingMessageDeletion.value = false;
    confirmingMessageEdit.value = false;
    selectedMessage.value = null;
};
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

                            <div class="mt-1 flex w-full justify-between">
                                <span
                                    v-if="message.sender"
                                    class="text-xs text-white/50">
                                    Sent by: {{ message.sender.name }} - {{ message.createdAt }}
                                </span>

                                <div class="flex gap-2">
                                    <button
                                        class="transition-all hover:scale-105 hover:text-red-400"
                                        @click="confirmMessageEdit(message)">
                                        <Pencil class="size-4" />
                                    </button>

                                    <button
                                        class="transition-all hover:scale-105 hover:text-red-400"
                                        @click="confirmMessageDeletion(message)">
                                        <Trash class="size-4" />
                                    </button>
                                </div>
                            </div>
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

        <Teleport to="body">
            <DialogModal
                :show="confirmingMessageDeletion"
                @close="closeModal">
                <template #title> Delete Message</template>

                <template #content>
                    Are you sure you want to delete your message? Once deleted, we won't be able to restore any data
                    related to it. Please enter your password to confirm you would like to permanently delete your
                    message.
                </template>

                <template #footer>
                    <SecondaryButton @click="closeModal"> Cancel</SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        @click="deleteMessage">
                        Delete reply
                    </DangerButton>
                </template>
            </DialogModal>

            <!-- Edit modal -->
            <DialogModal
                :show="confirmingMessageEdit"
                @close="closeModal">
                <template #title> Edit Message</template>

                <template #content>
                    <InputLabel
                        for="subject"
                        value="Subject">
                    </InputLabel>
                    <input
                        v-model="messageForm.subject"
                        class="mt-1 w-full rounded-md bg-gray-700 p-2 text-white"
                        type="text"
                        placeholder="Type your subject here" />
                    <InputError :message="messageForm.errors.subject" />

                    <InputLabel
                        class="mt-4"
                        for="content"
                        value="Content">
                    </InputLabel>
                    <textarea
                        v-model="messageForm.content"
                        class="mt-1 w-full rounded-md bg-gray-700 p-2 text-white"
                        rows="3"
                        placeholder="Type your reply here"></textarea>

                    <InputError :message="messageForm.errors.content" />
                </template>

                <template #footer>
                    <SecondaryButton @click="closeModal"> Cancel</SecondaryButton>

                    <PrimaryButton
                        class="ms-3"
                        @click="submitEdit">
                        Update message
                    </PrimaryButton>
                </template>
            </DialogModal>
        </Teleport>
    </AppLayout>
</template>

<style scoped></style>
