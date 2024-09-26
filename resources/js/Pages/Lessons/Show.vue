<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Card from "@/Components/Card.vue";
import LessonData = App.Data.LessonData;
import { can } from "momentum-lock";

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

            <div
                class="col-span-12 md:col-span-6 lg:col-span-5"
                style="width:">
                <Card>
                    <template #header>Lektion</template>
                    <div class="p-2">
                        Can: {{ can(lesson, "delete") }}
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
                    <div class="p-2">Noget fravær</div>
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
