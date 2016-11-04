import Vue from 'vue'

export default {
  loadPendingWebsites() {
    return Vue.http.get('/api/admin/websites/pending')
    .then((response) => {
      return response.data
    })
  },

  loadAccounts() {
    return Vue.http.get('/api/admin/accounts?include=campaigns')
    .then((response) => {
      return response.data
    })
  },

  activateAccount(id, status) {
    return Vue.http.post('/api/admin/accounts/' + id + '/activate?include=campaigns', {
      status: status
    })
    .then((response) => {
      return response.data.data
    })
  }
}
