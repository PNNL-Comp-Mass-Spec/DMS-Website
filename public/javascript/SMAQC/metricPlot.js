var plot;
$(document).ready(function() {

    var isQCART = Settings.title.indexOf("QC-ART") > 0; // QC-ART specific plot (similar to the standard metric view but with custom colors and labels)
    var isQCDM = !isQCART && Settings.title.indexOf("QCDM") > 0; // QCDM metric

    var plotDataSeries = {
        // Settings.plotdata
        name: 'Data',
        type: 'scatter',
        data: Settings.plotdata,
        symbolSize: 5,
        color: 'rgb(75, 178, 197)',
        tooltip: {
            formatter: function(d) {
                return '' + d.seriesName + '<br />' + d.value[2] + '<br />' + d.value[1];
            }
        }
    };

    var averageSeries = {
        // Settings.plotdata_average
        name: 'Median',
        type: 'line',
        data: Settings.plotdata_average,
        showSymbol: false,
        symbol: 'circle',
        symbolSize: 4,
        color: '#01818A',
        lineStyle: {
            width: 2
        }
    };

    var stdDevUpperSeries = {
        // Settings.stddevupper
        name: '1.5x MAD',
        type: 'line',
        data: Settings.stddevupper,
        showSymbol: false,
        symbol: 'none',
        color: '#BB0000',
        lineStyle: {
            width: 3
        }
    };

    var stdDevLowerSeries = {
        // Settings.stddevlower
        name: '1.5x MAD',
        type: 'line',
        data: Settings.stddevlower,
        showSymbol: false,
        symbol: 'none',
        color: '#BB0000',
        lineStyle: {
            width: 3
        },
        label: {
            show: false
        }
    };

    var poorDataSeries = {
        // Settings.plotDataPoor (QCDM value out-of-range)
        name: 'Bad QCDM Score',
        type: 'scatter',
        data: Settings.plotDataPoor, //(QCDM value out-of-range)
        symbolSize: 5,
        color: '#FA8100',
        tooltip: {
            formatter: function(d) {
                return '' + d.seriesName + '<br />' + d.value[2] + '<br />' + d.value[1];
            }
        }
    };

    var badDataSeries = {
        // Settings.plotDataBad (dataset not released)
        name: 'Bad Dataset',
        type: 'scatter',
        data: Settings.plotDataBad, // (dataset not released)
        symbolSize: 4,
        color: '#551A8B',
        tooltip: {
            formatter: function(d) {
                return '' + d.seriesName + '<br />' + d.value[2] + '<br />' + d.value[1];
            }
        }
    };

    if (isQCART)
    {
        // Series adjustments for QC-ART
        averageSeries = {
            // Settings.plotdata_average (Fraction Set Average)
            name: 'Fraction Set Avg',
            type: 'line',
            data: Settings.plotdata_average,
            showSymbol: false,
            symbol: 'circle',
            symbolSize: 4,
            color: '#01818A',
            lineStyle: {
                width: 2.5
            }
        };

        stdDevUpperSeries = {
            // Settings.stddevupper (threshold for very bad scores)
            name: 'Bad Threshold',
            type: 'line',
            data: Settings.stddevupper,
            showSymbol: false,
            symbol: 'none',
            color: '#BB0000',
            lineStyle: {
                width: 2
            }
        };

        stdDevLowerSeries = {
            // Settings.stddevlower (threshold for poor scores)
            name: 'Poor Threshold',
            type: 'line',
            data: Settings.stddevlower,
            showSymbol: false,
            symbol: 'none',
            color: '#FFFF00',
            lineStyle: {
                width: 2
            }
        };

        // Settings.plotDataPoor (QC-ART value larger than the threshold for very bad scores))
        //poorDataSeries.label = 'Bad QC-ART Score';
        poorDataSeries.name = 'Bad QC-ART Score';
    }

    // Default data series: used for Standard and QC-ART
    var series = [ plotDataSeries, averageSeries, stdDevUpperSeries, stdDevLowerSeries, poorDataSeries, badDataSeries ];

    if (isQCDM)
    {
        // Series adjustments for QCDM
        stdDevLowerSeries = {
            // Settings.stddevlower
            name: 'Limit',
            type: 'line',
            data: Settings.stddevlower,
            showSymbol: false,
            symbol: 'none',
            color: '#66CD00',
            lineStyle: {
                width: 3
            }
        };

        // QCDM: drops Settings.stddevupper
        series = [ plotDataSeries, averageSeries, stdDevLowerSeries, poorDataSeries, badDataSeries ];
    }

    var options = {
        title: {
            test: Settings.title,
        },
        animation: false,
        legend: {
            // if 'data' is not provided here, it is auto-collected from the series
            orient: 'vertical',
            left: 'right',
            top: 'middle',
            align: 'left'
        },
        grid: {
            top: '10',
            bottom: '0',
            left: '25',
            right: '10%',
            show: true,
            containLabel: true
        },
        xAxis: {
            type: 'time',
            min: 'dataMin',
            max: 'dataMax',
            //axisTick: {},
            axisLabel: {
                rotate: 60,
                fontSize: '8pt',
                formatter: '{MMM} {dd}, {yyyy}'
            },
            splitNumber: 15,
            splitLine: {
                show: true
            }
        },
        yAxis: {
            type: 'value',
            name: Settings.metric_units,
            nameLocation: 'middle',
            nameGap: 25
        },
        tooltip: {
            trigger: 'item',
            axisPointer: {
                type: 'cross'
            }
        },
        toolbox: {
            show: true,
            itemSize: 20,
            feature: {
                dataZoom: {
                    show: true
                },
                restore: {}
            }
        },
        dataZoom: [
            {
                type: 'inside'
            }
        ],
        series: series
    }

    var chartDom = document.getElementById('chartdiv');
    plot = echarts.init(chartDom);
    options && plot.setOption(options);
});
