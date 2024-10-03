<script setup lang="ts">
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import ActionSection from "@/Components/ActionSection.vue";
import DangerButton from "@/Components/DangerButton.vue";
import DialogModal from "@/Components/DialogModal.vue";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: "",
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    setTimeout(() => passwordInput.value.focus(), 250);
};

const deleteUser = () => {
    form.delete(route("current-user.destroy"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <ActionSection>
        <template #title> Slet konto</template>

        <template #description> Slet din konto permanent.</template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
                Når din konto er slettet, vil alle dens ressourcer og data blive slettet permanent. Før du sletter din
                konto, skal du downloade eventuelle data eller oplysninger, som du ønsker at beholde.
            </div>

            <div class="mt-5">
                <DangerButton @click="confirmUserDeletion"> Slet konto</DangerButton>
            </div>

            <!-- Delete Account Confirmation Modal -->
            <DialogModal
                :show="confirmingUserDeletion"
                @close="closeModal">
                <template #title> Slet konto</template>

                <template #content>
                    Er du sikker på, at du vil slette din konto? Når din konto er slettet, vil alle dens ressourcer og
                    data blive slettet permanent. Indtast venligst din adgangskode for at bekræfte, at du ønsker at
                    slette din konto permanent.

                    <div class="mt-4">
                        <TextInput
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            class="mt-1 block w-3/4"
                            placeholder="Adgangskode"
                            autocomplete="current-password"
                            @keyup.enter="deleteUser" />

                        <InputError
                            :message="form.errors.password"
                            class="mt-2" />
                    </div>
                </template>

                <template #footer>
                    <SecondaryButton @click="closeModal"> Annuller</SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser">
                        Slet konto
                    </DangerButton>
                </template>
            </DialogModal>
        </template>
    </ActionSection>
</template>
