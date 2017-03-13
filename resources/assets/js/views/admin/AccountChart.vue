<template>
  <div>
    <div id="chart" class="account-chart">

    </div>
  </div>
</template>

<style lang="scss">
  .account-chart {
    height: 253px;
  }

  .userinfo-addtllinkswrap {
    margin-top: 0px !important;
  }
</style>

<script>
  import Highcharts from 'highcharts'

  export default {
    name: 'AccountChart',

    props: {
      chartData: {
        default: () => []
      }
    },

    data() {
      return {
        chart: null
      }
    },

    methods: {
      renderChart() {
        this.chart = Highcharts.chart('chart', {
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
              data: this.chartData.impressions
            },
            {
              name: 'Requests',
              color: '#468c01',
              data: this.chartData.tagRequests
            },
            {
              name: 'Fills',
              color: '#00a2d9',
              data: this.chartData.fills
            },
            {
              name: 'Desktop Pageviews',
              color: '#009688',
              data: this.chartData.desktopPageviews
            },
            {
              name: 'Mobile Pageviews',
              color: '#FF9800',
              data: this.chartData.mobilePageviews
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
