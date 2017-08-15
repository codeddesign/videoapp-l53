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
    return axios.get('/admin/accounts?include=campaigns,websites,websites.backfill,websites.backfill.adType,notes')
    .then((response) => {
      return response.data
    })
  },

  activateAccount(id, status) {
    return axios.post('/admin/accounts/' + id + '/activate?include=campaigns,websites,websites.backfill,websites.backfill.adType,notes', {
      status: status
    })
    .then((response) => {
      return response.data.data
    })
  },

  addWebsite(userId, domain) {
    return axios.post('/admin/websites?include=backfill,backfill.adType', {
      user_id: userId,
      domain: domain
    })
    .then((response) => {
      return response.data
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

  ownedWebsite(id, status) {
    return axios.post('/admin/websites/' + id + '/owned', {
      status: status
    })
    .then((response) => {
      return response.data.data
    })
  },

  activateCampaign(id, status) {
    return axios.post('/admin/campaigns/' + id + '/activate', {
      active: status
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

  saveReport(report) {
    if (report.id === 0) {
      return this.createReport(report)
    } else {
      return this.updateReport(report)
    }
  },

  saveAccount(account) {
    return axios.post('/admin/accounts', account)
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

  updateReport(report) {
    return axios.patch('/admin/reports/' + report.id, report)
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
  },

  loadWebsites() {
    return axios.get('/admin/websites')
    .then((response) => {
      return response.data.data
    })
  },

  loadCampaignsStats(userId, range) {
    return axios.get('/admin/campaigns/stats?user_id=' + userId + '&time=' + range)
      .then((response) => {
        return response.data.data
      })
  }
}
