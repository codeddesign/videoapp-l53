<template>
  <div style="height: 60%;">
    <div class="campaignstats-title">{{ title | uppercase }}</div>
    <div class="campaignstats-digit" v-bind:style="{'color': color}">
      <div v-if="animated">
        <animated-number :type="type" :number="value"></animated-number>
      </div>
      <div v-else>
        {{ value }}
      </div>
    </div>
  </div>
</template>

<script>
  import Sparkline from 'jquery-sparkline' // eslint-disable-line no-unused-vars
  import AnimatedNumber from '../../../components/animated-number.js' // eslint-disable-line no-unused-vars

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
      },
      animated: {
        type: Boolean,
        default: false
      },
      type: {
        default: ''
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
