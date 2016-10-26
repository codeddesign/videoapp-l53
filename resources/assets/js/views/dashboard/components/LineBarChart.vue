<template>
  <div>
    <div id="chart" class="chart">
      
    </div>
  </div>
</template>

<script>
  import Highcharts from 'highcharts'
  import _ from 'lodash'
  import { mapGetters } from 'vuex'
  import socket from '../../../services/socket'

  let now = (Math.ceil(Date.now()/1000))*1000

  let options = {
    credits: {
      enabled: false
    },
    chart: {
      backgroundColor: 'transparent'
    },
    shadow: false,
    title: false,
    xAxis: [
      {
        type: 'datetime',
        dateTimeLabelFormats: { // don't display the dummy year
          second: '%l:%M:%S %P',
          minute: '%l:%M %P',
          hour: '%l:%M %P'
        },
      }
    ],
    yAxis: [
      { // Primary yAxis
        labels: {
          format: '${value}',
          style: {
            color: '#989898',
            fontWeight: 'bold',
            backgroundColor: 'red'
          }
        },
        title: false
      },
      { // Secondary yAxis
        labels: {
          format: '{value}',
          style: {
            color: Highcharts.getOptions().colors[0]
          }
        },
        title: false,
        opposite: true,
        visible: false
      }
    ],
    tooltip: {
      shared: true,
      backgroundColor: '#373f52',
      color: '#ffffff',
      borderWidth: 0,
      shadow: false,
      useHTML: true,
      //headerFormat: '',
      xDateFormat: '%l:%M:%S %P',
      style: {
        color: '#fff',
        fontSize: '9pt'
      }
    },
    legend: {
      layout: 'vertical',
      align: 'right',
      verticalAlign: 'top',
      floating: true
    },
    series: [
      {
        name: 'Revenue',
        type: 'column',
        yAxis: 0,
        color: '#cce0a3',
        data: [[now, 0]], //this.$store.state.charts.revenue.slice(),
        tooltip: {
          valuePrefix: '$'
        }
      },
      {
        name: 'Impressions',
        type: 'spline',
        color: '#01a3de',
        yAxis: 1,
        data: [[now, 0]], //this.$store.state.charts.impressions.slice()
      }
    ]
  }

  export default {
    data() {
      return {
      }
    },

    methods: {
      roundToNearestSecond(timestamp) {
        return (Math.ceil(timestamp/1000))*1000
      },

      updateChart() {
        let revenue = this.chart.series[0]
        let impressions = this.chart.series[1]
        let seriesLength = impressions.data.length
        let shift = seriesLength > 30

        let lastImpressionsPoint = impressions.data[seriesLength - 1]

        let timestampsToUpdate = _.keys(this.chartData)

        if(timestampsToUpdate.includes(lastImpressionsPoint.x.toString())) {
          // there's new information for the last 
          // point so it needs to be updated
          console.log('Before update: ' + lastImpressionsPoint.y);

          lastImpressionsPoint.update({ y: this.chartData[lastImpressionsPoint.x] }, false)
          
          revenue.data[seriesLength - 1].update({ y: (this.chartData[lastImpressionsPoint.x]/1000)*4 }, false)
          

          _.unset(this.chartData, lastImpressionsPoint.x)
          console.log('After update: ' + lastImpressionsPoint.y);
        }

        _.mapKeys(this.chartData, (value, key) => {
          this.chart.series[0].addPoint([
            this.roundToNearestSecond(key),
            (value/1000)*4
          ], false, shift)
          this.chart.series[1].addPoint([
            this.roundToNearestSecond(key),
            value
          ], false, shift)
        })

        this.chartData = {}

        this.chart.redraw()
      }
    },

    computed: {
    },

    mounted() {
      this.$store.subscribe((mutation, state) => {
        if (mutation.type === 'LOAD_DATA') {
          this.$nextTick(function() {
            this.chart = Highcharts.chart('chart', options)
            this.chartData = {}
            this.mapSeriesToTimestamps = {}

            let echo = socket.connection()
            if(echo) {
              echo.private('user.1')
                  .listen('CampaignEventReceived', (e) => {
                    let eventTimestamp = this.roundToNearestSecond(e.timestamp)

                    if(_.has(this.chartData, eventTimestamp)) {
                      this.chartData[eventTimestamp] += 1
                    } else {
                      this.chartData[eventTimestamp] = 1
                    }
                  });
            } else {
              console.error('Couldn\'t connect to web socket');
            }

            let that = this
            setInterval(function() {
              that.updateChart()
            }, 1000);
          })
        }
      })
    }
  }
</script>

<style lang="scss">
  .chart {
    padding: 12px 8px 0 0;
  }

  .highcharts-plot-background {
    fill: #fff;
  }
</style>
