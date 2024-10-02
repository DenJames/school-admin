<script setup lang="ts">
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import ActionSection from "@/Components/ActionSection.vue";
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import GroupData = App.Data.GroupData;

interface Props {
    group: GroupData;
}

const props = defineProps<Props>();

const confirmingGroupDeletion = ref(false);
const form = useForm({});

const confirmGroupDeletion = () => {
    confirmingGroupDeletion.value = true;
};

const deleteTeam = () => {
    form.delete(route("groups.destroy", props.group), {
        errorBag: "deleteTeam",
    });
};
</script>

<template>
    <ActionSection>
        <template #title> Slet gruppe</template>

        <template #description> Slet denne gruppe permanent</template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
                Når en gruppe er slettet, vil alle dens ressourcer og data blive slettet permanent.
            </div>

            <div class="mt-5">
                <DangerButton @click="confirmGroupDeletion"> Slet gruppe</DangerButton>
            </div>

            <!-- Delete Team Confirmation Modal -->
            <ConfirmationModal
                :show="confirmingGroupDeletion"
                @close="confirmingGroupDeletion = false">
                <template #title> Slet gruppe</template>

                <template #content>
                    Er du sikker på, at du vil slette denne gruppe? Når en gruppe er slettet, vil alle dens ressourcer
                    og data blive slettet permanent.
                </template>

                <template #footer>
                    <SecondaryButton @click="confirmingGroupDeletion = false"> Annuller</SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteTeam">
                        Slet gruppe
                    </DangerButton>
                </template>
            </ConfirmationModal>
        </template>
    </ActionSection>
</template>
