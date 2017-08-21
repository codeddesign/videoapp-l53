<template>
<!-- CREATE & EDIT TAGS -->
  <div class="tagmanage-tagcreationwrapper">
    <div class="tagcreate-topbuttonswrap">
      <div v-show="tag.id !== 0" class="tagcreate-topcancel" @click="deleteTag">DELETE TAG</div>
      <div class="tagcreate-topcancel" @click="hideForm">CANCEL EDIT</div>
      <div v-bind:style="tag.id === 0 ? 'background: grey' : ''" class="tagcreate-toptestpage" @click="loadTestPage">LOAD TEST PAGE</div>
    </div>
    <div class="tagcreate-formwrapper">
      <div class="tagcreate-fullheadertitle">TAG CREATION / EDITING</div>

      <div class="tagcreate-formbg">
        <div class="tagcreate-fullinnertitle">AD TAG</div>
        <input name="url" ref="url" v-model="tag['url']" v-validate data-vv-rules="required" placeholder="http://a3m.io/a/h/xxx?cb=[CACHE_BREAKER]&pageUrl=[REFERRER_URL]&eov=eov" class="tagcreate-longinput">
        <ul class="tagcreate-macrolist">
          <li v-for="macro in macros" @click="insertMacro(macro)">
           {{ macro }}
          </li>
        </ul>
        <div class="tagcreate-showmacros">SHOW ALL MACROS <span></span></div>
      </div>

      <div class="tagcreate-formbg">
        <div class="tagcreate-halfinnerwrap">
          <div class="tagcreate-fullinnertitle">ADVERTISER NAME</div>
          <input class="tagcreate-longinput" v-model="tag['advertiser']" placeholder="A3M" name="advertiser" v-validate data-vv-rules="required">
        </div>
        <div class="tagcreate-halfinnerwrap">
          <div class="tagcreate-fullinnertitle">TAG DESCRIPTION NAME</div>
          <input class="tagcreate-longinput tagcreate-uppercase" v-model="tag['description']" placeholder="A3M_DESKTOP_OUTSTREAM" name="description" v-validate data-vv-rules="required">
        </div>
      </div>

    </div>
    <div class="tagcreate-formwrapper">
      <div class="tagcreate-fullheadertitle">LIVE CONFIGURATION</div>

      <div class="tagcreate-formbg">
        <div class="tagcreate-quarterinnerwrap" style="margin-left:0;">
          <div class="tagcreate-fullinnertitle">PLATFORM TYPE</div>
          <div class="tagcreate-selectwrap">
            <select v-model="tag['platform_type']" class="tagcreate-dropdown">
              <option value="all">All</option>
              <option value="desktop">Desktop</option>
              <option value="mobile">Mobile</option>
            </select>
            <div class="tagcreate-selectarrow"></div>
          </div>
        </div>
        <div class="tagcreate-quarterinnerwrap">
          <div class="tagcreate-fullinnertitle">TYPE</div>
          <div class="tagcreate-selectwrap">
            <select v-model="tag['type']" @change="changedType()" class="tagcreate-dropdown">
              <option value="all">All</option>
              <option value="instream">Instream</option>
              <option value="outstream">Outstream</option>
            </select>
            <div class="tagcreate-selectarrow"></div>
          </div>
        </div>
      </div>

      <div class="tagcreate-formbg">
        <div class="tagcreate-quarterinnerwrap" style="margin-left:0;">
          <div class="tagcreate-fullinnertitle">AD TYPES</div>
          <div class="tagcreate-checkwrap">
            <input v-bind:disabled="disabled.preroll" v-model="tag['ad_types']" value="3" type="checkbox" :id="owned + 'check-preroll'" />
            <label v-bind:class="{ disabled:disabled.preroll }" :for="owned + 'check-preroll'">Pre-roll</label>
          </div>
          <div class="tagcreate-checkwrap">
            <input v-bind:disabled="disabled.onscroll" v-model="tag['ad_types']" value="1" type="checkbox" :id="owned + 'check-on-scroll'" />
            <label v-bind:class="{ disabled:disabled.onscroll }" :for="owned + 'check-on-scroll'">On-scroll</label>
          </div>
          <div class="tagcreate-checkwrap">
            <input v-bind:disabled="disabled.infinity" v-model="tag['ad_types']" value="2" type="checkbox" :id="owned + 'check-infinity'" />
            <label v-bind:class="{ disabled:disabled.infinity }" :for="owned + 'check-infinity'">Infinity</label>
          </div>
        </div>
      </div>

      <div class="tagcreate-formbg">
        <div class="tagcreate-fullinnertitle">AD START-END</div>
        <div class="tagcreate-checkwrap">
          <div class="tagcreate-startendcheckwrap">
            <input type="checkbox" v-model="tag['date_range']" :id="owned + 'check-adstart'" />
            <label :for="owned + 'check-adstart'">ACTIVATE START-END TIME</label>
          </div>
        </div>
        <div class="tagcreate-sepline"></div>
        <div class="tagcreate-starttimewrap">
          <div class="tagcreate-timetitle">START DATE</div>
          <input id="starttime-datepicker" v-datepicker="{ key: 'start_date' }" class="tagtime-datepicker" placeholder="select start date..">
        </div>
        <div class="tagcreate-endtimewrap">
          <div class="tagcreate-timetitle">END DATE</div>
          <input id="endtime-datepicker" v-datepicker="{ key: 'end_date' }" class="tagtime-datepicker" placeholder="select date..">
        </div>
      </div>

      <div class="tagcreate-formbg">
        <div class="tagcreate-quarterinnerwrap" style="margin-left:0;">
          <div class="tagcreate-fullinnertitle">TIMEOUT LIMIT</div>
          <input v-model="tag['timeout_limit']" class="tagcreate-longinput" placeholder="0">
        </div>
        <div class="tagcreate-quarterinnerwrap">
          <div class="tagcreate-fullinnertitle">WRAPPER LIMIT</div>
          <input v-model="tag['wrapper_limit']" class="tagcreate-longinput" placeholder="0">
        </div>
        <div class="tagcreate-quarterinnerwrap">
          <div class="tagcreate-fullinnertitle">DELAY TIME</div>
          <input v-model="tag['delay_time']" class="tagcreate-longinput" placeholder="0">
        </div>
      </div>

      <div class="tagcreate-formbg">
        <div class="tagcreate-quarterinnerwrap" style="margin-left:0;">
          <div class="tagcreate-fullinnertitle">INFINITY TIMEOUT LIMIT</div>
          <input v-model="tag['infinity_timeout_limit']" class="tagcreate-longinput" placeholder="0">
        </div>
        <div class="tagcreate-quarterinnerwrap">
          <div class="tagcreate-fullinnertitle">INFINITY WRAPPER LIMIT</div>
          <input v-model="tag['infinity_wrapper_limit']" class="tagcreate-longinput" placeholder="0">
        </div>
        <div class="tagcreate-quarterinnerwrap">
          <div class="tagcreate-fullinnertitle">INFINITY DELAY TIME</div>
          <input v-model="tag['infinity_delay_time']" class="tagcreate-longinput" placeholder="0">
        </div>
      </div>

      <div class="tagcreate-formbg">
        <div class="tagcreate-quarterinnerwrap" style="margin-left:0;">
          <div class="tagcreate-fullinnertitle">DAILY REQUEST LIMIT</div>
          <input class="tagcreate-longinput" v-model="tag['daily_request_limit']" placeholder="0">
        </div>
        <div class="tagcreate-quarterinnerwrap">
          <div class="tagcreate-fullinnertitle">eCPM</div>
          <input class="tagcreate-longinput" v-model="tag['ecpm']" placeholder="0" name="ecpm" v-validate data-vv-rules="required|not_in:0">
        </div>
      </div>

      <div class="tagcreate-formbg">
        <div class="tagcreate-quarterinnerwrap" style="margin-left:0;">
          <div class="tagcreate-fullinnertitle">GUARANTEE LIMIT</div>
          <input class="tagcreate-longinput" v-model="tag['guarantee_limit']" placeholder="0">
        </div>
        <div class="tagcreate-quarterinnerwrap">
          <div class="tagcreate-fullinnertitle">GUARANTEE ORDER</div>
          <div class="tagcreate-selectwrap">
            <select class="tagcreate-dropdown" v-model="tag['guarantee_order']">
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
            </select>
            <div class="tagcreate-selectarrow"></div>
          </div>
        </div>
        <div class="tagcreate-quarterinnerwrap">
          <div class="tagcreate-fullinnertitle">CONFIRM?</div>
          <div class="tagcreate-checkwrap">
            <div class="tagcreate-startendcheckwrap" style="margin-top:8px;">
              <input v-model="tag['guarantee_enabled']" type="checkbox" :id="owned + 'check-dailyguarantee'" />
              <label :for="owned + 'check-dailyguarantee'">CONFIRM GUARANTEE</label>
            </div>
          </div>
        </div>
      </div>

      <div class="tagcreate-formbg">
        <div class="tagcreate-fullinnertitle">AD TARGETING</div>
        <div class="tagcreate-targetwrap">
          <div class="tagcreate-targetleftsidebar">
            <ul class="tagcreate-targetlist">
              <li @click="setAdTargeting('geo')">GEOGRAPHY</li>
              <li @click="setAdTargeting('websites')">WEBSITES</li>
              <li>DEVICES</li>
            </ul>
          </div>
          <div class="tagcreate-targetcenterarea" v-if="adTargeting === 'geo'">
            <div class="tabmanage-headerbar"></div>
            <div class="tagmanage-tabbed tagmanage-targeting">
              <div>
                <input name="tagmanage-targeting" id="tagmanage-tabbed10" type="radio" checked>
                <section>
                  <h1>
                    <label for="tagmanage-tabbed10">SEARCH / BROWSER</label>
                  </h1>
                  <div>
                    <div class="tabcreate-searchwrap">
                      <input v-model="geoFilter" class="tagcreate-tabsearch" placeholder="SEARCH GEO LOCATION..">
                      <div class="tagcreate-tabsearchsubmit">
                        <div class="tagcreate-searchsubmiticon"></div>
                      </div>
                    </div>
                    <a v-show="showLocations !== 'countries'" @click="geoBack()">Back</a>
                    <ul class="tagcreate-geolist">
                      <li v-for="location in locations">
                        <div class="tagcreate-geotitle" @click="expandLocation(location)">
                          {{ location.name }}
                          <span>{{ location.type }}</span>
                        </div>
                        <div class="tagcreate-geodropbutton" @click="toggleInclude()">
                          <div class="adtargetdrop"></div>
                        </div>
                        <div class="tagcreate-geoinclude" v-show="!include" @click="excludeLocation(location)">exclude</div>
                        <div class="tagcreate-geoinclude" v-show="include" @click="includeLocation(location)">include</div>
                      </li>
                    </ul>
                  </div>
                </section>
              </div>
              <div>
                <input name="tagmanage-targeting" id="tagmanage-tabbed11" type="radio">
                <section>
                  <h1>
                    <label for="tagmanage-tabbed11">MANUAL ENTRY</label>
                  </h1>
                  <div><p style="padding: 8px;">Coming soon..</p></div>
                </section>
              </div>
            </div>
          </div>
          <div class="tagcreate-targetcenterarea" v-if="adTargeting === 'websites'">
            <div class="tabmanage-headerbar"></div>
            <div class="tagmanage-tabbed tagmanage-targeting">
              <div>
                <input name="tagmanage-targeting" id="tagmanage-tabbed10" type="radio" checked>
                <section>
                  <h1>
                    <label for="tagmanage-tabbed10">SEARCH / BROWSER</label>
                  </h1>
                  <div>
                    <div class="tabcreate-searchwrap">
                      <input v-model="geoFilter" class="tagcreate-tabsearch" placeholder="SEARCH WEBSITES..">
                      <div class="tagcreate-tabsearchsubmit">
                        <div class="tagcreate-searchsubmiticon"></div>
                      </div>
                    </div>
                    <ul class="tagcreate-geolist">
                      <li v-for="website in websites">
                        <div class="tagcreate-geotitle">
                          {{ website.domain }}
                          <span>WEBSITE</span>
                        </div>
                        <div class="tagcreate-geodropbutton" @click="toggleInclude()">
                          <div class="adtargetdrop"></div>
                        </div>
                        <div class="tagcreate-geoinclude" v-show="!include" @click="targetWebsite(website, 'exclude')">exclude</div>
                        <div class="tagcreate-geoinclude" v-show="include" @click="targetWebsite(website, 'include')">include</div>
                      </li>
                    </ul>
                  </div>
                </section>
              </div>
            </div>
          </div>
          <div class="tagcreate-targetrightsidebar">
            <div class="tagcreate-criteriaheader">SELECTED CRITERIA</div>
            <!-- GEOGRAPHY DROPDOWN -->
            <div class="tagcreate-criteriatitle createcriteriatitle">
              GEOGRAPHY (INCLUDED)
              <span></span>
            </div>
            <ul class="tagcreate-criterialist creategeolistdrop">
              <li v-for="criteria in tag.included_locations">
                <div class="tagcreate-criteriaradius"></div>
                <div class="tagcreate-criteriainner"><span>{{ capitalize(criteria.type) }}:</span> {{ criteria.name }}</div>
                <div class="tagcreate-criteriadelete" @click="deleteInclude(criteria, 'geo')"></div>
              </li>
            </ul>

            <div class="tagcreate-criteriatitle createcriteriatitle">
              GEOGRAPHY (EXCLUDED)
              <span></span>
            </div>
            <ul class="tagcreate-criterialist creategeolistdrop">
              <li v-for="criteria in tag.excluded_locations">
                <div class="tagcreate-criteriaradius"></div>
                <div class="tagcreate-criteriainner"><span>{{ capitalize(criteria.type) }}:</span> {{ criteria.name }}</div>
                <div class="tagcreate-criteriadelete" @click="deleteExclude(criteria, 'geo')"></div>
              </li>
            </ul>
            <div class="tagcreate-criteriatitle">WEBSITES (INCLUDED)<span></span></div>
            <ul class="tagcreate-criterialist creategeolistdrop">
              <li v-for="criteria in tag.included_websites">
                <div class="tagcreate-criteriaradius"></div>
                <div class="tagcreate-criteriainner"><span></span> {{ capitalize(criteria.domain) }}</div>
                <div class="tagcreate-criteriadelete" @click="deleteInclude(criteria, 'website')"></div>
              </li>
            </ul>
            <div class="tagcreate-criteriatitle">WEBSITES (EXCLUDED)<span></span></div>
            <ul class="tagcreate-criterialist creategeolistdrop">
              <li v-for="criteria in tag.excluded_websites">
                <div class="tagcreate-criteriaradius"></div>
                <div class="tagcreate-criteriainner"><span></span> {{ capitalize(criteria.domain) }}</div>
                <div class="tagcreate-criteriadelete" @click="deleteExclude(criteria, 'website')"></div>
              </li>
            </ul>
            <div class="tagcreate-criteriatitle">DEVICES <span></span></div>
          </div>
        </div>
      </div>

    </div><!-- END .tagcreate-formwrapper -->

    <div class="tagcreate-formwrapper">
      <div class="tagcreate-fullheadertitle">DEMO CONFIGURATION</div>
      <div class="tagcreate-formbg">

        <div class="tagcreate-quarterinnerwrap" style="margin-left:0;">
          <div class="tagcreate-fullinnertitle">PLATFORM TYPE</div>
          <div class="tagcreate-selectwrap">
            <select v-model="tag['demo_data']['platform_type']" class="tagcreate-dropdown">
              <option value="all">All</option>
              <option value="desktop">Desktop</option>
              <option value="mobile">Mobile</option>
            </select>
            <div class="tagcreate-selectarrow"></div>
          </div>
        </div>

        <div class="tagcreate-quarterinnerwrap">
          <div class="tagcreate-fullinnertitle">AD TYPE</div>
          <div class="tagcreate-selectwrap">
            <select v-model="tag['demo_data']['type']" class="tagcreate-dropdown">
              <option value="all">All</option>
              <option value="instream">Instream</option>
              <option value="outstream">Outstream</option>
            </select>
            <div class="tagcreate-selectarrow"></div>
          </div>
        </div>

        <div class="tagcreate-quarterinnerwrap">
          <div class="tagcreate-fullinnertitle">CAMPAIGN TYPE</div>
          <div class="tagcreate-selectwrap">
            <select v-model="tag['demo_data']['ad_types']" class="tagcreate-dropdown">
              <option value="1">On-scroll</option>
              <option value="2">Infinity</option>
              <option value="3">Pre-roll</option>
            </select>
            <div class="tagcreate-selectarrow"></div>
          </div>
        </div>

      </div><!-- END .tagcreate-formbg -->

      <div class="tagcreate-formbg">

        <div class="tagcreate-quarterinnerwrap" style="margin-left:0;">
          <div class="tagcreate-fullinnertitle">TIMEOUT LIMIT</div>
          <input v-model="tag['demo_data']['timeout_limit']" class="tagcreate-longinput" placeholder="0">
        </div>

        <div class="tagcreate-quarterinnerwrap">
          <div class="tagcreate-fullinnertitle">WRAPPER LIMIT</div>
          <input v-model="tag['demo_data']['wrapper_limit']" class="tagcreate-longinput" placeholder="0">
        </div>

        <div class="tagcreate-quarterinnerwrap">
          <div class="tagcreate-fullinnertitle">DELAY TIME</div>
          <input v-model="tag['demo_data']['delay_time']" class="tagcreate-longinput" placeholder="0">
        </div>

        <div class="tagcreate-quarterinnerwrap">
          <div class="tagcreate-fullinnertitle">SESSION MAX REQUESTS</div>
          <input v-model="tag['demo_data']['session_max_requests']" class="tagcreate-longinput" placeholder="0">
        </div>

      </div><!-- END .tagcreate-formbg -->

    </div><!-- END .tagcreate-formwrapper -->

    <div class="tagcreate-savetagwrap">
      <div class="tagcreate-savetagbutton" @click="saveTag">SAVE TAG CONFIGURATION</div>
      <div class="tagcreate-canceltagbutton" @click="hideForm">CANCEL</div>
    </div>

  </div>
