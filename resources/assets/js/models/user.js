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
          // TODO: Maybe we should handle the other error codes differently?
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

  loadChart(id) {
    return axios.get('/admin/charts/all?time=today&user=' + id)
      .then((response) => {
        return response.data
      })
  },

  loadWebsiteStats(id) {
    return axios.get('/admin/websites/stats?user_id=' + id)
      .then((response) => {
        return response.data.data
      })
  }
}
