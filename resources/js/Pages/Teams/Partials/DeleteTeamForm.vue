<script setup lang="ts">
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import ActionSection from "@/Components/ActionSection.vue";
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

interface Props {
    team: object;
}

const props = defineProps<Props>();

const confirmingTeamDeletion = ref(false);
const form = useForm({});

const confirmTeamDeletion = () => {
    confirmingTeamDeletion.value = true;
};

const deleteTeam = () => {
    form.delete(route("teams.destroy", props.team), {
        errorBag: "deleteTeam",
    });
};
</script>

<template>
    <ActionSection>
        <template #title> Slet team</template>

        <template #description> Slet dette team permanent.</template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
                Når et team er slettet, vil alle dets ressourcer og data blive slettet permanent. Før du sletter dette
                team, skal du downloade eventuelle data eller oplysninger vedrørende dette team, som du ønsker at
                beholde.
            </div>

            <div class="mt-5">
                <DangerButton @click="confirmTeamDeletion"> Slet team</DangerButton>
            </div>

            <!-- Delete Team Confirmation Modal -->
            <ConfirmationModal
                :show="confirmingTeamDeletion"
                @close="confirmingTeamDeletion = false">
                <template #title> Slet team</template>

                <template #content>
                    Er du sikker på, at du vil slette dette team? Når et team er slettet, vil alle dets ressourcer og
                    data blive slettet permanent.
                </template>

                <template #footer>
                    <SecondaryButton @click="confirmingTeamDeletion = false"> Annuller</SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteTeam">
                        Slet team
                    </DangerButton>
                </template>
            </ConfirmationModal>
        </template>
    </ActionSection>
</template>
