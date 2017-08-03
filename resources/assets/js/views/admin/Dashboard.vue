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
          <div @click="viewAccount(site.user_id)" class="dashboard-secondaryviewaccount">VIEW ACCOUNT</div>
        </div>
      </div>

      <div class="display-dashboardtoparea">
        <div class="dashboard-livedatestamp">{{ currentTime.format('MMMM D YYYY') }}
          <span>( LIVE STATS UP TO {{ currentTime.format('hh:mm a') }} )</span>
        </div>
        <router-link :to="{ name: 'admin.reports.create'}">
          <div class="currentcamp-createbutton">GENERATE REPORT</div>
        </router-link>
        <div class="edittagsselect-wrapper comparetagsselect-wrapper" style="float: right;">
          <div class="edittagsselect-title">Ad Type</div>
          <select v-model="adTypeFilter">
            <option value="0">
              All
            </option>
            <option value="1">
              On-scroll
            </option>
            <option value="2">
              Infinity
            </option>
            <option value="3">
              Pre-roll
            </option>
          </select>
          <div class="edittagsselect-selectarrow" style="margin-left: 136px;"></div>
        </div>
      </div>
      <!-- ANALYTICS STATS -->
      <!-- TOP ANALYTICS -->
      <ul class="campaignstats-row">
        <li>
          <stats title="requests" :value="tagRequests" :animated="true"></stats>
          <spark-chart id="requests-chart" :chartData="chartData.tagRequests" color="#7772a7"></spark-chart>
        </li>
        <li>
          <stats title="impressions" :value="impressions" :animated="true"></stats>
          <spark-chart id="impressions-chart" :chartData="chartData.impressions" color="#7772a7"></spark-chart>
        </li>
        <li>
          <stats title="video revenue" :value="revenue" :animated="true" type="money" color="#1aa74f"></stats>
          <spark-chart id="revenue-chart" :chartData="chartData.revenue" color="#1aa74f"></spark-chart>
        </li>
        <li>
          <stats title="ecpm" :value="ecpm" color="#1aa74f" type="money"></stats>
          <spark-chart id="ecpm-chart" :chartData="ecpmSpark" color="#1aa74f"></spark-chart>
        </li>
      </ul>
      <!---<ul class="campaignstats-row">
        <li>
          <stats title="desktop pre-roll fill" type="percentage"
          :value="calculateFillRate(tags.desktop.preroll.fills, tags.desktop.preroll.tagRequests)"></stats>
          <spark-chart id="desktop-preroll-fill-chart" :chartData="chartData.desktopPrerollFill" color="#7772a7"></spark-chart>
        </li>
        <li>
          <stats title="mobile pre-roll fill" type="percentage"
          :value="calculateFillRate(tags.mobile.preroll.fills, tags.mobile.preroll.tagRequests)"></stats>
          <spark-chart id="mobile-preroll-fill-chart" :chartData="chartData.mobilePrerollFill" color="#7772a7"></spark-chart>
        </li>
        <li>
          <stats title="desktop pre-roll errors" type="percentage"
          :value="calculateErrorRate(tags.desktop.preroll.tagRequests, tags.desktop.preroll.errors)"></stats>
          <spark-chart id="desktop-preroll-errors-chart" :chartData="chartData.desktopPrerollErrors" color="#7772a7"></spark-chart>
        </li>
        <li>
          <stats title="mobile pre-roll errors" type="percentage"
          :value="calculateErrorRate(tags.mobile.preroll.tagRequests, tags.mobile.preroll.errors)"></stats>
          <spark-chart id="mobile-preroll-errors-chart" :chartData="chartData.mobilePrerollErrors" color="#7772a7"></spark-chart>
        </li>
      </ul>-->
      <ul class="campaignstats-row">
        <li>
          <stats title="desktop backfill revenue" :value="desktopBackfillRevenue" :animated="true" type="money"></stats>
          <spark-chart id="desktop-backfill-chart" :chartData="chartData.desktopBackfillRevenue" color="#7772a7"></spark-chart>
        </li>
        <li>
          <stats title="mobile backfill revenue" :value="mobileBackfillRevenue" :animated="true" type="money"></stats>
          <spark-chart id="mobile-backfill-chart" :chartData="chartData.mobileBackfillRevenue" color="#7772a7"></spark-chart>
        </li>
        <li>
          <stats title="backfill revenue" :value="backfillRevenue" :animated="true" type="money"></stats>
          <spark-chart id="total-backfill-chart" :chartData="backfillRevenueSpark" color="#7772a7"></spark-chart>
        </li>
        <li>
          <stats title="backfill ecpm" :value="backfillEcpm" type="money"></stats>
          <spark-chart id="backfill-ecpm-chart" :chartData="backfillEcpmSpark" color="#7772a7"></spark-chart>
        </li>
      </ul>
      <ul class="campaignstats-row">
        <li>
          <stats title="desktop outstream fill" type="percentage"
          :value="calculateFillRate(tags.desktop.outstream.fills, tags.desktop.outstream.tagRequests)"></stats>
          <spark-chart id="desktop-outstream-fill-chart" :chartData="chartData.desktopOutstreamFill" color="#7772a7"></spark-chart>
        </li>
        <li>
          <stats title="mobile outstream fill" type="percentage"
          :value="calculateFillRate(tags.mobile.outstream.fills, tags.mobile.outstream.tagRequests)"></stats>
          <spark-chart id="mobile-outstream-fill-chart" :chartData="chartData.mobileOutstreamFill" color="#7772a7"></spark-chart>
        </li>
        <li>
          <stats title="desktop outstream errors" type="percentage"
          :value="calculateErrorRate(tags.desktop.outstream.tagRequests, tags.desktop.outstream.errors)"></stats>
          <spark-chart id="desktop-outstream-errors-chart" :chartData="chartData.desktopOutstreamErrors" color="#7772a7"></spark-chart>
        </li>
        <li>
          <stats title="mobile outstream errors" type="percentage"
          :value="calculateErrorRate(tags.mobile.outstream.tagRequests, tags.mobile.outstream.errors)"></stats>
          <spark-chart id="mobile-outstream-errors-chart" :chartData="chartData.mobileOutstreamErrors" color="#7772a7"></spark-chart>
        </li>
      </ul>
      <ul class="campaignstats-row">
        <li>
          <stats title="desktop fill" :value="tags.desktop.fills" :animated="true"></stats>
          <spark-chart id="desktop-fill-chart" :chartData="chartData.desktopFill" color="#7772a7"></spark-chart>
        </li>
        <li>
          <stats title="mobile fill" :value="tags.mobile.fills" :animated="true"></stats>
          <spark-chart id="mobile-fill-chart" :chartData="chartData.mobileFill" color="#7772a7"></spark-chart>
        </li>
        <li>
          <stats title="desktop use-rate" type="percentage"
          :value="calculateUseRate(tags.desktop.impressions, tags.desktop.fills)"></stats>
          <spark-chart id="desktop-use-rate-chart" :chartData="chartData.desktopUserate" color="#7772a7"></spark-chart>
        </li>
        <li>
          <stats title="mobile use-rate" type="percentage"
          :value="calculateUseRate(tags.mobile.impressions, tags.mobile.fills)"></stats>
          <spark-chart id="mobile-use-rate-chart" :chartData="chartData.mobileUserate" color="#7772a7"></spark-chart>
        </li>
      </ul>
      <ul class="campaignstats-row">
        <li>
          <stats title="desktop pageviews" :value="desktopPageviews" :animated="true"></stats>
          <spark-chart id="desktop-pageviews-chart" :chartData="chartData.desktopPageviews" color="#7772a7"></spark-chart>
        </li>
        <li>
          <stats title="mobile pageviews" :value="mobilePageviews" :animated="true"></stats>
          <spark-chart id="mobile-pageviews-chart" :chartData="chartData.mobilePageviews" color="#7772a7"></spark-chart>
        </li>
        <li>
          <stats title="desktop pageviews fill" type="percentage"
          :value="calculateFillRate(tags.desktop.fills, desktopPageviews)"></stats>
          <spark-chart id="desktop-pageviews-fill-chart" :chartData="chartData.desktopPageviewsFill" color="#7772a7"></spark-chart>
        </li>
        <li>
          <stats title="mobile pageviews fill" type="percentage"
            :value="calculateFillRate(tags.mobile.fills, mobilePageviews)"></stats>
          <spark-chart id="mobile-pageviews-fill-chart" :chartData="chartData.mobilePageviewsFill" color="#7772a7"></spark-chart>
        </li>
      </ul>

      <tag-list></tag-list>
    </div>
  </div>
