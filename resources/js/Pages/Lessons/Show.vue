<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Card from "@/Components/Card.vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import { ref } from "vue";
import DangerButton from "../../Components/DangerButton.vue";
import InputError from "../../Components/InputError.vue";
import InputLabel from "../../Components/InputLabel.vue";
import { router, useForm } from "@inertiajs/vue3";
import DialogModal from "../../Components/DialogModal.vue";
import Pencil from "../../Components/Icons/Pencil.vue";
import TextInput from "../../Components/TextInput.vue";
import Trash from "../../Components/Icons/Trash.vue";
import ConfirmationModal from "../../Components/ConfirmationModal.vue";
import { can } from "momentum-lock";
import List from "../../Components/Homework/List.vue";
import LessonData = App.Data.LessonData;
import AbsenceData = App.Data.AbsenceData;
import HomeworkData = App.Data.HomeworkData;

interface Props {
    lesson: LessonData & { homeworks: HomeworkData[] | null };
}

const props = defineProps<Props>();

const showAbsenceStatusModal = ref(false);
const showAbsenceModal = ref(false);
const showDeleteAbsenceModal = ref(false);
const selectedAbsence = ref(null);

const options = { hour: "2-digit", minute: "2-digit", timeZone: "Europe/Copenhagen" };
const formattedStartTime = new Date(props.lesson.start).toLocaleTimeString("da-DK", options);
const formattedEndTime = new Date(props.lesson.end).toLocaleTimeString("da-DK", options);

const availableActions = [
    { id: 1, name: "Lovligt" },
    { id: 2, name: "Ulovligt" },
];

const form = useForm({
    action_id: null,
    status: null,
});

const absenceForm = useForm({
    reason: "",
});

function selectAbsence(absence: AbsenceData, shouldDelete: boolean = false) {
    selectedAbsence.value = absence;

    if (!can(selectedAbsence.value, "updateStatus") && !shouldDelete) {
        return;
    }

    if (can(selectedAbsence.value, "delete") && shouldDelete) {
        showDeleteAbsenceModal.value = true;
        return;
    }

    showAbsenceStatusModal.value = true;
}

function setStatus(status) {
    form.action_id = status.id;
    form.status = status.name === "Lovligt";
}

function update() {
    form.post(route("absences.status.store", selectedAbsence.value), {
        onSuccess: () => {
            form.reset();
            showAbsenceStatusModal.value = false;
        },
    });

    showAbsenceStatusModal.value = false;
}

function submit() {
    absenceForm.post(route("absences.store", props.lesson), {
        errorBag: "absenceForm",
        onSuccess: () => {
            absenceForm.reset();
            showAbsenceModal.value = false;
        },
    });
}

function remove() {
    router.delete(route("absences.destroy", selectedAbsence.value));
    showDeleteAbsenceModal.value = false;
}
</script>

