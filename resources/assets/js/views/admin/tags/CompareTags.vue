<template>
  <div>
    <input name="tagmanage-tabbed" id="tagmanage-tabbed2" type="radio" checked>
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
                  <div class="taggraph-listnumber">{{ requests }}</div>
                </li>
                <li>
                  <div class="taggraph-listtitle">FILLS:</div>
                  <div class="taggraph-listnumber">{{ fills }}</div>
                </li>
                <li>
                  <div class="taggraph-listtitle">IMPRESSIONS:</div>
                  <div class="taggraph-listnumber">{{ impressions }}</div>
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
                  <div class="taggraph-listnumber">{{ errors }}</div>
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
  import stats from '../../../services/stats'
  import http from '../../../services/http'
  import TagChart from './TagChart.vue'
  import TagList from './TagList.vue'
  import moment from 'moment'
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

        requests: 0,
        impressions: 0,
        revenue: 0,
        fills: 0,
        errors: 0,

        selectedTags: [],

        chartData: []
      }
    },

    methods: {
      newSelectedTags(selectedTags) {
        this.selectedTags = selectedTags
        this.fetchStats()
      },

      fetchStats() {
        http.get('/admin/stats/all?time=' + this.timeRange + '&tags=' + this.selectedTags)
            .then((response) => {
              this.requests = parseInt(response.data.tagRequests)
              this.impressions = parseInt(response.data.impressions)
              this.fills = parseInt(response.data.fills)
              this.errors = parseInt(response.data.errors)
              this.revenue = parseFloat(response.data.revenue)
            })
            .catch((error) => {
              console.error('Error fetching the stats count.')
            })

        http.get('/admin/charts/all?time=' + this.timeRange + '&tags=' + this.selectedTags)
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
        return this.calculateErrorRate(this.requests, this.errors)
      },

      useRate() {
        return this.calculateUseRate(this.impressions, this.fills)
      }
    },

    mounted() {
      this.$nextTick(function() {
        this.fetchStats()
        this.$store.dispatch('loadTags')
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
      TagChart,
      TagList
    }

  }
</script>

<style lang="scss">

</style>
