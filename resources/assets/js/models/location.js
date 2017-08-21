import axios from '../services/http'

export default {
  all() {
    return axios.get('locations')
      .then((response) => {
        return response.data.data
      })
  },

  expand(location) {
    return axios.post('locations/expand', location)
          .then((response) => {
            return response.data.data
          })
  }
}
