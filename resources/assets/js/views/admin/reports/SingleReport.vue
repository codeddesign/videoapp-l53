<template>
  <div>
    <div class="display-dashboardtoparea">
      <div class="dashboard-livedatestamp">REPORT QUERY: <span>{{ report.title }}</span></div>
      <div class="currentcamp-createbutton" @click="downloadXls()">EXPORT TO XLS</div>
      <div class="currentcamp-createbutton" @click="edit()">EDIT</div>
    </div>

    <!-- ANALYTICS STATS -->
    <!-- TOP ANALYTICS -->
    <ul class="campaignstats-row singlereportspurple">
      <li>
        <div class="campaignstats-title">REQUESTS</div>
        <div class="campaignstats-digit">{{ stats.allStats.requests }}</div>
        <div class="campaignstats-digit"><span id="graph_month"></span></div>
      </li>
      <li>
        <div class="campaignstats-title">IMPRESSIONS</div>
        <div class="campaignstats-digit">{{ stats.allStats.impressions }}</div>
        <div class="campaignstats-digit"><span id="graph_day"></span></div>
      </li>
      <li>
        <div class="campaignstats-title">FILLS</div>
        <div class="campaignstats-digit">{{ stats.allStats.fills }}</div>
        <div class="campaignstats-digit"><span id="graph_month_r"></span></div>
      </li>
    </ul>
    <!-- BOTTOM ANALYTICS -->
    <ul class="campaignstats-row singlereportsblue">
      <li>
        <div class="campaignstats-title">FILL-RATE</div>
        <div class="campaignstats-digit">
          {{ calculateFillRate(stats.allStats.fills, stats.allStats.requests) }}
        </div>
        <div class="campaignstats-digit"><span id="graph_month"></span></div>
      </li>
      <li>
        <div class="campaignstats-title">ERROR-RATE</div>
        <div class="campaignstats-digit">
          {{ calculateErrorRate(stats.allStats.requests, stats.allStats.ad_errors) }}
        </div>
        <div class="campaignstats-digit"><span id="graph_day"></span></div>
      </li>
      <li>
        <div class="campaignstats-title">USE-RATE</div>
        <div class="campaignstats-digit">
          {{ calculateUseRate(stats.allStats.impressions, stats.allStats.fills) }}
        </div>
        <div class="campaignstats-digit"><span id="graph_month_r"></span></div>
      </li>
    </ul>

    <div class="dashstats-graphwrapper">
      <div id="chart" class="chart">

      </div>
    </div>

    <!-- CAMPAIGN SELECTION AREA -->

    <div class="dashboard-dailystatstitle">DAILY STATS</div>
    <div class="dashreports-scrollwrapper">
      <div class="dashreports-scrollarea">
        <ul class="dashboard-dailystatstitles dashreports-titlewidth">
          <li>ADVERTISER</li>
          <li>TAG NAME</li>
          <li>PLATFORM</li>
          <li>TYPE</li>
          <li>IMPRESSIONS</li>
          <li>AD REQUESTS</li>
          <li>FILLS</li>
          <li>FILL %</li>
          <li>CLICKS</li>
          <li>CTR</li>
          <li>eCPM</li>
          <li>REVENUE</li>
          <li>ONSCROLL</li>
          <li>ONSCROLL %</li>
          <li>ONSCROLL ERRORS</li>
          <li>INFINITY</li>
          <li>INFINITY %</li>
          <li>INFINITY ERRORS</li>
          <li>START</li>
          <li>FIRST QUARTILE</li>
          <li>MIDPOINT</li>
          <li>THIRD QUARTILE</li>
          <li>COMPLETE</li>
          <li>COMPLETION RATE</li>
          <li>AVERAGE VIEW RATE</li>
          <li>AVERAGE VIEW TIME</li>
          <li>AVG VIDEO LENGTH</li>
          <li>TOTAL ERRORS</li>
          <li>ERROR RATE</li>
          <li>ERROR 101</li>
          <li>ERROR 102</li>
          <li>ERROR 200</li>
          <li>ERROR 201</li>
          <li>ERROR 202</li>
          <li>ERROR 203</li>
          <li>ERROR 300</li>
          <li>ERROR 301</li>
          <li>ERROR 302</li>
          <li>ERROR 303</li>
          <li>ERROR 400</li>
          <li>ERROR 401</li>
          <li>ERROR 402</li>
          <li>ERROR 403</li>
          <li>ERROR 405</li>
          <li>ERROR 500</li>
          <li>ERROR 501</li>
          <li>ERROR 502</li>
          <li>ERROR 503</li>
          <li>ERROR 600</li>
          <li>ERROR 601</li>
          <li>ERROR 602</li>
          <li>ERROR 603</li>
          <li>ERROR 604</li>
          <li>ERROR 900</li>
          <li>ERROR 901</li>
        </ul>
        <ul class="dashboard-dailystatslist dashreports-width">
          <li v-for="tag in paginatedStats">
            <div class="dashboard-statslist1">{{ tag.advertiser }}</div>
            <div class="dashboard-statslist2">{{ tag.description }}</div>
            <div class="dashboard-statslist2">{{ tag.platform_type }}</div>
            <div class="dashboard-statslist2">{{ tag.ad_type }}</div>
            <div class="dashboard-statslist2">{{ tag.impressions }}</div>
            <div class="dashboard-statslist2">{{ tag.requests }}</div>
            <div class="dashboard-statslist2">{{ tag.fills }}</div>
            <div class="dashboard-statslist2">{{ tag.fill_rate }}%</div>
            <div class="dashboard-statslist2">{{ tag.clicks }}</div>
            <div class="dashboard-statslist2">TBD</div>
            <div class="dashboard-statslist2">${{ tag.ecpm }}</div>
            <div class="dashboard-statslist2">${{ tag.revenue }}</div>
            <div class="dashboard-statslist2">TBD</div>
            <div class="dashboard-statslist2">TBD</div>
            <div class="dashboard-statslist2">TBD</div>
            <div class="dashboard-statslist2">TBD</div>
            <div class="dashboard-statslist2">TBD</div>
            <div class="dashboard-statslist2">TBD</div>
            <div class="dashboard-statslist2">{{ tag.start }}</div>
            <div class="dashboard-statslist2">{{ tag.firstquartile }}</div>
            <div class="dashboard-statslist2">{{ tag.midpoint }}</div>
            <div class="dashboard-statslist2">{{ tag.thirdquartile }}</div>
            <div class="dashboard-statslist2">{{ tag.complete }}</div>
            <div class="dashboard-statslist2">TBD</div>
            <div class="dashboard-statslist2">TBD</div>
            <div class="dashboard-statslist2">TBD</div>
            <div class="dashboard-statslist2">TBD</div>
            <div class="dashboard-statslist2">{{ tag.errors }}</div>
            <div class="dashboard-statslist2">{{ tag.error_rate }}%</div>
            <div class="dashboard-statslist2">{{ tag.error101 }}</div>
            <div class="dashboard-statslist2">{{ tag.error102 }}</div>
            <div class="dashboard-statslist2">{{ tag.error200 }}</div>
            <div class="dashboard-statslist2">{{ tag.error201 }}</div>
            <div class="dashboard-statslist2">{{ tag.error202 }}</div>
            <div class="dashboard-statslist2">{{ tag.error203 }}</div>
            <div class="dashboard-statslist2">{{ tag.error300 }}</div>
            <div class="dashboard-statslist2">{{ tag.error301 }}</div>
            <div class="dashboard-statslist2">{{ tag.error302 }}</div>
            <div class="dashboard-statslist2">{{ tag.error303 }}</div>
            <div class="dashboard-statslist2">{{ tag.error400 }}</div>
            <div class="dashboard-statslist2">{{ tag.error401 }}</div>
            <div class="dashboard-statslist2">{{ tag.error402 }}</div>
            <div class="dashboard-statslist2">{{ tag.error403 }}</div>
            <div class="dashboard-statslist2">{{ tag.error405 }}</div>
            <div class="dashboard-statslist2">{{ tag.error500 }}</div>
            <div class="dashboard-statslist2">{{ tag.error501 }}</div>
            <div class="dashboard-statslist2">{{ tag.error502 }}</div>
            <div class="dashboard-statslist2">{{ tag.error503 }}</div>
            <div class="dashboard-statslist2">{{ tag.error600 }}</div>
            <div class="dashboard-statslist2">{{ tag.error601 }}</div>
            <div class="dashboard-statslist2">{{ tag.error602 }}</div>
            <div class="dashboard-statslist2">{{ tag.error603 }}</div>
            <div class="dashboard-statslist2">{{ tag.error604 }}</div>
            <div class="dashboard-statslist2">{{ tag.error900 }}</div>
            <div class="dashboard-statslist2">{{ tag.error901 }}</div>
          </li>
        </ul>
      </div><!-- END .dashreports-scrollarea -->
    </div><!-- END .dashreports-scrollwrapper -->
    <div class="understatlist-wrapper">
      <div class="dashpagination-wrapper">
        <div @click="pagination.previousPage()" class="dashpag-left"></div>
        <div class="dashpag-numbers">{{ pagination.currentPage() }} of {{ pagination.totalPages() }}</div>
        <div @click="pagination.nextPage()" class="dashpag-right"></div>
      </div>
      <div class="dashpagerows-wrapper">
        <div class="dashpagerows-title">Display Rows:</div>
        <select v-model="pagination['perPage']">
          <option value="10">10</option>
          <option value="25">25</option>
          <option value="50">50</option>
        </select>
        <div class="dashpagerows-selectarrow"></div>
      </div>
    </div>
  </div>
