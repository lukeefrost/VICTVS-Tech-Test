<template>
    <div
        class="flex items-center justify-between rounded-lg bg-white p-4 shadow-sm"
    >
        <div>
            <h3 class="text-lg font-semibold">{{ schedule.title }}</h3>
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
                @click="$emit('save', schedule.id)"
                class="rounded border px-3 py-1 text-sm hover:bg-gray-50"
            >
                Advance status
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

const props = defineProps<Props>();

defineEmits<{
    save: [id: number];
}>();

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
        default:
            return 'bg-blue-100 text-blue-800';
    }
});
</script>
