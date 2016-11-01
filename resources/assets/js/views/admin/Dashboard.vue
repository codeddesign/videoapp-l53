<template>
  <div v-if="currentUser.isAdmin">
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
              <stats title="request" :value="requests"></stats>
          </li>
          <li>
            <stats title="impressions" :value="impressions"></stats>
          </li>
          <li>
            <stats title="revenue" :value="revenue" color="#1aa74f"></stats>
          </li>
          <li>
            <stats title="ecpm" :value="ecpm" color="#1aa74f"></stats>
          </li>
        </ul>
        <!-- BOTTOM ANALYTICS -->
        <ul class="campaignstats-row">
          <li>
            <stats title="fill" :value="fills"></stats>
          </li>
          <li>
            <stats title="fill-rate" :value="fillRate"></stats>
          </li>
          <li>
            <stats title="error-rate" :value="errorRate" color="#009dd7"></stats>
          </li>
          <li>
            <stats title="use-rate" :value="useRate" color="#009dd7"></stats>
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
            <line-bar-chart :timeRange="timeRange" :revenue="revenueChartData" :impressions="impressionsChartData"></line-bar-chart>
          </div>
        </div>

        <!-- CAMPAIGN SELECTION AREA -->
        <div class="dashboard-dailystatstitle">DAILY STATS</div>
        <ul class="dashboard-dailystatstitles">
          <li>DATE</li>
          <li>REQUESTS</li>
          <li>FILL-RATE</li>
          <li>eCPM</li>
          <li>REVENUE</li>
        </ul>
        <ul class="dashboard-dailystatslist">
          <li v-for="(stat, date) in dailyStats">
            <div class="dashboard-statslist1">{{ date }}</div>
            <div class="dashboard-statslist2">{{ stat.requests }}</div>
            <div class="dashboard-statslist2">{{ calculateFillRate(stat.impressions, stat.requests) }}%</div>
            <div class="dashboard-statslist2">
              {{ calculateEcpm(stat.impressions, calculateRevenue(stat.impressions, false)) }}
            </div>
            <div class="dashboard-statslist2">{{ calculateRevenue(stat.impressions) }}</div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
  import Stats from '../dashboard/components/Stats.vue'
  import LineBarChart from '../dashboard/components/LineBarChart.vue'
  import socket from '../../services/socket'
  import events from '../../services/events'
  import stats from '../../services/stats'
  import moment from 'moment'

  export default {
    name: 'AdminDashboard',

    data() {
      return {
        // used for the Time Range Select.
        currentTime: moment(),

        timeRange: 'realtime',
        timeRangeOptions: [
          { text: 'Real-time', value: 'realtime' },
          { text: 'Today', value: 'today' },
          { text: 'Yesterday', value: 'yesterday' },
          { text: 'Last 7 Days', value: 'sevenDays' },
          { text: 'This Month', value: 'thisMonth' },
          { text: 'Last Month', value: 'lastMonth' }
        ],

        requests: 0,
        impressions: 0,

        fills: 0,
        fillErrors: 0,

        adErrors: 0,

        useRate: 0,

        requestsChartData: [],
        impressionsChartData: [],
        revenueChartData: [],

        dailyStats: []
      }
    },

    computed: {
      currentUser() {
        return this.$store.state.users.currentUser
      },

      revenue() {
        return this.calculateRevenue(this.impressions)
      },

      ecpm() {
        // we calculate the revenue again to get the raw
        // value instead of the formatted currency
        return this.calculateEcpm(this.impressions, this.calculateRevenue(this.impressions, false))
      },

      fillRate() {
        return this.calculateFillRate(this.impressions, this.requests)
      },

      errorRate() {
        return this.calculateErrorRate(this.impressions, this.adErrors)
      },

      useRate() {
        return this.calculateUseRate(this.impressions, this.fills)
      }
    },
    mounted() {
      this.$nextTick(function() {
        this.fetchStats()
        this.fetchChart()
      })
    },

    methods: {
      fetchStats() {
        this.$http.get('/api/admin/stats/all?time=realtime').then(
          (response) => {
            this.requests = parseInt(response.data.requests)
            this.impressions = parseInt(response.data.impressions)
            this.fills = parseInt(response.data.fills)
            this.adErrors = parseInt(response.data.adErrors)
            this.fillErrors = parseInt(response.data.fillErrors)
          }, () => console.log('Error fetching the stats count.')
        )

        this.$http.get('/api/admin/stats/all?time=tenDays').then(
          (response) => {
            this.dailyStats = response.data
          }, () => console.log('Error fetching the stats count.')
        )
      },

      fetchChart() {
        this.$http.get('/api/charts/all?time=' + this.timeRange).then((response) => {
          this.revenueChartData = response.data.revenue
          this.impressionsChartData = response.data.impressions
          this.requestsChartData = response.data.requests
        }, () => console.log('Error fetching the stats.'))
      },

      ...stats
    },

    watch: {
      timeRange(newTimeRange) {
        this.fetchChart()
      },

      currentUser() {
        if (!this.currentUser.isAdmin) {
          this.$router.push({ name: 'dashboard' })
        }

        let echo = socket.connection()
        if (echo) {
          echo.private('user.' + this.currentUser.id)
              .listen('CampaignEventReceived', (e) => {
                switch (events.type(e)) {
                  case 'request':
                    this.requests++
                    break
                  case 'impression':
                    this.impressions++
                    break
                  case 'fill':
                    this.fills++
                    break
                  case 'tag-error':
                    this.fillErrors++
                    break
                  case 'ad-error':
                    this.adErrors++
                    break
                }
              })
          let that = this
          setInterval(function() {
            that.currentTime = moment()
          }, 5000) // every 5 seconds
        } else {
          console.error('Couldn\'t connect to web socket')
        }
      }
    },

    components: {
      Stats,
      LineBarChart
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
