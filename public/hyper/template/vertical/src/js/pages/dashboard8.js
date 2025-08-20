//[Dashboard Javascript]

//Project:  Hyper Admin - Responsive Admin Template
//Primary use:   Used only for the main dashboard (index.html)

 Morris.Area({
        element: 'area-chart3',
        data: [{
              period: '2018',
              data1: 0,
              data2: 0
            }, {
              period: '2019',
              data1: 55,
              data2: 20
            }, {
              period: '2020',
              data1: 25,
              data2: 55
            }, {
              period: '2021',
              data1: 65,
              data2: 17
            }, {
              period: '2022',
              data1: 35,
              data2: 25
            }, {
              period: '2023',
              data1: 30,
              data2: 85
            }, {
              period: '2024',
              data1: 15,
              data2: 15
            }


            ],
            lineColors: ['#e00051', '#f9b423'],
            xkey: 'period',
            ykeys: ['data1', 'data2'],
            labels: ['Data 1', 'Data 2'],
            pointSize: 0,
            padding: 1,
            lineWidth: 0,
            resize:true,
            fillOpacity: 1,
            behaveLikeLine: true,
            gridLineColor: '#ffffff0',
            hideHover: 'auto',
            axes: false,

      });
 
$(function () {

  'use strict';
$("#baralc").sparkline([32,24,26,24,32,26,40,34,22,24,22,24,34,32,38,28,36,36,40,38,30,34,38], {
            type: 'bar',
            height: '95',
            barWidth: 6,
            barSpacing: 4,
            barColor: '#0bb2d4',
        });



  var options = {
          series: [{
          name: 'series1',
          data: [ 20,15,25,25,30,27,33,30,35,32,25,31,20,25,30,27,33,30,20]
          }],
          chart: {
          height: 465,
          type: 'area',
          toolbar: {
            show: false,
            },
            offsetY: 0,
        },
        colors: ["#ffa800"],
        fill: {
          colors: ["#ffa800" ],
          type: "gradient",
          gradient: {
            shade: "light",
            type: "vertical",
            shadeIntensity: 0.4,
            inverseColors: false,
            opacityFrom: 0.7,
            opacityTo: 0.1,
            stops: [0,85,90],
          },
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
        width: [2],
          curve: 'smooth'
        },
        grid: {
          show: false,
          padding: {
            left: -10,
            top: -25,
            right: -0,
          },
        },
        markers: {
            size: 0,
        },
        xaxis: {
          type: 'datetime',
          categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z", "2018-09-19T06:30:00.000Z"]
        },
        legend: {
            show: false,
        },
        tooltip: {
          x: {
            format: 'dd/MM/yy HH:mm'
          },
        },
        yaxis: {
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false,
          },
          labels: {
            show: false,
            formatter: function (val) {
              return val + "%";
            }
          },
        },
        xaxis: {
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false,
          },
          labels: {
            show: false,
          },
        },
        };

        var chart = new ApexCharts(document.querySelector("#chart-widget1"), options);
        chart.render();
            


          
    
        

    let draw = Chart.controllers.line.prototype.draw;
    Chart.controllers.line = Chart.controllers.line.extend({
        draw: function() {
            draw.apply(this, arguments);
            let ctx = this.chart.chart.ctx;
            let _stroke = ctx.stroke;
            ctx.stroke = function() {
                ctx.save();
                ctx.shadowColor = '#ccc';
                ctx.shadowBlur = 20;
                ctx.shadowOffsetX = 0;
                ctx.shadowOffsetY = 1;
                _stroke.apply(this, arguments)
                ctx.restore();
            }
        }
    });












      
    

    var ctx = document.getElementById("canvas1");
    // ctx.height = 200;
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun", "Mon"],
            datasets: [{
                data: [100, 70, 150, 120, 300, 250, 400, 300],
                borderWidth: 3,
                borderColor: "#0bb2d4",
                pointBackgroundColor: "#FFF",
                pointBorderColor: "#0bb2d4",
                pointHoverBackgroundColor: "#FFF",
                pointHoverBorderColor: "#0bb2d4",
                pointRadius: 0,
                pointHoverRadius: 6,
                fill: !1
            }]
        },
        options: {
            responsive: !0,
            maintainAspectRatio: false, 
            legend: {
                display: !1
            },
            scales: {
                xAxes: [{
                    display: !1,
                    gridLines: {
                        display: !1
                    }
                }],
                yAxes: [{
                    display: !1,
                    ticks: {
                        padding: 10,
                        stepSize: 100,
                        max: 600,
                        min: 0
                    },
                    gridLines: {
                        display: !0,
                        draw1Border: !1,
                        lineWidth: 0.5,
                        zeroLineColor: "#e5e5e5"
                    }
                }]
            }
        },
    });



    var ctx = document.getElementById("canvas2");
    // ctx.height = 200;
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun", "Mon"],
            datasets: [{
                data: [100, 70, 150, 120, 300, 250, 400, 300],
                borderWidth: 3,
                borderColor: "#ff4c52",
                pointBackgroundColor: "#FFF",
                pointBorderColor: "#ff4c52",
                pointHoverBackgroundColor: "#FFF",
                pointHoverBorderColor: "#ff4c52",
                pointRadius: 0,
                pointHoverRadius: 6,
                fill: !1
            }]
        },
        options: {
            responsive: !0,
            maintainAspectRatio: false, 
            legend: {
                display: !1
            },
            scales: {
                xAxes: [{
                    display: !1,
                    gridLines: {
                        display: !1
                    }
                }],
                yAxes: [{
                    display: !1,
                    ticks: {
                        padding: 10,
                        stepSize: 100,
                        max: 600,
                        min: 0
                    },
                    gridLines: {
                        display: !0,
                        draw1Border: !1,
                        lineWidth: 0.5,
                        zeroLineColor: "#e5e5e5"
                    }
                }]
            }
        },
    });


    var ctx = document.getElementById("canvas3");
    // ctx.height = 200;
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun", "Mon"],
            datasets: [{
                data: [100, 70, 150, 120, 300, 250, 400, 300],
                borderWidth: 3,
                borderColor: "#faa700",
                pointBackgroundColor: "#FFF",
                pointBorderColor: "#faa700",
                pointHoverBackgroundColor: "#FFF",
                pointHoverBorderColor: "#faa700",
                pointRadius: 0,
                pointHoverRadius: 6,
                fill: !1
            }]
        },
        options: {
            responsive: !0,
            maintainAspectRatio: false, 
            legend: {
                display: !1
            },
            scales: {
                xAxes: [{
                    display: !1,
                    gridLines: {
                        display: !1
                    }
                }],
                yAxes: [{
                    display: !1,
                    ticks: {
                        padding: 10,
                        stepSize: 100,
                        max: 600,
                        min: 0
                    },
                    gridLines: {
                        display: !0,
                        draw1Border: !1,
                        lineWidth: 0.5,
                        zeroLineColor: "#e5e5e5"
                    }
                }]
            }
        },
    });



   

    
    

     
    
   
    
    
    
          


    
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




