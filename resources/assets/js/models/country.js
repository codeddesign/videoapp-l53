import axios from '../services/http'

export default {
  all() {
    return axios.get('admin/countries')
      .then((response) => {
        return response.data.data
      })
  }
}
