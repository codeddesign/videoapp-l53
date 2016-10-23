import {
  LOAD_DATA
} from '../mutation-types'

import _ from 'lodash'

const state = {
  revenue: [],
  impressions: [],
  requests: []
}

const actions = {
  loadData({ commit }, data) {
    commit(LOAD_DATA, data.data)
  }
}

const mutations = {
  [LOAD_DATA](state, data) {
    state.revenue = data.revenue
    state.impressions = data.impressions
    state.requests = data.requests
  }
}

const getters = {
}

export default {
  state,
  actions,
  mutations,
  getters
}
