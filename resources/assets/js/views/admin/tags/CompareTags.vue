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
              <ul class="taggraph-selection">
                <li class="selected">Requests</li>
                <li class="selected">Fill</li>
                <li>Impressions</li>
                <li>Errors</li>
              </ul>
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
                  <div class="taggraph-listnumber">1.35%</div>
                </li>
                <li>
                  <div class="taggraph-listtitle">USE-RATE:</div>
                  <div class="taggraph-listnumber">57.31%</div>
                </li>
                <li>
                  <div class="taggraph-listtitle">ERRORS:</div>
                  <div class="taggraph-listnumber">1,507,579</div>
                </li>
                <li>
                  <div class="taggraph-listtitle">REVENUE:</div>
                  <div class="taggraph-listnumber">$2,309</div>
                </li>
              </ul>
            </div>
          </div>
          <!-- END GRAPH AREA -->
          <!-- START TAGS AREA -->

        <!-- EDIT TAGS PLATFORM SELECT -->
        <div class="edittagsselect-wrapper comparetagsselect-wrapper">
            <div class="edittagsselect-title">Type:</div>
            <select>
              <option value="">All</option>
              <option value="">Instream</option>
              <option value="">Outstream</option>
            </select>
            <div class="edittagsselect-selectarrow" style="margin-left:118px;"></div>
          </div>

          <!-- EDIT TAGS TYPE SELECT -->
          <div class="edittagsselect-wrapper comparetagsselect-wrapper" style="margin-right:20px;">
            <div class="edittagsselect-title">Platform:</div>
            <select>
              <option value="">All</option>
              <option value="">Desktop</option>
              <option value="">Mobile</option>
            </select>
            <div class="edittagsselect-selectarrow"></div>
          </div>

          <!-- EDIT TAGS COMPARE TIME -->
          <div class="edittagsselect-wrapper comparetagsselect-wrapper" style="margin-right:20px;">
            <div class="edittagsselect-title">Compare Time to: </div>
            <select>
              <option value="">Yesterday</option>
              <option value="">2 Days Ago</option>
              <option value="">3 Days Ago</option>
              <option value="">1 Week Ago</option>
              <option value="">Last 2 Days</option>
              <option value="">Last 3 Days</option>
              <option value="">Last 7 Days</option>
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
              <li>
                  <div class="dashboard-statslist1">
                    <div class="tagcreate-checkwrap">
                <input type="checkbox" id="check-onscroll">
                <label for="check-onscroll"></label>
              </div>
                    AOL
                </div>
                  <div class="dashboard-statslist2">DESKTOP</div>
                  <div class="dashboard-statslist2">INSTREAM</div>
                  <div class="dashboard-statslist2">1,057 <span class="up">(+ 34%)</span></div>
                  <div class="dashboard-statslist2">1,501 <span class="down">(- 34%)</span></div>
                  <div class="dashboard-statslist2">17% <span class="down">(- 34%)</span></div>
                  <div class="dashboard-statslist2">4% <span class="down">(- 34%)</span></div>
                  <div class="dashboard-statslist2">17% <span class="down">(- 34%)</span></div>
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
          <!-- END TAGS AREA -->
      </div>
    </section><!-- END COMPARE TAGS -->
  </div>
</template>

<script>
  import stats from '../../../services/stats'
  import http from '../../../services/http'
  import moment from 'moment'

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

        requests: 0,
        impressions: 0,

        fills: 0,
        fillErrors: 0,

        adErrors: 0,

        useRate: 0,

        dailyStats: []
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
            })
            .catch((error) => {
              console.error('Error fetching the stats count.')
            })
      }
    },

    mounted() {
      this.$nextTick(function() {
        this.fetchStats()
      })
    },

    watch: {
      timeRange(newTimeRange) {
        this.fetchStats()
      },
    }

  }
</script>