am5.ready(function() {


// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var root = am5.Root.new("chartdiv");


// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root.setThemes([
  am5themes_Animated.new(root)
]);


// Create chart
// https://www.amcharts.com/docs/v5/charts/xy-chart/
var chart = root.container.children.push(am5xy.XYChart.new(root, {
  panX: false,
  panY: false,
  paddingLeft: 0,
  wheelX: "panX",
  wheelY: "zoomX",
  layout: root.verticalLayout
}));


// Add legend
// https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
var legend = chart.children.push(
  am5.Legend.new(root, {
    centerX: am5.p50,
    x: am5.p50
  })
);

var data = [{
  "year": "2021",
  "sales": 2,
  "visits": 4,
  "clicks": 3
},{
    "year": "2022",
    "lineColor": ["#0bb2d4", "#3e8ef7"],
    "fillColors": ["#0bb2d4", "#3e8ef7"],
    "sales": 4,
   "visits": 7,
   "clicks": 5
}, {
  "year": "2023",
  "sales": 2,
   "visits": 3,
   "clicks": 4
}, {
  "year": "2024",
  "sales": 4.5,
   "visits": 6,
   "clicks": 4,
}]


// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
var xRenderer = am5xy.AxisRendererX.new(root, {
  cellStartLocation: 0.1,
  cellEndLocation: 0.9,
  minorGridEnabled: true
})

var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
  categoryField: "year",
  renderer: xRenderer,
  tooltip: am5.Tooltip.new(root, {})
}));

