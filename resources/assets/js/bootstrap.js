window.$ = window.jQuery = require('jquery')
require('bootstrap-sass/assets/javascripts/bootstrap')

window.$.datetimepicker = require('eonasdan-bootstrap-datetimepicker')

import 'jquery-ui/ui/widgets/datepicker'

// Fetch JWT from LS or redirect to login page
import auth from './services/auth'
const jwt = auth.authenticate()

// Set axios defaults
import http from './services/http'
http.defaults.headers.common['Authorization'] = 'Bearer ' + jwt
http.defaults.baseURL = window.apiDomain + '/api'

// Bootstrap the web socket if
// a connection is available
// import socket from './services/socket'
// socket.bootstrap('//' + window.socketIoIp + ':3000', jwt)

// Setup Vue.js and it's dependencies
import Vue from 'vue'
import VeeValidate from 'vee-validate'
import router from './router'
import store from './vuex/store'
import { sync } from 'vuex-router-sync'
import App from './views/layouts/default/default.vue'

Vue.use(VeeValidate)

sync(store, router)

const app = new Vue({
  store,
  router,
  ...App,
  data() {
    return {
      jwt: jwt
    }
  }
})

export {
  app,
  router
}
