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
            <a href="/campaign">
              <div class="currentcamp-createbutton">GENERATE REPORT</div>
          </a>
          </div>
          <!-- END CHART TIME FRAME -->
        <!-- TAG GRAPH AREA -->
          <div class="taggraph-wrapper">
            <div class="taggraph-wrapleft">
              <!-- <ul class="taggraph-selection">
                <li class="selected">Requests</li>
                <li class="selected">Fill</li>
                <li>Impressions</li>
                <li>Errors</li>
              </ul>-->
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
                  <div class="taggraph-listnumber">{{ adErrors + fillErrors }}</div>
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

        <!-- EDIT TAGS PLATFORM SELECT -->
        <div class="edittagsselect-wrapper comparetagsselect-wrapper">
            <div class="edittagsselect-title">Type:</div>
            <select v-model="filters['type']">
              <option value="all">All</option>
              <option value="instream">Instream</option>
              <option value="outstream">Outstream</option>
            </select>
            <div class="edittagsselect-selectarrow" style="margin-left:118px;"></div>
          </div>

          <!-- EDIT TAGS TYPE SELECT -->
          <div class="edittagsselect-wrapper comparetagsselect-wrapper" style="margin-right:20px;">
            <div class="edittagsselect-title">Platform:</div>
            <select v-model="filters['platform']">
              <option value="all">All</option>
              <option value="desktop">Desktop</option>
              <option value="mobile">Mobile</option>
            </select>
            <div class="edittagsselect-selectarrow"></div>
          </div>

          <!-- EDIT TAGS COMPARE TIME -->
          <div class="edittagsselect-wrapper comparetagsselect-wrapper" style="margin-right:20px;">
            <div class="edittagsselect-title">Compare Time to: </div>
              <select v-model="compareTagsRange">
                <option v-for="compareRange in compareRangeOptions" v-bind:value="compareRange.value">
                  {{ compareRange.text }}
                </option>
              </select>
            <div class="edittagsselect-selectarrow" style="margin-left:185px;"></div>
          </div>

        <!-- CAMPAIGN SELECTION AREA -->
          <ul class="dashboard-dailystatstitles dashboard-tagsedit dashboard-tagsedittitle">
              <li style="width:16%;">SUPPLIER</li>
              <li style="width:19.5%;">PLATFORM</li>
              <li>TYPE</li>
              <li>REQUESTS</li>
              <li>IMPRESSIONS</li>
              <li>FILL-RATE</li>
              <li>ERROR-RATE</li>
              <li>TAG DISPLAY %</li>
          </ul>
          <ul class="admindashboard-dailystatslist dashboard-tagsedit dashboard-tagscompare">
              <li v-for="tag in showTags">
                <div class="dashboard-statslist1">
                  <div class="tagcreate-checkwrap">
                    <input type="checkbox" id="check-onscroll">
                    <label for="check-onscroll"></label>
                  </div>
                  {{ tag.advertiser }}
                </div>
                <div class="dashboard-statslist2">{{ tag.platform_type }}</div>
                <div class="dashboard-statslist2">{{ tag.ad_type }}</div>
                <div class="dashboard-statslist2">
                  {{ tag.stats.requests }}
                  <span v-html="showComparePercent(tag, 'requests')"></span>
                </div>
                <div class="dashboard-statslist2">
                  {{ tag.stats.impressions }}
                  <span v-html="showComparePercent(tag, 'impressions')"></span>
                </div>
                <div class="dashboard-statslist2">
                  {{ calculateFillRate(tag.stats.impressions, tag.stats.requests) }}
                  <span v-html="showComparePercent(tag, 'fillRate')"></span>
                </div>
                <div class="dashboard-statslist2">
                  {{ calculateErrorRate(tag.stats.impressions, tag.stats.adErrors) }}
                  <span v-html="showComparePercent(tag, 'errorRate')"></span>
                </div>
                <div class="dashboard-statslist2">
                  17%
                  <span class="down">(- 34%)</span>
                </div>
              </li>
          </ul>
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
          <!-- END TAGS AREA -->
      </div>
    </section><!-- END COMPARE TAGS -->
  </div>
</template>

