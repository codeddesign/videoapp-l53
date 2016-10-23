<template>
  <div>
    <div id="chart" class="chart">
      
    </div>
  </div>
</template>

<script>
  import Highcharts from 'highcharts'
  import _ from 'lodash'

  export default {
    data() {
      return {
        
      }
    },

    computed: {
      options() {
        return {
          credits: {
            enabled: false
          },
          chart: {
            backgroundColor:'transparent'
          },
          shadow: false,
          title: false,
          xAxis: [
            {
              type: 'datetime',
              dateTimeLabelFormats: { // don't display the dummy year
                second: '%l:%M:%S %P',
                minute: '%l:%M %P',
                hour: '%l:%M %P',
              },
            }
          ],
          yAxis: [{ // Primary yAxis
            labels: {
              format: '${value}',
              style: {
                color: '#989898',
                fontWeight: 'bold',
                backgroundColor: 'red'
              }
            },
            title: false
          }, { // Secondary yAxis
            labels: {
              format: '{value}',
              style: {
                color: Highcharts.getOptions().colors[0]
              }
            },
            title: false,
            opposite: true,
            visible: false
          }],
          tooltip: {
            shared: true,
            backgroundColor: '#373f52',
            color: '#ffffff',
            borderWidth: 0,
            shadow: false,  
            useHTML: true,
            headerFormat: '',
            style: {
              color: '#fff',
              fontSize: '9pt',
            }
          },
          legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            floating: true,
          },
          series: [
            {
              name: 'Revenue',
              type: 'column',
              yAxis: 0,
              color: '#cce0a3',
              data: this.$store.state.charts.revenue,
              tooltip: {
                valuePrefix: '$'
              }
            },
            {
              name: 'Impressions',
              type: 'spline',
              color: '#01a3de',
              yAxis: 1,
              data: this.$store.state.charts.impressions,
            }
          ]
        }
      }
    },
    mounted() {
      this.$store.subscribe((mutation, state) => {
        if(mutation.type === 'LOAD_DATA') {
          this.$nextTick(function() {
            console.log(this.options)
            Highcharts.chart('chart', this.options);
          })
        }
      })
      
    },
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
