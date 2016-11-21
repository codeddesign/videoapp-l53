import axios from '../services/http'

export default {
  all() {
    return axios.get('admin/locations')
      .then((response) => {
        return response.data.data
      })
  },

  expand(location) {
    return axios.post('admin/locations/expand', location)
          .then((response) => {
            return response.data.data
          })
  }
}
