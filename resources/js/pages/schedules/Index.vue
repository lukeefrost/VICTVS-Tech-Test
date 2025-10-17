<template>
    <div class="min-h-screen p-6">
        <div class="mx-auto max-w-5xl">
            <header class="mb-6">
                <h1 class="text-3xl font-bold">Exam Schedules</h1>
                <p class="text-sm text-gray-600">
                    View, filter, and advance schedule status
                </p>
            </header>

            <ScheduleListContainer />
        </div>
    </div>
</template>

<script setup lang="ts">
import ScheduleListContainer from '@/components/ScheduleListContainer.vue';
</script>
