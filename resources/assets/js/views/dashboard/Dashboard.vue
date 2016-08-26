<template>
    <div>
        <div class="page-index">
            <div class="display-dashboardtoparea">
                <div class="display-dashboardtimewrap">
                    <span>Time Range</span>
                    <select>
                        <option value="">Today</option>
                        <option value="">Yesterday</option>
                        <option value="">Last 7 Days</option>
                        <option value="">This Month</option>
                        <option value="">Last Month</option>
                    </select>
                </div>
                <div class="currentcamp-createbutton">
                    <a href="#">INDEPTH REPORTS</a>
                </div>
            </div>
            <!-- ANALYTICS STATS -->
            <!-- TOP ANALYTICS -->
            <ul class="campaignstats-row" :graphStats='graphStats'>
                <li>
                    <stats title="request" :value="123123"></stats>
                </li>
                <li>
                    <stats title="impressions" :value="123123"></stats>
                </li>
                <li>
                    <stats title="revenue" :value="123123" :iscurrency="true" color="#1aa74f"></stats>
                </li>
                <li>
                    <stats title="eCPM" :value="123123" :iscurrency="true" color="#1aa74f"></stats>
                </li>
            </ul>
            <!-- BOTTOM ANALYTICS -->
            <ul class="campaignstats-row" :graphStats='graphStats'>
                <li>
                    <stats title="fill" :value="123123"></stats>
                </li>
                <li>
                    <stats title="fill-rate" :value="123123"></stats>
                </li>
                <li>
                    <stats title="error-rate" :value="4" :ispercentage="true" color="#009dd7"></stats>
                </li>
                <li>
                    <stats title="use-rate" :value="12" :ispercentage="true" color="#009dd7"></stats>
                </li>
            </ul>

            <ul class="totalstats-row">
                <li>
                    <div class="campaignstats-digit">
                        <canvas id="graph_total" width="1000" height="285"></canvas>
                    </div>
                </li>
            </ul>

            <!-- CAMPAIGN SELECTION AREA -->
            <div class="dashboard-dailystatstitle">LATEST CAMPAIGNS</div>
            <ul class="dashboard-dailystatstitles">
                <li>DATE</li>
                <li>REQUESTS</li>
                <li>FILL-RATE</li>
                <li>eCPM</li>
                <li>REVENUE</li>
            </ul>
            <ul class="dashboard-dailystatslist">
                <li>
                    <div class="dashboard-statslist1">July 10, 2016</div>
                    <div class="dashboard-statslist2">18,000</div>
                    <div class="dashboard-statslist2">18,000</div>
                    <div class="dashboard-statslist2">$479</div>
                    <div class="dashboard-statslist2">$479</div>
                </li>
                <li>
                    <div class="dashboard-statslist1">July 10, 2016</div>
                    <div class="dashboard-statslist2">18,000</div>
                    <div class="dashboard-statslist2">18,000</div>
                    <div class="dashboard-statslist2">$479</div>
                    <div class="dashboard-statslist2">$479</div>
                </li>
                <li>
                    <div class="dashboard-statslist1">July 10, 2016</div>
                    <div class="dashboard-statslist2">18,000</div>
                    <div class="dashboard-statslist2">18,000</div>
                    <div class="dashboard-statslist2">$479</div>
                    <div class="dashboard-statslist2">$479</div>
                </li>
                <li>
                    <div class="dashboard-statslist1">July 10, 2016</div>
                    <div class="dashboard-statslist2">18,000</div>
                    <div class="dashboard-statslist2">18,000</div>
                    <div class="dashboard-statslist2">$479</div>
                    <div class="dashboard-statslist2">$479</div>
                </li>
                <li>
                    <div class="dashboard-statslist1">July 10, 2016</div>
                    <div class="dashboard-statslist2">18,000</div>
                    <div class="dashboard-statslist2">18,000</div>
                    <div class="dashboard-statslist2">$479</div>
                    <div class="dashboard-statslist2">$479</div>
                </li>
                <li>
                    <div class="dashboard-statslist1">July 10, 2016</div>
                    <div class="dashboard-statslist2">18,000</div>
                    <div class="dashboard-statslist2">18,000</div>
                    <div class="dashboard-statslist2">$479</div>
                    <div class="dashboard-statslist2">$479</div>
                </li>
                <li>
                    <div class="dashboard-statslist1">July 10, 2016</div>
                    <div class="dashboard-statslist2">18,000</div>
                    <div class="dashboard-statslist2">18,000</div>
                    <div class="dashboard-statslist2">$479</div>
                    <div class="dashboard-statslist2">$479</div>
                </li>
                <li>
                    <div class="dashboard-statslist1">July 10, 2016</div>
                    <div class="dashboard-statslist2">18,000</div>
                    <div class="dashboard-statslist2">18,000</div>
                    <div class="dashboard-statslist2">$479</div>
                    <div class="dashboard-statslist2">$479</div>
                </li>
                <li>
                    <div class="dashboard-statslist1">July 10, 2016</div>
                    <div class="dashboard-statslist2">18,000</div>
                    <div class="dashboard-statslist2">18,000</div>
                    <div class="dashboard-statslist2">$479</div>
                    <div class="dashboard-statslist2">$479</div>
                </li>
            </ul>
        </div>
    </div>
</template>
<script>
    import Stats from './components/Stats.vue';

    export default {
        components: {Stats}
    }
</script>
