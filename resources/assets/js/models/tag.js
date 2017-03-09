import axios from '../services/http'

export default {
  all(range = null) {
    let url = 'admin/tags'

    if (range !== null) {
      url = url + '?compareRange=' + range
    }

    return axios.get(url)
      .then((response) => {
        return response.data.data
      })
  },

  create(tag) {
    return axios.post('/admin/tags', tag)
      .then((response) => {
        return response.data
      })
  },

  update(tag) {
    return axios.patch('/admin/tags/' + tag.id, tag)
      .then((response) => {
        return response.data
      })
  },

  delete(tag) {
    return axios.delete('/admin/tags/' + tag.id)
      .then((response) => {
        return response.data
      })
  },

  save(tag) {
    if (tag.id === 0) {
      return this.create(tag)
    } else {
      return this.update(tag)
    }
  },

  activate(id, status) {
    return axios.post('/admin/tags/' + id + '/activate', {
      status: status
    })
    .then((response) => {
      return response.data.data
    })
  },

  default() {
    return {
      id: 0,
      url: '',
      advertiser: '',
      description: '',
      platform_type: 'all',
      type: 'outstream',
      date_range: false,
      start_date: '',
      end_date: '',
      ad_types: [],
      included_locations: [],
      excluded_locations: [],
      included_websites: [],
      excluded_websites: [],
      timeout_limit: '',
      daily_request_limit: '',
      wrapper_limit: '',
      infinity_timeout_limit: '',
      infinity_daily_request_limit: '',
      infinity_wrapper_limit: '',
      guarantee_limit: '',
      guarantee_order: '1',
      guarantee_enabled: false,
      ecpm: '',
      delay_time: '',
      priority_count: '',
      demo_data: {
        platform_type: 'all',
        type: 'outstream',
        ad_types: [1],
        timeout_limit: '',
        wrapper_limit: '',
        delay_time: '',
        session_max_requests: ''
      }
    }
  }
}
