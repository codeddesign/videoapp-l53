import Vue from 'vue'
import auth from '../services/auth'

export default {
  load() {
    return Vue.http.get('/api/user')
    .then((response) => {
      return response.data
    }, (errorResponse) => {
      if (errorResponse.status === 401) {
        console.log('logout')
        auth.logout()
      }
    })
  }
}
