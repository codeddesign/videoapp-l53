<template>
  <div>
    <div class="display-dashboardtoparea">
      <div class="dashboard-livedatestamp">REPORT QUERY: <span>{{ report.title }}</span><span>{{ showCustomDate(report) }}</span></div>
      <div class="currentcamp-createbutton" @click="downloadXls()">EXPORT TO XLS</div>
      <div class="currentcamp-createbutton" @click="edit()">EDIT</div>
    </div>

    <!-- ANALYTICS STATS -->
    <!-- TOP ANALYTICS -->
    <div>
      <div v-show="loading" class="report-loading">
        <bounce-loader :loading="loading" color="#7772a7" size="100px"></bounce-loader>
      </div>
      <ul class="campaignstats-row">
        <li>
          <stats title="impressions" :value="stats.allStats.impressions" :animated="true"></stats>
          <spark-chart id="impressions-chart" :chartData="chartData.impressions" color="#7772a7"></spark-chart>
        </li>
        <li>
          <stats title="video revenue" :value="stats.allStats.revenue" color="#1aa74f" type="money" :animated="true"></stats>
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
          <stats title="desktop pageviews" :value="stats.allStats.desktopPageviews" :animated="true"></stats>
          <spark-chart id="desktop-pageviews-chart" :chartData="chartData.desktopPageviews" color="#7772a7"></spark-chart>
        </li>
        <li>
          <stats title="desktop impressions" :value="stats.allStats.tags.desktop.impressions" :animated="true"></stats>
          <spark-chart id="desktop-impressions-chart" :chartData="chartData.desktopImpressions" color="#7772a7"></spark-chart>
        </li>
        <li>
          <stats title="desktop ecpm" :value="desktopEcpm" color="#1aa74f" type="money"></stats>
          <spark-chart id="desktop-ecpm-chart" :chartData="desktopEcpmSpark" color="#1aa74f"></spark-chart>
        </li>
        <li>
          <stats title="desktop revenue" :value="stats.allStats.desktopRevenue" color="#1aa74f" type="money" :animated="true"></stats>
          <spark-chart id="desktop-revenue-chart" :chartData="chartData.desktopRevenue" color="#1aa74f"></spark-chart>
        </li>
      </ul>

      <ul class="campaignstats-row">
        <li>
          <stats title="mobile pageviews" :value="stats.allStats.mobilePageviews" :animated="true"></stats>
          <spark-chart id="mobile-pageviews-chart" :chartData="chartData.mobilePageviews" color="#7772a7"></spark-chart>
        </li>
        <li>
          <stats title="mobile impressions" :value="stats.allStats.tags.mobile.impressions" :animated="true"></stats>
          <spark-chart id="mobile-impressions-chart" :chartData="chartData.mobileImpressions" color="#7772a7"></spark-chart>
        </li>
        <li>
          <stats title="mobile ecpm" :value="mobileEcpm" color="#1aa74f" type="money"></stats>
          <spark-chart id="mobile-ecpm-chart" :chartData="mobileEcpmSpark" color="#1aa74f"></spark-chart>
        </li>
        <li>
          <stats title="mobile revenue" :value="stats.allStats.mobileRevenue" color="#1aa74f" type="money" :animated="true"></stats>
          <spark-chart id="mobile-revenue-chart" :chartData="chartData.mobileRevenue" color="#1aa74f"></spark-chart>
        </li>
      </ul>
    </div>

    <div class="dashstats-graphwrapper">
      <div id="chart" class="chart">

      </div>
    </div>

    <!-- CAMPAIGN SELECTION AREA -->

    <div class="dashboard-dailystatstitle">COMPARISON STATS</div>
    <div class="dashreports-scrollwrapper">
      <div class="dashreports-scrollarea">
        <ul class="dashboard-dailystatstitles dashreports-titlewidth">
          <li v-for="header in stats.header">
            {{ header }}
          </li>
        </ul>
        <ul class="dashboard-dailystatslist dashreports-width">
          <li v-for="stats in paginatedStats">
            <div :class="'dashboard-statslist' + (index === 0 ? '1' : '2')" v-for="(stat, key, index) in stats">
              {{ presentStat(stat, key) }}
            </div>
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
          <option v-for="option in pagination['perPageOptions']" v-bind:value="option">{{ option }}</option>
        </select>
        <div class="dashpagerows-selectarrow"></div>
      </div>
    </div>
  </div>
</template>

