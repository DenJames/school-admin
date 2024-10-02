<script setup lang="ts">
import Pencil from "@/Components/Icons/Pencil.vue";
import Trash from "@/Components/Icons/Trash.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import DialogModal from "@/Components/DialogModal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import PrimaryButton from "../PrimaryButton.vue";
import InputError from "../InputError.vue";
import ReplyData = App.Data.ReplyData;

interface Props {
    reply: ReplyData;
}

const props = defineProps<Props>();

const user = computed(() => usePage().props.auth.user);

const confirmingReplyDeletion = ref(false);
const confirmingReplyEdit = ref(false);
const selectedReply = ref(null);

const form = useForm({
    content: props.reply.content,
});

function submit() {
    form.put(route("message.reply.update", props.reply), {
        onError: () => {
            form.reset();
        },
        onFinish: () => {
            form.reset();
            closeModal();
        },
    });
}

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

    selectReply(reply);
}

function editReply(reply: ReplyData) {
    confirmingReplyEdit.value = true;

    selectReply(reply);
}

function selectReply(reply: ReplyData) {
    selectedReply.value = reply;
}

const closeModal = () => {
    confirmingReplyDeletion.value = false;
    confirmingReplyEdit.value = false;
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
                <button
                    class="transition-all hover:scale-105"
                    @click="editReply(reply)">
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
            <template #title> Slet svar</template>

            <template #content>
                Er du sikker på, at du vil slette dit svar? Når det er slettet, kan vi ikke gendanne nogen data
                relateret til det. Indtast venligst din adgangskode for at bekræfte, at du vil slette dit svar
                permanent.
            </template>

            <template #footer>
                <SecondaryButton @click="closeModal"> Cancel</SecondaryButton>

                <DangerButton
                    class="ms-3"
                    @click="deleteReply">
                    Slet svar
                </DangerButton>
            </template>
        </DialogModal>

        <!-- Edit modal -->
        <DialogModal
            :show="confirmingReplyEdit"
            @close="closeModal">
            <template #title> Rediger svar</template>

            <template #content>
                <textarea
                    v-model="form.content"
                    class="w-full rounded-md bg-gray-700 p-2 text-white"
                    rows="3"
                    placeholder="Skriv dit svar her"></textarea>

                <InputError :message="form.errors.content" />
            </template>

            <template #footer>
                <SecondaryButton @click="closeModal"> Annuller</SecondaryButton>

                <PrimaryButton
                    class="ms-3"
                    @click="submit">
                    Opdater svar
                </PrimaryButton>
            </template>
        </DialogModal>
    </Teleport>
</template>

<style scoped></style>
