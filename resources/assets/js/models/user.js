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
        }
      })
  },

  addNote(id, note) {
    return axios.post('/admin/accounts/' + id + '/note', { content: note })
      .then((response) => {
        return response.data.data
      })
  },

  loadWebsiteStats(id) {
    return axios.get('/admin/websites/stats?user_id=' + id)
      .then((response) => {
        return response.data.data
      })
  }
}
