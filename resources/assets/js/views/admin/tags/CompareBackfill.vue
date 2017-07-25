<template>
  <div>
    <input name="tagmanage-tabbed" id="tagmanage-tabbed5" v-on:click="isVisible()" type="radio">
    <section>
      <h1>
        <label for="tagmanage-tabbed5">COMPARE BACKFILL</label>
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
            <router-link :to="{ name: 'admin.reports.create'}">
              <div class="currentcamp-createbutton">GENERATE REPORT</div>
            </router-link>
          </div>
          <!-- END CHART TIME FRAME -->
        <!-- TAG GRAPH AREA -->
          <div class="taggraph-wrapper">
            <div class="taggraph-wrapleft">
              <backfill-chart chart-id="backfill-chart" :chart-data="chartData"></backfill-chart>
            </div>
            <div class="taggraph-wrapright">
              <ul class="taggraph-textlist">
                <li>
                  <div class="taggraph-listtitle">PAGEVIEWS:</div>
                  <div class="taggraph-listnumber">{{ presentNumber(totalPageviews) }}</div>
                </li>
                <li>
                  <div class="taggraph-listtitle">IMPRESSIONS:</div>
                  <div class="taggraph-listnumber">{{ presentNumber(backfill) }}</div>
                </li>
                <li>
                  <div class="taggraph-listtitle">ECPM:</div>
                  <div class="taggraph-listnumber">{{ presentMoney(ecpm) }}</div>
                </li>
                <li>
                  <div class="taggraph-listtitle">REVENUE:</div>
                  <div class="taggraph-listnumber">{{ presentMoney(totalBackfillRevenue) }}</div>
                </li>
              </ul>
            </div>
          </div>
          <!-- END GRAPH AREA -->
          <!-- START TAGS AREA -->

          <backfill-list v-on:selectedBackfill="newSelectedBackfill"></backfill-list>
      </div>
    </section><!-- END COMPARE TAGS -->
  </div>
</template>

<script>
  import stats from '../../../services/stats'
  import http from '../../../services/http'
  import BackfillChart from './BackfillChart.vue'
  import BackfillList from './BackfillList.vue'
  import moment from 'moment'
  import numeral from 'numeral'
  import accounting from 'accounting'
  import _ from 'lodash'

  export default {
    name: 'CompareBackfill',

    data() {
      return {
        // used for the Time Range Select.
        currentTime: moment(),

        visible: false,

        timeRange: 'today',
        timeRangeOptions: [
          { text: 'Today', value: 'today' },
          { text: 'Yesterday', value: 'yesterday' },
          { text: '2 Days Ago', value: 'twoDaysAgo' },
          { text: 'Last 7 Days', value: 'sevenDays' },
          { text: 'This Month', value: 'thisMonth' },
          { text: 'Last Month', value: 'lastMonth' }
        ],

        backfill: 0,
        desktopBackfillRevenue: 0,
        mobileBackfillRevenue: 0,
        totalBackfillRevenue: 0,
        desktopPageviews: 0,
        mobilePageviews: 0,
        totalPageviews: 0,

        selectedBackfill: [],

        chartData: []
      }
    },

    methods: {
      newSelectedBackfill(selectedBackfill) {
        this.selectedBackfill = selectedBackfill
        this.fetchStats()
      },

      isVisible() {
        this.chartData = _.cloneDeep(this.chartData)
      },

      presentNumber(number) {
        return numeral(number).format('0,0')
      },

      presentMoney(number) {
        return accounting.formatMoney(number)
      },

      fetchStats() {
        http.get('/admin/stats/all?time=' + this.timeRange + '&backfill=' + this.selectedBackfill)
            .then((response) => {
              this.backfill = parseInt(response.data.backfill)
              this.desktopBackfillRevenue = parseFloat(response.data.desktopBackfillRevenue)
              this.mobileBackfillRevenue = parseFloat(response.data.mobileBackfillRevenue)
              this.totalBackfillRevenue = this.desktopBackfillRevenue + this.mobileBackfillRevenue
              this.desktopPageviews = parseInt(response.data.desktopPageviews)
              this.mobilePageviews = parseInt(response.data.mobilePageviews)
              this.totalPageviews = this.desktopPageviews + this.mobilePageviews
            })
            .catch((error) => {
              console.error('Error fetching the stats count.')
            })

        http.get('/admin/charts/all?time=' + this.timeRange + '&backfill=' + this.selectedBackfill)
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
        return this.presentMoney(this.revenue)
      },

      ecpm() {
        return this.calculateEcpm(this.backfill, this.totalBackfillRevenue, false)
      },

      fillRate() {
        return this.calculateFillRate(this.fills, this.requests)
      },

      errorRate() {
        return this.calculateErrorRate(this.requests, this.errors)
      },

      useRate() {
        return this.calculateUseRate(this.impressions, this.fills)
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
      compareTagsRange(newTimeRange) {
        this.fetchStats()
      }
    },

    components: {
      BackfillChart,
      BackfillList
    }

  }
</script>

<style lang="scss">

</style>
