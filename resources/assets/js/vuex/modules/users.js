import {
  LOAD_USER
} from '../mutation-types'

import User from '../../models/user'

const state = {
  currentUser: {}
}

const actions = {
  loadUser({ commit }) {
    User.load().then((user) => {
      commit(LOAD_USER, user.data)
    })
  }
}

const mutations = {
  [LOAD_USER](state, user) {
    state.currentUser = user
  }
}

const getters = {}

export default {
  state,
  actions,
  mutations,
  getters
}
