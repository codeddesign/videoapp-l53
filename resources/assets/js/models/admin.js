import axios from '../services/http'

export default {
  loadPendingWebsites() {
    return axios.get('/admin/websites/pending')
    .then((response) => {
      return response.data
    })
  },

  loadAccounts() {
    return axios.get('/admin/accounts?include=campaigns')
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
  }
}
