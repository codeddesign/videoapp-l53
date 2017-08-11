<template>
  <div>
    <div class="page-index">
      <div class="adminreports-deletebutton" @click="deleteReports()">DELETE</div>
      <router-link :to="{ name: 'reports.create'}">
        <div class="tagcreate-topcancel tagcreate-editpagetopcancel">CREATE NEW QUERY</div>
      </router-link>
    </div>
    <!-- CAMPAIGN SELECTION AREA -->
    <ul class="admindashboard-dailystatstitles">
        <li class="adminreports-statslist1">QUERY NAME</li>
        <li>SCHEDULE</li>
    </ul>
    <ul class="admindashboard-dailystatslist">
        <li v-for="report in reports">
          <div class="dashboard-statslist1 adminreports-statslist1">
            <div class="tagcreate-checkwrap" v-show="report.deletable">
              <input type="checkbox" v-bind:id="report.id" v-bind:value="report.id" v-model="selectedReports">
              <label v-bind:for="report.id"></label>
            </div>
            <div @click="showReport(report)">
              {{ report.title }}
            </div>
          </div>
          <div class="dashboard-statslist2 adminreports-statslist2">{{ report.schedule }}</div>
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
  </div>
</template>

<script>
  import Pagination from '../../services/pagination'

  export default {
    name: 'Reports',
    data() {
      return {
        selectedReports: [],
        pagination: new Pagination()
      }
    },

    methods: {
      showReport(report) {
        this.$router.push({ name: 'reports.show', params: { reportId: report.id }})
      },

      deleteReports() {
        this.$store.dispatch('users/deleteReports', this.selectedReports)
      }
    },

    computed: {
      reports() {
        this.pagination.data = this.$store.state.users.reports

        return this.pagination.getData()
      }
    },

    mounted() {
      this.$store.dispatch('users/loadReports')
    }
  }
</script>
