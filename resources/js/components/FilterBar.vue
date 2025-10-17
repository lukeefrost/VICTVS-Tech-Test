<template>
    <div
        class="mb-4 flex flex-wrap items-center gap-3 rounded-lg bg-white p-4 shadow"
    >
        <input
            v-model="local.candidate"
            placeholder="Candidate"
            class="w-48 rounded border p-2"
        />
        <input
            v-model="local.country"
            placeholder="Country"
            class="w-48 rounded border p-2"
        />
        <input v-model="local.date" type="date" class="rounded border p-2" />
        <select v-model="local.status" class="rounded border p-2">
            <option value="">All statuses</option>
            <option value="Pending">Pending</option>
            <option value="Started">Started</option>
            <option value="Finished">Finished</option>
        </select>
        <select v-model="local.sort" class="rounded border p-2">
            <option value="datetime:asc">Date ↑</option>
            <option value="datetime:desc">Date ↓</option>
            <option value="title:asc">Title ↑</option>
        </select>

        <div class="ml-auto flex gap-2">
            <button @click="emit('clear')" class="text-sm text-gray-600">
                Clear
            </button>
            <button
                @click="emit('apply', local)"
                class="rounded bg-blue-600 px-3 py-1 text-sm text-white"
            >
                Apply
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { reactive, watch } from 'vue';

interface Filters {
    status?: string;
    candidate?: string;
    country?: string;
    date?: string;
    sort?: string;
}

interface Props {
    filters: Filters;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    apply: [filters: Filters];
    clear: [];
}>();

const local = reactive<Filters>({ ...props.filters });

watch(
    () => props.filters,
    (newFilters) => {
        Object.assign(local, newFilters);
    },
    { deep: true },
);
</script>

<style scoped></style>
