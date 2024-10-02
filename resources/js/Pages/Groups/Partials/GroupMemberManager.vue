<script setup lang="ts">
import { ref } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import ActionMessage from "@/Components/ActionMessage.vue";
import ActionSection from "@/Components/ActionSection.vue";
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import DialogModal from "@/Components/DialogModal.vue";
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SectionBorder from "@/Components/SectionBorder.vue";
import LiveUserSearchInput from "../../../Components/LiveUserSearchInput.vue";
import GroupData = App.Data.GroupData;
import UserData = App.Data.UserData;

interface Props {
    group: GroupData;
    availableRoles: Array<any>;
    isAdmin: boolean;
}

const props = defineProps<Props>();

const page = usePage();

const addGroupMemberForm = useForm({
    recipient: {
        id: null,
        name: "",
    },
    group_role_id: null,
});

function setRecipient(recipient) {
    addGroupMemberForm.recipient.id = recipient.id;
    addGroupMemberForm.recipient.name = recipient.name;
}

const updateRoleForm = useForm({
    role: null,
});

const leaveGroupForm = useForm({});
const removeGroupMemberForm = useForm({});

const currentlyManagingRole = ref(false);
const managingRoleFor = ref(null);
const confirmingLeavingGroup = ref(false);
const GroupMemberBeingRemoved = ref(null);

const addGroupMember = () => {
    addGroupMemberForm.post(route("groups.invite.store", props.group), {
        errorBag: "addGroupMember",
        preserveScroll: true,
        onSuccess: () => addGroupMemberForm.reset(),
    });
};

const cancelGroupInvitation = (invitation) => {
    router.delete(route("groups.invite.destroy", invitation), {
        preserveScroll: true,
    });
};

const manageRole = (groupMember) => {
    managingRoleFor.value = groupMember;
    updateRoleForm.role = membership(groupMember).pivot.group_role_id;
    currentlyManagingRole.value = true;
};

const updateRole = () => {
    updateRoleForm.put(route("group.member.update", [props.group, managingRoleFor.value]), {
        preserveScroll: true,
        onSuccess: () => (currentlyManagingRole.value = false),
    });
};

const confirmLeavingGroup = () => {
    confirmingLeavingGroup.value = true;
};

const leaveGroup = () => {
    leaveGroupForm.delete(route("groups.member.destroy", [props.group, page.props.auth.user]));
};

const confirmGroupMemberRemoval = (teamMember) => {
    GroupMemberBeingRemoved.value = teamMember;
};

const removeGroupMember = () => {
    removeGroupMemberForm.delete(route("groups.member.destroy", [props.group, GroupMemberBeingRemoved.value]), {
        errorBag: "removeGroupMember",
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => (GroupMemberBeingRemoved.value = null),
    });
};

const displayableRole = (roleId) => {
    return props.availableRoles.find((r) => r.id === roleId).name;
};

const membership = (user: UserData) => {
    return props.group.users.find((u: UserData) => u.id === user.id);
};
</script>

