<script setup lang="ts">
import { defineProps } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import HomeworkData = App.Data.HomeworkData;
import Card from "@/Components/Card.vue";
import Calendar from "@/Components/Icons/Calendar.vue";
import { useForm } from "@inertiajs/vue3";
import { route } from "../../../../vendor/tightenco/ziggy";

interface Props {
    homework: HomeworkData;
    file: any;
}

const props = defineProps<Props>();

const form = useForm({
    comment: null,
    file: null,
});

const submitForm = () => {
    const formData = new FormData();
    formData.append("comment", form.comment);
    if (form.file) {
        formData.append("file", form.file);
    }
    form.post(route("homework.store", props.homework.id), {
        data: formData,
        forceFormData: true,
    });
};

const handleFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.file = target.files[0];
    }
};
</script>

<template>
    <AppLayout title="Homework">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">{{ homework.name }}</h2>
        </template>

        <div class="flex flex-col gap-3">
            <Card>
                <template #header>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                        Homework Submission
                    </h2>
                </template>
                <div class="flex flex-col gap-2 px-3 pb-2 pt-5">
                    <form @submit.prevent="submitForm">
                        <div class="text-muted-foreground flex items-center gap-2 space-x-2 text-sm">
                            <Calendar />
                            <span class="text-gray-500 dark:text-gray-400"
                                >Due date: {{ homework.dueDateForHumans }}</span
                            >
                        </div>
                        <div>
                            <h3 class="mb-2 font-semibold">Assignment Description:</h3>
                            <p class="text-muted-foreground text-sm">{{ homework.description }}</p>
                        </div>
                        <div>
                            <h4 class="bold text-lg">Additional Comments</h4>
                            <textarea
                                id="comment"
                                v-model="form.comment"
                                class="mt-2 h-24 w-full rounded-md border border-gray-300 p-2 dark:border-gray-700 dark:bg-gray-600"
                                placeholder="Any additional comments about your submission..."></textarea>
                            <div v-if="form.errors.comment">{{ form.errors.comment }}</div>
                        </div>
                        <div>
                            <h4 class="bold text-lg">Upload Your Assignment</h4>
                            <input
                                type="file"
                                class="mt-2"
                                @change="handleFileChange" />
                        </div>
                        <progress
                            v-if="form.progress"
                            :value="form.progress.percentage"
                            max="100">
                            {{ form.progress.percentage }}%
                        </progress>
                        <div class="mt-4">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-700">
                                Submit
                            </button>
                            <div />
                        </div>
                    </form>
                </div>
            </Card>

            <Card>
                <template #header>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">File</h2>
                </template>
                <div class="flex flex-col gap-2 px-3 pb-2 pt-5">
                    <ul>
                        <li>
                            <a
                                :href="file.url"
                                target="_blank"
                                class="text-blue-500 hover:text-blue-700"
                                >{{ file.filename }}</a
                            >
                        </li>
                    </ul>
                </div>
            </Card>
        </div>
    </AppLayout>
</template>

<style scoped></style>
