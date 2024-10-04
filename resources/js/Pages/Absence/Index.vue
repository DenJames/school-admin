<script setup lang="ts">
import AppLayout from "../../Layouts/AppLayout.vue";
import Card from "../../Components/Card.vue";
import { Link } from "@inertiajs/vue3";

interface Props {
    absences: any[];
}

const props = defineProps<Props>();
</script>

<template>
    <AppLayout title="Fravær">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Fravær</h2>
        </template>

        <Card>
            <template #header> Fravær</template>

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
                                Opgjort
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-3.5 text-left text-sm font-semibold text-white">
                                Opgjort moduler
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-3.5 text-left text-sm font-semibold text-white">
                                Årlig opgjort
                            </th>

                            <th
                                scope="col"
                                class="px-3 py-3.5 text-left text-sm font-semibold text-white">
                                Årlig opgjort moduler
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700 overflow-x-auto">
                        <tr
                            v-for="absence in absences"
                            :key="absence.id">
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white sm:pl-0">
                                <Link
                                    :href="route('absences.show', absence.team_id)"
                                    class="hover:underline">
                                    {{ absence.team_name }}
                                </Link>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-white">
                                {{ absence.absence_for_lesson_percentage }}%
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-white">
                                {{ absence.total_absence_count }} /
                                {{ absence.total_lessons }}
                            </td>

                            <td class="whitespace-nowrap px-3 py-4 text-sm text-white">
                                {{ absence.absence_for_year_percentage }}%
                            </td>

                            <td class="whitespace-nowrap px-3 py-4 text-sm text-white">
                                {{ absence.absence_count_year }} /
                                {{ absence.total_lessons_year }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </Card>
    </AppLayout>
</template>
