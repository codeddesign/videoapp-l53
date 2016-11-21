import axios from '../services/http'

export default {
  all() {
    return axios.get('admin/tags')
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
      ad_type: 'all',
      date_range: false,
      start_date: '',
      end_date: '',
      campaign_types: {
        preroll: false,
        onscroll: false,
        infinity: false,
        unknown: false
      },
      included_locations: [],
      excluded_locations: [],
      timeout_limit: '',
      daily_request_limit: '',
      wrapper_limit: '',
      guarantee_limit: '',
      guarantee_order: '1',
      guarantee_enabled: false,
      ecpm: '',
      delay_time: '',
      priority_count: ''
    }
  }
}
