<script setup lang="ts">
import { computed, defineProps } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import HomeworkData = App.Data.HomeworkData;
import Card from "@/Components/Card.vue";
import Calendar from "@/Components/Icons/Calendar.vue";
import { useForm } from "@inertiajs/vue3";
import HomeworkSubmissionData = App.Data.HomeworkSubmissionData;

interface Props {
    homework: HomeworkData;
    homeworkSubmission: HomeworkSubmissionData;
    files: any;
}

const props = defineProps<Props>();

const form = useForm({
    content: props.homeworkSubmission?.content,
    files: null,
});

const submitForm = () => {
    const formData = new FormData();
    formData.append("content", form.content);
    if (form.files) {
        formData.append("files", form.files);
    }
    form.post(route("homework.store", props.homework.id), {
        data: formData,
        forceFormData: true,
        onSuccess: () => {
            form.reset();
            // reset the file input
            const fileInput = document.querySelector('input[type="file"]');
            if (fileInput) {
                fileInput.value = "";
            }
        },
    });
};

const deleteFile = (file_id) => {
    form.delete(route("document.delete", file_id), {});
};

const handleFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.files = target.files;
    }
};

const progress = computed(() => {
    if (form.pending) {
        return {
            percentage: 0,
        };
    }

    if (form.progress) {
        return {
            percentage: "width: " + form.progress.percentage + "%",
        };
    }

    return null;
});
</script>

<template>
    <AppLayout title="Opgave aflevering">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">{{ homework.name }}</h2>
        </template>

        <div class="flex flex-col gap-3">
            <div
                v-if="homeworkSubmission.feedback"
                class="mb-4 flex rounded-lg bg-blue-50 p-4 text-sm text-blue-800 dark:bg-gray-800 dark:text-blue-400"
                role="alert">
                <svg
                    class="me-3 mt-[2px] inline h-5 w-5 flex-shrink-0"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor"
                    viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                </svg>
                <span class="sr-only">Info</span>
                <div>
                    <span class="text-xl">Svar på opgave:</span>
                    <p class="text-md pt-2">{{ homeworkSubmission.feedback }}</p>
                </div>
            </div>
            <Card>
                <template #header>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                        Opgave aflevering
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
                            <h3 class="mb-2 font-semibold">Opgave beskrivelse:</h3>
                            <p class="text-muted-foreground text-sm">{{ homework.description }}</p>
                        </div>
                        <div>
                            <h4 class="bold text-lg">Opgave tekst</h4>
                            <textarea
                                id="comment"
                                v-model="form.content"
                                class="mt-2 h-24 w-full rounded-md border border-gray-300 p-2 dark:border-gray-700 dark:bg-gray-600"
                                placeholder="Any additional comments about your submission..."></textarea>
                            <div v-if="form.errors.content">{{ form.errors.content }}</div>
                        </div>
                        <div>
                            <h4 class="bold text-lg">Upload dine filer</h4>
                            <input
                                type="file"
                                name="files"
                                multiple
                                class="mt-2"
                                @change="handleFileChange" />
                        </div>
                        <div
                            v-if="form.progress && form.progress.percentage !== 100"
                            class="mt-2 h-2.5 w-full rounded-full bg-gray-200 dark:bg-gray-700">
                            <div
                                class="h-2.5 rounded-full bg-blue-600"
                                :style="progress.percentage"></div>
                        </div>
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
                    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Filer</h2>
                </template>
                <div class="flex flex-col gap-2 px-3 pb-2 pt-5">
                    <ul>
                        <li
                            v-for="file in files"
                            :key="file.id">
                            <div class="flex justify-between">
                                <a
                                    :href="route('document.view', file.id)"
                                    target="_blank"
                                    class="text-blue-500 hover:text-blue-700"
                                    >{{ file.filename }}</a
                                >
                                <div class="flex gap-2">
                                    <a
                                        :href="route('document.download', file.id)"
                                        class="text-blue-500 hover:text-blue-700"
                                        >Download</a
                                    >
                                    <form @submit.prevent="deleteFile(file.id)">
                                        <input
                                            type="hidden"
                                            name="_method"
                                            value="DELETE" />
                                        <button
                                            type="submit"
                                            class="text-red-500 hover:text-red-700">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </Card>
        </div>
    </AppLayout>
</template>

<style scoped></style>
