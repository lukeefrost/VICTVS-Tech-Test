import { ref } from 'vue'
import * as api from '../api/schedules'

export function useSchedules() {
    const items = ref([])
    const loading = ref(false)
    const error = ref(null)
    const filters = ref({
        status: '',
        candidate: '',
        country: '',
        date: '',
        sort: 'datetime:asc'
    })

    async function load() {
        loading.value = true
        error.value = null
        try {
            const params = { ...filters.value }
            Object.keys(params).forEach(k => { if (!params[k]) delete params[k] })
            const payload = await api.getSchedules(params)
            items.value = payload.data || payload
            return payload
        } catch (e) {
            error.value = e?.message || 'Failed to load'
            throw e
        } finally {
            loading.value = false
        }
    }

    async function save(id) {
        try {
            const response = await api.saveSchedule(id)
            const updated = response.data || response
            const idx = items.value.findIndex(i => i.id === id)
            if (idx !== -1) items.value.splice(idx, 1, updated)
            return updated
        } catch (e) {
            error.value = e?.message || 'Failed to update'
            throw e
        }
    }

    async function cancel(id) {
        try {
            const response = await api.cancelSchedule(id);
            const updated = response.data || response;
            const idx = items.value.findIndex((i) => i.id === id);
            if (idx !== -1) items.value.splice(idx, 1, updated);
            return updated;
        } catch (e) {
            error.value = e?.message || 'Failed to update';
            throw e;
        }
    }

    return { items, loading, error, filters, load, save, cancel };
}
