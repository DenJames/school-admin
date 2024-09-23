<script setup lang="ts">
import { onMounted, ref } from "vue";
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import rrulePlugin from "@fullcalendar/rrule";
import listPlugin from "@fullcalendar/list";
import resourceTimelinePlugin from "@fullcalendar/resource-timeline";
import tippy from "tippy.js";
import "tippy.js/dist/tippy.css"; // Tippy.js CSS

const props = defineProps<{
    now: string | Date; // Define the type of the `now` prop, adjust according to your data type
    initialView?: string; // Define the type of the `initialView` prop, adjust according to your data type
}>();

const calendarRef = ref(null);

const calendarOptions = {
    schedulerLicenseKey: "CC-Attribution-NonCommercial-NoDerivatives",
    locale: "en",
    weekNumbers: true,
    timeZone: "Europe/Copenhagen",
    plugins: [dayGridPlugin, timeGridPlugin, listPlugin, resourceTimelinePlugin, rrulePlugin],
    initialView: props.initialView ?? "timeGridWeek",
    footerToolbar: {
        left: "timeGridDay,timeGridWeek,dayGridMonth,listWeek",
    },
    aspectRatio: 1,
    slotDuration: "00:15:00", // Show lines for every 15 minutes
    slotLabelInterval: "00:15:00", // Label every 15 minutes
    height: "auto",
    slotLabelFormat: {
        // Customize time label format
        hour: "numeric", // Display hours without leading zeros
        minute: "2-digit", // Display minutes with two digits (e.g., 8:00, 8:15)
        hour12: false, // Use 24-hour format, set to true for AM/PM
    },
    slotMinTime: "08:00:00",
    slotMaxTime: "16:00:00",
    businessHours: {
        startTime: "08:00",
        endTime: "16:00",
    },
    nowIndicator: true,
    now: props.now,
    themeSystem: "bootstrap5",
    events: "/api/lessons",
    firstDay: 1,
    hiddenDays: [0, 6],
    eventDidMount: (info) => {
        console.log(info.event.extendedProps.description);
        tippy(info.el, {
            content: info.event.extendedProps.description || "No description available",
            placement: "top",
            trigger: "hover",
            theme: "light",
        });
    },
    allDaySlot: false,
};

onMounted(() => {
    const calendarApi = calendarRef.value?.getApi();
    if (calendarApi) {
        calendarApi.updateSize(); // Ensure the calendar is properly sized after mounting
    }
});
</script>

<template>
    <FullCalendar
        ref="calendarRef"
        :options="calendarOptions" />
</template>

<style scoped>
.fc-timegrid-slot-label-cushion {
    color: #fff !important;
}
</style>
