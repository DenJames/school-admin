<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Card from "@/Components/Card.vue";
import { useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import LiveUserSearchInput from "../../Components/LiveUserSearchInput.vue";

interface Props {
    hasHeader?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    hasHeader: true,
});

const form = useForm({
    recipient: {
        id: null,
        name: "",
    },
    subject: "",
    content: "",
});

function submit() {
    form.post(route("messages.store"), {
        onFinish: () => form.reset(),
    });
}

function setRecipient(recipient) {
    form.recipient.id = recipient.id;
    form.recipient.name = recipient.name;
}
</script>

<template>
    <AppLayout title="Beskeder">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Send besked</h2>
        </template>

        <Card
            :has-header="false"
            content-classes="p-2">
            <form
                class="grid grid-cols-1 gap-4 lg:grid-cols-2"
                @submit.prevent="submit">
                <div class="relative col-span-2 lg:col-span-1">
                    <InputLabel
                        for="receiver"
                        value="Modtager" />
                    <LiveUserSearchInput @selected-recipient="setRecipient($event)" />
                    <InputError
                        class="mt-2"
                        :message="form.errors.recipient" />
                </div>

                <div class="col-span-2 lg:col-span-1">
                    <InputLabel
                        for="subject"
                        value="Emne" />
                    <TextInput
                        id="subject"
                        v-model="form.subject"
                        type="text"
                        class="mt-1 block w-full"
                        placeholder="Indtast et emne for beskeden"
                        required
                        autofocus
                        autocomplete="off" />
                    <InputError
                        class="mt-2"
                        :message="form.errors.subject" />
                </div>

                <div class="col-span-2">
                    <InputLabel
                        for="content"
                        value="Besked" />
                    <textarea
                        id="content"
                        v-model="form.content"
                        class="min-h-[200px] w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600"
                        required
                        autofocus
                        autocomplete="off" />
                    <InputError
                        class="mt-2"
                        :message="form.errors.content" />
                </div>

                <button
                    type="submit"
                    class="col-span-2 rounded-md bg-gray-700 px-4 py-2 transition-all hover:bg-gray-600"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing">
                    Send
                </button>
            </form>
        </Card>
    </AppLayout>
</template>