</template>

<script>
  import TagList from './DashboardTagList.vue'
  import Stats from '../dashboard/components/Stats.vue'
  import LineBarChart from '../dashboard/components/LineBarChart.vue'
  import SparkChart from '../components/SparkChart.vue'
  import stats from '../../services/stats'
  import http from '../../services/http'
  import moment from 'moment'

  export default {
    name: 'AdminDashboard',

    data() {
      return {
        // used for the Time Range Select.
        currentTime: moment(),

        adTypeFilter: '0',

        tags: {
          mobile: {
            fills: 0,
            impressions: 0,

            preroll: {
              tagRequests: 0,
              fills: 0,
              impressions: 0,
              errors: 0
            },

            outstream: {
              tagRequests: 0,
              fills: 0,
              impressions: 0,
              errors: 0
            }
          },

          desktop: {
            fills: 0,
            impressions: 0,

            preroll: {
              tagRequests: 0,
              fills: 0,
              impressions: 0,
              errors: 0
            },

            outstream: {
              tagRequests: 0,
              fills: 0,
              impressions: 0,
              errors: 0
            }
          }
        },

        tagRequests: 0,
        impressions: 0,
        revenue: 0,
        fills: 0,
        errorCount: 0,
        mobilePageviews: 0,
        desktopPageviews: 0,
        backfill: 0,
        mobileBackfillRevenue: 0,
        desktopBackfillRevenue: 0,

        chartData: {},

        autoUpdateInterval: null,

        realtimeRetries: 0,

        destroyed: false
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

      ecpm() {
        return this.calculateEcpm(this.impressions, this.revenue)
      },

      backfillEcpm() {
        return this.calculateEcpm(this.backfill, this.desktopBackfillRevenue + this.mobileBackfillRevenue)
      },

      backfillRevenue() {
        return this.desktopBackfillRevenue + this.mobileBackfillRevenue
      },

      ecpmSpark() {
        if (!this.chartData.impressions) return []

        return this.chartData.impressions.map((item, index) => {
          let ecpm = this.calculateEcpm(item[1], this.chartData.revenue[index][1], false)
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

      backfillEcpmSpark() {
        if (!this.chartData.mobileBackfillRevenue) return []

        return this.chartData.backfill.map((item, index) => {
          let ecpm = this.calculateEcpm(item[1], this.backfillRevenueSpark[index][1], false)
          return [item[0], +ecpm.toFixed(2)]
        })
      },

      fillRate() {
        return this.calculateFillRate(this.impressions, this.tagRequests)
      },

      errorRate() {
        return this.calculateErrorRate(this.impressions, this.errorCount)
      },

      useRate() {
        return this.calculateUseRate(this.impressions, this.fills)
      }
    },

    mounted() {
      this.$nextTick(function() {
        this.fetchStats()
        this.fetchCharts()
        this.$store.dispatch('admin/loadPendingWebsites')

        let that = this
        this.autoUpdateInterval = setInterval(function() {
          that.currentTime = moment()
        }, 2000)
      })
    },

    destroyed() {
      this.destroyed = true
      window.clearInterval(this.autoUpdateInterval)
    },

    methods: {
      viewAccount(userId) {
        this.$router.push({ name: 'admin.accounts.info', params: { accountId: userId }})
      },

      fetchStats() {
        let typeFilterQuery = ''

        if (parseInt(this.adTypeFilter) !== 0) {
          typeFilterQuery = '&type=' + this.adTypeFilter
        }

        http.get('/admin/stats/all?time=realtime' + typeFilterQuery)
          .then((response) => {
            this.impressions = parseInt(response.data.impressions)
            this.fills = parseInt(response.data.fills)
            this.revenue = parseFloat(response.data.revenue)
            this.errorCount = parseInt(response.data.errors)
            this.tagRequests = parseInt(response.data.tagRequests)
            this.mobilePageviews = parseInt(response.data.mobilePageviews)
            this.desktopPageviews = parseInt(response.data.desktopPageviews)
            this.backfill = parseInt(response.data.backfill)
            this.mobileBackfillRevenue = parseFloat(response.data.mobileBackfillRevenue)
            this.desktopBackfillRevenue = parseFloat(response.data.desktopBackfillRevenue)
            this.tagRequests = parseInt(response.data.tagRequests)
            this.tags = response.data.tags

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

      fetchCharts() {
        let typeFilterQuery = ''

        if (parseInt(this.adTypeFilter) !== 0) {
          typeFilterQuery = '&type=' + this.adTypeFilter
        }

        http.get('/admin/charts/all?time=lastTwentyFourHours' + typeFilterQuery)
          .then((response) => {
            this.chartData = response.data
          })
          .catch((error) => {
            console.error('Error fetching the charts.')
          })
      },

      ...stats
    },

    watch: {
      adTypeFilter() {
        this.fetchCharts()
      }
    },

    components: {
      Stats,
      LineBarChart,
      TagList,
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
  width: 9.5%;
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
