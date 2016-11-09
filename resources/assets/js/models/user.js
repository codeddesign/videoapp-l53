import auth from '../services/auth'
import axios from '../services/http'

export default {
  load() {
    return axios.get('/user')
      .then((response) => {
        return response.data
      })
      .catch((errorResponse) => {
        if (errorResponse.status === 401) {
          console.log('logout')
          auth.logout()
        }
      })
  }
}
