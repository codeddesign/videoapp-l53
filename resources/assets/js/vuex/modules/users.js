import {
  LOAD_USER,
  LOAD_REPORTS,
  IMPERSONATE
} from '../mutation-types'

import User from '../../models/user'
import http from '../../services/http'
import router from '../../router'

const state = {
  currentUser: {},
  reports: [],
  impersonating: false
}

const actions = {
  clearUser({ commit }) {
    commit(LOAD_USER, {})
  },

  loadUser({ commit }) {
    User.load().then((user) => {
      commit(LOAD_USER, user.data)
    })
  },

  loadReports({ commit }) {
    User.loadReports().then((reports) => {
      commit(LOAD_REPORTS, reports)
    })
  },

  impersonate({ dispatch, commit }, value) {
    if (value != null) {
      http.defaults.headers.common['Ad3-Impersonate'] = value
    } else {
      delete http.defaults.headers.common['Ad3-Impersonate']
    }

    dispatch('clearUser')
    dispatch('loadUser')

    commit(IMPERSONATE, value)
    router.push({ name: 'dashboard' })
  }
}

const mutations = {
  [LOAD_USER](state, user) {
    state.currentUser = user
  },
  [LOAD_REPORTS](state, reports) {
    state.reports = reports
  },
  [IMPERSONATE](state, value) {
    state.impersonating = value
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
