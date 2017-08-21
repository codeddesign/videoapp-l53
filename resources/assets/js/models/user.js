import auth from '../services/auth'
import axios from '../services/http'

export default {
  load() {
    return axios.get('/user')
      .then((response) => {
        return response.data
      })
      .catch((error) => {
        if (error.response.status === 401) {
          console.log('logout')
          auth.logout()
        } else {
          console.error("Failed to get user's info")
        }
      })
  },

  update(account) {
    return axios.patch('/user', account)
      .then((response) => {
        return response.data.data
      })
  },

  loadReports() {
    return axios.get('/reports')
    .then((response) => {
      return response.data.data
    })
  },

  loadTags() {
    return axios.get('/tags')
    .then((response) => {
      return response.data.data
    })
  },

  saveReport(report) {
    if (report.id === 0) {
      return this.createReport(report)
    } else {
      return this.updateReport(report)
    }
  },

  createReport(report) {
    return axios.post('/reports', report)
    .then((response) => {
      return response.data.data
    })
  },

  updateReport(report) {
    return axios.patch('/reports/' + report.id, report)
    .then((response) => {
      return response.data.data
    })
  },

  addNote(id, note) {
    return axios.post('/admin/accounts/' + id + '/note', { content: note })
      .then((response) => {
        return response.data.data
      })
  },

  saveBackfill(backfill) {
    if (backfill.id === 0) {
      return this.newBackfill(backfill)
    } else {
      return this.updateBackfill(backfill)
    }
  },

  newBackfill(backfill) {
    return axios.post('/admin/backfill/' + backfill.website_id + '', backfill)
      .then((response) => {
        return response.data.data
      })
  },

  updateBackfill(backfill) {
    return axios.patch('/admin/backfill/' + backfill.id + '', backfill)
      .then((response) => {
        return response.data.data
      })
  },

  deleteBackfill(backfill) {
    return axios.post('/admin/backfill/delete', {
      backfill: backfill
    })
    .then((response) => {
      return response.data.deleted_reports
    })
  },

  activateBackfill(id, status) {
    return axios.post('/admin/backfill/' + id + '/activate', {
      status: status
    })
    .then((response) => {
      return response.data.data
    })
  },

  loadChart(id) {
    return axios.get('/admin/charts/all?time=sevenDays&user=' + id)
      .then((response) => {
        return response.data
      })
  },

  loadWebsites() {
    return axios.get('/websites')
    .then((response) => {
      return response.data.data
    })
  },

  loadWebsiteStats(id, range) {
    return axios.get('/admin/websites/stats?user_id=' + id + '&time=' + range)
      .then((response) => {
        return response.data.data
      })
  },

  defaultBackfill() {
    return {
      id: 0,
      embed: '',
      advertiser: '',
      ad_type: 1,
      width: 'responsive',
      platform_type: 'all',
      website_id: '',
      ecpm: ''
    }
  }
}
