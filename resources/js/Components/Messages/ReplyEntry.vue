<script setup lang="ts">
import Pencil from "@/Components/Icons/Pencil.vue";
import Trash from "@/Components/Icons/Trash.vue";
import { router, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import DialogModal from "../DialogModal.vue";
import SecondaryButton from "../SecondaryButton.vue";
import DangerButton from "../DangerButton.vue";
import ReplyData = App.Data.ReplyData;

interface Props {
    reply: ReplyData;
}

const props = defineProps<Props>();

const user = computed(() => usePage().props.auth.user);

const confirmingReplyDeletion = ref(false);
const selectedReply = ref(null);

function deleteReply() {
    router.delete(route("message.reply.destroy", selectedReply.value), {
        preserveScroll: true,
        onFinish: () => {
            closeModal();
            selectedReply.value = null;
        },
    });
}

function confirmReplyDeletion(reply: ReplyData) {
    confirmingReplyDeletion.value = true;
    selectedReply.value = reply;
}

const closeModal = () => {
    confirmingReplyDeletion.value = false;
    selectedReply.value = null;
};
</script>

<template>
    <div class="rounded-md bg-gray-700 p-2">
        <p>
            {{ reply.content }}
        </p>

        <div class="flex justify-between gap-6">
            <span
                v-if="reply.user"
                class="text-xs text-white/50">
                Sent by: {{ reply.user.name }} - {{ reply.createdAt }}
            </span>

            <div
                v-if="reply.userId === user.id"
                class="flex gap-2">
                <button class="transition-all hover:scale-105">
                    <Pencil class="size-4" />
                </button>

                <button
                    class="transition-all hover:scale-105 hover:text-red-400"
                    @click="confirmReplyDeletion(reply)">
                    <Trash class="size-4" />
                </button>
            </div>
        </div>
    </div>

    <Teleport to="body">
        <DialogModal
            :show="confirmingReplyDeletion"
            @close="closeModal">
            <template #title> Delete Reply</template>

            <template #content>
                Are you sure you want to delete your reply? Once deleted, we won't be able to restore any data related
                to it. Please enter your password to confirm you would like to permanently delete your reply.
            </template>

            <template #footer>
                <SecondaryButton @click="closeModal"> Cancel</SecondaryButton>

                <DangerButton
                    class="ms-3"
                    @click="deleteReply">
                    Delete reply
                </DangerButton>
            </template>
        </DialogModal>
    </Teleport>
</template>

<style scoped></style>
