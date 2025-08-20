//[Dashboard Javascript]

//Project:  Hyper Admin - Responsive Admin Template
//Primary use:   Used only for the main dashboard (index.html)


$(function () {

  'use strict';
    
    //ticker
    if ($('#webticker-1').length) {   
        $("#webticker-1").webTicker({
            height:'auto', 
            duplicate:true, 
            startEmpty:false, 
            rssfrequency:5
        });
    }
    
    var options = {
      chart: {
        height: 508,
        type: 'candlestick',
        toolbar: {
                    show: false
                },
      },
      series: [{
        data: [{
            x: new Date(1538778600000),
            y: [6629.81, 6650.5, 6623.04, 6633.33]
          },
          {
            x: new Date(1538780400000),
            y: [6632.01, 6643.59, 6620, 6630.11]
          },
          {
            x: new Date(1538782200000),
            y: [6630.71, 6648.95, 6623.34, 6635.65]
          },
          {
            x: new Date(1538784000000),
            y: [6635.65, 6651, 6629.67, 6638.24]
          },
          {
            x: new Date(1538785800000),
            y: [6638.24, 6640, 6620, 6624.47]
          },
          {
            x: new Date(1538787600000),
            y: [6624.53, 6636.03, 6621.68, 6624.31]
          },
          {
            x: new Date(1538789400000),
            y: [6624.61, 6632.2, 6617, 6626.02]
          },
          {
            x: new Date(1538791200000),
            y: [6627, 6627.62, 6584.22, 6603.02]
          },
          {
            x: new Date(1538793000000),
            y: [6605, 6608.03, 6598.95, 6604.01]
          },
          {
            x: new Date(1538794800000),
            y: [6604.5, 6614.4, 6602.26, 6608.02]
          },
          {
            x: new Date(1538796600000),
            y: [6608.02, 6610.68, 6601.99, 6608.91]
          },
          {
            x: new Date(1538798400000),
            y: [6608.91, 6618.99, 6608.01, 6612]
          },
          {
            x: new Date(1538800200000),
            y: [6612, 6615.13, 6605.09, 6612]
          },
          {
            x: new Date(1538802000000),
            y: [6612, 6624.12, 6608.43, 6622.95]
          },
          {
            x: new Date(1538803800000),
            y: [6623.91, 6623.91, 6615, 6615.67]
          },
          {
            x: new Date(1538805600000),
            y: [6618.69, 6618.74, 6610, 6610.4]
          },
          {
            x: new Date(1538807400000),
            y: [6611, 6622.78, 6610.4, 6614.9]
          },
          {
            x: new Date(1538809200000),
            y: [6614.9, 6626.2, 6613.33, 6623.45]
          },
          {
            x: new Date(1538811000000),
            y: [6623.48, 6627, 6618.38, 6620.35]
          },
          {
            x: new Date(1538812800000),
            y: [6619.43, 6620.35, 6610.05, 6615.53]
          },
          {
            x: new Date(1538814600000),
            y: [6615.53, 6617.93, 6610, 6615.19]
          },
          {
            x: new Date(1538816400000),
            y: [6615.19, 6621.6, 6608.2, 6620]
          },
          {
            x: new Date(1538818200000),
            y: [6619.54, 6625.17, 6614.15, 6620]
          },
          {
            x: new Date(1538820000000),
            y: [6620.33, 6634.15, 6617.24, 6624.61]
          },
          {
            x: new Date(1538821800000),
            y: [6625.95, 6626, 6611.66, 6617.58]
          },
          {
            x: new Date(1538823600000),
            y: [6619, 6625.97, 6595.27, 6598.86]
          },
          {
            x: new Date(1538825400000),
            y: [6598.86, 6598.88, 6570, 6587.16]
          },
          {
            x: new Date(1538827200000),
            y: [6588.86, 6600, 6580, 6593.4]
          },
          {
            x: new Date(1538829000000),
            y: [6593.99, 6598.89, 6585, 6587.81]
          },
          {
            x: new Date(1538830800000),
            y: [6587.81, 6592.73, 6567.14, 6578]
          },
          {
            x: new Date(1538832600000),
            y: [6578.35, 6581.72, 6567.39, 6579]
          },
          {
            x: new Date(1538834400000),
            y: [6579.38, 6580.92, 6566.77, 6575.96]
          },
          {
            x: new Date(1538836200000),
            y: [6575.96, 6589, 6571.77, 6588.92]
          },
          {
            x: new Date(1538838000000),
            y: [6588.92, 6594, 6577.55, 6589.22]
          },
          {
            x: new Date(1538839800000),
            y: [6589.3, 6598.89, 6589.1, 6596.08]
          },
          {
            x: new Date(1538841600000),
            y: [6597.5, 6600, 6588.39, 6596.25]
          },
          {
            x: new Date(1538843400000),
            y: [6598.03, 6600, 6588.73, 6595.97]
          },
          {
            x: new Date(1538845200000),
            y: [6595.97, 6602.01, 6588.17, 6602]
          },
          {
            x: new Date(1538847000000),
            y: [6602, 6607, 6596.51, 6599.95]
          },
          {
            x: new Date(1538848800000),
            y: [6600.63, 6601.21, 6590.39, 6591.02]
          },
          {
            x: new Date(1538850600000),
            y: [6591.02, 6603.08, 6591, 6591]
          },
          {
            x: new Date(1538852400000),
            y: [6591, 6601.32, 6585, 6592]
          },
          {
            x: new Date(1538854200000),
            y: [6593.13, 6596.01, 6590, 6593.34]
          },
          {
            x: new Date(1538856000000),
            y: [6593.34, 6604.76, 6582.63, 6593.86]
          },
          {
            x: new Date(1538857800000),
            y: [6593.86, 6604.28, 6586.57, 6600.01]
          },
          {
            x: new Date(1538859600000),
            y: [6601.81, 6603.21, 6592.78, 6596.25]
          },
          {
            x: new Date(1538861400000),
            y: [6596.25, 6604.2, 6590, 6602.99]
          },
          {
            x: new Date(1538863200000),
            y: [6602.99, 6606, 6584.99, 6587.81]
          },
          {
            x: new Date(1538865000000),
            y: [6587.81, 6595, 6583.27, 6591.96]
          },
          {
            x: new Date(1538866800000),
            y: [6591.97, 6596.07, 6585, 6588.39]
          },
          {
            x: new Date(1538868600000),
            y: [6587.6, 6598.21, 6587.6, 6594.27]
          },
          {
            x: new Date(1538870400000),
            y: [6596.44, 6601, 6590, 6596.55]
          },
          {
            x: new Date(1538872200000),
            y: [6598.91, 6605, 6596.61, 6600.02]
          },
          {
            x: new Date(1538874000000),
            y: [6600.55, 6605, 6589.14, 6593.01]
          },
          {
            x: new Date(1538875800000),
            y: [6593.15, 6605, 6592, 6603.06]
          },
          {
            x: new Date(1538877600000),
            y: [6603.07, 6604.5, 6599.09, 6603.89]
          },
          {
            x: new Date(1538879400000),
            y: [6604.44, 6604.44, 6600, 6603.5]
          },
          {
            x: new Date(1538881200000),
            y: [6603.5, 6603.99, 6597.5, 6603.86]
          },
          {
            x: new Date(1538883000000),
            y: [6603.85, 6605, 6600, 6604.07]
          },
          {
            x: new Date(1538884800000),
            y: [6604.98, 6606, 6604.07, 6606]
          },
        ]
      }],
      title: {
        text: 'CandleStick Chart',
        align: 'left'
      },
      xaxis: {
        type: 'datetime'
      },
      yaxis: {
        tooltip: {
          enabled: true
        }
      }
    }

    var chart = new ApexCharts(
      document.querySelector("#bcn-btc"),
      options
    );

    chart.render();
    
    
    
    

    am4core.ready(function() {

    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end

    var chart = am4core.create("chartdivtrading", am4charts.XYChart);

    var data = [];
    var price = 100;
    var quantity = 1000;
    for (var i = 0; i < 300; i++) {
        price += Math.round((Math.random() < 0.5 ? 1 : -1) * Math.random() * 100);
        quantity += Math.round((Math.random() < 0.5 ? 1 : -1) * Math.random() * 1000);
        data.push({ date: new Date(2000, 1, i), price: price, quantity: quantity });
    }

    var interfaceColors = new am4core.InterfaceColorSet();

    chart.data = data;
    // the following line makes value axes to be arranged vertically.
    chart.leftAxesContainer.layout = "vertical";

    // uncomment this line if you want to change order of axes
    //chart.bottomAxesContainer.reverseOrder = true;

    var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
    dateAxis.renderer.grid.template.location = 0;
    dateAxis.renderer.ticks.template.length = 8;
    dateAxis.renderer.ticks.template.strokeOpacity = 0.1;
    dateAxis.renderer.grid.template.disabled = true;
    dateAxis.renderer.ticks.template.disabled = false;
    dateAxis.renderer.ticks.template.strokeOpacity = 0.2;

    // these two lines makes the axis to be initially zoomed-in
    //dateAxis.start = 0.7;
    //dateAxis.keepSelection = true;

    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    valueAxis.tooltip.disabled = true;
    valueAxis.zIndex = 1;
    valueAxis.renderer.baseGrid.disabled = true;

    // Set up axis
    valueAxis.renderer.inside = true;
    valueAxis.height = am4core.percent(60);
    valueAxis.renderer.labels.template.verticalCenter = "bottom";
    valueAxis.renderer.labels.template.padding(2,2,2,2);
    //valueAxis.renderer.maxLabelPosition = 0.95;
    valueAxis.renderer.fontSize = "0.8em"

    // uncomment these lines to fill plot area of this axis with some color
    valueAxis.renderer.gridContainer.background.fill = interfaceColors.getFor("alternativeBackground");
    valueAxis.renderer.gridContainer.background.fillOpacity = 0.05;


    var series = chart.series.push(new am4charts.LineSeries());
    series.dataFields.dateX = "date";
    series.dataFields.valueY = "price";
    series.tooltipText = "{valueY.value}";
    series.name = "Series 1";

    var valueAxis2 = chart.yAxes.push(new am4charts.ValueAxis());
    valueAxis2.tooltip.disabled = true;

    // this makes gap between panels
    valueAxis2.marginTop = 30;
    valueAxis2.renderer.baseGrid.disabled = true;
    valueAxis2.renderer.inside = true;
    valueAxis2.height = am4core.percent(40);
    valueAxis2.zIndex = 3
    valueAxis2.renderer.labels.template.verticalCenter = "bottom";
    valueAxis2.renderer.labels.template.padding(2,2,2,2);
    //valueAxis2.renderer.maxLabelPosition = 0.95;
    valueAxis2.renderer.fontSize = "0.8em"

    // uncomment these lines to fill plot area of this axis with some color
    valueAxis2.renderer.gridContainer.background.fill = interfaceColors.getFor("alternativeBackground");
    valueAxis2.renderer.gridContainer.background.fillOpacity = 0.05;

    var series2 = chart.series.push(new am4charts.ColumnSeries());
    series2.columns.template.width = am4core.percent(50);
    series2.dataFields.dateX = "date";
    series2.dataFields.valueY = "quantity";
    series2.yAxis = valueAxis2;
    series2.tooltipText = "{valueY.value}";
    series2.name = "Series 2";

    chart.cursor = new am4charts.XYCursor();
    chart.cursor.xAxis = dateAxis;

    var scrollbarX = new am4charts.XYChartScrollbar();
    scrollbarX.series.push(series);
    scrollbarX.marginBottom = 20;
    chart.scrollbarX = scrollbarX;

    }); // end am4core.ready()
    
    

    
        // ------------------------------
    // Nested chart
    // ------------------------------
    // based on prepared DOM, initialize echarts instance
        var nestedChart = echarts.init(document.getElementById('nested-pie'));
        var option = {
            
           tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b}: {c} ({d}%)"
                },

                // Add legend
                legend: {
                    orient: 'vertical',
                    x: 'left',
                    data: ['BTC','LTC','NEO','EOS'],
                    textStyle: {
                        color:"#ffffff"
                        },
                },

                // Add custom colors
                color: ['#0bb2d4', '#faa700', '#3e8ef7', '#ff4c52'],

                // Display toolbox
                toolbox: {
                    show: true,
                    orient: 'vertical',
                    feature: {
                        mark: {
                            show: true,
                            title: {
                                mark: 'Markline switch',
                                markUndo: 'Undo markline',
                                markClear: 'Clear markline'
                            }
                        },
                        dataView: {
                            show: true,
                            readOnly: false,
                            title: 'View data',
                            lang: ['View chart data', 'Close', 'Update']
                        },
                        magicType: {
                            show: true,
                            title: {
                                pie: 'Switch to pies',
                                funnel: 'Switch to funnel',
                            },
                            type: ['pie', 'funnel']
                        },
                        restore: {
                            show: true,
                            title: 'Restore'
                        },
                        saveAsImage: {
                            show: true,
                            title: 'Same as image',
                            lang: ['Save']
                        }
                    }
                },

                // Enable drag recalculate
                calculable: false,

                // Add series
                series: [

                    // Inner
                    {
                        name: 'Countries',
                        type: 'pie',
                        selectedMode: 'single',
                        radius: [0, '40%'],

                        // for funnel
                        x: '15%',
                        y: '7.5%',
                        width: '40%',
                        height: '85%',
                        funnelAlign: 'right',
                        max: 1548,

                        itemStyle: {
                            normal: {
                                label: {
                                    position: 'inner'
                                },
                                labelLine: {
                                    show: false
                                }
                            },
                            emphasis: {
                                label: {
                                    show: true
                                }
                            }
                        },

                        data: [
                            {value: 535, name: 'Cloud'},
                            {value: 679, name: 'Online'}
                        ]
                    },

                    // Outer
                    {
                        name: 'Countries',
                        type: 'pie',
                        radius: ['60%', '85%'],

                        // for funnel
                        x: '55%',
                        y: '7.5%',
                        width: '35%',
                        height: '85%',
                        funnelAlign: 'left',
                        max: 1048,

                        data: [
                            {value:335, name: 'BTC'},
                            {value:310, name: 'LTC'},
                            {value:234, name: 'NEO'},
                            {value:135, name: 'EOS'}
                        ]
                    }
                ]
        };    
       
    
        nestedChart.setOption(option);
    
    
    // Callback that creates and populates a data table, instantiates the stacked column chart, passes in the data and draws it.
    var stackedColumnChart = c3.generate({
        bindto: '#stacked-column',
        size: { height: 400 },
        color: {
            pattern: ['#0bb2d4', '#faa700', '#3e8ef7', '#ff4c52'    ]
        },

        // Create the data table.
        data: {
            columns: [
                ['BTC', -30, 200, 200, 400, -150, 250],
                ['NEO', 130, 100, -100, 200, -150, 50],
                ['EOS', -230, 200, 200, -300, 250, 250]
            ],
            type: 'bar',
            groups: [
                ["BTC", "NEO"]
            ]
        },
        grid: {
            y: {
                show: true
            }
        },
    });

    // Instantiate and draw our chart, passing in some options.
    setTimeout(function() {
        stackedColumnChart.groups([
            ["data1", "NEO", "LTC"]
        ]);
    }, 1000);

    setTimeout(function() {
        stackedColumnChart.load({
            columns: [
                ['LTC', 100, -50, 150, 200, -300, -100]
            ]
        });
    }, 1500);

    setTimeout(function() {
        stackedColumnChart.groups([
            ["BTC", "NEO", "EOS", "LTC"]
        ]);
    }, 2000);
    
//dashboard_daterangepicker
    
    if(0!==$("#dashboard_daterangepicker").length) {
        var n=$("#dashboard_daterangepicker"),
        e=moment(),
        t=moment();
        n.daterangepicker( {
            startDate:e, endDate:t, opens:"left", ranges: {
                Today: [moment(), moment()], Yesterday: [moment().subtract(1, "days"), moment().subtract(1, "days")], "Last 7 Days": [moment().subtract(6, "days"), moment()], "Last 30 Days": [moment().subtract(29, "days"), moment()], "This Month": [moment().startOf("month"), moment().endOf("month")], "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
            }
        }
        , a),
        a(e, t, "")
    }
    function a(e, t, a) {
        var r="",
        o="";
        t-e<100||"Today"==a?(r="Today:", o=e.format("MMM D")): "Yesterday"==a?(r="Yesterday:", o=e.format("MMM D")): o=e.format("MMM D")+" - "+t.format("MMM D"), n.find(".subheader_daterange-date").html(o), n.find(".subheader_daterange-title").html(r)
    }
    
    
}); // End of use strict




                

