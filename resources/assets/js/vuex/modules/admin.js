import {
  LOAD_PENDING_WEBSITES,
  LOAD_ACCOUNTS,
  ACTIVATE_USER
} from '../mutation-types'

import Admin from '../../models/admin'

const state = {
  pendingWebsites: [],
  accounts: []
}

const actions = {
  loadPendingWebsites({ commit }) {
    Admin.loadPendingWebsites().then((sites) => {
      commit(LOAD_PENDING_WEBSITES, sites.data)
    })
  },

  loadAccounts({ commit }) {
    Admin.loadAccounts().then((accounts) => {
      commit(LOAD_ACCOUNTS, accounts.data)
    })
  },

  activateUser({ commit }, data) {
    Admin.activateAccount(data.id, data.status).then((user) => {
      commit(ACTIVATE_USER, user)
    })
  }
}

const mutations = {
  [LOAD_PENDING_WEBSITES](state, sites) {
    state.pendingWebsites = sites
  },
  [LOAD_ACCOUNTS](state, accounts) {
    state.accounts = accounts
  },
  [ACTIVATE_USER](state, user) {
    _.remove(state.accounts, (account) => {
      return account.id == user.id
    })
    state.accounts.push(user)
  }
}

const getters = {}

export default {
  state,
  actions,
  mutations,
  getters
}