xRenderer.grid.template.setAll({
  location: 1
})

xAxis.data.setAll(data);

var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
  renderer: am5xy.AxisRendererY.new(root, {
    strokeOpacity: 0.1
  })
}));


// Add series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
function makeSeries(name, fieldName) {
  var series = chart.series.push(am5xy.ColumnSeries.new(root, {
    name: name,
    xAxis: xAxis,
    yAxis: yAxis,
    valueYField: fieldName,
    categoryXField: "year"
  }));

  series.columns.template.setAll({
    tooltipText: "{name}, {categoryX}:{valueY}",
    width: am5.percent(90),
    tooltipY: 0,
    strokeOpacity: 0
  });

  series.data.setAll(data);

  // Make stuff animate on load
  // https://www.amcharts.com/docs/v5/concepts/animations/
  series.appear();

  series.bullets.push(function () {
    return am5.Bullet.new(root, {
      locationY: 0,
      sprite: am5.Label.new(root, {
        text: "{valueY}",
        fill: root.interfaceColors.get("alternativeText"),
        centerY: 0,
        centerX: am5.p50,
        populateText: true
      })
    });
  });

  legend.data.push(series);
}



makeSeries("sales", "sales");
makeSeries("visits", "visits");
makeSeries("clicks", "clicks");


// Make stuff animate on load
// https://www.amcharts.com/docs/v5/concepts/animations/
chart.appear(1000, 100);

}); // end am5.ready()


var myvalues = [1300, 500, 1920, 927, 831, 1127, 719, 1930, 1221];
          $('#sparkline-1').sparkline(myvalues, {
            type     : 'line',
            lineColor: '#67757c',
            fillColor: '#7231F5',
            height   : '50',
            width    : '70'
          });
          myvalues = [715, 319, 620, 342, 662, 990, 730, 467, 559, 340, 881];
          $('#sparkline-2').sparkline(myvalues, {
            type     : 'line',
            lineColor: '#67757c',
            fillColor: '#E6155E',
            height   : '50',
            width    : '70'
          });
          myvalues = [88, 49, 22,35, 45, 72, 11, 55, 25, 19, 27];
          $('#sparkline-3').sparkline(myvalues, {
            type     : 'line',
            lineColor: '#67757c',
            fillColor: '#faa700',
            height   : '50',
            width    : '70'
          });

  jQuery('#world-map-markers').vectorMap(
{
    map: 'world_mill_en',
    backgroundColor: 'rgba(255,255,255,0)',
    borderColor: '#818181',
    borderOpacity: 0.25,
    borderWidth: 1,
    color: '#f4f3f0',
    regionStyle : {
        initial : {
          fill : '#1e88e5'
        }
      },
    markerStyle: {
      initial: {
                    r: 9,
                    'fill': '#fff',
                    'fill-opacity':1,
                    'stroke': '#000',
                    'stroke-width' : 5,
                    'stroke-opacity': 0.4
                },
                },
    enableZoom: true,
    hoverColor: '#0a89c1',
    markers : [{
        latLng : [37.00, 96.00],
        name : 'Text'
      
      }],
    hoverOpacity: null,
    normalizeFunction: 'linear',
    scaleColors: ['#b6d6ff', '#005ace'],
    selectedColor: '#c9dfaf',
    selectedRegions: [],
    showTooltip: true,
    onRegionClick: function(element, code, region)
    {
        var message = 'You clicked "'
            + region
            + '" which has the code: '
            + code.toUpperCase();

        alert(message);
    }
});

var options = {
        series: [{
          name: 'Net Profit',
          data: [44, 55, 57, 56, 61, 58, 63, 60, 66, 55, 57, 56]
        }],
        chart: {
          type: 'bar',
          toolbar: {
        show: false
      },
          height: 135

        },
    colors:['#2444e8'],
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '20%',
            endingShape: 'rounded'
          },
        },
        dataLabels: {
          enabled: false
        },
    grid: {
      show: false,  
    },
        stroke: {
          show: false,
          width: 0,
          colors: ['transparent']
        },
        xaxis: {
          // categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan'],

        },
    
        yaxis: {
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false,
          },
          labels: {
            show: false,
          }
          
        
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return "$ " + val + " thousands"
            }
          }
        }
      };

      var chart = new ApexCharts(document.querySelector("#profit"), options);
      chart.render();
  


