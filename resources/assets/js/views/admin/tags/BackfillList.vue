<template>
  <div>
    <!-- EDIT TAGS PLATFORM SELECT -->
    <div class="edittagsselect-wrapper comparetagsselect-wrapper">
      <div class="edittagsselect-title">Player Type:</div>
      <select v-model="filters['type']">
        <option value="all">All</option>
        <option value="1">On-scroll</option>
        <option value="2">Infinity</option>
        <option value="3">Pre-roll</option>
      </select>
      <div class="edittagsselect-selectarrow" style="margin-left:158px;"></div>
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

    <!-- CAMPAIGN SELECTION AREA -->
    <ul class="dashboard-dailystatstitles dashboard-tagsedit dashboard-tagsedittitle">
        <li style="width:16%;">BACKFILL</li>
        <li style="width:19.5%;">PLAYER</li>
        <li>PLATFORM</li>
        <li>WIDTH</li>
        <li>IMPRESSIONS</li>
        <li>ECPM</li>
        <li>REVENUE</li>
    </ul>
    <ul class="admindashboard-dailystatslist dashboard-tagsedit dashboard-tagscompare">
        <li v-for="backfill in showBackfill" v-bind:title="'ID: ' + backfill.id">
          <div class="dashboard-statslist1">
            <div class="tagcreate-checkwrap">
              <input type="checkbox" v-bind:id="'backfill' + backfill.id" v-bind:value="backfill.id" v-model="selectedBackfill">
              <label v-bind:for="'backfill' + backfill.id"></label>
            </div>
            {{ backfill.advertiser }}
          </div>
          <div class="dashboard-statslist2">{{ backfill.ad_type_name }}</div>
          <div class="dashboard-statslist2">{{ backfill.platform_type }}</div>
          <div class="dashboard-statslist2">
            {{ backfill.width }}
          </div>
          <div class="dashboard-statslist2">
            {{ presentNumber(backfill.stats.backfill) }}
          </div>
          <div class="dashboard-statslist2">
            {{ calculateEcpm(backfill.stats.backfill, (backfill.stats.desktopBackfillRevenue + backfill.stats.mobileBackfillRevenue))}}
          </div>
          <div class="dashboard-statslist2">
            {{ presentMoney(backfill.stats.desktopBackfillRevenue + backfill.stats.mobileBackfillRevenue) }}
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
          <option v-for="option in pagination['perPageOptions']" v-bind:value="option">{{ option }}</option>
        </select>
        <div class="dashpagerows-selectarrow"></div>
      </div>
    </div>
    <!-- END TAGS AREA -->
  </div>
</template>

<script>
  import Pagination from '../../../services/pagination'
  import stats from '../../../services/stats'
  import http from '../../../services/http'
  import numeral from 'numeral'
  import accounting from 'accounting'

  export default {
    name: 'BackfillList',

    data() {
      return {
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

        backfill: [],

        selectedBackfill: [],

        pagination: new Pagination()
      }
    },

    methods: {
      fetchCompareTags() {
        http.get('/admin/backfill?compareRange=today')
          .then((response) => {
            this.backfill = response.data.data
          })
          .catch((error) => {
            console.error('Error fetching the backfill stats.')
          })
      },

      presentNumber(number) {
        return numeral(number).format('0,0')
      },

      presentMoney(number) {
        return accounting.formatMoney(number)
      },

      ...stats
    },

    computed: {
      showBackfill() {
        let backfill = this.backfill

        if (this.filters.platform !== 'all') {
          backfill = backfill.filter((b) => {
            return b.platform_type === this.filters.platform
          })
        }

        if (this.filters.type !== 'all') {
          backfill = backfill.filter((b) => {
            return b.ad_type_id === parseInt(this.filters.type)
          })
        }

        this.pagination.data = backfill

        return this.pagination.getData()
      }
    },

    mounted() {
      this.$nextTick(function() {
        this.fetchCompareTags()
      })
    },

    watch: {
      compareTagsRange(newTimeRange) {
        this.fetchCompareTags()
      },
      selectedBackfill(newBackfill) {
        this.$emit('selectedBackfill', newBackfill)
      }
    }
  }
</script>
