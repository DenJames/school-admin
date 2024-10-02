<script setup lang="ts">
import { computed, ref, watch } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import ActionSection from "@/Components/ActionSection.vue";
import ConfirmsPassword from "@/Components/ConfirmsPassword.vue";
import DangerButton from "@/Components/DangerButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

interface Props {
    requiresConfirmation: boolean;
}

const props = defineProps<Props>();

const page = usePage();
const enabling = ref(false);
const confirming = ref(false);
const disabling = ref(false);
const qrCode = ref(null);
const setupKey = ref(null);
const recoveryCodes = ref([]);

const confirmationForm = useForm({
    code: "",
});

const twoFactorEnabled = computed(() => !enabling.value && page.props.auth.user?.two_factor_enabled);

watch(twoFactorEnabled, () => {
    if (!twoFactorEnabled.value) {
        confirmationForm.reset();
        confirmationForm.clearErrors();
    }
});

const enableTwoFactorAuthentication = () => {
    enabling.value = true;

    router.post(
        route("two-factor.enable"),
        {},
        {
            preserveScroll: true,
            onSuccess: () => Promise.all([showQrCode(), showSetupKey(), showRecoveryCodes()]),
            onFinish: () => {
                enabling.value = false;
                confirming.value = props.requiresConfirmation;
            },
        },
    );
};

const showQrCode = () => {
    return axios.get(route("two-factor.qr-code")).then((response) => {
        qrCode.value = response.data.svg;
    });
};

const showSetupKey = () => {
    return axios.get(route("two-factor.secret-key")).then((response) => {
        setupKey.value = response.data.secretKey;
    });
};

const showRecoveryCodes = () => {
    return axios.get(route("two-factor.recovery-codes")).then((response) => {
        recoveryCodes.value = response.data;
    });
};

const confirmTwoFactorAuthentication = () => {
    confirmationForm.post(route("two-factor.confirm"), {
        errorBag: "confirmTwoFactorAuthentication",
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            confirming.value = false;
            qrCode.value = null;
            setupKey.value = null;
        },
    });
};

const regenerateRecoveryCodes = () => {
    axios.post(route("two-factor.recovery-codes")).then(() => showRecoveryCodes());
};

const disableTwoFactorAuthentication = () => {
    disabling.value = true;

    router.delete(route("two-factor.disable"), {
        preserveScroll: true,
        onSuccess: () => {
            disabling.value = false;
            confirming.value = false;
        },
    });
};
</script>