var ctx5 = document.getElementById('chartBar1').getContext('2d');
        new Chart(ctx5, {
          type: 'bar',
          data: {
            labels: [0,1,2,3,4,5,6,7],
            datasets: [{
              data: [2, 4, 10, 20, 45, 40, 35, 18],
              backgroundColor: '#17b3a3'
            }, {
              data: [3, 6, 15, 35, 50, 45, 35, 25],
              backgroundColor: '#3e8ef7'
            }]
          },
          options: {
            maintainAspectRatio: false,
            tooltips: {
              enabled: false
            },
            legend: {
              display: false,
                labels: {
                  display: false
                }
            },
            scales: {
              yAxes: [{
                display: false,
                ticks: {
                  beginAtZero:true,
                  fontSize: 11,
                  max: 80
                }
              }],
              xAxes: [{
                barPercentage: 0.6,
                gridLines: {
                  color: 'rgba(0,0,0,0.08)'
                },
                ticks: {
                  beginAtZero:true,
                  fontSize: 11,
                  display: false
                }
              }]
            }
          }
        });


          Morris.Bar({
        element: 'bar-chart',
        data: [{
            y: '2012',
            a: 405,
            b: 295,
            c: 265
        }, {
            y: '2013',
            a: 840,
            b: 720,
            c: 245
        }, {
            y: '2014',
            a: 455,
            b: 445,
            c: 235
        }, {
            y: '2015',
            a: 680,
            b: 750,
            c: 345
        }, {
            y: '2016',
            a: 585,
            b: 435,
            c: 235
        }, {
            y: '2023',
            a: 880,
            b: 730,
            c: 245
        }, {
            y: '2018',
            a: 905,
            b: 495,
            c: 245
        }],
        xkey: 'y',
        ykeys: ['a', 'b', 'c'],
        labels: ['A', 'B', 'C'],
         barColors:['#fec801', '#4d7cff', '#51ce8a'],
        hideHover: 'auto',
        gridLineColor: '#eef0f2',
        resize: true
    });      
  

 


    
    // [ Bar Chart2 ] Start
       var chart = AmCharts.makeChart("bar-chart2", {
           "type": "serial",
           "theme": "dark",
           "marginTop": 10,
           "marginRight": 0,
           "valueAxes": [{
               "id": "v1",
               "position": "left",
               "axisAlpha": 0,
               "lineAlpha": 0,
                "color": '#ffffff',
               "autoGridCount": false,
               "labelFunction": function(value) {
                   return +Math.round(value) + "00";
               }
           }],
           "graphs": [{
               "id": "g1",
               "valueAxis": "v1",
               "lineColor": ["#0bb2d4", "#3e8ef7"],
               "fillColors": ["#0bb2d4", "#3e8ef7"],
               "fillAlphas": 1,
               "type": "column",
               "title": "SALES",
               "valueField": "sales",
               "columnWidth": 0.3,
               "legendValueText": "$[[value]]M",
               "balloonText": "[[title]]<br /><b style='font-size: 130%'>$[[value]]M</b>"
           },{
               "id": "g2",
               "valueAxis": "v1",
               "lineColor": ["#ff4c52", "#E6155E"],
               "fillColors": ["#ff4c52", "#E6155E"],
               "fillAlphas": 1,
               "type": "column",
               "title": "VISITS ",
               "valueField": "visits",
               "columnWidth": 0.3,
               "legendValueText": "$[[value]]M",
               "balloonText": "[[title]]<br /><b style='font-size: 130%'>$[[value]]M</b>"
           },{
               "id": "g3",
               "valueAxis": "v1",
               "lineColor": ["#17b3a3", "#57c7d4"],
               "fillColors": ["#17b3a3", "#57c7d4"],
               "fillAlphas": 1,
               "type": "column",
               "title": "CLICKS",
               "valueField": "clicks",
               "columnWidth": 0.3,
               "legendValueText": "$[[value]]M",
               "balloonText": "[[title]]<br /><b style='font-size: 130%'>$[[value]]M</b>"
           }],
           "chartCursor": {
               "pan": true,
               "valueLineEnabled": true,
               "valueLineBalloonEnabled": true,
               "cursorAlpha": 0,
               "valueLineAlpha": 0.2
           },
           "categoryField": "Year",
           "categoryAxis": {
               "dashLength": 1,
               "gridAlpha": 0,
               "axisAlpha": 0,
               "lineAlpha": 0,
               "color": '#ffffff',
               "minorGridEnabled": true
           },
           "legend": {
               "useGraphSettings": true,
               "position": "top",
               "color": '#ffffff',
           },
           "balloon": {
               "borderThickness": 1,
               "shadowAlpha": 0
           },
           "dataProvider": [{
               "Year": "2014",
               "sales": 2,
               "visits": 4,
               "clicks": 3
           },{
               "Year": "2015",
               "sales": 4,
               "visits": 7,
               "clicks": 5
           },{
               "Year": "2016",
               "sales": 2,
               "visits": 3,
               "clicks": 4
           },{
               "Year": "2017",
               "sales": 4.5,
               "visits": 6,
               "clicks": 4
           }]
       });
    
 


