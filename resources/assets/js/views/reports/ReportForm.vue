<template>
  <div class="tagmanage-tagcreationwrappers" style="margin-top:58px;float:left;width:100%;">
      <div class="tagcreate-formwrapper">
        <div class="tagcreate-fullheadertitle">REPORTS: QUERY CREATION</div>

        <div class="tagcreate-formbg">
          <div class="tagcreate-fullinnertitle">QUERY TITLE</div>
          <input class="tagcreate-longinput" name="title" v-model="report.title" v-validate data-vv-rules="required" placeholder="Give this query a query..">
        </div><!-- END .tagcreate-formbg -->

        <div class="tagcreate-formbg">
          <div class="tagcreate-quarterinnerwrap" style="margin-left:0;">
            <div class="tagcreate-fullinnertitle">DATE RANGE</div>
            <div class="tagcreate-selectwrap">
             <select v-model="report.date_range" class="tagcreate-dropdown">
                <option v-for="timeRange in timeRangeOptions" v-bind:value="timeRange.value">
                  {{ timeRange.text }}
                </option>
              </select>
              <div class="tagcreate-selectarrow"></div>
            </div>
          </div>

          <!-- CUSTOM DATE RANGE -->
          <div v-show="report.date_range === 'custom'" class="reportquery-customdaterange">
            <div class="tagcreate-quarterinnerwrap">
              <div class="tagcreate-fullinnertitle">CUSTOM RANGE</div>
              <div class="tagcreate-selectwrap reportsquery-datedrop">
                <input id="starttime-datepicker" v-datepicker="{ key: 'start_date' }" class="tagtime-datepicker" placeholder="select date..">
                <div class="tagcreate-selectarrow"></div>
              </div>
            </div>

            <div class="tagcreate-dateformto">TO</div>

            <div class="tagcreate-quarterinnerwrap">
              <div class="tagcreate-fullinnertitle"></div>
              <div class="tagcreate-selectwrap reportsquery-datedroptwo">
                <input id="endtime-datepicker" v-datepicker="{ key: 'end_date' }" class="tagtime-datepicker" placeholder="select date..">
                <div class="tagcreate-selectarrow"></div>
              </div>
            </div>
          </div><!-- END .reportquery-customdaterange -->
          <!-- END CUSTOM DATE RANGE -->

        </div><!-- END .tagcreate-formbg -->

        <div class="tagcreate-formbg">
          <div class="tagcreate-quarterinnerwrap" style="margin-left:0;">
            <div class="tagcreate-fullinnertitle">PLATFORM TYPE</div>
            <div class="tagcreate-selectwrap">
              <select class="tagcreate-dropdown" v-model="report.filter.type">
                <option value="website">Website</option>
                <option value="platform_type">Platform Type</option>
                <option value="ad_type">Ad Type</option>
              </select>
              <div class="tagcreate-selectarrow"></div>
            </div>
          </div>
          <div class="tagcreate-quarterinnerwrap" style="margin-left:6px;margin-right:-42px;">
            <div class="tagcreate-fullinnertitle"></div>
            <div class="tagcreate-selectwrap">
              <select class="tagcreate-dropdown" v-model="report.filter.filter">
                <option value="doesNotContain">Does not contain</option>
                <option value="contains">Contains</option>
                <option value="is">Is</option>
                <option value="isNot">Is not</option>
              </select>
              <div class="tagcreate-selectarrow"></div>
            </div>
          </div>
          <div class="tagcreate-quarterinnerwrap">
            <div class="tagcreate-fullinnertitle"></div>
            <input class="tagcreate-longinput" v-model="report.filter.value" placeholder="Type filter item..">
          </div>
        </div><!-- END .tagcreate-formbg -->

        <div class="tagcreate-formbg">
          <div class="tagcreate-quarterinnerwrap" style="margin-left:0px;">
            <div class="tagcreate-fullinnertitle">SORT DIMENSION</div>
            <div class="tagcreate-selectwrap">
              <select class="tagcreate-dropdown" v-model="report.sort_by">
                <option value=""></option>
                <option value="website">Website</option>
                <option value="platform_type">Platform Type</option>
                <option value="ad_type">Ad Type</option>
              </select>
              <div class="tagcreate-selectarrow"></div>
            </div>
          </div>
        </div><!-- END .tagcreate-formbg -->
        <div class="tagcreate-formbg">
          <div class="tagcreate-quarterinnerwrap" style="margin-left:0;">
            <div class="tagcreate-fullinnertitle">INCLUDE METRICS</div>
            <div class="reportquery-fullinnertitle">VIDEO</div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="all_video" @click="includeAll('video', $event)">
              <label for="all_video">All Video</label>
            </div>
            <div class="tagcreate-checkwrap" v-for="(key, metric) in metrics.video">
              <input type="checkbox" v-bind:id="key" v-bind:value="key" v-model="report.included_metrics">
              <label v-bind:for="key">{{ metric }}</label>
            </div>
          </div><!-- END VIDEO -->
          <div class="tagcreate-quarterinnerwrap" style="margin-left:0;">
            <div class="tagcreate-fullinnertitle"></div>
            <div class="reportquery-fullinnertitle">VIEWERSHIP</div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="all_viewership" @click="includeAll('viewership', $event)">
              <label for="all_viewership">All Viewership</label>
            </div>
            <div class="tagcreate-checkwrap" v-for="(key, metric) in metrics.viewership">
              <input type="checkbox" v-bind:id="key" v-bind:value="key" v-model="report.included_metrics">
              <label v-bind:for="key">{{ metric }}</label>
            </div>
          </div><!-- END VIEWERSHIP -->
        </div><!-- END .tagcreate-formbg -->
        <div class="tagcreate-formbg">
          <div class="tagcreate-quarterinnerwrap" style="margin-left:0;">
            <div class="tagcreate-fullinnertitle">GENERATE REPORT</div>
            <div class="tagcreate-selectwrap">
              <select class="tagcreate-dropdown" v-model="report.schedule">
                <option value="once">Once</option>
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
              </select>
              <div class="tagcreate-selectarrow"></div>
            </div>
          </div>
          <div v-show="report.schedule === 'weekly' "class="tagcreate-quarterinnerwrap">
            <div class="tagcreate-fullinnertitle">EVERY</div>
            <div class="tagcreate-selectwrap">
              <select class="tagcreate-dropdown" v-model="report.weekly_every">
                <option value="0">Sunday</option>
                <option value="1">Monday</option>
                <option value="2">Tuesday</option>
                <option value="3">Wednesday</option>
                <option value="4">Thurdsay</option>
                <option value="5">Friday</option>
                <option value="6">Saturday</option>
              </select>
              <div class="tagcreate-selectarrow"></div>
            </div>
          </div>
          <div v-show="report.schedule === 'monthly' "class="tagcreate-quarterinnerwrap">
            <div class="tagcreate-fullinnertitle">EVERY</div>
            <div class="tagcreate-selectwrap">
              <select class="tagcreate-dropdown" v-model="report.monthly_every">
                <option value="beginning">Beginning of Month</option>
                <option value="end">End of Month</option>
              </select>
              <div class="tagcreate-selectarrow"></div>
            </div>
          </div>
          <div class="tagcreate-quarterinnerwrap" style="clear:left;margin-left:0;margin-top:25px;width:50%;">
            <div class="tagcreate-fullinnertitle">EMAIL REPORT TO</div>
            <input class="tagcreate-longinput" name="recipient" v-model="report.recipient" v-validate data-vv-rules="required" placeholder="Enter an email address..">
          </div>
        </div><!-- END .tagcreate-formbg -->

      </div><!-- END .tagcreate-formwrapper -->

      <!-- SAVE GLOBAL PRESETS -->
      <div class="tagcreate-savetagwrap" style="margin-top:30px;">
        <div @click="save" class="tagcreate-savetagbutton" style="width:100%;">SAVE AND GENERATE REPORT</div>
      </div>
      <!-- END SAVE GLOBAL PRESETS -->
    </div>
