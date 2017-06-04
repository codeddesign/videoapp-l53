import {
  LOAD_PENDING_WEBSITES,
  LOAD_ACCOUNTS,
  ACTIVATE_USER,
  SET_SHOW_TAG_FORM,
  ACTIVATE_TAG,
  SET_CURRENT_TAG,
  SAVE_CURRENT_TAG,
  DELETE_CURRENT_TAG,
  LOAD_TAGS,
  LOAD_LOCATIONS,
  LOCATION_BACK,
  ADD_NOTE,
  LOAD_WEBSITE_STATS,
  LOAD_CAMPAIGN_STATS,
  LOAD_GLOBAL_OPTIONS,
  UPDATE_GLOBAL_OPTIONS,
  LOAD_REPORTS,
  DELETE_REPORTS,
  LOAD_WEBSITES
} from '../mutation-types'

import User from '../../models/user'
import Admin from '../../models/admin'
import Country from '../../models/country'
import Tag from '../../models/tag'
import _ from 'lodash'

const state = {
  pendingWebsites: [],
  accounts: [],
  tags: [],
  tagsToCompare: [],
  reports: [],
  showTagForm: false,
  showTagFormOwned: false,
  currentTag: Tag.default(),
  locations: {
    countries: [],
    states: [],
    cities: []
  },
  websitesStats: [],
  campaignStats: [],
  showLocations: 'countries',
  globalOptions: [],
  websites: []
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

  loadGlobalOptions({ commit }) {
    Admin.loadGlobalOptions().then((globalOptions) => {
      commit(LOAD_GLOBAL_OPTIONS, globalOptions)
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

  setShowTagForm({ commit }, { status, owned }) {
    commit(SET_SHOW_TAG_FORM, { status: status, owned: owned })
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

  deleteCurrentTag({ commit }) {
    Tag.delete(state.currentTag).then((tags) => {
      commit(DELETE_CURRENT_TAG, tags.data)
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
  },

  addNote({ commit }, { account, note }) {
    User.addNote(account.id, note).then(note => {
      commit(ADD_NOTE, { account: account, note: note })
    })
  },

  loadWebsitesStats({ commit }, { account, range }) {
    User.loadWebsiteStats(account.id, range).then(stats => {
      commit(LOAD_WEBSITE_STATS, stats)
    })
  },

  loadCampaignsStats({ commit }, { account, range }) {
    Admin.loadCampaignsStats(account.id, range).then(stats => {
      commit(LOAD_CAMPAIGN_STATS, stats)
    })
  },

  updateGlobalOptions({ commit }, globalOptions) {
    Admin.updateGlobalOptions(globalOptions).then(globalOptions => {
      commit(UPDATE_GLOBAL_OPTIONS, globalOptions)
    })
  },

  loadReports({ commit }) {
    Admin.loadReports().then(reports => {
      commit(LOAD_REPORTS, reports)
    })
  },

  deleteReports({ commit }, reports) {
    Admin.deleteReports(reports).then(reportsDeleted => {
      commit(DELETE_REPORTS, reportsDeleted)
    })
  },

  loadWebsites({ commit }, websites) {
    Admin.loadWebsites().then(websites => {
      commit(LOAD_WEBSITES, websites)
    })
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

  [SET_SHOW_TAG_FORM](state, { status, owned }) {
    if (owned) {
      state.showTagFormOwned = status
    } else {
      state.showTagForm = status
    }
  },

  [SET_CURRENT_TAG](state, tag) {
    let globalOptions = state.globalOptions

    // if the tag option is undefined, use the global option
    globalOptions.map(option => {
      if (tag[option.option] === '') {
        tag[option.option] = option.value
      }
    })

    state.currentTag = tag
  },

  [SAVE_CURRENT_TAG](state, tags) {
    state.tags = tags
    state.currentTag = Tag.default()
    state.showTagForm = false
  },

  [DELETE_CURRENT_TAG](state, tags) {
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
  },

  [ADD_NOTE](state, { account, note }) {
    let user = _.find(state.accounts, { id: account.id })
    user.notes.data.unshift(note)
  },

  [LOAD_WEBSITE_STATS](state, stats) {
    state.websitesStats = stats
  },

  [LOAD_CAMPAIGN_STATS](state, stats) {
    state.campaignStats = stats
  },

  [LOAD_GLOBAL_OPTIONS](state, globalOptions) {
    state.globalOptions = globalOptions
  },

  [UPDATE_GLOBAL_OPTIONS](state, globalOptions) {
    state.globalOptions = globalOptions
  },

  [LOAD_REPORTS](state, reports) {
    state.reports = reports
  },

  [DELETE_REPORTS](state, reports) {
    state.reports = _.filter(state.reports, report => {
      console.log(reports.indexOf(report.id))
      return reports.indexOf(report.id) === -1
    })
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