<template>
    <ActionSection>
        <template #title> To-faktor-godkendelse</template>

        <template #description> Tilføj ekstra sikkerhed til din konto ved hjælp af to-faktor-godkendelse.</template>

        <template #content>
            <h3
                v-if="twoFactorEnabled && !confirming"
                class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Du har aktiveret to-faktor-godkendelse.
            </h3>

            <h3
                v-else-if="twoFactorEnabled && confirming"
                class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Afslut aktivering af to-faktor-godkendelse.
            </h3>

            <h3
                v-else
                class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Du har ikke aktiveret to-faktor-godkendelse.
            </h3>

            <div class="mt-3 max-w-xl text-sm text-gray-600 dark:text-gray-400">
                <p>
                    Når to-faktor-godkendelse er aktiveret, bliver du bedt om en sikker, tilfældig token under
                    godkendelse. Du kan hente denne token fra din telefons Google Authenticator-applikation.
                </p>
            </div>

            <div v-if="twoFactorEnabled">
                <div v-if="qrCode">
                    <div class="mt-4 max-w-xl text-sm text-gray-600 dark:text-gray-400">
                        <p
                            v-if="confirming"
                            class="font-semibold">
                            For at afslutte aktivering af to-faktor-godkendelse skal du scanne følgende QR-kode ved
                            hjælp af din telefons authenticator-applikation eller indtaste opsætningsnøglen og angive
                            den genererede OTP-kode.
                        </p>

                        <p v-else>
                            To-faktor-godkendelse er nu aktiveret. Scan følgende QR-kode ved hjælp af din telefons
                            authenticator-applikation eller indtaste opsætningsnøglen.
                        </p>
                    </div>

                    <div
                        class="mt-4 inline-block bg-white p-2"
                        v-html="qrCode" />

                    <div
                        v-if="setupKey"
                        class="mt-4 max-w-xl text-sm text-gray-600 dark:text-gray-400">
                        <p class="font-semibold">Setup Key: <span v-html="setupKey"></span></p>
                    </div>

                    <div
                        v-if="confirming"
                        class="mt-4">
                        <InputLabel
                            for="code"
                            value="Kode" />

                        <TextInput
                            id="code"
                            v-model="confirmationForm.code"
                            type="text"
                            name="code"
                            class="mt-1 block w-1/2"
                            inputmode="numeric"
                            autofocus
                            autocomplete="one-time-code"
                            @keyup.enter="confirmTwoFactorAuthentication" />

                        <InputError
                            :message="confirmationForm.errors.code"
                            class="mt-2" />
                    </div>
                </div>

                <div v-if="recoveryCodes.length > 0 && !confirming">
                    <div class="mt-4 max-w-xl text-sm text-gray-600 dark:text-gray-400">
                        <p class="font-semibold">
                            Opbevar disse gendannelseskoder i en sikker adgangskodeadministrator. De kan bruges til at
                            gendanne adgangen til din konto, hvis din to-faktor-godkendelsesenhed er mistet.
                        </p>
                    </div>

                    <div
                        class="mt-4 grid max-w-xl gap-1 rounded-lg bg-gray-100 px-4 py-4 font-mono text-sm dark:bg-gray-900 dark:text-gray-100">
                        <div
                            v-for="code in recoveryCodes"
                            :key="code">
                            {{ code }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <div v-if="!twoFactorEnabled">
                    <ConfirmsPassword @confirmed="enableTwoFactorAuthentication">
                        <PrimaryButton
                            type="button"
                            :class="{ 'opacity-25': enabling }"
                            :disabled="enabling">
                            Aktiver
                        </PrimaryButton>
                    </ConfirmsPassword>
                </div>

                <div v-else>
                    <ConfirmsPassword @confirmed="confirmTwoFactorAuthentication">
                        <PrimaryButton
                            v-if="confirming"
                            type="button"
                            class="me-3"
                            :class="{ 'opacity-25': enabling }"
                            :disabled="enabling">
                            Bekræft
                        </PrimaryButton>
                    </ConfirmsPassword>

                    <ConfirmsPassword @confirmed="regenerateRecoveryCodes">
                        <SecondaryButton
                            v-if="recoveryCodes.length > 0 && !confirming"
                            class="me-3">
                            Generer gendannelseskoder
                        </SecondaryButton>
                    </ConfirmsPassword>

                    <ConfirmsPassword @confirmed="showRecoveryCodes">
                        <SecondaryButton
                            v-if="recoveryCodes.length === 0 && !confirming"
                            class="me-3">
                            Vis gendannelseskoder
                        </SecondaryButton>
                    </ConfirmsPassword>

                    <ConfirmsPassword @confirmed="disableTwoFactorAuthentication">
                        <SecondaryButton
                            v-if="confirming"
                            :class="{ 'opacity-25': disabling }"
                            :disabled="disabling">
                            Annuller
                        </SecondaryButton>
                    </ConfirmsPassword>

                    <ConfirmsPassword @confirmed="disableTwoFactorAuthentication">
                        <DangerButton
                            v-if="!confirming"
                            :class="{ 'opacity-25': disabling }"
                            :disabled="disabling">
                            Deaktiver
                        </DangerButton>
                    </ConfirmsPassword>
                </div>
            </div>
        </template>
    </ActionSection>
</template>