</template>

<script>
  import $ from 'jquery'
  import _ from 'lodash'
  import moment from 'moment'
  import user from '../../models/user'

  export default {
    name: 'CreateReport',

    data() {
      return {
        reportData: {
          id: 0,
          title: '',
          date_range: 'today',
          filter: {
            type: 'website',
            filter: 'doesNotContain',
            value: ''
          },
          included_metrics: [],
          combine_by: 'website',
          sort_by: '',
          schedule: 'once',
          weekly_every: '0',
          monthly_every: 'beginning',
          recipient: ''
        },

        metrics: {
          video: {
            'Ad Type': 'ad_type',
            'Platform Type': 'platform_type',
            'Website': 'website',
            'Impressions': 'impressions',
            'Unfilled Impressions': 'unfilled_impressions',
            'Clicks': 'click',
            'CTR': 'ctr',
            'Revenue': 'revenue',
            'CPM': 'cpm',
            'RPM': 'rpm',
            'Fills': 'fills',
            'Fill %': 'fill_rate',
            'PV Fill %': 'pv_fill_rate'
          },
          viewership: {
            'Start': 'start',
            'First Quartile': 'firstquartile',
            'Midpoint': 'midpoint',
            'Third Quartile': 'thirdquartile',
            'Completed': 'complete',
            'Average View Rate': 'average_view_rate',
            'Average View Time': 'average_view_time',
            'Completion Rate': 'completion_rate',
            'Video Length': 'view_length'
          }
        },

        timeRangeOptions: [
          { text: 'Today', value: 'today' },
          { text: 'Yesterday', value: 'yesterday' },
          { text: 'Last 3 Days', value: 'threeDays' },
          { text: 'Last 7 Days', value: 'sevenDays' },
          { text: 'Last 30 Days', value: 'thirtyDays' },
          { text: 'This Month', value: 'thisMonth' },
          { text: 'Last Month', value: 'lastMonth' },
          { text: 'Custom', value: 'custom' }
        ]
      }
    },

    methods: {
      save(e) {
        this.$validator.validateAll()

        if (this.report.schedule === 'monthly') {
          this.report.schedule_every = this.report.monthly_every
        }

        if (this.report.schedule === 'weekly') {
          this.report.schedule_every = this.report.weekly_every
        }

        if (this.errors.any()) {
          e.preventDefault()
          var errorMsg = this.errors.errors.map((error) => {
            return error.msg + '\n'
          })

          window.alert(errorMsg)
        } else {
          user.saveReport(this.report)
              .then(newReport => {
                this.$store.dispatch('users/loadReports')
                this.$router.push({ name: 'reports.show', params: { reportId: newReport.id }})
              })
        }
      },

      includeAll(type, e) {
        var metrics = this.metrics[type]

        if (e.target.checked) {
          _.values(metrics).forEach(metric => {
            if (this.report.included_metrics.indexOf(metric) === -1) {
              this.report.included_metrics.push(metric)
            }
          })
        } else {
          this.report.included_metrics = _.filter(this.report.included_metrics, metric => {
            return _.values(metrics).indexOf(metric) === -1
          })
        }
      }
    },

    computed: {
      report() {
        if (!this.$route.params.reportId) {
          return this.reportData
        }

        console.log('custom')

        let report = _.find(this.$store.state.users.reports, { 'id': parseInt(this.$route.params.reportId) })

        if (report === undefined) {
          this.$store.dispatch('users/loadReports')
          return {
            filter: {}
          }
        }

        this.reportData = _.cloneDeep(report)

        return this.reportData
      }
    },

    directives: {
      datepicker: {
        bind: function (el, binding, vnode) {
          var vm = vnode.context
          $(el).datepicker({
            onClose: function (date) {
              vm.$set(vm.report, binding.value.key, date)
            }
          })
          if (vm.report[binding.value.key]) {
            let date = moment(vm.report[binding.value.key])
            $(el).datepicker('setDate', date.format('L'))
          }
        }
      }
    }
  }
</script>

<style lang="scss"></style>
