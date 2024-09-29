<script setup lang="ts">
import { ref, watch } from "vue";
import debounce from "lodash/debounce";
import axios from "axios";
import TextInput from "./TextInput.vue";

const potentialRecipients = ref([]);

interface Emits {
    (event: "selectedRecipient", recipient: any): void;
}

const emit = defineEmits<Emits>();

const recipientName = ref(null);

function selectRecipient(recipient) {
    recipientName.value = recipient.name;
    potentialRecipients.value = [];

    emit("selectedRecipient", recipient);
}

watch(
    () => recipientName.value,
    debounce(async (newValue: string, oldValue: string) => {
        if (newValue.trim().length > 0) {
            if (oldValue !== recipientName.value) {
                potentialRecipients.value = [];
            }

            if (oldValue !== recipientName.value) {
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
    <TextInput
        id="receiver"
        v-model="recipientName"
        type="text"
        class="mt-1 block w-full"
        placeholder="Start typing to search for a user"
        required
        autofocus
        autocomplete="off" />

    <div
        v-if="
            potentialRecipients.length > 0 && !potentialRecipients.some((recipient) => recipient.name === recipientName)
        "
        class="absolute z-10 mt-1 w-full rounded-md border border-gray-700 bg-gray-800 text-white">
        <ul>
            <li
                v-for="recipient in potentialRecipients"
                :key="recipient.id"
                class="hover:bg-custom-primary cursor-pointer px-4 py-2 transition-all"
                @click="selectRecipient(recipient)">
                {{ recipient.name }}
            </li>
        </ul>
    </div>
</template>
