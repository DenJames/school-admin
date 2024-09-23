<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import SectionBorder from "@/Components/SectionBorder.vue";
import UpdateGroupNameForm from "./Partials/UpdateGroupNameForm.vue";
import DeleteGroupForm from "./Partials/DeleteGroupForm.vue";
import GroupMemberManager from "./Partials/GroupMemberManager.vue";
import GroupData = App.Data.GroupData;

interface Props {
    group: GroupData;
    availableRoles: Array<any>;
}

defineProps<Props>();
</script>

<template>
    <AppLayout title="Group Settings">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Group Settings</h2>
        </template>

        <div>
            <div class="mx-auto max-w-7xl py-10 sm:px-6 lg:px-8">
                <UpdateGroupNameForm
                    :group="group"
                    role="admin" />
                <!-- TODO: pass correct role -->

                <GroupMemberManager
                    class="mt-10 sm:mt-0"
                    :group="group"
                    :available-roles="availableRoles"
                    role="admin" />
                <!-- TODO: pass correct role -->

                <!-- TODO: Check if user is group owner -->
                <template v-if="true">
                    <SectionBorder />

                    <DeleteGroupForm
                        class="mt-10 sm:mt-0"
                        :group="group" />
                </template>
            </div>
        </div>
    </AppLayout>
</template>
