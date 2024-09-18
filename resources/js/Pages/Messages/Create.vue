<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Card from "@/Components/Card.vue";
import { useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import { ref, watch } from "vue";
import debounce from "lodash/debounce";
import axios from "axios";

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

const previousRecipientName = ref("");

function submit() {
    form.post(route("messages.store"), {
        onFinish: () => form.reset(),
    });
}

const potentialRecipients = ref([]);

function selectRecipient(recipient) {
    console.log("Recipient selected:", recipient);
    form.recipient.id = recipient.id;
    form.recipient.name = recipient.name;
    potentialRecipients.value = []; // Clear the list after selection
}

watch(
    () => form.recipient.name,
    debounce(async (newValue: string, oldValue: string) => {
        if (newValue.trim().length > 0) {
            if (oldValue !== form.recipient.name) {
                potentialRecipients.value = [];
            }

            if (oldValue !== form.recipient.name) {
                try {
                    const response = await axios.get(route("message.receiver"), {
                        params: { name: newValue },
                    });
                    potentialRecipients.value = response.data;
                } catch (error) {
                    console.error("Error fetching recipients:", error);
                }
            }
        } else {
            potentialRecipients.value = [];
        }
    }, 500),
);
</script>

<template>
    <AppLayout title="Messages">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Send message</h2>
        </template>

        <Card
            :has-header="false"
            content-classes="p-2">
            <form
                class="grid grid-cols-2 gap-4"
                @submit.prevent="submit">
                <div class="relative">
                    <InputLabel
                        for="receiver"
                        value="Receiver" />
                    <TextInput
                        id="receiver"
                        v-model="form.recipient.name"
                        type="text"
                        class="mt-1 block w-full"
                        placeholder="Start typing to search for a user"
                        required
                        autofocus
                        autocomplete="off" />
                    <InputError
                        class="mt-2"
                        :message="form.errors.recipient" />

                    <div
                        v-if="
                            potentialRecipients.length > 0 &&
                            !potentialRecipients.some((recipient) => recipient.name === form.recipient.name)
                        "
                        class="absolute z-10 mt-1 w-full rounded-md border border-gray-700 bg-gray-800">
                        <ul>
                            <li
                                v-for="recipient in potentialRecipients"
                                :key="recipient.id"
                                class="cursor-pointer px-4 py-2 transition-all hover:bg-gray-700"
                                @click="selectRecipient(recipient)">
                                {{ recipient.name }}
                            </li>
                        </ul>
                    </div>
                </div>

                <div>
                    <InputLabel
                        for="subject"
                        value="Subject" />
                    <TextInput
                        id="subject"
                        v-model="form.subject"
                        type="text"
                        class="mt-1 block w-full"
                        placeholder="Enter a subject for the message"
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
                        value="Message" />
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
