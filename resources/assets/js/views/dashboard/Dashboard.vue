<template>
    <div>
        <div class="page-index">
            <div class="display-dashboardtoparea">
                <div class="display-dashboardtimewrap">
                    <span>Time Range</span>
                    <select v-model="timeRange">
                        <option v-for="timeRange in timeRangeOptions" v-bind:value="timeRange.value">
                            {{ timeRange.text }}
                        </option>
                    </select>
                </div>
                <div class="currentcamp-createbutton">
                    <a href="#">INDEPTH REPORTS</a>
                </div>
            </div>
            <!-- ANALYTICS STATS -->
            <!-- TOP ANALYTICS -->
            <ul class="campaignstats-row">
                <li>
                    <stats title="request" :value="requests"></stats>
                    <small-chart></small-chart>
                </li>
                <li>
                    <stats title="impressions" :value="impressions"></stats>
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
    import SmallChart from './components/SmallChart.vue';

    export default {
        data() {
            return {
                timeRange: 'today',
                timeRangeOptions: [
                    { text: 'Today', value: 'today' },
                    { text: 'Yesterday', value: 'yesterday' },
                    { text: 'Last 7 Days', value: '7-days' },
                    { text: 'Current Month', value: 'current-month' },
                    { text: 'Last Month', value: 'last-month' },
                ],
                requests: '',
                impressions: '',
                revenue: '',
                ecpm: '',
                fill: '',
                fillRate: '',
                errorRate: '',
                useRate: ''
            }
        },
        ready() {
            // upon loading the page for the first time,
            // fetch all the stats.
            this.stats();
        },
        watch: {
            // when changing the time range, this will
            // update the states value on the page.
            timeRange: function(newVal, oldVal) {
                this.stats();
            }
        },
        methods: {
            stats() {
                this.request();
                this.impression();
            },
            request() {
                this.$http.get('/api/stats/requests?time='+this.timeRange).then((response) => {
                    this.requests = parseInt(response.data);
                }, () => console.log('Error Fetching the requests count.'));
            },
            impression() {
                this.$http.get('/api/stats/impressions?time='+this.timeRange).then((response) => {
                    this.impressions = parseInt(response.data);
                }, () => console.log('Error Fetching the impressions count.'));
            }
        },
        components: {Stats, SmallChart}
    }
</script>
