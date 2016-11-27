import {
  LOAD_PENDING_WEBSITES,
  LOAD_ACCOUNTS,
  ACTIVATE_USER,
  SET_SHOW_TAG_FORM,
  ACTIVATE_TAG,
  SET_CURRENT_TAG,
  SAVE_CURRENT_TAG,
  LOAD_TAGS,
  LOAD_LOCATIONS,
  LOCATION_BACK
} from '../mutation-types'

import Admin from '../../models/admin'
import Country from '../../models/country'
import Tag from '../../models/tag'
import _ from 'lodash'

const state = {
  pendingWebsites: [],
  accounts: [],
  tags: [],
  tagsToCompare: [],
  showTagForm: false,
  currentTag: Tag.default(),
  locations: {
    countries: [],
    states: [],
    cities: []
  },
  showLocations: 'countries'
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
  },

  loadTags({ commit }, range = null) {
    Tag.all(range).then((tags) => {
      commit(LOAD_TAGS, tags)
    })
  },

  setShowTagForm({ commit }, status) {
    commit(SET_SHOW_TAG_FORM, status)
  },

  setCurrentTag({ commit }, tag) {
    // Passing in a clone to prevent
    // unintended reactive actions
    commit(SET_CURRENT_TAG, _.cloneDeep(tag))
  },

  saveCurrentTag({ commit }) {
    Tag.save(state.currentTag).then((tags) => {
      commit(SAVE_CURRENT_TAG, tags.data)
    })
  },

  activateTag({ commit }, data) {
    Tag.activate(data.id, data.status).then((tag) => {
      commit(ACTIVATE_TAG, tag)
    })
  },

  loadCountries({ commit }) {
    Country.all().then((countries) => {
      commit(LOAD_LOCATIONS, countries)
    })
  },

  expandLocation({ commit }, location) {
    Country.expand(location).then(locations => {
      commit(LOAD_LOCATIONS, locations)
    })
  },

  locationBack({ commit }) {
    commit(LOCATION_BACK)
  }
}

const mutations = {
  [LOAD_PENDING_WEBSITES](state, sites) {
    state.pendingWebsites = sites
  },

  [LOAD_ACCOUNTS](state, accounts) {
    state.accounts = _.sortBy(accounts, ['id'])
  },

  [ACTIVATE_USER](state, user) {
    _.remove(state.accounts, (account) => {
      return account.id === user.id
    })
    state.accounts.push(user)
    state.accounts = _.sortBy(state.accounts, ['id'])
  },

  [SET_SHOW_TAG_FORM](state, status) {
    state.showTagForm = status
  },

  [SET_CURRENT_TAG](state, tag) {
    state.currentTag = tag
  },

  [SAVE_CURRENT_TAG](state, tags) {
    state.tags = tags
    state.currentTag = Tag.default()
    state.showTagForm = false
  },

  [LOAD_TAGS](state, tags) {
    state.tags = tags
  },

  [ACTIVATE_TAG](state, tag) {

  },

  [LOAD_LOCATIONS](state, locations) {
    if (locations.length === 0) {
      return
    }

    switch (locations[0].type) {
      case 'country':
        state.showLocations = 'countries'
        state.locations.countries = locations
        break
      case 'state':
        state.showLocations = 'states'
        state.locations.states = locations
        break
      case 'city':
        state.showLocations = 'cities'
        state.locations.cities = locations
        break
    }
  },

  [LOCATION_BACK](state) {
    switch (state.showLocations) {
      case 'cities':
        state.showLocations = 'states'
        break
      case 'states':
        state.showLocations = 'countries'
        break
    }
  }
}

const getters = {}

export default {
  state,
  actions,
  mutations,
  getters
}
