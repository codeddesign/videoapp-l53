<template>
  <div>
    <div class="page-index">
      <div class="adminreports-deletebutton">DELETE</div>
      <div class="adminreports-editbutton">EDIT</div>
      <div class="tagcreate-topcancel tagcreate-editpagetopcancel">CREATE NEW QUERY</div>
    </div>
    <!-- CAMPAIGN SELECTION AREA -->
    <ul class="admindashboard-dailystatstitles">
        <li class="adminreports-statslist1">QUERY NAME</li>
        <li>SCHEDULE</li>
    </ul>
    <ul class="admindashboard-dailystatslist">
        <li>
            <div class="dashboard-statslist1 adminreports-statslist1">
              <div class="tagcreate-checkwrap">
          <input type="checkbox" id="check-editreport">
          <label for="check-editreport"></label>
        </div>
              TODAY
          </div>
            <div class="dashboard-statslist2 adminreports-statslist2">DAILY</div>
        </li>
        <li>
            <div class="dashboard-statslist1 adminreports-statslist1">
              <div class="tagcreate-checkwrap">
          <input type="checkbox" id="check-editreport">
          <label for="check-editreport"></label>
        </div>
              YESTERDAY
          </div>
            <div class="dashboard-statslist2 adminreports-statslist2">NONE</div>
        </li>
        <li>
            <div class="dashboard-statslist1 adminreports-statslist1">
              <div class="tagcreate-checkwrap">
          <input type="checkbox" id="check-editreport">
          <label for="check-editreport"></label>
        </div>
              LAST 3 DAYS
          </div>
            <div class="dashboard-statslist2 adminreports-statslist2">NONE</div>
        </li>
        <li>
            <div class="dashboard-statslist1 adminreports-statslist1">
              <div class="tagcreate-checkwrap">
          <input type="checkbox" id="check-editreport">
          <label for="check-editreport"></label>
        </div>
              LAST 7 DAYS
          </div>
            <div class="dashboard-statslist2 adminreports-statslist2">WEEKLY</div>
        </li>
        <li>
            <div class="dashboard-statslist1 adminreports-statslist1">
              <div class="tagcreate-checkwrap">
          <input type="checkbox" id="check-editreport">
          <label for="check-editreport"></label>
        </div>
              THIS MONTH
          </div>
            <div class="dashboard-statslist2 adminreports-statslist2">NONE</div>
        </li>
        <li>
            <div class="dashboard-statslist1 adminreports-statslist1">
              <div class="tagcreate-checkwrap">
          <input type="checkbox" id="check-editreport">
          <label for="check-editreport"></label>
        </div>
              LAST MONTH
          </div>
            <div class="dashboard-statslist2 adminreports-statslist2">MONTHLY</div>
        </li>
        <li>
            <div class="dashboard-statslist1 adminreports-statslist1">
              <div class="tagcreate-checkwrap">
          <input type="checkbox" id="check-editreport">
          <label for="check-editreport"></label>
        </div>
              OUTSTREAM TODAY
          </div>
            <div class="dashboard-statslist2 adminreports-statslist2">NONE</div>
        </li>
        <li>
            <div class="dashboard-statslist1 adminreports-statslist1">
              <div class="tagcreate-checkwrap">
          <input type="checkbox" id="check-editreport">
          <label for="check-editreport"></label>
        </div>
              OUTSTREAM YESTERDAY
          </div>
            <div class="dashboard-statslist2 adminreports-statslist2">DAILY</div>
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
  </div>
</template>

<script>
  export default {
    name: 'Reports'
  }
</script>