</template>

<script>
  import Highcharts from 'highcharts'
  import Pagination from '../../../services/pagination'
  import _ from 'lodash'
  import http from '../../../services/http'
  import stats from '../../../services/stats'

  export default {
    name: 'SingleReport',
    data() {
      return {
        chart: null,
        stats: {
          allStats: {},
          tagStats: {}
        },
        pagination: new Pagination()
      }
    },

    methods: {
      fetchStats(report) {
        http.get('/admin/reports/' + report.id + '/stats')
            .then((response) => {
              this.stats = response.data
              this.showChart()
            })
      },

      edit() {
        this.chart.destroy()
        this.$router.push({ name: 'admin.reports.edit', params: { reportId: this.report.id }})
      },

      downloadXls() {
        http.get('/user/token').then((response) => {
          let token = response.data
          window.open('/api/admin/reports/' + this.report.id + '/xls?jwt=' + token, '_self')
        })
      },

      showChart() {
        let categories = []
        let chartStats = {
          impressions: [],
          fills: [],
          errors: []
        }

        _.each(this.stats.tagStats, tag => {
          categories.push(tag.description)
          chartStats.impressions.push(tag.impressions)
          chartStats.fills.push(tag.fills)
          chartStats.errors.push(tag.errors)
        })

        this.chart = Highcharts.chart('chart', {
          credits: {
            enabled: false
          },
          chart: {
            type: 'column',
            backgroundColor: 'transparent'
          },
          shadow: false,
          title: false,
          xAxis: {
            categories: categories
          },
          legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            floating: true
          },
          series: [
            {
              name: 'Impressions',
              color: '#00a2d9',
              data: chartStats.impressions
            }, {
              name: 'Fills',
              color: '#468c01',
              data: chartStats.fills
            }, {
              name: 'Errors',
              color: '#ff4001',
              data: chartStats.errors
            }
          ]
        })
      },
      ...stats
    },

    computed: {
      report() {
        let report = _.find(this.$store.state.admin.reports, { 'id': parseInt(this.$route.params.reportId) })

        if (report === undefined) {
          this.$store.dispatch('loadReports')
          return {}
        }

        this.fetchStats(report)

        return report
      },

      paginatedStats() {
        this.pagination.data = this.stats.tagStats
        console.log(this.stats.tagStats)
        return this.pagination.getData()
      }
    },

    mounted() {
      this.$nextTick(function() {

      })
    }
  }
</script>
