window._ = require('lodash');
window.Cookies = require('js-cookie');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

window.$ = window.jQuery = require('jquery');
require('bootstrap-sass/assets/javascripts/bootstrap');

window.$.datatimepicker = require('eonasdan-bootstrap-datetimepicker');

import Vue from 'vue'
import VueResource from 'vue-resource'
import router from './router'
import App from './views/layouts/default/default.vue'

Vue.use(VueResource)

Vue.component('app-modal', {
    template: `
        <div class="app_modal-overlay" v-show="visible" style="position:absolute">
            <div class="app_modal">
                <span class="app_modal-close" @click="close">&times;</span>
                <div class="app_modal-body">
                    <div class="app_modal-title" v-show="title">{{ title }}</div>
                    <div class="app_modal-content" v-show="body">
                        <div v-if="!html">
                            <div v-html="body"></div>
                        </div>
                        <div v-else>
                            <div v-html="body"></div>
                        </div>
                    </div>

                    <div class="app_modal-footer" v-show="confirm">
                        <button class="proceed" @click="proceed" :disabled="loading">Proceed</button>
                    </div>
                </div>
            </div>
        </div>`,
    data: function() {
        return {
            visible: false,
            title: false,
            body: false,
            confirm: false,
            callback: false,
            loading: false,
            html: false
        }
    },

    events: {
        'modal-open': function(setup, callback) {
            this.title = setup.title || false;
            this.body = setup.body || false;
            this.confirm = setup.confirm || false;
            this.html = setup.html || false;
            this.callback = callback || false;

            this.loading = false;

            this.open();
        },

        'modal-close': function() {
            this.close();
        }
    },

    methods: {
        open: function(callback) {
            this.visible = true;
        },

        close: function() {
            this.visible = false;
        },

        proceed: function() {
            if (typeof this.callback == 'function') {
                this.loading = true;

                this.callback();

                return false;
            }

            this.close();
        }
    }
});

/**
 * We'll register a HTTP interceptor to attach the "XSRF" header to each of
 * the outgoing requests issued by this application. The CSRF middleware
 * included with Laravel will automatically verify the header's value.
 */
Vue.http.interceptors.push(function (request, next) {
    request.headers.set('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));

    next();
});

const app = new Vue({
  router,
  ...App,
  filters: {
    capitalize: function (value) {
      if (!value) return ''
      value = value.toString()
      return value.charAt(0).toUpperCase() + value.slice(1)
    }
  }
})

export { app, router }
