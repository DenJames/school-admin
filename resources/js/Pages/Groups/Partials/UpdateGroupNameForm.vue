<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import ActionMessage from "@/Components/ActionMessage.vue";
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { computed } from "vue";

interface Props {
    group: object;
    role: string;
}

const props = defineProps<Props>();

const form = useForm({
    name: props.group.name,
});

const isAdmin = computed(() => props.role === "admin");

const updateTeamName = () => {
    form.put(route("teams.update", props.group), {
        errorBag: "updateTeamName",
        preserveScroll: true,
    });
};
</script>

<template>
    <FormSection @submitted="updateTeamName">
        <template #title> Team Name</template>

        <template #description> The team's name and owner information.</template>

        <template #form>
            <!-- Team Owner Information -->
            <div class="col-span-6">
                <InputLabel value="Team Owner" />

                <div class="mt-2 flex items-center">
                    <img
                        class="h-12 w-12 rounded-full object-cover"
                        :src="group.owner.profile_photo_url"
                        :alt="group.owner.name" />

                    <div class="ms-4 leading-tight">
                        <div class="text-gray-900 dark:text-white">{{ group.owner.name }}</div>
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            {{ group.owner.email }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Name -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel
                    for="name"
                    value="Team Name" />

                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full"
                    :disabled="!isAdmin" />

                <InputError
                    :message="form.errors.name"
                    class="mt-2" />
            </div>
        </template>

        <template
            v-if="isAdmin"
            #actions>
            <ActionMessage
                :on="form.recentlySuccessful"
                class="me-3">
                Saved.
            </ActionMessage>

            <PrimaryButton
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing">
                Save
            </PrimaryButton>
        </template>
    </FormSection>
</template>
