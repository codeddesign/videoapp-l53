<template>
    <div>
        <div class="campaignstats-title">{{ title | uppercase }}</div>
        <div class="campaignstats-digit" id="currentMonthViews" v-bind:style="{'color': color}">{{ value }}</div>
        <div class="campaignstats-digit"><span id="{{title}}"></span></div>
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
            chartUrl: '',
            chartColor: ''
        },
        ready() {
            if (this.ispercentage == true) {
                this.percentageFilter();
            }
            if(this.chartUrl) {
                this.$http.get(this.chartUrl).then((response)=> {
                    $("#"+this.title).sparkline(response.data, {
                        type: 'bar',
                        barWidth: 4,
                        height: '50px',
                        barColor: this.chartColor,
                        negBarColor: '#c6c6c6',
                        zeroColor: '#cacaca'
                    });
                });
            }
        },
        methods: {
            currencyFilter() {
                this.value = this.$options.filters.currency(this.value);
            },
            percentageFilter() {
                this.value = '%' + this.value;
            }
        }
    }
</script>
