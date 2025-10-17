import axios from  'axios'

const API_LINK = '/api'

export async function getSchedules(params = {}) {
    const res = await axios.get(`${API_LINK}/schedules`, { params })
    return res.data
}

export async function saveSchedule(id) {
    const res = await axios.patch(`${API_LINK}/schedules/${id}`)
    return res.data
}
