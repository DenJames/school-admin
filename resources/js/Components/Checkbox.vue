<script setup lang="ts">
import { computed } from "vue";

interface Props {
    checked?: boolean | Array<string>;
    value?: string | null;
}

interface Emits {
    (event: "update:checked", value: boolean | Array<string>): void;
}

const props = withDefaults(defineProps<Props>(), {
    checked: false,
    value: null,
});

const emit = defineEmits<Emits>();

const proxyChecked = computed({
    get() {
        return props.checked;
    },

    set(val) {
        emit("update:checked", val);
    },
});
</script>

<template>
    <input
        v-model="proxyChecked"
        type="checkbox"
        :value="value"
        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
</template>
