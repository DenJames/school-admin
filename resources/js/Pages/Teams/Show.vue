<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import DeleteTeamForm from "@/Pages/Teams/Partials/DeleteTeamForm.vue";
import SectionBorder from "@/Components/SectionBorder.vue";
import TeamMemberManager from "@/Pages/Teams/Partials/TeamMemberManager.vue";
import UpdateTeamNameForm from "@/Pages/Teams/Partials/UpdateTeamNameForm.vue";

interface Props {
    team: object;
    availableRoles: Array<any>;
    permissions: object;
}

defineProps<Props>();
</script>

<template>
    <AppLayout title="Team Indstillinger">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Team indstillinger</h2>
        </template>

        <div>
            <div class="mx-auto max-w-7xl py-10 sm:px-6 lg:px-8">
                <UpdateTeamNameForm
                    :team="team"
                    :permissions="permissions" />

                <TeamMemberManager
                    class="mt-10 sm:mt-0"
                    :team="team"
                    :available-roles="availableRoles"
                    :user-permissions="permissions" />

                <template v-if="permissions.canDeleteTeam && !team.personal_team">
                    <SectionBorder />

                    <DeleteTeamForm
                        class="mt-10 sm:mt-0"
                        :team="team" />
                </template>
            </div>
        </div>
    </AppLayout>
</template>
