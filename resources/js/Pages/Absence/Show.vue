<script setup lang="ts">
import AppLayout from "../../Layouts/AppLayout.vue";
import Card from "../../Components/Card.vue";

interface Props {
    team: any;
    absences: any[];
}

const props = defineProps<Props>();
</script>

<template>
    <AppLayout :title="`Fravær - ${team.name}`">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Fravær for hold {{ team.name }}
            </h2>
        </template>

        <Card>
            <template #header> Fravær for hold {{ team.name }}</template>

            <div class="inline-block max-h-[800px] min-w-full overflow-y-auto p-4 align-middle">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead>
                        <tr>
                            <th
                                scope="col"
                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-0">
                                Dato
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-3.5 text-left text-sm font-semibold text-white">
                                Tid
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-3.5 text-left text-sm font-semibold text-white">
                                Navn
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-3.5 text-left text-sm font-semibold text-white">
                                Lærer
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-3.5 text-left text-sm font-semibold text-white">
                                Årsag
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-3.5 text-left text-sm font-semibold text-white">
                                Lovligt fravær
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        <template
                            v-for="absence in absences"
                            :key="absence.lesson_id">
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-white sm:pl-0">
                                    {{ new Date(absence.starts_at).toLocaleDateString("da-DK") }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-white">
                                    {{
                                        new Date(absence.starts_at).toLocaleTimeString("da-DK", {
                                            timeZone: "Europe/Copenhagen",
                                            hour: "2-digit",
                                            minute: "2-digit",
                                            hour12: false,
                                        })
                                    }}
                                    -
                                    {{
                                        new Date(absence.ends_at).toLocaleTimeString("da-DK", {
                                            timeZone: "Europe/Copenhagen",
                                            hour: "2-digit",
                                            minute: "2-digit",
                                            hour12: false,
                                        })
                                    }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-white">
                                    {{ absence.lesson_name }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-white">
                                    {{ absence.teacher_name }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-white">
                                    {{ absence.reason }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-white">
                                    {{ !!absence.excused ? "Ja" : "Nej" }}
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </Card>
    </AppLayout>
</template>
