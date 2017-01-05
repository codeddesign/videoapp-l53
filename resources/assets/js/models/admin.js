import axios from '../services/http'

export default {
  loadPendingWebsites() {
    return axios.get('/admin/websites/pending')
    .then((response) => {
      return response.data
    })
  },

  loadGlobalOptions() {
    return axios.get('/admin/globalOptions')
    .then((response) => {
      return response.data.data
    })
  },

  loadAccounts() {
    return axios.get('/admin/accounts?include=campaigns,websites,notes')
    .then((response) => {
      return response.data
    })
  },

  activateAccount(id, status) {
    return axios.post('/admin/accounts/' + id + '/activate?include=campaigns', {
      status: status
    })
    .then((response) => {
      return response.data.data
    })
  },

  activateWebsite(id, status) {
    return axios.post('/admin/websites/' + id + '/activate', {
      status: status
    })
    .then((response) => {
      return response.data.data
    })
  },

  updateGlobalOptions(options) {
    return axios.put('/admin/globalOptions', options)
    .then((response) => {
      return response.data.data
    })
  },

  createReport(report) {
    return axios.post('/admin/reports', report)
    .then((response) => {
      return response.data.data
    })
  },

  loadReports() {
    return axios.get('/admin/reports')
    .then((response) => {
      return response.data.data
    })
  },

  deleteReports(reports) {
    return axios.post('/admin/reports/delete', {
      reports: reports
    })
    .then((response) => {
      return response.data.deleted_reports
    })
  }
}
