<template>
  <div>
    <div class="page-index">
      <div class="display-dashboardtoparea">
        <div class="dashboard-livedatestamp">{{ currentTime.format('MMMM D YYYY') }}
          <span>( LIVE STATS UP TO {{ currentTime.format('hh:mm a') }} )</span>
        </div>
        <router-link :to="{ name: 'campaigns.create'}">
          <div class="currentcamp-createbutton">CREATE NEW CAMPAIGN</div>
        </router-link>
      </div>
      <!-- ANALYTICS STATS -->
      <!-- TOP ANALYTICS -->
      <ul class="campaignstats-row">
        <li>
          <stats title="impressions" :value="stats.impressions" :animated="true"></stats>
          <spark-chart id="impressions-chart" :chartData="chartData.impressions" color="#7772a7"></spark-chart>
        </li>
        <li>
          <stats title="video revenue" :value="stats.revenue" color="#1aa74f" type="money" :animated="true"></stats>
          <spark-chart id="revenue-chart" :chartData="chartData.revenue" color="#1aa74f"></spark-chart>
        </li>
        <li>
          <stats title="display revenue" :value="backfillRevenue" color="#1aa74f" type="money" :animated="true"></stats>
          <spark-chart id="backfill-revenue-chart" :chartData="backfillRevenueSpark" color="#1aa74f"></spark-chart>
        </li>
        <li>
          <stats title="total revenue" :value="totalRevenue" color="#1aa74f" type="money" :animated="true"></stats>
          <spark-chart id="total-revenue-chart" :chartData="totalRevenueSpark" color="#1aa74f"></spark-chart>
        </li>
      </ul>

      <ul class="campaignstats-row">
        <li>
          <stats title="desktop pageviews" :value="stats.desktopPageviews" :animated="true"></stats>
          <spark-chart id="desktop-pageviews-chart" :chartData="chartData.desktopPageviews" color="#7772a7"></spark-chart>
        </li>
        <li>
          <stats title="desktop impressions" :value="stats.tags.desktop.impressions" :animated="true"></stats>
          <spark-chart id="desktop-impressions-chart" :chartData="chartData.desktopImpressions" color="#7772a7"></spark-chart>
        </li>
        <li>
          <stats title="desktop rpm" :value="stats.desktopRpm" color="#1aa74f" type="money"></stats>
          <spark-chart id="desktop-ecpm-chart" :chartData="chartData.desktopRpm" color="#1aa74f"></spark-chart>
        </li>
        <li>
          <stats title="desktop revenue" :value="stats.desktopRevenue" color="#1aa74f" type="money" :animated="true"></stats>
          <spark-chart id="desktop-revenue-chart" :chartData="chartData.desktopRevenue" color="#1aa74f"></spark-chart>
        </li>
      </ul>

      <ul class="campaignstats-row">
        <li>
          <stats title="mobile pageviews" :value="stats.mobilePageviews" :animated="true"></stats>
          <spark-chart id="mobile-pageviews-chart" :chartData="chartData.mobilePageviews" color="#7772a7"></spark-chart>
        </li>
        <li>
          <stats title="mobile impressions" :value="stats.tags.mobile.impressions" :animated="true"></stats>
          <spark-chart id="mobile-impressions-chart" :chartData="chartData.mobileImpressions" color="#7772a7"></spark-chart>
        </li>
        <li>
          <stats title="mobile rpm" :value="stats.mobileRpm" color="#1aa74f" type="money"></stats>
          <spark-chart id="mobile-ecpm-chart" :chartData="chartData.mobileRpm" color="#1aa74f"></spark-chart>
        </li>
        <li>
          <stats title="mobile revenue" :value="stats.mobileRevenue" color="#1aa74f" type="money" :animated="true"></stats>
          <spark-chart id="mobile-revenue-chart" :chartData="chartData.mobileRevenue" color="#1aa74f"></spark-chart>
        </li>
      </ul>

      <div class="dashstats-graphwrapper">
        <div class="display-dashboardtimewrap">
          <div class="dashstats-choosetime">Graph Time Range: </div>
          <select v-model="timeRange" class="dashboard-graphtimeselect">
            <option v-for="timeRange in timeRangeOptions" v-bind:value="timeRange.value">
              {{ timeRange.text }}
            </option>
          </select>
          <div class="dashstats-graphselectarrow"></div>
        </div>
        <!-- START GRAPH AREA -->
        <div class="dashstats-graph">
          <line-bar-chart
            :timeRange="timeRange"
            :revenue="revenueChartData"
            :impressions="impressionsChartData"
          ></line-bar-chart>
        </div>
      </div>

      <!-- CAMPAIGN SELECTION AREA -->
      <div class="dashboard-dailystatstitle">DAILY STATS</div>
      <ul class="dashboard-dailystatstitles">
        <li>DATE</li>
        <li>PAGEVIEWS</li>
        <li>FILL-RATE</li>
        <li>eCPM</li>
        <li>REVENUE</li>
      </ul>
      <ul class="dashboard-dailystatslist">
        <li v-for="(stat, date) in dailyStats">
          <div class="dashboard-statslist1">{{ date }}</div>
          <div class="dashboard-statslist2">{{ stat.desktopPageviews + stat.mobilePageviews }}</div>
          <div class="dashboard-statslist2">{{ calculateFillRate(stat.impressions, (stat.desktopPageviews + stat.mobilePageviews)) }}</div>
          <div class="dashboard-statslist2">
            {{ calculateEcpm(stat.impressions, stat.revenue) }}
          </div>
          <div class="dashboard-statslist2">{{ presentRevenue(stat.revenue) }}</div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
  import Stats from './components/Stats.vue'
  import LineBarChart from './components/LineBarChart.vue'
  import SparkChart from '../components/SparkChart.vue'
  import stats from '../../services/stats'
  import http from '../../services/http'
  import moment from 'moment'
  import accounting from 'accounting'
  import _ from 'lodash'

  export default {
    name: 'Dashboard',

    data() {
      return {
        // used for the Time Range Select.
        currentTime: moment(),

        timeRange: 'today',
        timeRangeOptions: [
          { text: 'Today', value: 'today' },
          { text: 'Yesterday', value: 'yesterday' },
          { text: 'Last 7 Days', value: 'sevenDays' },
          { text: 'This Month', value: 'thisMonth' },
          { text: 'Last Month', value: 'lastMonth' }
        ],

        stats: {
          tags: {
            desktop: {},
            mobile: {}
          }
        },

        chartData: {},

        requestsChartData: [],
        impressionsChartData: [],
        revenueChartData: [],

        dailyStats: [],

        realtimeRetries: 0,
        destroyed: false
      }
    },

    computed: {
      currentUser() {
        return this.$store.state.users.currentUser
      },

      ecpm() {
        // we calculate the revenue again to get the raw
        // value instead of the formatted currency
        return this.calculateEcpm(this.stats.impressions, this.stats.revenue, false)
      },

      backfillRevenue() {
        return this.stats.desktopBackfillRevenue + this.stats.mobileBackfillRevenue
      },

      totalRevenue() {
        return this.backfillRevenue + this.stats.revenue
      },

      desktopEcpm() {
        return this.calculateEcpm(this.stats.tags.desktop.impressions, this.stats.desktopRevenue, false)
      },

      mobileEcpm() {
        return this.calculateEcpm(this.stats.tags.mobile.impressions, this.stats.mobileRevenue, false)
      },

      desktopEcpmSpark() {
        if (!this.chartData.desktopImpressions) return []

        return this.chartData.desktopImpressions.map((item, index) => {
          let ecpm = this.calculateEcpm(item[1], this.chartData.desktopRevenue[index][1], false)
          return [item[0], +ecpm.toFixed(2)]
        })
      },

      mobileEcpmSpark() {
        if (!this.chartData.mobileImpressions) return []

        return this.chartData.mobileImpressions.map((item, index) => {
          let ecpm = this.calculateEcpm(item[1], this.chartData.mobileRevenue[index][1], false)
          return [item[0], +ecpm.toFixed(2)]
        })
      },

      backfillRevenueSpark() {
        if (!this.chartData.mobileBackfillRevenue) return []

        let backfillRevenue = []

        for (let i = 0; i < this.chartData.mobileBackfillRevenue.length; i++) {
          let revenue = this.chartData.mobileBackfillRevenue[i][1] + this.chartData.desktopBackfillRevenue[i][1]
          backfillRevenue.push([this.chartData.mobileBackfillRevenue[i][0], revenue])
        }

        return backfillRevenue
      },

      totalRevenueSpark() {
        if (!this.chartData.mobileBackfillRevenue) return []

        let totalRevenue = []

        for (let i = 0; i < this.chartData.mobileBackfillRevenue.length; i++) {
          let revenue = this.chartData.mobileBackfillRevenue[i][1] + this.chartData.desktopBackfillRevenue[i][1] + this.chartData.revenue[i][1]
          totalRevenue.push([this.chartData.mobileBackfillRevenue[i][0], revenue])
        }

        return totalRevenue
      }
    },
    mounted() {
      this.$nextTick(function() {
        this.fetchStats()
        this.fetchChart()

        // if the user is loaded, set up the socket
        // if not, the socket will be set by the watcher
        if (!_.isEmpty(this.currentUser)) {
          this.userLoaded()
        }

        let that = this
        this.autoUpdateInterval = setInterval(function() {
          that.currentTime = moment()
        }, 2000)
      })
    },

    methods: {
      fetchStats() {
        http.get('/stats/all?time=realtime')
            .then((response) => {
              this.stats = response.data

              this.realtimeRetries = 0

              if (!this.destroyed) {
                setTimeout(this.fetchStats, 2000)
              }
            })
            .catch((error) => {
              let backoff = Math.pow(2, this.realtimeRetries) * 1000

              console.error('Error fetching the stats count, retrying in ' + backoff + 'ms...')

              if (!this.destroyed) {
                setTimeout(this.fetchStats, backoff)
              }

              this.realtimeRetries++
            })
      },

      fetchChart() {
        http.get('/charts/all?time=' + this.timeRange)
            .then((response) => {
              this.revenueChartData = response.data.revenue
              this.impressionsChartData = response.data.impressions
              this.requestsChartData = response.data.requests
            })
            .catch((error) => {
              console.error('Error fetching the stats.')
            })

        http.get('/stats/all?time=tenDays')
            .then((response) => {
              this.dailyStats = response.data
            })
            .catch((error) => {
              console.error('Error fetching the stats count.')
            })

        http.get('/charts/all?time=lastTwentyFourHours')
          .then((response) => {
            this.chartData = response.data
          })
          .catch((error) => {
            console.error('Error fetching the charts.')
          })
      },

      presentRevenue(revenue) {
        return accounting.formatMoney(revenue)
      },

      userLoaded() {
        if (this.currentUser.isAdmin) {
          this.$router.push({ name: 'admin.dashboard' })
        }
      },

      ...stats
    },

    watch: {
      timeRange(newTimeRange) {
        this.fetchChart()
      },
      currentUser() {
        this.userLoaded()
      }
    },

    destroyed() {
      this.destroyed = true
      window.clearInterval(this.autoUpdateInterval)
    },

    components: {
      Stats,
      LineBarChart,
      SparkChart
    }
  }
