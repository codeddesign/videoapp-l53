<template>
    <div :ignoreMe="waitChartData">
        <div class="campaignstats-title">{{ title | uppercase }}</div>
        <div class="campaignstats-digit" id="currentMonthViews" v-bind:style="{'color': color}">{{ value }}</div>
        <div class="campaignstats-digit"><span v-bind:id="title"></span></div>
    </div>
</template>

<script>
    import Sparkline from 'jquery-sparkline';

    export default {
        props: {
            title: '',
            value: '',
            ispercentage: false,
            color: '',
            chartColor: '',
            chartData: []
        },

        mounted() {
            this.$nextTick(function () {
                this.fillGraph();

                if(this.ispercentage == true) {
                    this.percentageFilter();
                }
            })
        },

        computed: {
            waitChartData() {
                this.fillGraph();
            }
        },

        methods: {
            fillGraph() {
                if(this.chartData != null) {
                    $("#" + this.title).sparkline(this.chartData, {
                        type: 'bar',
                        barWidth: 4,
                        height: '50px',
                        barColor: this.chartColor,
                        negBarColor: '#c6c6c6',
                        zeroColor: '#cacaca'
                    });
                }
            },
            percentageFilter() {
                this.value = '%' + this.value;
            }
        }
    }
</script>
