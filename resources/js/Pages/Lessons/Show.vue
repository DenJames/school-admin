<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Card from "@/Components/Card.vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import LessonData = App.Data.LessonData;

interface Props {
    lesson: LessonData;
}

const props = defineProps<Props>();
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">{{ lesson.name }}</h2>
        </template>

        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 md:col-span-6 lg:col-span-2">
                <Card>
                    <template #header>Lære</template>
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
                        <p>Lektion: {{ lesson.name }}</p>
                        <p>Fag: {{ lesson.classCategory.name }}</p>
                        <p>Skole: {{ lesson.teacher?.school?.name }}</p>
                        <p>Klasseværelse: {{ lesson.classroomReservation.classroom.name }}</p>
                    </div>
                </Card>
            </div>

            <div class="col-span-12 md:col-span-6 lg:col-span-5">
                <Card>
                    <template #header>Fravær</template>
                    <div class="space-y-2 p-2">
                        <template
                            v-for="absence in lesson.absences"
                            :key="absence.id">
                            <div class="flex items-center justify-between gap-4 rounded-md bg-gray-700 p-2">
                                <div class="flex items-center gap-4">
                                    <img
                                        class="h-10 w-10 rounded-full"
                                        :src="absence.user.profilePhotoUrl"
                                        alt="" />

                                    <div class="flex flex-col">
                                        <strong class="text-white/80">{{ absence.user.name }}</strong>
                                        <small>
                                            {{ absence.reason }}
                                        </small>
                                    </div>
                                </div>

                                <div v-if="absence.approvedAt && absence.excused">
                                    <span class="text-xs font-bold text-green-500">Godkendt</span>
                                </div>

                                <div v-else-if="absence.approvedAt && !absence.excused">
                                    <span class="text-xs font-bold text-red-500">Ikke godkendt</span>
                                </div>

                                <div v-if="!absence.approvedAt">
                                    <SecondaryButton class="text-xs font-bold hover:underline">Håndter</SecondaryButton>
                                </div>
                            </div>
                        </template>
                    </div>
                </Card>
            </div>

            <div class="col-span-12">
                <Card>
                    <template #header>Lektier</template>
                    <div class="p-2">Nogle lektier</div>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
