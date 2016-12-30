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
                <input id="starttime-datepicker" v-model="report.start_date" class="tagtime-datepicker hasDatepicker" placeholder="select start date..">
                <div class="tagcreate-selectarrow"></div>
              </div>
            </div>

            <div class="tagcreate-dateformto">TO</div>

            <div class="tagcreate-quarterinnerwrap">
              <div class="tagcreate-fullinnertitle"></div>
              <div class="tagcreate-selectwrap reportsquery-datedroptwo">
                <input id="endtime-datepicker" v-model="report.end_date" class="tagtime-datepicker hasDatepicker" placeholder="select end date..">
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
                <option value="advertiser">Advertiser</option>
                <option value="tagName">Tag Name</option>
                <option value="website">Website</option>
                <option value="platformType">Platform Type</option>
                <option value="adType">Ad Type</option>
                <option value="campaignType">Campaign Type</option>
                <option value="geography">Geography</option>
                <option value="userAccount">User Account</option>
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
          <div class="tagcreate-quarterinnerwrap" style="margin-left:0;">
            <div class="tagcreate-fullinnertitle">SORT BY DIMENSION</div>
            <div class="tagcreate-selectwrap">
              <select class="tagcreate-dropdown" v-model="report.sort_by">
                <option value="advertiser">Advertiser</option>
                <option value="tagName">Tag Name</option>
                <option value="platformType">Platform Type</option>
                <option value="adType">Ad Type</option>
                <option value="campaignType">Campaign Type</option>
                <option value="country">Counrty</option>
                <option value="state">State</option>
                <option value="city">City</option>
                <option value="postalCode">Postal Code</option>
                <option value="date">Date</option>
                <option value="dayOfWeek">Day of Week</option>
                <option value="hour">Hour</option>
                <option value="month">Month</option>
                <option value="week">Week</option>
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
              <input type="checkbox" id="check-preroll">
              <label for="check-preroll">All Video</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">Ad Type</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">Platform Type</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-onscroll">
              <label for="check-onscroll">Impressions</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">Unfilled Impressions</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">Ads Requests</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">Clicks</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">Revenue</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">CPM</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">Fills</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">Fill %</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">Errors</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">Error Rate %</label>
            </div>
          </div><!-- END VIDEO -->
          <div class="tagcreate-quarterinnerwrap" style="margin-left:0;">
            <div class="tagcreate-fullinnertitle"></div>
            <div class="reportquery-fullinnertitle">TOTALS</div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-preroll">
              <label for="check-preroll">All Totals</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">Total Ad Type %</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">Total Platform Type %</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">Total Campaign Type %</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">Total Platform Type Errors</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">Total Ad Type Errors</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">Total Campaign Type Errors</label>
            </div>
          </div><!-- END TOTALS -->
          <div class="tagcreate-quarterinnerwrap" style="margin-left:0;">
            <div class="tagcreate-fullinnertitle"></div>
            <div class="reportquery-fullinnertitle">VIEWERSHIP</div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-preroll">
              <label for="check-preroll">All Viewership</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">Start</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">First Quartile</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">Midpoint</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">Third Quartile</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">Completed</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">Average View Rate</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">Average View Time</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">Completion Rate</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">Video Length</label>
            </div>
          </div><!-- END VIEWERSHIP -->
          <div class="tagcreate-quarterinnerwrap" style="margin-left:0;">
            <div class="tagcreate-fullinnertitle"></div>
            <div class="reportquery-fullinnertitle">ERRORS</div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-preroll">
              <label for="check-preroll">All Errors</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">101</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">102</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">200</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">201</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">202</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">203</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">300</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">300</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">301</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">302</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">303</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">400</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">401</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">402</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">403</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">405</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">500</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">501</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">502</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">503</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">600</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">601</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">602</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">603</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">604</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">900</label>
            </div>
            <div class="tagcreate-checkwrap">
              <input type="checkbox" id="check-infinity">
              <label for="check-infinity">901</label>
            </div>
          </div><!-- END ERRORS -->
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
  import admin from '../../../models/admin'

  export default {
    name: 'CreateReport',

    data() {
      return {
        report: {
          title: '',
          date_range: 'today',
          filter: {
            type: 'advertiser',
            filter: 'doesNotContain',
            value: ''
          },
          sort_by: 'advertiser',
          schedule: 'once',
          recipient: ''
        },

        timeRangeOptions: [
          { text: 'Today', value: 'today' },
          { text: 'Yesterday', value: 'yesterday' },
          { text: 'Last 3 Days', value: 'lastThreeDays' },
          { text: 'Last 7 Days', value: 'lastSevenDays' },
          { text: 'This Month', value: 'thisMonth' },
          { text: 'Last Month', value: 'lastMonth' },
          { text: 'Custom', value: 'custom' }
        ]
      }
    },

    methods: {
      save(e) {
        this.$validator.validateAll()
        if (this.errors.any()) {
          e.preventDefault()
          var errorMsg = this.errors.errors.map((error) => {
            return error.msg + '\n'
          })

          window.alert(errorMsg)
        } else {
          admin.createReport(this.report)
              .then(newReport => {
                alert('Report created.')
              })
        }
      }
    }
  }
</script>

<style lang="scss"></style>