</script>

<style lang="scss">
.dashboard-livedatestamp {
  float: left;
  font-size: 11px;
  color: #303749;
  font-weight: 600;
  margin-left: 20px;
  line-height: 60px;
  text-transform: uppercase;

  span {
    color: #888888;
    margin-left: 10px;
    font-weight: 500;
  }
}

.dashstats-graphwrapper {
    float: left;
    width: 100%;
    height: 350px;
    margin: 0;
    padding: 0;
    font-family: "proxima-nova",sans-serif;
    /*border-top: 1px solid #E3E1E0;*/
}

.dashstats-graph {
  float: left;
  width: 100%;
  height: 300px;
  background: #F9F9F7;
}

.display-dashboardtimewrap {
  float: left;
  margin-left: 20px;
  margin-top: 12px;

  span {
    font-size: 12px;
    color: #373F52;
    margin-right: 5px;
  }

  select {
    height: 35px;
    background: #F9F9F7;
    border: 1px solid #E3E1E0;
    border-radius: 0px;
    -moz-border-radius: 0px;
    -webkit-border-radius: 0px;
  }
}

.dashstats-choosetime {
  float: left;
  font-size: 12px;
    color: #373F52;
    margin-right: 10px;
    margin-top: 10px;
    font-weight: 600;
}

.dashboard-graphtimeselect {
  width: 110px;
  background: #FFFFFF !important;
  border: 1px solid #E3E1E0 !important;
  height: 35px !important;
  font-size: 13px;
  color: #00A3DE;
  -webkit-border-radius: 0px;
  -moz-border-radius: 0px;
  border-radius: 0px;
  -webkit-appearance: none;
  padding: 0 8px;
  line-height: 22px;
  cursor: pointer;
}

.dashstats-graphselectarrow {
  position: absolute;
  background: url('/images/campviewselectarrow.png');
  width: 9px;
  height: 4px;
  margin-top: -19px;
  margin-left: 202px;
}
</style>
