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
        <template #title> Delete Group</template>

        <template #description> Permanently delete this group</template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
                Once a group is deleted, all of its resources and data will be permanently deleted.
            </div>

            <div class="mt-5">
                <DangerButton @click="confirmGroupDeletion"> Delete Group</DangerButton>
            </div>

            <!-- Delete Team Confirmation Modal -->
            <ConfirmationModal
                :show="confirmingGroupDeletion"
                @close="confirmingGroupDeletion = false">
                <template #title> Delete Group</template>

                <template #content>
                    Are you sure you want to delete this group? Once a group is deleted, all of its resources and data
                    will be permanently deleted.
                </template>

                <template #footer>
                    <SecondaryButton @click="confirmingGroupDeletion = false"> Cancel</SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteTeam">
                        Delete group
                    </DangerButton>
                </template>
            </ConfirmationModal>
        </template>
    </ActionSection>
</template>
