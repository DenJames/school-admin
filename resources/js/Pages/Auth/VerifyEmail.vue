<script setup lang="ts">
import { computed } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Components/AuthenticationCardLogo.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

interface Props {
    status: string;
}

const props = defineProps<Props>();

const form = useForm({});

const submit = () => {
    form.post(route("verification.send"));
};

const verificationLinkSent = computed(() => props.status === "verification-link-sent");
</script>

<template>
    <Head title="E-mailverifikation" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            Før du fortsætter, kan du venligst bekræfte din e-mailadresse ved at klikke på det link, vi lige har sendt
            til dig? Hvis du ikke modtog e-mailen, sender vi gerne en ny.
        </div>

        <div
            v-if="verificationLinkSent"
            class="mb-4 text-sm font-medium text-green-600 dark:text-green-400">
            Et nyt verifikationslink er blevet sendt til den e-mailadresse, du angav i dine profilindstillinger.
        </div>

        <form @submit.prevent="submit">
            <div class="mt-4 flex items-center justify-between">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing">
                    Send verifikations-e-mail igen
                </PrimaryButton>

                <div>
                    <Link
                        :href="route('profile.show')"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800">
                        Rediger profil
                    </Link>

                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="ms-2 rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800">
                        Log ud
                    </Link>
                </div>
            </div>
        </form>
    </AuthenticationCard>
</template>
