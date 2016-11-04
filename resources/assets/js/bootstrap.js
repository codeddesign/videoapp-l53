window.$ = window.jQuery = require('jquery')
require('bootstrap-sass/assets/javascripts/bootstrap')

window.$.datatimepicker = require('eonasdan-bootstrap-datetimepicker')

// Fetch JWT from LS or redirect to login page
import auth from './services/auth'
let jwt = auth.authenticate()

// Bootstrap the web socket if
// a connection is available
import socket from './services/socket'
socket.bootstrap('//' + window.socketIoIp + ':3000', jwt)

// Setup Vue.js and it's dependencies
import Vue from 'vue'
import VueResource from 'vue-resource'
import router from './router'
import store from './vuex/store'
import { sync } from 'vuex-router-sync'
import App from './views/layouts/default/default.vue'

sync(store, router)

Vue.use(VueResource)

Vue.http.interceptors.push(function(request, next) {
  request.headers.set(
    'X-CSRF-TOKEN',
    $('meta[name="csrf-token"]').attr('content')
  )

  request.headers.set(
    'Authorization',
    `Bearer ${jwt}`
  )

  next()
})

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
