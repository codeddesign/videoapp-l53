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
          <span>( LIVE STATS FROM {{ currentTime.clone().startOf('hour').format('hh:mm a') }} UP TO {{ currentTime.format('hh:mm a') }} )</span>
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
          <stats title="revenue" :value="presentRevenue" color="#1aa74f"></stats>
        </li>
        <li>
          <stats title="ecpm" :value="ecpm" color="#1aa74f"></stats>
        </li>
      </ul>
      <ul class="campaignstats-row">
        <li>
          <stats title="desktop pre-roll fill"
          :value="calculateFillRate(tags.desktop.preroll.impressions, tags.desktop.preroll.requests)"></stats>
        </li>
        <li>
          <stats title="mobile pre-roll fill"
          :value="calculateFillRate(tags.mobile.preroll.impressions, tags.mobile.preroll.requests)"></stats>
        </li>
        <li>
          <stats title="desktop pre-roll errors"
          :value="calculateErrorRate(tags.desktop.preroll.impressions, tags.desktop.preroll.errors)"></stats>
        </li>
        <li>
          <stats title="mobile pre-roll errors"
          :value="calculateErrorRate(tags.mobile.preroll.impressions, tags.mobile.preroll.errors)"></stats>
        </li>
      </ul>
      <ul class="campaignstats-row">
        <li>
          <stats title="desktop outstream fill"
          :value="calculateFillRate(tags.desktop.outstream.impressions, tags.desktop.outstream.requests)"></stats>
        </li>
        <li>
          <stats title="mobile outstream fill"
          :value="calculateFillRate(tags.mobile.outstream.impressions, tags.mobile.outstream.requests)"></stats>
        </li>
        <li>
          <stats title="desktop outstream errors"
          :value="calculateErrorRate(tags.desktop.outstream.impressions, tags.desktop.outstream.errors)"></stats>
        </li>
        <li>
          <stats title="mobile outstream errors"
          :value="calculateErrorRate(tags.mobile.outstream.impressions, tags.mobile.outstream.errors)"></stats>
        </li>
      </ul>

      <tag-list></tag-list>
    </div>
  </div>
</template>

<script>
  import TagList from './tags/TagList.vue'
  import Stats from '../dashboard/components/Stats.vue'
  import LineBarChart from '../dashboard/components/LineBarChart.vue'
  import socket from '../../services/socket'
  import events from '../../services/events'
  import stats from '../../services/stats'
  import http from '../../services/http'
  import moment from 'moment'
  import accounting from 'accounting'
  import _ from 'lodash'

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

        tags: {
          mobile: {
            preroll: {
              requests: 0,
              impressions: 0,
              errors: 0
            },

            outstream: {
              requests: 0,
              impressions: 0,
              errors: 0
            }
          },

          desktop: {
            preroll: {
              requests: 0,
              impressions: 0,
              errors: 0
            },

            outstream: {
              requests: 0,
              impressions: 0,
              errors: 0
            }
          }
        },

        requests: 0,
        impressions: 0,

        revenue: 0,

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

      presentRevenue() {
        return accounting.formatMoney(this.revenue)
      },

      alerts() {
        return this.pendingWebsites.length
      },

      pendingWebsites() {
        return this.$store.state.admin.pendingWebsites
      },

      ecpm() {
        // we calculate the revenue again to get the raw
        // value instead of the formatted currency
        return this.calculateEcpm(this.impressions, this.revenue)
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

        // if the user is loaded, set up the socket
        // if not, the socket will be set by the watcher
        if (!_.isEmpty(this.currentUser)) {
          this.setupSocket()
        }
      })
    },

    methods: {
      fetchStats() {
        http.get('/admin/stats/all?time=realtime')
          .then((response) => {
            this.requests = parseInt(response.data.requests)
            this.impressions = parseInt(response.data.impressions)
            this.fills = parseInt(response.data.fills)
            this.revenue = parseFloat(response.data.revenue)
            this.adErrors = parseInt(response.data.adErrors)
            this.fillErrors = parseInt(response.data.fillErrors)
            this.tags = response.data.tags
          })
          .catch((error) => {
            console.error('Error fetching the stats count.')
          })

        http.get('/admin/stats/all?time=tenDays')
          .then((response) => {
            this.dailyStats = response.data
          })
          .catch((error) => {
            console.error('Error fetching the stats count.')
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
            console.error('Error fetching the charts data.')
          })
      },

      getTagStats(tag) {
        let types = _.map(tag.campaign_types, (value, type) => {
          if (value === true) {
            return type
          }
        }).filter((type) => { return type !== undefined })

        let tagStats = types.map(type => {
          return this.tags[tag.platform_type][type]
        }).filter((tag) => { return tag !== undefined })

        if (this.tags[tag.platform_type][tag.ad_type] !== undefined) {
          tagStats.push(this.tags[tag.platform_type][tag.ad_type])
        }

        return tagStats
      },

      setupSocket() {
        console.log('setting up socket')
        let echo = socket.connection()
        if (echo) {
          echo.private('user.' + this.currentUser.id)
              .listen('CampaignEventReceived', (e) => {
                console.log(e)
                let tags = this.getTagStats(e.tag)
                switch (events.type(e)) {
                  case 'request':
                    this.requests++
                    tags.forEach((tag) => {
                      tag.requests++
                    })
                    break
                  case 'impression':
                    tags.forEach((tag) => {
                      tag.impressions++
                    })
                    this.impressions++
                    this.revenue += (e.tag.ecpm) / 1000
                    break
                  case 'fill':
                    this.fills++
                    break
                  case 'tag-error':
                    tags.forEach((tag) => {
                      tag.errors++
                    })
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
      },

      ...stats
    },

    watch: {
      timeRange(newTimeRange) {
        this.fetchChart()
      },

      currentUser() {
        this.setupSocket()
      }
    },

    components: {
      Stats,
      LineBarChart,
      TagList
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