<template>
    <div>
        <div v-if="isAdmin">
            <SectionBorder />

            <!-- Add Team Member -->
            <FormSection @submitted="addGroupMember">
                <template #title> Tilføj gruppe medlem</template>

                <template #description> Tilføj et nyt medlem til din gruppe, så de kan samarbejde med dig. </template>

                <template #form>
                    <div class="col-span-6">
                        <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
                            Angiv venligst e-mailadressen på den person, du ønsker at tilføje til denne gruppe.
                        </div>
                    </div>

                    <!-- Member -->
                    <div class="relative col-span-6 sm:col-span-4">
                        <InputLabel
                            for="username"
                            value="Bruger" />
                        <LiveUserSearchInput @selected-recipient="setRecipient($event)" />
                        <InputError
                            :message="addGroupMemberForm.errors.recipient"
                            class="mt-2" />
                    </div>

                    <!-- Role -->
                    <div
                        v-if="availableRoles.length > 0"
                        class="col-span-6 lg:col-span-4">
                        <InputLabel
                            for="roles"
                            value="Rolle" />
                        <InputError
                            :message="addGroupMemberForm.errors.role"
                            class="mt-2" />

                        <div
                            class="relative z-0 mt-1 cursor-pointer rounded-lg border border-gray-200 dark:border-gray-700">
                            <button
                                v-for="(role, i) in availableRoles"
                                :key="i"
                                type="button"
                                class="relative inline-flex w-full rounded-lg px-4 py-3 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:border-indigo-600 dark:focus:ring-indigo-600"
                                :class="{
                                    'rounded-t-none border-t border-gray-200 focus:border-none dark:border-gray-700':
                                        i > 0,
                                    'rounded-b-none': i != Object.keys(availableRoles).length - 1,
                                }"
                                @click="addGroupMemberForm.group_role_id = role.id">
                                <div
                                    :class="{
                                        'opacity-50':
                                            addGroupMemberForm.group_role_id &&
                                            addGroupMemberForm.group_role_id != role.id,
                                    }">
                                    <!-- Role Name -->
                                    <div class="flex items-center">
                                        <div
                                            class="text-sm text-gray-600 dark:text-gray-400"
                                            :class="{ 'font-semibold': addGroupMemberForm.group_role_id == role.id }">
                                            {{ role.name }}
                                        </div>

                                        <svg
                                            v-if="addGroupMemberForm.group_role_id == role.id"
                                            class="ms-2 h-5 w-5 text-green-400"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </template>

                <template #actions>
                    <ActionMessage
                        :on="addGroupMemberForm.recentlySuccessful"
                        class="me-3">
                        Tilføjet.
                    </ActionMessage>

                    <PrimaryButton
                        :class="{ 'opacity-25': addGroupMemberForm.processing }"
                        :disabled="addGroupMemberForm.processing">
                        Tilføj
                    </PrimaryButton>
                </template>
            </FormSection>
        </div>

        <div v-if="group.invitations && group.invitations.length > 0 && isAdmin">
            <SectionBorder />

            <!-- Team Member Invitations -->
            <ActionSection class="mt-10 sm:mt-0">
                <template #title>Afventende gruppeinvitationer</template>

                <template #description>
                    Disse personer er blevet inviteret til din gruppe og har modtaget en invitationsmail. De kan deltage
                    i gruppen ved at acceptere e-mailinvitationen.
                </template>

                <!-- Pending Team Member Invitation List -->
                <template #content>
                    <div class="space-y-6">
                        <div
                            v-for="invitation in group.invitations"
                            :key="invitation.id"
                            class="flex items-center justify-between">
                            <div class="text-gray-600 dark:text-gray-400">
                                {{ invitation.user.name }}
                            </div>

                            <div class="flex items-center">
                                <!-- Cancel Team Invitation -->
                                <button
                                    v-if="isAdmin"
                                    class="ms-6 cursor-pointer text-sm text-red-500 focus:outline-none"
                                    @click="cancelGroupInvitation(invitation)">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </ActionSection>
        </div>

        <div v-if="group.users.length > 0">
            <SectionBorder />

            <!-- Manage Team Members -->
            <ActionSection class="mt-10 sm:mt-0">
                <template #title> Gruppe medlemmer</template>

                <template #description> Alle de personer, der er en del af denne gruppe.</template>

                <!-- Team Member List -->
                <template #content>
                    <div class="space-y-6">
                        <div
                            v-for="user in group.users"
                            :key="user.id"
                            class="flex items-center justify-between">
                            <div class="flex items-center">
                                <img
                                    class="h-8 w-8 rounded-full object-cover"
                                    :src="user.profile_photo_url"
                                    :alt="user.name" />
                                <div class="ms-4 dark:text-white">
                                    {{ user.name }}
                                </div>
                            </div>

                            <div class="flex items-center">
                                <!-- Manage Team Member Role -->
                                <button
                                    v-if="isAdmin && availableRoles.length"
                                    class="ms-2 text-sm text-gray-400 underline"
                                    @click="manageRole(user)">
                                    {{ displayableRole(membership(user).pivot.group_role_id) }}
                                </button>

                                <div
                                    v-else-if="availableRoles.length"
                                    class="ms-2 text-sm text-gray-400">
                                    {{ displayableRole(membership(user).pivot.group_role_id) }}
                                </div>

                                <!-- Leave Team -->
                                <button
                                    v-if="$page.props.auth.user.id === user.id && group.user_id !== user.id"
                                    class="ms-6 cursor-pointer text-sm text-red-500"
                                    @click="confirmLeavingGroup">
                                    Leave
                                </button>

                                <!-- Remove Team Member -->
                                <button
                                    v-else-if="isAdmin && group.user_id !== user.id"
                                    class="ms-6 cursor-pointer text-sm text-red-500"
                                    @click="confirmGroupMemberRemoval(user)">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </ActionSection>
        </div>

        <!-- Role Management Modal -->
        <DialogModal
            :show="currentlyManagingRole"
            @close="currentlyManagingRole = false">
            <template #title> Administrer rolle</template>

            <template #content>
                <div v-if="managingRoleFor">
                    <div
                        class="relative z-0 mt-1 cursor-pointer rounded-lg border border-gray-200 dark:border-gray-700">
                        <button
                            v-for="(role, i) in availableRoles"
                            :key="role.id"
                            type="button"
                            class="relative inline-flex w-full rounded-lg px-4 py-3 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:border-indigo-600 dark:focus:ring-indigo-600"
                            :class="{
                                'rounded-t-none border-t border-gray-200 focus:border-none dark:border-gray-700': i > 0,
                                'rounded-b-none': i !== Object.keys(availableRoles).length - 1,
                            }"
                            @click="updateRoleForm.role = role.id">
                            <div :class="{ 'opacity-50': updateRoleForm.role && updateRoleForm.role !== role.id }">
                                <!-- Role Name -->
                                <div class="flex items-center">
                                    <div
                                        class="text-sm text-gray-600 dark:text-gray-400"
                                        :class="{ 'font-semibold': updateRoleForm.role === role.id }">
                                        {{ role.name }}
                                    </div>

                                    <svg
                                        v-if="updateRoleForm.role == role.id"
                                        class="ms-2 h-5 w-5 text-green-400"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </template>

            <template #footer>
                <SecondaryButton @click="currentlyManagingRole = false"> Cancel</SecondaryButton>

                <PrimaryButton
                    class="ms-3"
                    :class="{ 'opacity-25': updateRoleForm.processing }"
                    :disabled="updateRoleForm.processing"
                    @click="updateRole">
                    Save
                </PrimaryButton>
            </template>
        </DialogModal>

        <!-- Leave Team Confirmation Modal -->
        <ConfirmationModal
            :show="confirmingLeavingGroup"
            @close="confirmingLeavingGroup = false">
            <template #title> Forlad gruppe</template>

            <template #content> Er du sikker på, at du ønsker at forlade denne gruppe?</template>

            <template #footer>
                <SecondaryButton @click="confirmingLeavingGroup = false"> Annuller</SecondaryButton>

                <DangerButton
                    class="ms-3"
                    :class="{ 'opacity-25': leaveGroupForm.processing }"
                    :disabled="leaveGroupForm.processing"
                    @click="leaveGroup">
                    Forlad
                </DangerButton>
            </template>
        </ConfirmationModal>

        <!-- Remove Team Member Confirmation Modal -->
        <ConfirmationModal
            :show="GroupMemberBeingRemoved"
            @close="GroupMemberBeingRemoved = null">
            <template #title> Fjern gruppe medlem</template>

            <template #content> Er du sikker på, at du vil fjerne denne person fra gruppen?</template>

            <template #footer>
                <SecondaryButton @click="GroupMemberBeingRemoved = null"> Cancel</SecondaryButton>

                <DangerButton
                    class="ms-3"
                    :class="{ 'opacity-25': removeGroupMemberForm.processing }"
                    :disabled="removeGroupMemberForm.processing"
                    @click="removeGroupMember">
                    Fjern
                </DangerButton>
            </template>
        </ConfirmationModal>
    </div>
</template>
