<script setup lang="ts">
import HomeworkData = App.Data.HomeworkData;
import Checkmark from "../Icons/Checkmark.vue";
import Cross from "../Icons/Cross.vue";

interface Props {
    homework: HomeworkData[];
}

const props = defineProps<Props>();
</script>

<template>
    <table class="min-w-full divide-y divide-gray-200 dark:bg-gray-800">
        <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
                <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-white"
                    style="width: 5%">
                    Status
                </th>
                <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-white">
                    Title
                </th>
                <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-white">
                    Description
                </th>
                <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-white">
                    Due Date
                </th>
                <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-white">
                    Actions
                </th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white dark:bg-gray-700">
            <tr
                v-for="item in homework"
                :key="item.id">
                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-white">
                    <Checkmark
                        v-if="item.isSubmitted"
                        class="h-5 text-green-400" />
                    <Cross
                        v-else
                        class="h-5 text-red-400" />
                </td>
                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-white">
                    {{ item.name }}
                </td>
                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-white">
                    {{ item.description }}
                </td>
                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-white">
                    {{ item.dueDateForHumans }}
                </td>
                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-white">
                    <a
                        :href="route('homework.show', item.id)"
                        class="text-indigo-600 hover:text-indigo-900"
                        >View</a
                    >
                </td>
            </tr>
        </tbody>
    </table>
</template>
