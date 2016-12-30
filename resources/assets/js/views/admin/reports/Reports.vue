3<template>
  <div>
    <div class="page-index">
      <div class="adminreports-deletebutton">DELETE</div>
      <div class="adminreports-editbutton">EDIT</div>
      <router-link :to="{ name: 'admin.reports.create'}">
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
            <div class="tagcreate-checkwrap">
              <input type="checkbox" v-bind:id="report.id">
              <label v-bind:for="report.id"></label>
            </div>
            {{ report.title }}
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
  import http from '../../../services/http'
  import Pagination from '../../../services/pagination'

  export default {
    name: 'Reports',
    data() {
      return {
        reports: [],

        pagination: new Pagination()
      }
    },

    computed: {
      showReports() {
        this.pagination.data = this.reports

        return this.pagination.getData()
      }
    },

    mounted() {
      http.get('/admin/reports')
            .then((response) => {
              this.reports = response.data.data
            })
    }
  }
</script>
