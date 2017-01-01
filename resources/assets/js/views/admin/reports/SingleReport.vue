<template>
  <div>
    <div class="display-dashboardtoparea">
      <div class="dashboard-livedatestamp">REPORT QUERY: <span>YESTERDAY</span></div>
      <div class="currentcamp-createbutton">EXPORT TO XLS</div>
    </div>

    <!-- ANALYTICS STATS -->
    <!-- TOP ANALYTICS -->
    <ul class="campaignstats-row singlereportspurple" :graphStats='graphStats'>
      <li>
        <div class="campaignstats-title">REQUESTS</div>
        <div class="campaignstats-digit">60,551</div>
        <div class="campaignstats-digit"><span id="graph_month"></span></div>
      </li>
      <li>
        <div class="campaignstats-title">IMPRESSIONS</div>
        <div class="campaignstats-digit">3,762</div>
        <div class="campaignstats-digit"><span id="graph_day"></span></div>
      </li>
      <li>
        <div class="campaignstats-title">FILLS</div>
        <div class="campaignstats-digit">60,551</div>
        <div class="campaignstats-digit"><span id="graph_month_r"></span></div>
      </li>
    </ul>
    <!-- BOTTOM ANALYTICS -->
    <ul class="campaignstats-row singlereportsblue" :graphStats='graphStats'>
      <li>
        <div class="campaignstats-title">FILL-RATE</div>
        <div class="campaignstats-digit">37%</div>
        <div class="campaignstats-digit"><span id="graph_month"></span></div>
      </li>
      <li>
        <div class="campaignstats-title">ERROR-RATE</div>
        <div class="campaignstats-digit">1%</div>
        <div class="campaignstats-digit"><span id="graph_day"></span></div>
      </li>
      <li>
        <div class="campaignstats-title">USE-RATE</div>
        <div class="campaignstats-digit">90%</div>
        <div class="campaignstats-digit"><span id="graph_month_r"></span></div>
      </li>
    </ul>

    <div class="dashstats-graphwrapper">
      <div class="dashstats-graph"></div>
    </div>

    <!-- CAMPAIGN SELECTION AREA -->

    <div class="dashboard-dailystatstitle">DAILY STATS</div>
    <div class="dashreports-scrollwrapper">
      <div class="dashreports-scrollarea">
        <ul class="dashboard-dailystatstitles dashreports-titlewidth">
          <li>ADVERTISER</li>
          <li>TAG NAME</li>
          <li>PLATFORM</li>
          <li>TYPE</li>
          <li>IMPRESSIONS</li>
          <li>AD REQUESTS</li>
          <li>FILLS</li>
          <li>FILL %</li>
          <li>CLICKS</li>
          <li>CTR</li>
          <li>eCPM</li>
          <li>REVENUE</li>
          <li>ONSCROLL</li>
          <li>ONSCROLL %</li>
          <li>ONSCROLL ERRORS</li>
          <li>INFINITY</li>
          <li>INFINITY %</li>
          <li>INFINITY ERRORS</li>
          <li>START</li>
          <li>FIRST QUARTILE</li>
          <li>MIDPOINT</li>
          <li>THIRD QUARTILE</li>
          <li>COMPLETE</li>
          <li>COMPLETION RATE</li>
          <li>AVERAGE VIEW RATE</li>
          <li>AVERAGE VIEW TIME</li>
          <li>AVG VIDEO LENGTH</li>
          <li>TOTAL ERRORS</li>
          <li>ERROR RATE</li>
          <li>ERROR 101</li>
          <li>ERROR 102</li>
          <li>ERROR 200</li>
          <li>ERROR 201</li>
          <li>ERROR 202</li>
          <li>ERROR 203</li>
          <li>ERROR 300</li>
          <li>ERROR 301</li>
          <li>ERROR 302</li>
          <li>ERROR 303</li>
          <li>ERROR 400</li>
          <li>ERROR 401</li>
          <li>ERROR 402</li>
          <li>ERROR 403</li>
          <li>ERROR 405</li>
          <li>ERROR 500</li>
          <li>ERROR 501</li>
          <li>ERROR 502</li>
          <li>ERROR 503</li>
          <li>ERROR 600</li>
          <li>ERROR 601</li>
          <li>ERROR 602</li>
          <li>ERROR 603</li>
          <li>ERROR 604</li>
          <li>ERROR 900</li>
          <li>ERROR 901</li>
        </ul>
        <ul class="dashboard-dailystatslist dashreports-width">
          <li>
            <div class="dashboard-statslist1">AOL</div>
            <div class="dashboard-statslist2">AOL_OUTSTREAM_DESKTOP</div>
            <div class="dashboard-statslist2">DESKTOP</div>
            <div class="dashboard-statslist2">OUTSTREAM</div>
            <div class="dashboard-statslist2">3,792</div>
            <div class="dashboard-statslist2">102,472</div>
            <div class="dashboard-statslist2">5,224</div>
            <div class="dashboard-statslist2">27%</div>
            <div class="dashboard-statslist2">151</div>
            <div class="dashboard-statslist2">4%</div>
            <div class="dashboard-statslist2">$5.50</div>
            <div class="dashboard-statslist2">$920.50</div>
            <div class="dashboard-statslist2">1,792</div>
            <div class="dashboard-statslist2">65%</div>
            <div class="dashboard-statslist2">92</div>
            <div class="dashboard-statslist2">744</div>
            <div class="dashboard-statslist2">45%</div>
            <div class="dashboard-statslist2">37</div>
            <div class="dashboard-statslist2">1,126</div>
            <div class="dashboard-statslist2">944</div>
            <div class="dashboard-statslist2">901</div>
            <div class="dashboard-statslist2">731</div>
            <div class="dashboard-statslist2">390</div>
            <div class="dashboard-statslist2">14%</div>
            <div class="dashboard-statslist2">72.05%</div>
            <div class="dashboard-statslist2">21.62</div>
            <div class="dashboard-statslist2">0:30</div>
            <div class="dashboard-statslist2">20,746</div>
            <div class="dashboard-statslist2">21</div>
            <div class="dashboard-statslist2">54</div>
            <div class="dashboard-statslist2">87</div>
            <div class="dashboard-statslist2">45</div>
            <div class="dashboard-statslist2">78</div>
            <div class="dashboard-statslist2">3</div>
            <div class="dashboard-statslist2">35</div>
            <div class="dashboard-statslist2">90</div>
            <div class="dashboard-statslist2">34</div>
            <div class="dashboard-statslist2">576</div>
            <div class="dashboard-statslist2">34</div>
            <div class="dashboard-statslist2">21</div>
            <div class="dashboard-statslist2">54</div>
            <div class="dashboard-statslist2">87</div>
            <div class="dashboard-statslist2">45</div>
            <div class="dashboard-statslist2">78</div>
            <div class="dashboard-statslist2">3</div>
            <div class="dashboard-statslist2">35</div>
            <div class="dashboard-statslist2">90</div>
            <div class="dashboard-statslist2">34</div>
            <div class="dashboard-statslist2">576</div>
            <div class="dashboard-statslist2">34</div>
            <div class="dashboard-statslist2">90</div>
            <div class="dashboard-statslist2">34</div>
            <div class="dashboard-statslist2">576</div>
            <div class="dashboard-statslist2">34</div>
            <div class="dashboard-statslist2">176</div>
          </li>
        </ul>
      </div><!-- END .dashreports-scrollarea -->
    </div><!-- END .dashreports-scrollwrapper -->
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
    name: 'SingleReport',
  }
</script>
