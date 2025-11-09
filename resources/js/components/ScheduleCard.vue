<template>
    <div
        class="flex items-center justify-between rounded-lg bg-white p-4 shadow-sm"
    >
        <div>
            <div class="mb-4">
                <h2 class="mb-2 text-xl font-semibold">
                    {{ schedule.title }}
                </h2>
                <h3 class="text-xl font-semibold">
                    Schedule ID: {{ schedule.id }}
                </h3>
            </div>
            <p class="text-sm text-gray-500">{{ formattedDate }}</p>
            <p class="mt-1 text-sm">
                Candidates:
                <span class="font-medium">{{ candidateNames }}</span>
            </p>
            <p class="text-sm">
                Location: {{ location }} · Language:
                {{ schedule.language || '—' }}
            </p>
        </div>

        <div class="flex flex-col items-end gap-2">
            <span :class="statusClass" class="rounded-full px-3 py-1 text-sm">{{
                schedule.status
            }}</span>
            <button
                @click="$emit('advance', schedule.id)"
                class="rounded border px-3 py-1 text-sm hover:bg-gray-50"
            >
                Change Status
            </button>
            <button
                @click="$emit('cancel', schedule.id)"
                class="rounded border px-3 py-1 text-sm hover:bg-gray-50"
            >
                Cancel
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

interface Candidate {
    name: string;
}

interface Location {
    country: string;
}

interface Schedule {
    id: number;
    title: string;
    datetime: string;
    candidates?: (string | Candidate)[];
    location?: Location;
    language?: string;
    status: string;
}

interface Props {
    schedule: Schedule;
}

// Represents data passed from a parent component.
const props = defineProps<Props>();

// Represents custom events the component can send back to its parent
defineEmits<{
    advance: [id: number];
    cancel: [id: number];
}>();

// Represents reactive, auto-updating values that depend on other data (like props)
// Used to format or filter data
const candidateNames = computed(() => {
    const c = props.schedule.candidates || [];
    if (!c.length) return '';
    if (typeof c[0] === 'string') return c.join(', ');
    return (c as Candidate[]).map((x) => x.name).join(', ');
});

const formattedDate = computed(() => {
    return props.schedule.datetime
        ? new Date(props.schedule.datetime).toLocaleString()
        : '—';
});

const location = computed(() => {
    return props.schedule.location?.country || '—';
});

const statusClass = computed(() => {
    switch (props.schedule.status) {
        case 'Finished':
            return 'bg-green-100 text-green-800';
        case 'Started':
            return 'bg-yellow-100 text-yellow-800';
        case 'Cancelled':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-blue-100 text-blue-800';
    }
});
</script>
