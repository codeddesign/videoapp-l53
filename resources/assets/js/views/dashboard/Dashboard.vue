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
                    <stats title="request" :value="requests" :chart-data="requestsChartData" chart-color="#7772A7"></stats>
                </li>
                <li>
                    <stats title="impressions" :value="impressions" :chart-data="impressionsChartData" chart-color="#7772A7"></stats>
                </li>
                <li>
                    <stats title="revenue" :value="revenue" color="#1aa74f" :chart-data="revenueChartData" chart-color="#99c541"></stats>
                </li>
                <li>
                    <stats title="eCPM" :value="123123" color="#1aa74f" :chart-data="[1,2,3,4,5]" chart-color="#99c541"></stats>
                </li>
            </ul>
            <!-- BOTTOM ANALYTICS -->
            <ul class="campaignstats-row">
                <li>
                    <stats title="fill" :value="123123" :chart-data="[1,2,3,4,5]" chart-color="#7772A7"></stats>
                </li>
                <li>
                    <stats title="fill-rate" :value="123123" :chart-data="[1,2,3,4,5]" chart-color="#7772A7"></stats>
                </li>
                <li>
                    <stats title="error-rate" :value="4" :ispercentage="true" color="#009dd7" :chart-data="[1,2,3,4,5]" chart-color="rgb(0, 157, 215)"></stats>
                </li>
                <li>
                     <stats title="use-rate" :value="12" :ispercentage="true" color="#009dd7" :chart-data="[1,2,3,4,5]" chart-color="rgb(0, 157, 215)"></stats>
                </li>
            </ul>

            <ul class="totalstats-row">
                <li>
                    <line-bar-chart :revenue="revenueChartData" :impressions="impressionsChartData"></line-bar-chart>
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
            </ul>
        </div>
    </div>
</template>

<script>
    import Stats from './components/Stats.vue';
    import LineBarChart from './components/LineBarChart.vue';

    export default {
        data() {
            return {
                // used for the Time Range Select.
                timeRange: 'today',
                timeRangeOptions: [
                    {text: 'Today', value: 'today'},
                    {text: 'Yesterday', value: 'yesterday'},
                    {text: 'Last 7 Days', value: '7-days'},
                    {text: 'Current Month', value: 'current-month'},
                    {text: 'Last Month', value: 'last-month'},
                ],

                requests: '',
                impressions: '',

                ecpm: '', //@todo
                fill: '', //@todo
                fillRate: '', //@todo
                errorRate: '', //@todo
                useRate: '', //@todo

                requestsChartData: '',
                impressionsChartData: '',
                revenueChartData: '',
            }
        },
        computed: {
            revenue: function () {
                // apply 'currency' filter.
                return this.$options.filters.currency((4 * this.impressions) / 1000);
            },
        },
        ready() {
//            setInterval(()=> {
                this.stats();
//            }, 2000);
        },
        watch: {
            timeRange: function () {
                this.stats();
            }
        },
        methods: {
            stats() {
                this.$http.get('/api/charts/all').then((response) => {
                    this.$set('requestsChartData', response.data.requests);
                    this.$set('impressionsChartData', response.data.impressions);
                    this.$set('revenueChartData', response.data.revenue);
                }, () => console.log('Error fetching the stats.'));

                this.request();
                this.impression();
            },

            // fetch the requests count.
            request() {
                this.$http.get('/api/stats/requests?time=' + this.timeRange).then((response) => {
                    this.requests = parseInt(response.data);
                }, () => console.log('Error Fetching the requests count.'));
            },
            // fetch the impressions count.
            impression() {
                this.$http.get('/api/stats/impressions?time=' + this.timeRange).then((response) => {
                    this.impressions = parseInt(response.data);
                }, () => console.log('Error Fetching the impressions count.'));
            }
        },

        components: {Stats,LineBarChart}
    }
</script>
