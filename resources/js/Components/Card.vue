<script setup lang="ts">
interface Props {
    contentClasses?: string;
    headerClasses?: string;
    hasHeader?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    contentClasses: "",
    headerClasses: "",
    hasHeader: true,
});
</script>

<template>
    <div
        :class="[
            'w-full rounded-lg border border-gray-200 bg-white shadow dark:border-gray-700 dark:bg-gray-800',
            contentClasses,
        ]">
        <div
            v-if="hasHeader"
            :class="[
                'relative rounded-t-md bg-gray-700 p-1 text-center text-xl font-medium text-gray-500 dark:divide-gray-600 dark:text-gray-400',
                headerClasses,
            ]">
            <slot name="header" />
        </div>

        <div
            class="border-gray-200 text-gray-200 dark:border-gray-600"
            :class="{ 'border-t': hasHeader }">
            <slot />
        </div>
    </div>
</template>

<style scoped></style>
