<script setup lang="ts">
import AppLayout from "../../Layouts/AppLayout.vue";
import Card from "../../Components/Card.vue";
import GradeData = App.Data.GradeData;

interface Props {
    grades: GradeData[];
}

const props = defineProps<Props>();
</script>

<template>
    <AppLayout title="Karaktere">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Karaktere</h2>
        </template>

        <Card>
            <template #header> Karaktere</template>

            <div class="max-h-[800px] min-w-full overflow-y-auto p-4 align-middle">
                <table class="min-w-full divide-y divide-gray-700 overflow-x-auto">
                    <thead>
                        <tr>
                            <th
                                scope="col"
                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-0">
                                Hold
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-3.5 text-left text-sm font-semibold text-white">
                                Lærer
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-3.5 text-left text-sm font-semibold text-white">
                                Karakter
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-3.5 text-left text-sm font-semibold text-white">
                                Kommentar
                            </th>

                            <th
                                scope="col"
                                class="px-3 py-3.5 text-left text-sm font-semibold text-white">
                                Tildelt
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700 overflow-x-auto">
                        <tr
                            v-for="grade in grades"
                            :key="grade.id">
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white sm:pl-0">
                                {{ grade.team?.name }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-white">
                                {{ grade.teacher?.user?.name }}
                            </td>

                            <td class="whitespace-nowrap px-3 py-4 text-sm text-white">
                                {{ grade.grade }}
                            </td>

                            <td class="whitespace-nowrap px-3 py-4 text-sm text-white">
                                {{ grade.comment }}
                            </td>

                            <td class="whitespace-nowrap px-3 py-4 text-sm text-white">
                                {{ grade.createdAt }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </Card>
    </AppLayout>
</template>