<template>
    <AppLayout :title="lesson.name">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">{{ lesson.name }}</h2>
        </template>

        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 md:col-span-6 lg:col-span-2">
                <Card>
                    <template #header>Lærer</template>
                    <div class="flex flex-col items-center gap-4 px-4 py-4">
                        <img
                            :src="lesson.teacher.user.profilePhotoUrl"
                            alt=""
                            class="h-20 w-20 rounded-full" />
                        <p>{{ lesson.teacher.user.name }}</p>
                    </div>
                </Card>
            </div>

            <div class="col-span-12 md:col-span-6 lg:col-span-5">
                <Card>
                    <template #header>Lektion</template>
                    <div class="p-2">
                        <p><span class="font-bold">Lektion:</span> {{ lesson.name }}</p>
                        <p><span class="font-bold">Fag:</span> {{ lesson.classCategory.name }}</p>
                        <p><span class="font-bold">Skole:</span> {{ lesson.teacher?.school?.name }}</p>
                        <p>
                            <span class="font-bold">Klasseværelse:</span>
                            {{ lesson.classroomReservation.classroom.name }}
                        </p>
                        <p>
                            <span class="font-bold">Tidspunkt:</span> {{ formattedStartTime }} - {{ formattedEndTime }}
                        </p>
                    </div>
                </Card>
            </div>

            <div class="col-span-12 md:col-span-6 lg:col-span-5">
                <Card>
                    <template #header>
                        Fravær

                        <button
                            class="absolute right-1 top-1 rounded-md p-1 transition-all hover:bg-gray-800"
                            @click="showAbsenceModal = true">
                            <Pencil class="size-5" />
                        </button>
                    </template>
                    <div class="space-y-2 p-2">
                        <p v-if="!lesson.absences.length">Der er pt. intet fravær fra denne lektion.</p>

                        <template
                            v-for="absence in lesson.absences"
                            :key="absence.id">
                            <div class="flex items-center justify-between gap-4 rounded-md bg-gray-700 p-2">
                                <div class="flex items-center gap-4">
                                    <img
                                        class="h-10 w-10 rounded-full"
                                        :src="absence.user.profilePhotoUrl"
                                        alt="" />

                                    <div class="flex flex-col justify-center">
                                        <strong class="flex items-center gap-1 text-white/80">
                                            {{ absence.user.name }}

                                            <button
                                                v-if="can(absence, 'delete')"
                                                class="transition-all hover:text-red-400"
                                                @click="selectAbsence(absence, true)">
                                                <Trash class="size-4" />
                                            </button>
                                        </strong>
                                        <p class="text-xs">
                                            {{ absence.reason }}
                                        </p>
                                    </div>
                                </div>

                                <div v-if="absence.approvedAt && absence.excused">
                                    <span
                                        class="cursor-pointer text-nowrap text-xs font-bold text-green-500 hover:underline"
                                        @click="selectAbsence(absence)"
                                        >Lovligt</span
                                    >
                                </div>

                                <div v-else-if="absence.approvedAt && !absence.excused">
                                    <span
                                        class="cursor-pointer text-nowrap text-xs font-bold text-red-500 hover:underline"
                                        @click="selectAbsence(absence)"
                                        >Ikke lovligt</span
                                    >
                                </div>

                                <div v-if="!absence.approvedAt">
                                    <SecondaryButton
                                        v-if="can(absence, 'updateStatus')"
                                        class="text-xs font-bold hover:underline"
                                        @click="selectAbsence(absence)"
                                        >Håndter</SecondaryButton
                                    >
                                </div>
                            </div>
                        </template>
                    </div>
                </Card>
            </div>

            <div class="col-span-12">
                <Card>
                    <template #header>Lektier</template>

                    <List :homework="lesson.homeworks ?? []" />
                </Card>
            </div>
        </div>

        <DialogModal
            :show="showAbsenceModal"
            @close="showAbsenceStatusModal = false">
            <template #title> Meld fravær</template>

            <template #content>
                Beskriv hvorfor du kommer til at være fraværende fra lektionen.

                <div class="mt-4">
                    <InputLabel
                        for="reason"
                        value="Årsag" />

                    <TextInput
                        v-model="absenceForm.reason"
                        placeholder="Fraværsgrund"
                        class="w-full" />

                    <InputError
                        :message="absenceForm.errors.reason"
                        class="mt-2" />
                </div>
            </template>

            <template #footer>
                <SecondaryButton @click="submit"> Send</SecondaryButton>

                <DangerButton
                    class="ms-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="showAbsenceModal = false">
                    Luk
                </DangerButton>
            </template>
        </DialogModal>

        <DialogModal
            :show="showAbsenceStatusModal"
            @close="showAbsenceStatusModal = false">
            <template #title> Håndter fraværsmelding</template>

            <template #content>
                Vælg om fraværet er lovligt eller ej.

                <div class="mt-4">
                    <InputLabel
                        for="status"
                        value="Status" />

                    <div class="relative mt-1 flex w-full cursor-pointer gap-2 rounded-lg">
                        <button
                            v-for="(action, i) in availableActions"
                            :key="i"
                            type="button"
                            class="relative w-full rounded-lg border border-gray-200 px-4 py-3 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:focus:border-indigo-600 dark:focus:ring-indigo-600"
                            :class="{ 'border-2 !border-indigo-500': form.action_id == action.id }"
                            @click="setStatus(action)">
                            <div
                                :class="{
                                    'opacity-50': form.action_id && form.action_id != action.id,
                                }">
                                <div class="flex items-center">
                                    <div
                                        class="flex-nowrap text-sm text-gray-600 dark:text-gray-400"
                                        :class="{ 'font-semibold': form.action_id == action.id }">
                                        {{ action.name }}
                                    </div>
                                </div>
                            </div>
                        </button>
                    </div>

                    <InputError
                        :message="form.errors.status"
                        class="mt-2" />
                </div>
            </template>

            <template #footer>
                <SecondaryButton @click="update"> Gem</SecondaryButton>

                <DangerButton
                    class="ms-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="showAbsenceStatusModal = false">
                    Luk
                </DangerButton>
            </template>
        </DialogModal>

        <ConfirmationModal
            :show="showDeleteAbsenceModal"
            @close="showDeleteAbsenceModal = false">
            <template #title> Slet fraværsmelding</template>

            <template #content> Er du sikker på at du vil slette denne fraværsmelding? </template>

            <template #footer>
                <SecondaryButton @click="remove"> Slet</SecondaryButton>

                <DangerButton
                    class="ms-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="showDeleteAbsenceModal = false">
                    Luk
                </DangerButton>
            </template>
        </ConfirmationModal>
    </AppLayout>
</template>
