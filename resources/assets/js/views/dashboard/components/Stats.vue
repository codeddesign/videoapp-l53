<template>
    <div>
        <div class="campaignstats-title">{{ title | uppercase }}</div>
        <div class="campaignstats-digit" id="currentMonthViews" v-bind:style="{'color': color}">{{ value }}</div>
        <div class="campaignstats-digit"><span id="graph_month"></span></div>
    </div>
</template>
<script>
    export default {
        props: {
            title: '',
            value: '',
            iscurrency: false,
            ispercentage: false,
            color: '',
        },
        ready() {
            if (this.iscurrency == true) {
                this.currencyFilter();
            }
            if (this.ispercentage == true) {
                this.percentageFilter();
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
