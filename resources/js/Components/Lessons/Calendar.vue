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

interface Props {
    now: string | Date;
    initialView?: string;
}

const props = defineProps<Props>();

const calendarRef = ref(null);

const calendarOptions = {
    schedulerLicenseKey: "CC-Attribution-NonCommercial-NoDerivatives",
    locale: "da",
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
    buttonText: {
        today: "I dag",
        month: "Måned",
        week: "Uge",
        day: "Dag",
        list: "Liste",
    },
    hiddenDays: [0, 6],
    eventDidMount: (info) => {
        console.log(info.event.extendedProps.description);
        tippy(info.el, {
            content: info.event.extendedProps.description || "No description available",
            placement: "top",
            trigger: "hover",
            theme: "dark",
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

<style>
:root {
    --fc-bg-color: #1a1a2e; /* Background color for calendar areas */
    --fc-border-color: #444; /* Border color */
    --fc-text-color: #f0f0f0; /* Text color */
    --fc-button-bg-color: #3a3a5c; /* Button background */
    --fc-button-hover-bg-color: #4a4a7a; /* Button hover background */
    --fc-today-bg-color: #333c49; /* Background for today */
    --fc-event-bg-color: #374151; /* Background for events */
    --fc-event-bg-hover-color: #303742; /* Background for events */
    --fc-event-text-color: #ffffff; /* Text color for events */
}

.fc .fc-list-sticky .fc-list-day > * {
    background: var(--fc-event-bg-color) !important;
    color: var(--fc-text-color) !important;
}

.fc .fc-cell-shaded,
.fc .fc-day-disabled {
    background-color: var(--fc-event-bg-color) !important;
}

.fc .fc-list-event:hover td {
    background-color: var(--fc-event-bg-hover-color) !important;
}

.fc .fc-scrollgrid-section-sticky > * {
    background-color: transparent !important;
    color: var(--fc-text-color) !important;
}

.fc-button,
.fc-button-primary {
    background-color: var(--fc-event-bg-color) !important;
    color: var(--fc-text-color) !important;
}

.fc-button:hover {
    background-color: var(--fc-event-bg-hover-color) !important;
}

.fc-event:hover {
    background-color: var(--fc-event-bg-hover-color) !important;
    color: var(--fc-event-text-color) !important;
}

.fc-list-event:hover {
    background-color: var(--fc-event-bg-hover-color) !important;
    color: var(--fc-event-text-color) !important;
}

.fc-theme-standard .fc-list {
    border: 1px solid var(--fc-border-color);
    border-radius: 4px !important;
    overflow: hidden;
}
</style>
