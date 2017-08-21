import {
  LOAD_USER,
  LOAD_REPORTS,
  LOAD_WEBSITES,
  SET_SHOW_TAG_FORM,
  ACTIVATE_TAG,
  SET_CURRENT_TAG,
  SAVE_CURRENT_TAG,
  DELETE_CURRENT_TAG,
  LOAD_TAGS,
  IMPERSONATE,
  LOAD_LOCATIONS,
  LOCATION_BACK
} from '../mutation-types'

import User from '../../models/user'
import Tag from '../../models/tag'
import Location from '../../models/location'
import http from '../../services/http'
import router from '../../router'
import _ from 'lodash'

const state = {
  currentUser: {},
  reports: [],
  tags: [],
  websites: [],
  currentTag: Tag.default(),
  showTagForm: false,
  impersonating: false,
  showLocations: 'countries',
  locations: {
    countries: [],
    states: [],
    cities: []
  }
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

  loadTags({ commit }) {
    User.loadTags().then((tags) => {
      commit(LOAD_TAGS, tags)
    })
  },

  loadReports({ commit }) {
    User.loadReports().then((reports) => {
      commit(LOAD_REPORTS, reports)
    })
  },

  loadLocations({ commit }) {
    Location.all().then((countries) => {
      commit(LOAD_LOCATIONS, countries)
    })
  },

  expandLocation({ commit }, location) {
    Location.expand(location).then(locations => {
      commit(LOAD_LOCATIONS, locations)
    })
  },

  locationBack({ commit }) {
    commit(LOCATION_BACK)
  },

  setShowTagForm({ commit }, { status, owned }) {
    commit(SET_SHOW_TAG_FORM, { status: status, owned: owned })
  },

  setCurrentTag({ commit }, tag) {
    // Passing in a clone to prevent
    // unintended reactive actions
    commit(SET_CURRENT_TAG, _.cloneDeep(tag))
  },

  saveCurrentTag({ commit }, saved) {
    Tag.save(state.currentTag).then((tags) => {
      commit(SAVE_CURRENT_TAG, { tags: tags.data, saved: saved })
    })
  },

  deleteCurrentTag({ commit }, deleted) {
    Tag.delete(state.currentTag).then((tags) => {
      commit(DELETE_CURRENT_TAG, { tags: tags.data, deleted: deleted })
    })
  },

  activateTag({ commit }, data) {
    Tag.activate(data.id, data.status).then((tag) => {
      commit(ACTIVATE_TAG, tag)
    })
  },

  loadWebsites({ commit }, websites) {
    User.loadWebsites().then(websites => {
      commit(LOAD_WEBSITES, websites)
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
  [LOAD_TAGS](state, tags) {
    state.tags = tags
  },
  [IMPERSONATE](state, value) {
    state.impersonating = value
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
  },

  [SET_SHOW_TAG_FORM](state, { status, owned }) {
    if (owned) {
      state.showTagFormOwned = status
    } else {
      state.showTagForm = status
    }
  },

  [SET_CURRENT_TAG](state, tag) {
    state.currentTag = tag
  },

  [SAVE_CURRENT_TAG](state, { tags, saved }) {
    state.tags = tags
    state.currentTag = Tag.default()

    if (saved.for_owned) {
      state.showTagFormOwned = false
    } else {
      state.showTagForm = false
    }
  },

  [DELETE_CURRENT_TAG](state, { tags, deleted }) {
    state.tags = tags
    state.currentTag = Tag.default()

    if (deleted.for_owned) {
      state.showTagFormOwned = false
    } else {
      state.showTagForm = false
    }
  },

  [LOAD_TAGS](state, tags) {
    state.tags = tags
  },

  [ACTIVATE_TAG](state, tag) {

  },

  [LOAD_WEBSITES](state, websites) {
    state.websites = websites
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
