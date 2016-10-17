<template>
    <div :ignoreMe="waitChartData">
        <div class="campaignstats-digit">
            <canvas width="1000" height="285" id="barlinechart"
                    style="margin-top: 20px; padding-top: 20px; padding-bottom: 10px; border-top: 1px solid #d6d4d4; background-color: white">
            </canvas>
        </div>
    </div>
</template>

<script>
    import Chart from 'chart.js'

    export default {
        props: {
            revenue: '',
            impressions: ''
        },

        data() {
            return {
                chart: '',
                legend: '',
            };
        },

        mounted() {
            this.$nextTick(function () {
                this.render();
            })
        },

        computed: {
            waitChartData() {
                this.render();
            }
        },

        methods: {
            render() {
                var data = {
                    labels: _.map(Object.keys(this.revenue), function (value) {
                        return parseInt(value) + 1;
                    }),
                    datasets: [{
                        label: "Impressions",
                        type: 'line',
                        data: Object.keys(this.impressions).map(key => this.impressions[key]),
                        fill: false,
                        borderColor: 'rgb(0, 157, 215)',
                        backgroundColor: '#FFFFFF',
                        pointBorderColor: 'rgb(0, 157, 215)',
                        pointBackgroundColor: '#FFFFFF',
                        pointHoverBackgroundColor: '#7772A7',
                        pointHoverBorderColor: '#7772A7',
                        yAxisID: 'y-axis-impressions',
                    }, {
                        label: "Revenue",
                        type: 'bar',
                        data: Object.keys(this.revenue).map(key => this.revenue[key]),
                        fill: false,
                        backgroundColor: '#cce0a3',
                        borderColor: '#cce0a3',
                        hoverBackgroundColor: '#cce0a3',
                        hoverBorderColor: '#cce0a3',
                        yAxisID: 'y-axis-revenue'
                    }]
                };

                this.chart = new Chart(document.getElementById("barlinechart").getContext("2d"), {
                    type: 'bar',
                    data: data,
                    options: {
                        animation: false,
                        responsive: true,
                        tooltips: {
                            mode: 'label',
                            backgroundColor: '#303749'
                        },
                        elements: {
                            line: {
                                fill: false
                            }
                        },
                        legend: {
                            display: false
                        },
                        scales: {
                            xAxes: [{
                                display: true,
                                gridLines: {
                                    display: false
                                },
                                labels: {
                                    show: true
                                }
                            }],
                            yAxes: [{
                                type: "linear",
                                display: true,
                                position: "left",
                                id: "y-axis-revenue",
                                gridLines: {
                                    display: true
                                },
                                labels: {
                                    show: true,
                                }
                            }, {
                                type: "linear",
                                display: true,
                                position: "right",
                                id: "y-axis-impressions",
                                gridLines: {
                                    display: false
                                },
                                labels: {
                                    show: true,
                                }
                            }]
                        }
                    }
                });
            },
        }
    }
</script>
