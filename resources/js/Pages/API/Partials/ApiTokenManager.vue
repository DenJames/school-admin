<script setup lang="ts">
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import ActionMessage from "@/Components/ActionMessage.vue";
import ActionSection from "@/Components/ActionSection.vue";
import Checkbox from "@/Components/Checkbox.vue";
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import DialogModal from "@/Components/DialogModal.vue";
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SectionBorder from "@/Components/SectionBorder.vue";
import TextInput from "@/Components/TextInput.vue";

interface Props {
    tokens: Array<any>;
    availablePermissions: Array<any>;
    defaultPermissions: Array<any>;
}

const props = defineProps<Props>();

const createApiTokenForm = useForm({
    name: "",
    permissions: props.defaultPermissions,
});

const updateApiTokenForm = useForm({
    permissions: [],
});

const deleteApiTokenForm = useForm({});

const displayingToken = ref(false);
const managingPermissionsFor = ref(null);
const apiTokenBeingDeleted = ref(null);

const createApiToken = () => {
    createApiTokenForm.post(route("api-tokens.store"), {
        preserveScroll: true,
        onSuccess: () => {
            displayingToken.value = true;
            createApiTokenForm.reset();
        },
    });
};

const manageApiTokenPermissions = (token) => {
    updateApiTokenForm.permissions = token.abilities;
    managingPermissionsFor.value = token;
};

const updateApiToken = () => {
    updateApiTokenForm.put(route("api-tokens.update", managingPermissionsFor.value), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => (managingPermissionsFor.value = null),
    });
};

const confirmApiTokenDeletion = (token) => {
    apiTokenBeingDeleted.value = token;
};

const deleteApiToken = () => {
    deleteApiTokenForm.delete(route("api-tokens.destroy", apiTokenBeingDeleted.value), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => (apiTokenBeingDeleted.value = null),
    });
};
</script>

<template>
    <div>
        <!-- Generate API Token -->
        <FormSection @submitted="createApiToken">
            <template #title> Opret API-token</template>

            <template #description>
                API-tokens giver tredjeparts tjenester mulighed for at autentificere sig med vores applikation på dine
                vegne.
            </template>

            <template #form>
                <!-- Token Name -->
                <div class="col-span-6 sm:col-span-4">
                    <InputLabel
                        for="name"
                        value="Navn" />
                    <TextInput
                        id="name"
                        v-model="createApiTokenForm.name"
                        type="text"
                        class="mt-1 block w-full"
                        autofocus />
                    <InputError
                        :message="createApiTokenForm.errors.name"
                        class="mt-2" />
                </div>

                <!-- Token Permissions -->
                <div
                    v-if="availablePermissions.length > 0"
                    class="col-span-6">
                    <InputLabel
                        for="permissions"
                        value="Tilladelser" />

                    <div class="mt-2 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div
                            v-for="permission in availablePermissions"
                            :key="permission">
                            <label class="flex items-center">
                                <Checkbox
                                    v-model:checked="createApiTokenForm.permissions"
                                    :value="permission" />
                                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ permission }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </template>

            <template #actions>
                <ActionMessage
                    :on="createApiTokenForm.recentlySuccessful"
                    class="me-3">
                    Oprettet.
                </ActionMessage>

                <PrimaryButton
                    :class="{ 'opacity-25': createApiTokenForm.processing }"
                    :disabled="createApiTokenForm.processing">
                    Opret
                </PrimaryButton>
            </template>
        </FormSection>

        <div v-if="tokens.length > 0">
            <SectionBorder />

            <!-- Manage API Tokens -->
            <div class="mt-10 sm:mt-0">
                <ActionSection>
                    <template #title> Administrer API-tokens</template>

                    <template #description>
                        Du kan slette enhver af dine eksisterende tokens, hvis de ikke længere er nødvendige.
                    </template>

                    <!-- API Token List -->
                    <template #content>
                        <div class="space-y-6">
                            <div
                                v-for="token in tokens"
                                :key="token.id"
                                class="flex items-center justify-between">
                                <div class="break-all dark:text-white">
                                    {{ token.name }}
                                </div>

                                <div class="ms-2 flex items-center">
                                    <div
                                        v-if="token.last_used_ago"
                                        class="text-sm text-gray-400">
                                        Sidst brugt {{ token.last_used_ago }}
                                    </div>

                                    <button
                                        v-if="availablePermissions.length > 0"
                                        class="ms-6 cursor-pointer text-sm text-gray-400 underline"
                                        @click="manageApiTokenPermissions(token)">
                                        Tilladelser
                                    </button>

                                    <button
                                        class="ms-6 cursor-pointer text-sm text-red-500"
                                        @click="confirmApiTokenDeletion(token)">
                                        Slet
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </ActionSection>
            </div>
        </div>

        <!-- Token Value Modal -->
        <DialogModal
            :show="displayingToken"
            @close="displayingToken = false">
            <template #title> API-token</template>

            <template #content>
                <div>Kopier venligst dit nye API-token. Af sikkerhedsmæssige årsager vil det ikke blive vist igen.</div>

                <div
                    v-if="$page.props.jetstream.flash.token"
                    class="mt-4 break-all rounded bg-gray-100 px-4 py-2 font-mono text-sm text-gray-500 dark:bg-gray-900">
                    {{ $page.props.jetstream.flash.token }}
                </div>
            </template>

            <template #footer>
                <SecondaryButton @click="displayingToken = false"> Luk</SecondaryButton>
            </template>
        </DialogModal>

        <!-- API Token Permissions Modal -->
        <DialogModal
            :show="managingPermissionsFor != null"
            @close="managingPermissionsFor = null">
            <template #title> API-token-tilladelser</template>

            <template #content>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div
                        v-for="permission in availablePermissions"
                        :key="permission">
                        <label class="flex items-center">
                            <Checkbox
                                v-model:checked="updateApiTokenForm.permissions"
                                :value="permission" />
                            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ permission }}</span>
                        </label>
                    </div>
                </div>
            </template>

            <template #footer>
                <SecondaryButton @click="managingPermissionsFor = null"> Annuller</SecondaryButton>

                <PrimaryButton
                    class="ms-3"
                    :class="{ 'opacity-25': updateApiTokenForm.processing }"
                    :disabled="updateApiTokenForm.processing"
                    @click="updateApiToken">
                    Gem
                </PrimaryButton>
            </template>
        </DialogModal>

        <!-- Delete Token Confirmation Modal -->
        <ConfirmationModal
            :show="apiTokenBeingDeleted != null"
            @close="apiTokenBeingDeleted = null">
            <template #title> Slet API-token</template>

            <template #content> Er du sikker på, at du vil slette dette API-token?</template>

            <template #footer>
                <SecondaryButton @click="apiTokenBeingDeleted = null"> Annuller</SecondaryButton>

                <DangerButton
                    class="ms-3"
                    :class="{ 'opacity-25': deleteApiTokenForm.processing }"
                    :disabled="deleteApiTokenForm.processing"
                    @click="deleteApiToken">
                    Slet
                </DangerButton>
            </template>
        </ConfirmationModal>
    </div>
</template>
