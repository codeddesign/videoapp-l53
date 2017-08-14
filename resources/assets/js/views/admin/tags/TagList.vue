<template>
  <div>
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

    <!-- EDIT TAGS ACTIVE SELECT -->
    <div class="edittagsselect-wrapper comparetagsselect-wrapper" style="margin-right:20px;">
      <div class="edittagsselect-title">Active:</div>
      <select v-model="filters['active']">
        <option value="all">All</option>
        <option value="true">Active</option>
        <option value="false">Inactive</option>
      </select>
      <div class="edittagsselect-selectarrow" style="margin-left: 128px;"></div>
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
        <li>COMPLETION-RATE</li>
        <li>TAG DISPLAY %</li>
    </ul>
    <ul class="admindashboard-dailystatslist dashboard-tagsedit dashboard-tagscompare">
        <li v-for="tag in showTags" v-bind:title="'ID: ' + tag.id">
          <div class="dashboard-statslist1">
            <div class="tagcreate-checkwrap">
              <input type="checkbox" v-bind:id="tag.id" v-bind:value="tag.id" v-model="selectedTags">
              <label v-bind:for="tag.id"></label>
            </div>
            {{ tag.advertiser }}
          </div>
          <div class="dashboard-statslist2">{{ tag.platform_type }}</div>
          <div class="dashboard-statslist2">{{ tag.type }}</div>
          <div class="dashboard-statslist2">
            {{ tag.stats.tagRequests }}
            <span v-html="showComparePercent(tag, 'requests')"></span>
          </div>
          <div class="dashboard-statslist2">
            {{ tag.stats.impressions }}
            <span v-html="showComparePercent(tag, 'impressions')"></span>
          </div>
          <div class="dashboard-statslist2">
            {{ calculateFillRate(tag.stats.fills, tag.stats.tagRequests) }}
            <span v-html="showComparePercent(tag, 'fillRate')"></span>
          </div>
          <div class="dashboard-statslist2">
            {{ calculateCompletionRate(tag.stats.impressions, tag.stats.viewership.complete) }}
            <span v-html="showComparePercent(tag, 'completionRate')"></span>
          </div>
          <div class="dashboard-statslist2">
            {{ calculateTagDisplayPercent(tag.stats.impressions, totalTagImpressions(tags)) }}
            <span v-html="showComparePercent(tag, 'tagDisplay')"></span>
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
  import _ from 'lodash'

  export default {
    name: 'TagList',

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
          type: 'all',
          active: 'all'
        },

        tags: [],
        compareTags: [],

        selectedTags: [],

        pagination: new Pagination()
      }
    },

    methods: {
      showComparePercent(tag, stat) {
        let tagValue = 0
        let compareValue = 0
        let compareTag = _.find(this.compareTags, value => { return value.id === tag.id })

        if (!compareTag) {
          return
        }

        switch (stat) {
          case 'requests':
            tagValue = tag.stats.tagRequests
            compareValue = compareTag.stats.tagRequests
            break
          case 'impressions':
            tagValue = tag.stats.impressions
            compareValue = compareTag.stats.impressions
            break
          case 'fillRate':
            tagValue = this.calculateFillRate(tag.stats.fills, tag.stats.tagRequests)
            compareValue = this.calculateFillRate(compareTag.stats.fills, compareTag.stats.tagRequests)
            break
          case 'completionRate':
            tagValue = this.calculateCompletionRate(tag.stats.impressions, tag.stats.viewership.complete)
            compareValue = this.calculateCompletionRate(compareTag.stats.impressions, compareTag.stats.viewership.complete)
            break
          case 'tagDisplay':
            tagValue = this.calculateTagDisplayPercent(tag.stats.impressions, this.totalTagImpressions(this.tags))
            compareValue = this.calculateTagDisplayPercent(compareTag.stats.impressions, this.totalTagImpressions(this.compareTags))
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

        if (percent === Number.POSITIVE_INFINITY) {
          return ''
        }

        if (percent >= 0) {
          return '<span class="up">(+ ' + percent + ' %)</span>'
        } else {
          return '<span class="down">(- ' + Math.abs(percent) + ' %)</span>'
        }
      },

      fetchCompareTags() {
        http.get('/admin/tags?compareRange=today')
          .then((response) => {
            this.tags = response.data.data
          })
          .catch((error) => {
            console.error('Error fetching the tags stats.')
          })

        http.get('/admin/tags?compareRange=' + this.compareTagsRange)
          .then((response) => {
            this.compareTags = response.data.data
          })
          .catch((error) => {
            console.error('Error fetching the tags stats.')
          })
      },

      totalTagImpressions(tags) {
        return _.sumBy(tags, (tag) => {
          return tag.stats.impressions
        })
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

        if (this.filters.active !== 'all') {
          let activeFilter = this.filters.active === 'true'
          tags = tags.filter((tag) => {
            return tag.active === activeFilter
          })
        }

        if (this.filters.type !== 'all') {
          tags = tags.filter((tag) => {
            return tag.type === this.filters.type
          })
        }

        this.pagination.data = tags

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
      selectedTags(newTags) {
        this.$emit('selectedTags', newTags)
      }
    }
  }
</script>