<script>
  import Highcharts from 'highcharts'
  import Stats from '../dashboard/components/Stats.vue'
  import SparkChart from '../components/SparkChart.vue'
  import BounceLoader from 'vue-spinner/src/BounceLoader.vue'
  import Pagination from '../../services/pagination'
  import _ from 'lodash'
  import http from '../../services/http'
  import stats from '../../services/stats'
  import numeral from 'numeral'
  import moment from 'moment'
  import accounting from 'accounting'

  export default {
    name: 'SingleReport',
    data() {
      return {
        loading: true,
        chart: null,
        stats: {
          allStats: {
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
            }
          },
          stats: {}
        },

        chartData: {},
        pagination: new Pagination()
      }
    },

    methods: {
      presentStat(stat, key) {
        if (['cpm', 'revenue', 'rpm'].indexOf(key) !== -1) {
          if (stat === '—') {
            return stat
          }
          return accounting.formatMoney(stat)
        }

        if (['fill_rate', 'error_rate', 'ctr', 'completion_rate', 'pv_fill_rate'].indexOf(key) !== -1) {
          return stat + '%'
        }

        if (isNaN(stat)) {
          return stat
        }

        return numeral(stat).format('0,0')
      },

      showCustomDate(report) {
        if (report.date_range === 'custom') {
          let startDate = moment(report.start_date).format('MMMM Do')
          let endDate = moment(report.end_date).format('MMMM Do')
          return '(' + startDate + ' - ' + endDate + ')'
        }
      },

      fetchStats(report) {
        http.get('/reports/' + report.id + '/stats')
            .then((response) => {
              this.loading = false
              this.stats = response.data
              this.showChart()
            })
      },

      fetchCharts(report) {
        http.get('/charts/all?report=' + report.id + '')
          .then((response) => {
            this.chartData = response.data
          })
          .catch((error) => {
            console.error('Error fetching the charts.')
          })
      },

      edit() {
        this.chart.destroy()
        this.$router.push({ name: 'reports.edit', params: { reportId: this.report.id }})
      },

      downloadXls() {
        http.get('/user/token').then((response) => {
          let token = response.data
          window.open('/api/reports/' + this.report.id + '/xls?jwt=' + token, '_self')
        })
      },

      showChart() {
        let categories = []
        let chartStats = {
          impressions: [],
          fills: [],
          errors: []
        }

        for (let i = 0; i < this.stats.stats.length; i++) {
          if (i === this.stats.stats.length - 1) {
            continue
          }

          let stat = this.stats.stats[i]

          categories.push(stat[Object.keys(stat)[0]])
          chartStats.impressions.push(stat.impressions)
          chartStats.fills.push(stat.fills)
          chartStats.errors.push(stat.errors)
        }

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
            }
          ]
        })
      },
      ...stats
    },

    computed: {
      report() {
        let report = _.find(this.$store.state.users.reports, { 'id': parseInt(this.$route.params.reportId) })

        if (report === undefined) {
          this.$store.dispatch('users/loadReports')
          return {}
        }

        this.fetchStats(report)
        this.fetchCharts(report)

        return report
      },

      paginatedStats() {
        this.pagination.data = this.stats.stats
        return this.pagination.getData()
      },

      backfillEcpm() {
        return this.calculateEcpm(this.stats.allStats.backfill, this.stats.allStats.desktopBackfillRevenue + this.stats.allStats.mobileBackfillRevenue)
      },

      backfillRevenue() {
        return this.stats.allStats.desktopBackfillRevenue + this.stats.allStats.mobileBackfillRevenue
      },

      totalRevenue() {
        return this.backfillRevenue + this.stats.allStats.revenue
      },

      desktopEcpm() {
        return this.calculateEcpm(this.stats.allStats.tags.desktop.impressions, this.stats.allStats.desktopRevenue, false)
      },

      mobileEcpm() {
        return this.calculateEcpm(this.stats.allStats.tags.mobile.impressions, this.stats.allStats.mobileRevenue, false)
      },

      ecpm() {
        return this.calculateEcpm(this.stats.allStats.impressions, this.stats.allStats.revenue)
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

      totalRevenueSpark() {
        if (!this.chartData.mobileBackfillRevenue) return []

        let totalRevenue = []

        for (let i = 0; i < this.chartData.mobileBackfillRevenue.length; i++) {
          let revenue = this.chartData.mobileBackfillRevenue[i][1] + this.chartData.desktopBackfillRevenue[i][1] + this.chartData.revenue[i][1]
          totalRevenue.push([this.chartData.mobileBackfillRevenue[i][0], revenue])
        }

        return totalRevenue
      },

      fillRate() {
        return this.calculateFillRate(this.stats.allStats.impressions, this.stats.allStats.tagRequests)
      },

      errorRate() {
        return this.calculateErrorRate(this.stats.allStats.tagRequests, this.stats.allStats.errors)
      },

      useRate() {
        return this.calculateUseRate(this.stats.allStats.impressions, this.stats.allStats.fills)
      }
    },

    mounted() {
      this.$nextTick(function() {

      })
    },

    components: {
      BounceLoader,
      Stats,
      SparkChart
    }
  }
</script>

<style lang="scss">
  .dashstats-graphwrapper {
    height: auto;
  }
char
  ul.dashreports-titlewidth li {
    text-transform: uppercase;
  }

  ul.dashreports-titlewidth, ul.dashreports-width {
    width: auto;
    clear: left;
  }

  .chart {
    width: 100%;
  }

  .report-loading {
    padding-top: 160px;
    margin-top: 110px;
    padding-bottom: 40px;
    width: 100%;
    height: 100%;
    z-index: 1000;
    position: absolute;
    background-color: white;
  }

  .v-bounce {
    margin: 0 auto;
  }
</style>
