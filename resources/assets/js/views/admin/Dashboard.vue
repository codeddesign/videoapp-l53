<template>
  <div>
    <div class="page-index">

      <div v-show="alerts > 0">
        <div class="dashboard-alertwrapper">
          <div class="dashboard-alertcount">{{ alerts }} ALERTS</div>
          <div class="dashboard-alertclear">CLEAR ALL</div>
          <div class="dashboard-alertviewall">VIEW ALERTS</div>
        </div>

        <div v-for="site in pendingWebsites" class="dashboard-secondaryalert">
          <div class="dashboard-secondarytitle">WEBSITE ACTIVATION</div>
          <div class="dashboard-secondarytitletwo">{{ site.domain }}</div>
          <div class="dashboard-secondarydelete"></div>
          <div class="dashboard-secondaryviewaccount">VIEW ACCOUNT</div>
        </div>
      </div>
    
      <div class="display-dashboardtoparea">
        <div class="dashboard-livedatestamp">{{ currentTime.format('MMMM D YYYY') }}
          <span>( LIVE STATS UP TO {{ currentTime.format('hh:mm a') }} )</span>
        </div>
        <router-link :to="{ name: 'campaigns.create'}">
          <div class="currentcamp-createbutton">GENERATE REPORT</div>
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

            <!-- CAMPAIGN SELECTION AREA -->
      <div class="admindash-supplierwrapper">
        <div class="admindash-dailystatstitle">SUPPLIER ANALYTICS</div>
        <div class="admindash-suppliercompare">
          <div class="admindash-suppliercomparetitle">Compare to:</div>
          <div class="admindash-supplierdroplist">
            <select>
            <option value="">Yesterday</option>
            <option value="">Last 7 Days</option>
            <option value="">This Month</option>
            <option value="">Last Month</option>
          </select>
          <div class="admindash-supplierdroparrow"></div>
          </div>  
        </div>
      </div>    
      <ul class="admindashboard-dailystatstitles">
          <li>SUPPLIER</li>
          <li>PLATFORM</li>
          <li>TYPE</li>
          <li>REQUESTS</li>
          <li>IMPRESSIONS</li>
          <li>FILL-RATE</li>
          <li>ERROR-RATE</li>
          <li>TAG DISPLAY %</li>
      </ul>
      <ul class="admindashboard-dailystatslist">
          <li>
              <div class="dashboard-statslist1">AOL</div>
              <div class="dashboard-statslist2">DESKTOP</div>
              <div class="dashboard-statslist2">PRE-ROLL</div>
              <div class="dashboard-statslist2">1,057 <span class="down">(-34%)</span></div>
              <div class="dashboard-statslist2">1,501 <span class="down">(-34%)</span></div>
              <div class="dashboard-statslist2">17% <span class="down">(-34%)</span></div>
              <div class="dashboard-statslist2">4% <span class="down">(-34%)</span></div>
              <div class="dashboard-statslist2">17% <span class="up">(+34%)</span></div>
          </li>
      </ul>
      <div class="understatlist-wrapper">
        <div class="dashpagination-wrapper">
          <div class="dashpag-left"></div>
          <div class="dashpag-numbers">1 of 12</div>
          <div class="dashpag-right"></div>
        </div>
        <div class="dashpagerows-wrapper">
          <div class="dashpagerows-title">Display Rows:</div>
          <select>
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
          </select>
          <div class="dashpagerows-selectarrow"></div>
        </div>
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

        dailyStats: []
      }
    },

    computed: {
      currentUser() {
        return this.$store.state.users.currentUser
      },

      alerts() {
        return this.pendingWebsites.length
      },

      pendingWebsites() {
        return this.$store.state.admin.pendingWebsites
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
        this.$store.dispatch('loadPendingWebsites')
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

.admindash-supplierwrapper {
  float: left;
    width: 100%;
    height: 50px;
    background: #6C689E;
}

.admindash-dailystatstitle {
  float: left;
  font-size: 11px;
  color: #FFFFFF;
  line-height: 50px;
  margin-left: 34px;
  letter-spacing: 0.6px;
}

.admindash-suppliercompare {
  float: right;
  margin-right: 34px;
  margin-top: 6px;
}

.admindash-suppliercomparetitle {
  float: left;
  font-size: 12px;
  color: #EEEEEE;
  margin-right: 10px;
  margin-top: 10px;
  font-weight: 600;
}

.admindash-supplierdroplist {
  float: left;
}

.admindash-supplierdroplist select {
  width: 110px;
  background: #FFFFFF !important;
  border: 1px solid #615E8F !important;
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

.admindash-supplierdroparrow {
  position: absolute;
  background: url('/images/campviewselectarrow.png');
  width: 9px;
  height: 4px;
  margin-top: -19px;
  margin-left: 92px;
}

ul.admindashboard-dailystatstitles {
  float: left;
  width: 100%;
  margin: 0;
  padding: 0;
  border-top: 1px solid #E3E1E0;
  border-bottom: 1px solid #E3E1E0;
}

ul.admindashboard-dailystatstitles li {
  width: 12.5%;
  background: #F5F5F4;
  border-right: 1px solid #E3E1E0;
  font-size: 10px;
  line-height: 41px;
  padding-left: 20px;
  display: inline-block;
  float: left;
  height: 40px;
  list-style-type: none;
}

ul.admindashboard-dailystatslist {
  display: inline;
  float: left;
  width: 100%;
  margin: 0;
  padding: 0;
}

ul.admindashboard-dailystatslist li {
  list-style-type: none;
  line-height: 40px;
  height: 40px;
  float: left;
  width: 100%;
}

.understatlist-wrapper {
  float: left;
  width: 100%;
  height: 40px;
  background: #F9F9F7;
  border-top: 1px solid #E3E1E0;
  border-bottom: 1px solid #E3E1E0;
}

.dashpagination-wrapper {
  float: right;
  background: #EEEEEE;
  width: 149px;
  height: 38px;
  border-left: 1px solid #E3E1E0;
}

.dashpagination-wrapper {
  float: right;
  background: #EEEEEE;
  width: 149px;
  height: 38px;
  border-left: 1px solid #E3E1E0;
}

.dashpag-left {
  float: left;
  width: 32px;
  height: 30px;
  background: url('/images/dashpagleft.jpg');
  cursor: pointer;
  margin-top: 4px;
  margin-left: 12px;
}

.dashpag-left:hover {
  background: url('/images/dashpagleft.jpg') 0 -30px;
}

.dashpag-right {
  float: right;
  width: 32px;
  height: 30px;
  background: url('/images/dashpagright.jpg');
  cursor: pointer;
  margin-top: 4px;
  margin-right: 12px;
}

.dashpag-right:hover {
  background: url('/images/dashpagright.jpg') 0 -30px;
}

.dashpag-numbers {
  float: left;
  width: 59px;
  text-align: center;
  line-height: 39px;
  font-size: 12px;
  color: #AAAAAA;
}

.dashpagerows-wrapper {
  float: right;
  margin-right: 10px;
}

.dashpagerows-wrapper select {
  float: right;
  width: 50px;
    background: #FFFFFF;
    border: 1px solid #E3E1E0;
    height: 30px;
    font-size: 13px;
    color: #00A3DE;
    -webkit-border-radius: 0px;
    -moz-border-radius: 0px;
    border-radius: 0px;
    -webkit-appearance: none;
    padding: 0 6px;
    line-height: 30px;
    margin-top: 4px;
    cursor: pointer;
}

.dashpagerows-wrapper .dashpagerows-title {
  float: left;
  margin-top: 12px;
  color: #373F52;
  font-size: 12px;
  font-weight: 600;
  margin-right: 10px;
}

.dashpagerows-selectarrow {
  position: absolute;
    background: url('/images/campviewselectarrow.png');
    width: 9px;
    height: 4px;
    margin-top: 17px;
    margin-left: 117px;
}

ul.admindashboard-dailystatslist .dashboard-statslist2 span.down {
  color: #FF0000;
  margin-left: 4px;
}

ul.admindashboard-dailystatslist .dashboard-statslist2 span.up {
  color: #1AA750;
  margin-left: 4px;
}
</style>
