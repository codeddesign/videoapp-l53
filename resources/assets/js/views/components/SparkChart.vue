<template>
  <div class="chart-container">
    <div :id="id" class="spark-chart">

    </div>
  </div>

</template>

<style lang="scss">
  .chart-container {
    height: 40%;
    margin-top: 2px;
  }

  .spark-chart {
    margin: 0 auto;
    width: 70%;
    height: 100%;
  }
</style>

<script>
  import Highcharts from 'highcharts'

  export default {
    name: 'SparkChart',

    props: {
      id: {
        default: 'chart'
      },
      color: {
        default: '#000000'
      },
      chartData: {
        default: () => []
      }
    },

    methods: {
      renderChart() {
        Highcharts.chart(this.id, {
          chart: {
            backgroundColor: 'transparent',
            type: 'column'
          },
          shadow: false,
          title: false,
          credits: {
            enabled: false
          },
          legend: {
            enabled: false
          },
          tooltip: {
            formatter: function() {
              return this.y
            }
          },
          xAxis: [
            {
              type: 'datetime',
              visible: false
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
              title: false,
              visible: false
            }
          ],
          plotOptions: {
            column: {
              minPointLength: 3,
              pointPadding: 0,
              groupPadding: 0,
              borderWidth: 1
            }
          },
          series: [
            {
              color: this.color,
              data: this.chartData
            }
          ]
        })
      }
    },

    mounted() {
      this.$nextTick(function() {
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
