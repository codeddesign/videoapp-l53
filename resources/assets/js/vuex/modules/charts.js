import {
  LOAD_DATA,
  EVENT_RECEIVED
} from '../mutation-types'

import _ from 'lodash'

const state = {
  revenue: [],
  impressions: [],
  requests: [],
  events: []
}

const actions = {
  loadData({ commit }, data) {
    commit(LOAD_DATA, data.data)
  },

  eventReceived({ commit }, event) {
    commit(EVENT_RECEIVED, event)
  }
}

const mutations = {
  [LOAD_DATA](state, data) {
    state.revenue = data.revenue
    state.impressions = data.impressions
    state.requests = data.requests
  },

  [EVENT_RECEIVED](state, event) {
    state.events.push(event)
  }
}

const getters = {
  latestEvent: (state, getters) => {
    return _.last(state.events)
  }
}

export default {
  state,
  actions,
  mutations,
  getters
}