<script>
  import _ from 'lodash'
  import stats from '../../../services/stats'
  import http from '../../../services/http'
  import Pagination from '../../../services/pagination'
  import TagChart from './TagChart.vue'
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
          { text: 'Last 7 Days', value: 'sevenDays' },
          { text: 'This Month', value: 'thisMonth' },
          { text: 'Last Month', value: 'lastMonth' }
        ],

        compareTagsRange: 'yesterday',
        compareRangeOptions: [
          { text: 'Yesterday', value: 'yesterday' },
          { text: '2 Days Ago', value: 'twoDaysAgo' },
          { text: '3 Days Ago', value: 'threeDaysAgo' },
          { text: '1 Week Ago', value: 'oneWeekAgo' },
          { text: 'Last 2 Days', value: 'lastTwoDays' },
          { text: 'Last 3 Days', value: 'lastThreeDays' },
          { text: 'Last 7 Days', value: 'lastSevenDays' }
        ],

        filters: {
          platform: 'all',
          type: 'all'
        },

        requests: 0,
        impressions: 0,
        revenue: 0,
        fills: 0,
        fillErrors: 0,
        adErrors: 0,

        tags: [],
        compareTags: [],

        chartData: [],

        pagination: new Pagination()
      }
    },

    methods: {
      fetchStats() {
        http.get('/admin/stats/all?time=' + this.timeRange)
            .then((response) => {
              this.requests = parseInt(response.data.requests)
              this.impressions = parseInt(response.data.impressions)
              this.fills = parseInt(response.data.fills)
              this.adErrors = parseInt(response.data.adErrors)
              this.fillErrors = parseInt(response.data.fillErrors)
              this.revenue = parseFloat(response.data.revenue)
            })
            .catch((error) => {
              console.error('Error fetching the stats count.')
            })

        http.get('/admin/charts/all?time=' + this.timeRange)
            .then((response) => {
              this.chartData = response.data
            })
            .catch((error) => {
              console.error('Error fetching the chart stats.')
            })

        this.fetchCompareTags()
      },

      fetchCompareTags() {
        http.get('/admin/tags/?compareRange=today')
          .then((response) => {
            this.tags = response.data.data
          })
          .catch((error) => {
            console.error('Error fetching the tags stats.')
          })

        http.get('/admin/tags/?compareRange=' + this.compareTagsRange)
          .then((response) => {
            this.compareTags = response.data.data
          })
          .catch((error) => {
            console.error('Error fetching the tags stats.')
          })
      },

      showComparePercent(tag, stat) {
        let tagValue = 0
        let compareValue = 0
        let compareTag = _.find(this.compareTags, value => { return value.id === tag.id })

        if (!compareTag) {
          return
        }

        switch (stat) {
          case 'requests':
            tagValue = tag.stats.requests
            compareValue = compareTag.stats.requests
            break
          case 'impressions':
            tagValue = tag.stats.impressions
            compareValue = compareTag.stats.impressions
            break
          case 'fillRate':
            tagValue = this.calculateFillRate(tag.stats.impressions, tag.stats.requests)
            compareValue = this.calculateFillRate(compareTag.stats.impressions, compareTag.stats.requests)
            break
          case 'errorRate':
            tagValue = this.calculateErrorRate(tag.stats.impressions, tag.stats.adErrors)
            compareValue = this.calculateErrorRate(compareTag.stats.impressions, compareTag.stats.adErrors)
            break
        }

        let percent = 0

        if (parseFloat(compareValue) !== 0) {
          // if the tag value isn't a percentage
          if (!isNaN(tagValue)) {
            percent = Math.round(((tagValue / compareValue) * 100) - 100)
          } else {
            percent = Math.round(((parseFloat(tagValue) - parseFloat(compareValue)) / parseFloat(compareValue)) * 100)
          }
        } else {
          percent = Number.POSITIVE_INFINITY
        }

        if (percent >= 0) {
          return '<span class="up">(+ ' + percent + ' %)</span>'
        } else {
          return '<span class="down">(- ' + Math.abs(percent) + ' %)</span>'
        }
      },

      ...stats
    },

    computed: {
      showTags() {
        let tags = this.tags

        if (this.filters.platform !== 'all') {
          tags = tags.filter((tag) => {
            return tag.platform_type === this.filters.platform
          })
        }

        if (this.filters.type !== 'all') {
          tags = tags.filter((tag) => {
            return tag.ad_type === this.filters.type
          })
        }

        this.pagination.data = tags

        return this.pagination.getData()
      },

      presentRevenue() {
        return accounting.formatMoney(this.revenue)
      },

      ecpm() {
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
      TagChart
    }

  }
</script>

<style lang="scss">

</style>