var options = {
          series: [{
          name: 'XYZ MOTORS',
          data: dates
        }],
          chart: {
          type: 'area',
          stacked: false,
          height: 350,
          zoom: {
            type: 'x',
            enabled: true,
            autoScaleYaxis: true
          },
          toolbar: {
            autoSelected: 'zoom'
          }
        },
        dataLabels: {
          enabled: false
        },
        markers: {
          size: 0,
        },
        title: {
          text: 'Stock Price Movement',
          align: 'left'
        },
        fill: {
          type: 'gradient',
          gradient: {
            shadeIntensity: 1,
            inverseColors: false,
            opacityFrom: 0.5,
            opacityTo: 0,
            stops: [0, 90, 100]
          },
        },
        yaxis: {
          labels: {
            formatter: function (val) {
              return (val / 1000000).toFixed(0);
            },
          },
          title: {
            text: 'Price'
          },
        },
        xaxis: {
          type: 'datetime',
        },
        tooltip: {
          shared: false,
          y: {
            formatter: function (val) {
              return (val / 1000000).toFixed(0)
            }
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();



     var options = {
              chart: {
                type: 'area',
                stacked: false,
                height: 390,
                toolbar: {
                    show: false,
                },
                zoom: {
                  type: 'x',
                  enabled: true
                },
                toolbar: {
                  autoSelected: 'zoom'
                }
              },
              dataLabels: {
                enabled: false
              },
              series: [{
                name: 'Stock',
                data: dates
              }],
              markers: {
                size: 0,
              },
              fill: {
                gradient: {
                  enabled: true,
                  shadeIntensity: 1,
                  inverseColors: false,
                  opacityFrom: 0.9,
                  opacityTo: 0.2,
                  stops: [0, 90, 100]
                },
              },
              yaxis: {
                min: 20000000,
                max: 250000000,
                labels: {
                  formatter: function (val) {
                    return (val / 1000000).toFixed(0);
                  },
                },
              },
                
              xaxis: {
                type: 'datetime',
              },
                
                
              tooltip: {
                shared: false,
                y: {
                  formatter: function (val) {
                    return (val / 1000000).toFixed(0)
                  }
                }
              }
            }

            var chart = new ApexCharts(
              document.querySelector("#chart-line"),
              options
            );

            chart.render();



var sparklineData = [8,10,12,15,19,22,25,21,20,17,16,12,8,9,12,16,18,22,19,21,26,28,26,24];
  
var spark2 = {
      chart: {
      type: 'area',
      height: 171,
      sparkline: {
        enabled: true
      },
      },
      stroke: {     
      show: true,
      width: 0.5,
      curve: 'smooth'
      },
      fill: {
      opacity: 1,
      gradient: {
        enabled: false
      }
      },
      series: [{
      data: randomizeArray(sparklineData)
      }],
      labels: [...Array(24).keys()].map(n => `2018-09-0${n+1}`),
      yaxis: {
      min: 0
      },
      xaxis: {
      type: 'datetime',
      },
      colors: ['#303f9f'],
      subtitle: {
      offsetX: 0,
      style: {
        fontSize: '14px',
        cssClass: 'apexcharts-yaxis-title'
      }
      }
    }


    var spark2 = new ApexCharts(document.querySelector("#spark2"), spark2);
    spark2.render();
  
  
  
  
    WeatherIcon.add('icon1' , WeatherIcon.SLEET , {stroke:false , shadow:false , animated:true } );

  


