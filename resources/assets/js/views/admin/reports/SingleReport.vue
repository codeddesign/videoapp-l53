<template>
  <div>
    <div class="display-dashboardtoparea">
      <div class="dashboard-livedatestamp">REPORT QUERY: <span>{{ report.title }}</span></div>
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
        <stats title="requests" :value="stats.allStats.tagRequests" :animated="false"></stats>
        <spark-chart id="requests-chart" :chartData="chartData.tagRequests" color="#7772a7"></spark-chart>
      </li>
      <li>
        <stats title="impressions" :value="stats.allStats.impressions" :animated="false"></stats>
        <spark-chart id="impressions-chart" :chartData="chartData.impressions" color="#7772a7"></spark-chart>
      </li>
      <li>
        <stats title="revenue" :value="stats.allStats.revenue" :animated="false" type="money" color="#1aa74f"></stats>
        <spark-chart id="revenue-chart" :chartData="chartData.revenue" color="#1aa74f"></spark-chart>
      </li>
      <li>
        <stats title="ecpm" :value="ecpm" :animated="false" type="money" color="#1aa74f"></stats>
        <spark-chart id="ecpm-chart" :chartData="ecpmSpark" color="#1aa74f"></spark-chart>
      </li>
    </ul>
    <ul class="campaignstats-row">
      <li>
        <stats title="desktop pre-roll fill" type="percentage"
        :value="calculateFillRate(stats.allStats.tags.desktop.preroll.fills, stats.allStats.tags.desktop.preroll.tagRequests)"></stats>
        <spark-chart id="desktop-preroll-fill-chart" :chartData="chartData.desktopPrerollFill" color="#7772a7"></spark-chart>
      </li>
      <li>
        <stats title="mobile pre-roll fill" type="percentage"
        :value="calculateFillRate(stats.allStats.tags.mobile.preroll.fills, stats.allStats.tags.mobile.preroll.tagRequests)"></stats>
        <spark-chart id="mobile-preroll-fill-chart" :chartData="chartData.mobilePrerollFill" color="#7772a7"></spark-chart>
      </li>
      <li>
        <stats title="desktop pre-roll errors" type="percentage"
        :value="calculateErrorRate(stats.allStats.tags.desktop.preroll.tagRequests, stats.allStats.tags.desktop.preroll.errors)"></stats>
        <spark-chart id="desktop-preroll-errors-chart" :chartData="chartData.desktopPrerollErrors" color="#7772a7"></spark-chart>
      </li>
      <li>
        <stats title="mobile pre-roll errors" type="percentage"
        :value="calculateErrorRate(stats.allStats.tags.mobile.preroll.tagRequests, stats.allStats.tags.mobile.preroll.errors)"></stats>
        <spark-chart id="mobile-preroll-errors-chart" :chartData="chartData.mobilePrerollErrors" color="#7772a7"></spark-chart>
      </li>
    </ul>
    <ul class="campaignstats-row">
      <li>
        <stats title="desktop outstream fill" type="percentage"
        :value="calculateFillRate(stats.allStats.tags.desktop.outstream.fills, stats.allStats.tags.desktop.outstream.tagRequests)"></stats>
        <spark-chart id="desktop-outstream-fill-chart" :chartData="chartData.desktopOutstreamFill" color="#7772a7"></spark-chart>
      </li>
      <li>
        <stats title="mobile outstream fill" type="percentage"
        :value="calculateFillRate(stats.allStats.tags.mobile.outstream.fills, stats.allStats.tags.mobile.outstream.tagRequests)"></stats>
        <spark-chart id="mobile-outstream-fill-chart" :chartData="chartData.mobileOutstreamFill" color="#7772a7"></spark-chart>
      </li>
      <li>
        <stats title="desktop outstream errors" type="percentage"
        :value="calculateErrorRate(stats.allStats.tags.desktop.outstream.tagRequests, stats.allStats.tags.desktop.outstream.errors)"></stats>
        <spark-chart id="desktop-outstream-errors-chart" :chartData="chartData.desktopOutstreamErrors" color="#7772a7"></spark-chart>
      </li>
      <li>
        <stats title="mobile outstream errors" type="percentage"
        :value="calculateErrorRate(stats.allStats.tags.mobile.outstream.tagRequests, stats.allStats.tags.mobile.outstream.errors)"></stats>
        <spark-chart id="mobile-outstream-errors-chart" :chartData="chartData.mobileOutstreamErrors" color="#7772a7"></spark-chart>
      </li>
    </ul>
    <ul class="campaignstats-row">
      <li>
        <stats title="desktop fill" :value="stats.allStats.tags.desktop.fills" :animated="false"></stats>
        <spark-chart id="desktop-fill-chart" :chartData="chartData.desktopFill" color="#7772a7"></spark-chart>
      </li>
      <li>
        <stats title="mobile fill" :value="stats.allStats.tags.mobile.fills" :animated="false"></stats>
        <spark-chart id="mobile-fill-chart" :chartData="chartData.mobileFill" color="#7772a7"></spark-chart>
      </li>
      <li>
        <stats title="desktop use-rate" type="percentage"
        :value="calculateUseRate(stats.allStats.tags.desktop.impressions, stats.allStats.tags.desktop.fills)"></stats>
        <spark-chart id="desktop-use-rate-chart" :chartData="chartData.desktopUserate" color="#7772a7"></spark-chart>
      </li>
      <li>
        <stats title="mobile use-rate" type="percentage"
        :value="calculateUseRate(stats.allStats.tags.mobile.impressions, stats.allStats.tags.mobile.fills)"></stats>
        <spark-chart id="mobile-use-rate-chart" :chartData="chartData.mobileUserate" color="#7772a7"></spark-chart>
      </li>
    </ul>
    <ul class="campaignstats-row">
      <li>
        <stats title="desktop pageviews" :value="stats.allStats.desktopPageviews" :animated="false"></stats>
          <spark-chart id="desktop-pageviews-chart" :chartData="chartData.desktopPageviews" color="#7772a7"></spark-chart>
      </li>
      <li>
        <stats title="mobile pageviews" :value="stats.allStats.mobilePageviews" :animated="false"></stats>
          <spark-chart id="mobile-pageviews-chart" :chartData="chartData.mobilePageviews" color="#7772a7"></spark-chart>
      </li>
      <li>
        <stats title="desktop pageviews fill" type="percentage"
        :value="calculateFillRate(stats.allStats.tags.desktop.fills, stats.allStats.desktopPageviews)"></stats>
        <spark-chart id="desktop-pageviews-fill-chart" :chartData="chartData.desktopPageviewsFill" color="#7772a7"></spark-chart>
      </li>
      <li>
        <stats title="mobile pageviews fill" type="percentage"
          :value="calculateFillRate(stats.allStats.tags.mobile.fills, stats.allStats.mobilePageviews)"></stats>
        <spark-chart id="mobile-pageviews-fill-chart" :chartData="chartData.mobilePageviewsFill" color="#7772a7"></spark-chart>
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
              <span v-if="['ecpm', 'revenue'].indexOf(key) !== -1">$</span>{{ stat }}
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
  import Stats from '../../dashboard/components/Stats.vue'

  import SparkChart from '../SparkChart.vue'
  import BounceLoader from 'vue-spinner/src/BounceLoader.vue'
  import Pagination from '../../../services/pagination'
  import _ from 'lodash'
  import http from '../../../services/http'
  import stats from '../../../services/stats'

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
      fetchStats(report) {
        http.get('/admin/reports/' + report.id + '/stats')
            .then((response) => {
              this.loading = false
              this.stats = response.data
              this.showChart()
            })
      },

      fetchCharts(report) {
        http.get('/admin/charts/all?report=' + report.id + '')
          .then((response) => {
            this.chartData = response.data
          })
          .catch((error) => {
            console.error('Error fetching the charts.')
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

        _.each(this.stats.stats, tag => {
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
        this.fetchCharts(report)

        return report
      },

      paginatedStats() {
        this.pagination.data = this.stats.stats
        return this.pagination.getData()
      },

      ecpm() {
        return this.calculateEcpm(this.stats.allStats.impressions, this.stats.allStats.revenue)
      },

      ecpmSpark() {
        if (!this.chartData.impressions) return []

        return this.chartData.impressions.map((item, index) => {
          let ecpm = this.calculateEcpm(item[1], this.chartData.revenue[index][1], false)
          return [item[0], +ecpm.toFixed(2)]
        })
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
  ul.dashreports-titlewidth li {
    text-transform: uppercase;
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
