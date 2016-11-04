import Vue from 'vue'
import Vuex from 'vuex'
import users from './modules/users'
import admin from './modules/admin'

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    users,
    admin
  },

  strict: process.env.NODE_ENV !== 'production'
})
