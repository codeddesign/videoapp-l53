<template>
  <div>
    <div id="chart" class="chart">

    </div>
  </div>
</template>

<script>
  import Highcharts from 'highcharts'
  import _ from 'lodash'

  let now = (Math.ceil(Date.now() / 1000)) * 1000

  export default {
    data() {
      return {
        options: {
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
              }
            }
          ],
          yAxis: [
            { // Primary yAxis
              labels: {
                format: '${value:.2f}',
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
            // headerFormat: '',
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
              data: [[now, 0]],
              tooltip: {
                valuePrefix: '$'
              }
            },
            {
              name: 'Impressions',
              type: 'spline',
              color: '#01a3de',
              yAxis: 1,
              data: [[now, 0]]
            }
          ]
        }
      }
    },

    computed: {
      currentUser() {
        return this.$store.state.users.currentUser
      }
    },

    props: {
      timeRange: {
        default: ''
      },
      revenue: {
        default: () => []
      },
      impressions: {
        default: () => []
      }
    },

    methods: {
      roundToNearestSecond(timestamp) {
        return (Math.ceil(timestamp / 1000)) * 1000
      },

      updateChart() {
        if (this.timeRange !== 'realtime') {
          return
        }

        let revenue = this.chart.series[0]
        let impressions = this.chart.series[1]

        let seriesLength = impressions.data.length
        let shift = seriesLength > 30

        _.mapKeys(this.chartData, (value, time) => {
          let timestamp = time
          time = this.roundToNearestSecond(time)

          if (_.has(this.seriesMap, time)) {
            impressions.data[this.seriesMap[time]].update({ y: value }, false)
            revenue.data[this.seriesMap[time]].update({ y: parseFloat(this.revenueData[timestamp].toFixed(2)) }, false)
          } else {
            this.seriesMap[time] = seriesLength
            impressions.addPoint([time, value], false, shift)
            revenue.addPoint([time, parseFloat(this.revenueData[timestamp].toFixed(2))], false, shift)

            if (shift) {
              let newSeriesMap = {}

              _.forIn(this.seriesMap, (value, key) => {
                if (value > 0) {
                  newSeriesMap[key] = value - 1
                } else {
                  delete this.chartData[key]
                  delete this.revenueData[key]
                }
              })

              this.seriesMap = newSeriesMap
            } else {
              seriesLength++
            }
          }
        })

        this.chart.redraw()
      }
    },

    mounted() {
      this.$nextTick(function() {
        this.chart = Highcharts.chart('chart', this.options)
        this.chartData = {}
        this.revenueData = {}
        this.seriesMap = {}

        let that = this
        setInterval(function() {
          that.updateChart()
        }, 1000)
      })
    },

    watch: {
      revenue(newRevenue) {
        this.chart.series[0].setData(this.revenue)
      },
      impressions(newImpressions) {
        this.chart.series[1].setData(this.impressions)
      },
      currentUser() {
        if (this.currentUser) {
          Highcharts.setOptions({
            global: {
              timezone: this.currentUser.timezone
            }
          })
        }
      }
    }
  }
</script>

<style lang="scss">
  .chart {
    padding: 12px 8px 0 0;
    height: 300px;
  }

  .highcharts-plot-background {
    fill: #fff;
  }
</style>
