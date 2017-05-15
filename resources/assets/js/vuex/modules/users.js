import {
  LOAD_USER,
  LOAD_REPORTS
} from '../mutation-types'

import User from '../../models/user'

const state = {
  currentUser: {},
  reports: []
}

const actions = {
  loadUser({ commit }) {
    User.load().then((user) => {
      commit(LOAD_USER, user.data)
    })
  },

  loadReports({ commit }) {
    User.loadReports().then((reports) => {
      commit(LOAD_REPORTS, reports)
    })
  }
}

const mutations = {
  [LOAD_USER](state, user) {
    state.currentUser = user
  },
  [LOAD_REPORTS](state, reports) {
    state.reports = reports
  }
}

const getters = {}

export default {
  namespaced: true,
  state,
  actions,
  mutations,
  getters
}
