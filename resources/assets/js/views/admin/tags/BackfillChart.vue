<template>
  <div>
    <div v-bind:id="chartId" class="compare-chart">

    </div>
  </div>
</template>

<style lang="scss">
  .compare-chart {
    height: 270px;
  }
</style>

<script>
  import moment from 'moment-timezone'
  import Highcharts from 'highcharts'
  window.moment = moment

  export default {
    name: 'TagChart',

    props: {
      chartData: {
        default: () => []
      },
      chartId: {
        default: () => 'chart'
      }
    },

    data() {
      return {
        chart: null,
        visibility: [true, true, true]
      }
    },

    methods: {
      renderChart() {
        var self = this
        this.chart = Highcharts.chart(this.chartId, {
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
              type: 'datetime'
            }
          ],
          yAxis: [
            { // Primary yAxis
              labels: {
                format: '{value}',
                style: {
                  color: '#989898',
                  fontWeight: 'bold',
                  backgroundColor: 'red'
                }
              },
              title: false
            }
          ],
          tooltip: {
            shared: true,
            backgroundColor: '#373f52',
            color: '#ffffff',
            borderWidth: 0,
            shadow: false,
            useHTML: true,
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
              name: 'Impressions',
              color: '#ff4001',
              data: this.chartData.backfill,
              visible: self.visibility[0]
            },
            {
              name: 'Pageviews',
              color: '#468c01',
              data: this.combineCharts(this.chartData.desktopPageviews, this.chartData.mobilePageviews),
              visible: self.visibility[1]
            },
            {
              name: 'Revenue',
              color: '#00a2d9',
              data: this.combineCharts(this.chartData.desktopBackfillRevenue, this.chartData.mobileBackfillRevenue),
              visible: self.visibility[2]
            }
          ],
          plotOptions: {
            series: {
              events: {
                legendItemClick: function () {
                  self.setVisiblity(this._i, !this.visible)
                }
              }
            }
          }
        })
      },

      setVisiblity(index, state) {
        this.visibility[index] = state
      },

      combineCharts(first, second) {
        let newChart = []
        if (first === undefined) {
          return newChart
        }
        for (let i = 0; i < first.length; i++) {
          newChart.push([
            first[i][0],
            first[i][1] + second[i][1]
          ])
        }

        return newChart
      }
    },

    mounted() {
      this.$nextTick(function() {
        Highcharts.setOptions({
          global: {
            timezone: this.$store.state.users.currentUser.timezone
          }
        })
        this.renderChart()
      })
    },

    watch: {
      chartData(newChartData) {
        this.renderChart()
      }
    }
  }

</script>
