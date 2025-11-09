<template>
    <div>
        <FilterBar
            :filters="filters"
            @apply="applyFilters"
            @clear="clearFilters"
        />

        <div v-if="loading" class="text-center">Loading…</div>

        <div v-else class="grid gap-4">
            <ScheduleCard
                v-for="s in items"
                :key="s.id"
                :schedule="s"
                @advance="onAdvance"
                @cancel="onCancel"
            />
        </div>

        <div v-if="error" class="mt-4 text-red-600">{{ error }}</div>
    </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import { useSchedules } from '@/composables/useSchedules';
import FilterBar from '@/components/FilterBar.vue';
import ScheduleCard from '@/components/ScheduleCard.vue';

const { items, loading, error, filters, load, save, cancel } = useSchedules();

onMounted(() => load());

async function applyFilters(newFilters: any) {
    Object.assign(filters.value, newFilters);
    await load();
}

function clearFilters() {
    filters.value = {
        status: '',
        candidate: '',
        country: '',
        date: '',
        sort: 'datetime:asc',
    };
    load();
}

async function onAdvance(id: number) {
    await save(id);
}

async function onCancel(id: number) {
    await cancel(id);
}
</script>

<style scoped></style>
