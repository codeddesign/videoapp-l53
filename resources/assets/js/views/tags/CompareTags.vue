<template>
  <div>
    <input name="tagmanage-tabbed" id="tagmanage-tabbed2" v-on:click="isVisible()" type="radio" checked>
    <section>
      <h1>
        <label for="tagmanage-tabbed2">COMPARE TAGS</label>
      </h1>
      <div>
        <!-- START CHART TIME RANGE -->
        <div class="display-dashboardtoparea">
          <div class="display-dashboardtimewrap">
            <div class="dashmaintime-title">Time Range</div>
            <div class="dashboard-mainselect">
              <select v-model="timeRange">
                <option v-for="timeRange in timeRangeOptions" v-bind:value="timeRange.value">
                  {{ timeRange.text }}
                </option>
              </select>
            <div class="dashmain-selectarrow"></div>
            </div>
          </div>
          <div class="display-dashboardtimewrap">
            <div class="dashmaintime-title">Website</div>
            <div class="dashboard-mainselect">
              <select v-model="website">
                <option v-for="website in websiteOptions" v-bind:value="website.id">
                  {{ website.domain }}
                </option>
              </select>
            <div class="dashmain-selectarrow"></div>
            </div>
          </div>
          <router-link :to="{ name: 'admin.reports.create'}">
            <div class="currentcamp-createbutton">GENERATE REPORT</div>
          </router-link>
        </div>
          <!-- END CHART TIME FRAME -->
        <!-- TAG GRAPH AREA -->
          <div class="taggraph-wrapper">
            <div class="taggraph-wrapleft">
              <tag-chart :chart-data="chartData"></tag-chart>
            </div>
            <div class="taggraph-wrapright">
              <ul class="taggraph-textlist">
                <li>
                  <div class="taggraph-listtitle">REQUESTS:</div>
                  <div class="taggraph-listnumber">{{ presentNumber(requests) }}</div>
                </li>
                <li>
                  <div class="taggraph-listtitle">FILLS:</div>
                  <div class="taggraph-listnumber">{{ presentNumber(fills) }}</div>
                </li>
                <li>
                  <div class="taggraph-listtitle">IMPRESSIONS:</div>
                  <div class="taggraph-listnumber">{{ presentNumber(impressions) }}</div>
                </li>
                <li>
                  <div class="taggraph-listtitle">FILL-RATE:</div>
                  <div class="taggraph-listnumber">{{ fillRate }}</div>
                </li>
                <li>
                  <div class="taggraph-listtitle">USE-RATE:</div>
                  <div class="taggraph-listnumber">{{ useRate }}</div>
                </li>
                <li>
                  <div class="taggraph-listtitle">ERRORS:</div>
                  <div class="taggraph-listnumber">{{ presentNumber(errors) }}</div>
                </li>
                <li>
                  <div class="taggraph-listtitle">REVENUE:</div>
                  <div class="taggraph-listnumber">{{ presentRevenue }}</div>
                </li>
              </ul>
            </div>
          </div>
          <!-- END GRAPH AREA -->
          <!-- START TAGS AREA -->

          <tag-list v-on:selectedTags="newSelectedTags"></tag-list>
      </div>
    </section><!-- END COMPARE TAGS -->
  </div>
</template>

<script>
  import stats from '../../services/stats'
  import http from '../../services/http'
  import TagChart from './TagChart.vue'
  import TagList from './TagList.vue'
  import moment from 'moment'
  import numeral from 'numeral'
  import _ from 'lodash'
  import accounting from 'accounting'

  export default {
    name: 'CompareTags',

    data() {
      return {
        // used for the Time Range Select.
        currentTime: moment(),

        timeRange: 'today',
        timeRangeOptions: [
          { text: 'Today', value: 'today' },
          { text: 'Yesterday', value: 'yesterday' },
          { text: '2 Days Ago', value: 'twoDaysAgo' },
          { text: 'Last 7 Days', value: 'sevenDays' },
          { text: 'This Month', value: 'thisMonth' },
          { text: 'Last Month', value: 'lastMonth' }
        ],

        website: '0',

        requests: 0,
        impressions: 0,
        revenue: 0,
        fills: 0,
        errorCount: 0,

        selectedTags: [],

        chartData: []
      }
    },

    methods: {
      newSelectedTags(selectedTags) {
        this.selectedTags = selectedTags
        this.fetchStats()
      },

      isVisible() {
        this.chartData = _.cloneDeep(this.chartData)
      },

      presentNumber(number) {
        return numeral(number).format('0,0')
      },

      fetchStats() {
        http.get('/stats/all?time=' + this.timeRange + '&tags=' + this.selectedTags + '&website=' + this.website)
            .then((response) => {
              this.requests = parseInt(response.data.tagRequests)
              this.impressions = parseInt(response.data.impressions)
              this.fills = parseInt(response.data.fills)
              this.errorCount = parseInt(response.data.errors)
              this.revenue = parseFloat(response.data.revenue)
            })
            .catch((error) => {
              console.error('Error fetching the stats count.')
            })

        http.get('/charts/all?time=' + this.timeRange + '&tags=' + this.selectedTags + '&website=' + this.website)
            .then((response) => {
              this.chartData = response.data
            })
            .catch((error) => {
              console.error('Error fetching the chart stats.')
            })
      },
      ...stats
    },

    computed: {
      presentRevenue() {
        return accounting.formatMoney(this.revenue)
      },

      ecpm() {
        return this.calculateEcpm(this.impressions, this.revenue)
      },

      fillRate() {
        return this.calculateFillRate(this.fills, this.requests)
      },

      errorRate() {
        return this.calculateErrorRate(this.requests, this.errorCount)
      },

      useRate() {
        return this.calculateUseRate(this.impressions, this.fills)
      },

      websiteOptions() {
        return _.filter(_.concat([{
          'domain': 'All',
          'id': 0,
          'approved': true
        }], this.$store.state.users.websites), (website) => {
          return website.approved
        })
      }
    },

    mounted() {
      this.$nextTick(function() {
        this.fetchStats()
      })
    },

    watch: {
      timeRange(newTimeRange) {
        this.fetchStats()
      },
      website(newWebsite) {
        this.fetchStats()
      },
      compareTagsRange(newTimeRange) {
        this.fetchStats()
      }
    },

    components: {
      TagChart,
      TagList
    }

  }
</script>

<style lang="scss">

</style>
