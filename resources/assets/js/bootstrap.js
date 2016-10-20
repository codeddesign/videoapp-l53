window.Cookies = require('js-cookie')

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

window.$ = window.jQuery = require('jquery')
require('bootstrap-sass/assets/javascripts/bootstrap')

window.$.datatimepicker = require('eonasdan-bootstrap-datetimepicker')

import Vue from 'vue'
import VueResource from 'vue-resource'
import router from './router'
import App from './views/layouts/default/default.vue'

Vue.use(VueResource)

Vue.http.interceptors.push(function(request, next) {
  request.headers.set(
    'X-CSRF-TOKEN',
    $('meta[name="csrf-token"]').attr('content')
  )

  next()
})

const app = new Vue({
  router,
  ...App
})

export {
  app,
  router
}