</template>

<style lang="scss">
  .tagcreate-geotitle {
    text-transform: uppercase;
  }

  .disabled {
    text-decoration: line-through;
  }

  .tagcreate-targetwrap {
    height: 100% !important ;
  }

  .tagmanage-targeting a {
    display: block;
    padding: 0 0 4px 8px;
    cursor: pointer;
  }

  .tagcreate-geolist li {
    position: relative;
  }

  .tagcreate-topcancel {
    margin-right: 5px;
  }
</style>

<script>
  import $ from 'jquery'
  import _ from 'lodash'
  import moment from 'moment'
  import Fuse from 'fuse.js'

  export default {
    name: 'TagForm',

    props: ['owned'],

    data() {
      return {
        tag: _.cloneDeep(this.$store.state.users.currentTag),
        macros: [
          'CACHE_BREAKER', 'REFERRER_URL', 'REFERRER_ROOT', 'IP_ADDRESS', 'HEIGHT', 'WIDTH',
          'DATE', 'MEDIA_ID', 'USER_AGENT', 'CAMPAIGN_ID', 'TIMESTAMP'
        ],
        geoFilter: '',
        include: true,
        submitting: false,
        adTargeting: 'geo'
      }
    },

    mounted() {
      this.$watch('tag', (value) => {
        this.$store.dispatch('users/setCurrentTag', value)
      }, {
        deep: true
      })
    },

    methods: {
      saveTag(e) {
        this.$validator.validateAll()

        if (!this.validAdTypes) {
          this.errors.add('ad types', 'At least one ad type is required.')
        }

        if (this.errors.any()) {
          e.preventDefault()
          var errorMsg = this.errors.items.map((error) => {
            return error.msg + '\n'
          })

          window.alert(errorMsg)
        } else {
          this.$store.dispatch('users/saveCurrentTag', this.tag)
        }
      },

      loadTestPage() {
        if (this.tag.id === 0) {
          window.alert('Please save the tag first')
          return
        }

        window.open('http://ad3media.com/demo.php?_tid=' + this.tag.id)
      },

      changedType() {
        if (this.tag.type === 'instream') {
          _.remove(this.tag.ad_types, i => {
            return i !== 1 || i !== 2
          })
        }

        if (this.tag.type === 'outstream') {
          _.remove(this.tag.ad_types, i => {
            return i !== 3
          })
        }
      },

      setAdTargeting(newTarget) {
        this.adTargeting = newTarget
      },

      geoBack() {
        this.$store.dispatch('users/locationBack')
        this.geoFilter = ''
      },

      expandLocation(location) {
        if (location.type === 'city') {
          return
        }

        this.$store.dispatch('users/expandLocation', location)
        this.$store.subscribe((mutation, state) => {
          if (mutation.type === 'users/LOAD_LOCATIONS') {
            this.geoFilter = ''
          }
        })
      },

      targetWebsite(website, status) {
        if (this.checkWebsiteExistance(website)) {
          return
        }

        let key = status === 'include' ? 'included_websites' : 'excluded_websites'

        if (!this.tag[key]) {
          this.$set(this.tag, key, [website])
        } else {
          this.tag[key].push(website)
        }
      },

      includeLocation(location) {
        if (this.checkLocationExistance(location)) {
          return
        }

        if (!this.tag.included_locations) {
          this.$set(this.tag, 'included_locations', [location])
        } else {
          this.tag.included_locations.push(location)
        }
      },

      excludeLocation(location) {
        if (this.checkLocationExistance(location)) {
          return
        }

        if (!this.tag.excluded_locations) {
          this.$set(this.tag, 'excluded_locations', [location])
        } else {
          this.tag.excluded_locations.push(location)
        }
      },

      checkLocationExistance(location) {
        if (_.findIndex(this.tag.included_locations, location) !== -1) {
          window.alert(location.name + ' is already included.')
          return true
        }

        if (_.findIndex(this.tag.excluded_locations, location) !== -1) {
          window.alert(location.name + ' is already excluded.')
          return true
        }

        return false
      },

      checkWebsiteExistance(website) {
        if (_.findIndex(this.tag.included_websites, website) !== -1) {
          window.alert(website.domain + ' is already included.')
          return true
        }

        if (_.findIndex(this.tag.excluded_websites, website) !== -1) {
          window.alert(website.domain + ' is already excluded.')
          return true
        }

        return false
      },

      deleteInclude(criteria, type) {
        if (type === 'geo') {
          this.tag.included_locations = this.tag.included_locations.filter(item => item !== criteria)
        } else {
          this.tag.included_websites = this.tag.included_websites.filter(item => item.id !== criteria.id)
        }
      },

      deleteExclude(criteria, type) {
        if (type === 'geo') {
          this.tag.excluded_locations = this.tag.excluded_locations.filter(item => item !== criteria)
        } else {
          this.tag.excluded_websites = this.tag.excluded_websites.filter(item => item.id !== criteria.id)
        }
      },

      hideForm() {
        this.$store.dispatch('users/setShowTagForm', { status: false, owned: this.owned })
      },

      deleteTag() {
        let confirmed = window.confirm('Are you sure you want to delete this tag?')

        if (confirmed) {
          this.$store.dispatch('users/deleteCurrentTag', this.tag)
        }
      },

      insertMacro(value) {
        value = '[' + value + ']'

        var field = this.$refs.url
        var scrollPos = field.scrollTop
        var caretPos = field.selectionStart

        var front = (field.value).substring(0, caretPos)
        var back = (field.value).substring(field.selectionEnd, field.value.length)
        field.value = front + value + back
        caretPos = caretPos + value.length
        field.selectionStart = caretPos
        field.selectionEnd = caretPos
        field.focus()
        field.scrollTop = scrollPos
        this.$set(this.tag, 'url', field.value)
      },

      toggleInclude() {
        this.include = !this.include
      },

      capitalize(string) {
        return _.capitalize(string)
      }
    },

    computed: {
      validAdTypes() {
        return this.tag.ad_types.length > 0
      },

      websites() {
        let websites = this.$store.state.users.websites

        if (this.geoFilter !== '') {
          var options = {
            threshold: 0.3,
            keys: [
              'domain'
            ]
          }

          var fuse = new Fuse(websites, options)

          websites = fuse.search(this.geoFilter)
        }

        return websites
      },

      showLocations() {
        return this.$store.state.users.showLocations
      },

      locations() {
        let showLocations = this.showLocations
        let locations = this.$store.state.users.locations[showLocations]

        if (this.geoFilter !== '') {
          var options = {
            threshold: 0.3,
            keys: [
              'name'
            ]
          }

          var fuse = new Fuse(locations, options)

          locations = fuse.search(this.geoFilter)
        }

        return locations
      },

      disabled() {
        return {
          'preroll': this.tag.type === 'outstream',
          'onscroll': this.tag.type === 'instream',
          'infinity': this.tag.type === 'instream'
        }
      }
    },

    directives: {
      datepicker: {
        bind: function (el, binding, vnode) {
          var vm = vnode.context
          $(el).datepicker({
            onClose: function (date) {
              vm.$set(vm.tag, binding.value.key, date)
            }
          })
          if (vm.tag[binding.value.key]) {
            let date = moment(vm.tag[binding.value.key])
            $(el).datepicker('setDate', date.format('L'))
          }
        }
      }
    }
  }
</script>
