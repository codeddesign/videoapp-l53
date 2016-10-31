<template>
  <div>
    <div class="campaignstats-title">{{ title | uppercase }}</div>
    <div class="campaignstats-digit" id="currentMonthViews" v-bind:style="{'color': color}">
      {{ value }}
    </div>
    <div class="campaignstats-digit"><span v-bind:id="title"></span></div>
  </div>
</template>

<script>
  import Sparkline from 'jquery-sparkline' // eslint-disable-line no-unused-vars

  export default {
    name: 'Stats',

    props: {
      title: {
        type: String,
        default: 'Title'
      },
      value: {
        default: ''
      },
      color: {
        default: ''
      },
      chartColor: {
        default: ''
      },
      chartData: {
        default: () => []
      }
    },

    mounted() {
      this.$nextTick(function() {
        this.fillGraph()
      })
    },

    computed: {
      waitChartData() {
        this.fillGraph()
      }
    },

    methods: {
      fillGraph() {
        if (this.chartData != null) {
          $('#' + this.title).sparkline(this.chartData, {
            type: 'bar',
            barWidth: 4,
            height: '50px',
            barColor: this.chartColor,
            negBarColor: '#c6c6c6',
            zeroColor: '#cacaca'
          })
        }
      }
    },

    filters: {
      uppercase: v => v.toUpperCase()
    }
  }
</script>
