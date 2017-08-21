import axios from '../services/http'

const makeUrl = (url, prependAdmin) => {
  if (prependAdmin) {
    return 'admin/' + url
  }

  return url
}

export default {
  all(range = null, admin = false) {
    let url = makeUrl('tags', admin)

    if (range !== null) {
      url = url + '?compareRange=' + range
    }

    return axios.get(url)
      .then((response) => {
        return response.data.data
      })
  },

  create(tag, admin = false) {
    let url = makeUrl('tags', admin)

    return axios.post(url, tag)
      .then((response) => {
        return response.data
      })
  },

  update(tag, admin = false) {
    let url = makeUrl('tags/' + tag.id, admin)

    return axios.patch(url, tag)
      .then((response) => {
        return response.data
      })
  },

  delete(tag, admin = false) {
    let url = makeUrl('tags/' + tag.id, admin)

    return axios.delete(url)
      .then((response) => {
        return response.data
      })
  },

  save(tag, admin = false) {
    if (tag.id === 0) {
      return this.create(tag, admin)
    } else {
      return this.update(tag, admin)
    }
  },

  activate(id, status, admin = false) {
    let url = makeUrl('tags/' + id + '/activate', admin)

    return axios.post(url, {
      status: status
    })
    .then((response) => {
      return response.data.data
    })
  },

  default(forOwned = false) {
    return {
      id: 0,
      url: '',
      advertiser: '',
      description: '',
      platform_type: 'all',
      type: 'outstream',
      date_range: false,
      for_owned: forOwned,
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
