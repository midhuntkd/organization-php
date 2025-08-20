//[Dashboard Javascript]

//Project:  Hyper Admin - Responsive Admin Template
//Primary use:   Used only for the main dashboard (index.html)


$(function () {


  'use strict';



  var ctx1 = document.getElementById('chartBar1').getContext('2d');
        new Chart(ctx1, {
          type: 'bar',
          data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
              data: [12, 25, 20, 32, 25, 18],
              backgroundColor: '#0bb2d4'
            }, {
              data: [22, 30, 25, 30, 20, 25],
              backgroundColor: '#7231F5'
            }]
          },
          options: {
            maintainAspectRatio: false,
            responsive: true,
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
                  beginAtZero: false,
                  fontSize: 10,
                  max: 60,
                  padding: 0
                }
              }],
              xAxes: [{
                gridLines: {
                  display: false,
                },
                barPercentage: 0.6,
                ticks: {
                  beginAtZero:true,
                  fontSize: 11,
                  fontFamily: 'Arial'
                }
              }]
            }
          }
        });



        var ctx2 = document.getElementById('chartBar2').getContext('2d');
        new Chart(ctx2, {
          type: 'bar',
          data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
              data: [20, 25, 32, 18, 25, 23],
              backgroundColor: '#17b3a3'
            }, {
              data: [22, 30, 25, 30, 20, 30],
              backgroundColor: '#ff4c52'
            }]
          },
          options: {
            maintainAspectRatio: false,
            responsive: true,
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
                  beginAtZero: false,
                  fontSize: 10,
                  max: 60,
                  padding: 0
                }
              }],
              xAxes: [{
                gridLines: {
                  display: false,
                },
                barPercentage: 0.6,
                ticks: {
                  beginAtZero:true,
                  fontSize: 11,
                  fontFamily: 'Arial'
                }
              }]
            }
          }
        });
  
  
  
  
  
  
    $("#baralc").sparkline([32,24,26,24,32,26,40,34,22,24,22,24,34,32,38,28,36,36,40,38,30,34,38], {
      type: 'bar',
      height: '95',
      barWidth: 6,
      barSpacing: 4,
      barColor: '#0bb2d4',
    });

  
      // Sparkline charts
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
  //map 
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

  
  /** CHARTS **/

      var options = {
    chart: {
      height: 317,
      type: "area",
       toolbar: {
         show: false
     },
      
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: "smooth"
    },
    series: [{
      name: "Online",
      data: [31, 40, 28, 51, 42, 109, 100]
    }, {
      name: "Store",
      data: [11, 32, 45, 32, 34, 52, 41]
    }],
    colors: ["#4d7cff", "#fec801"],
    xaxis: {
      type: "datetime",
      categories: ["2018-09-19T00:00:00", "2018-09-19T01:30:00", "2018-09-19T02:30:00", "2018-09-19T03:30:00", "2018-09-19T04:30:00", "2018-09-19T05:30:00",
        "2018-09-19T06:30:00"
      ],
    },
    tooltip: {
      x: {
        format: "dd/MM/yy HH:mm"
      },
    }
  }
  var chart = new ApexCharts(
    document.querySelector("#apexcharts-area"),
    options
  );
  chart.render();




        
  // [ Widget-line-chart ] start
    var chartDatac = [{
        "day": "Mon",
        "value": 60
    }, {
        "day": "Tue",
        "value": 45
    }, {
        "day": "Wed",
        "value": 70
    }, {
        "day": "Thu",
        "value": 55
    }, {
        "day": "Fri",
        "value": 70
    }, {
        "day": "Sat",
        "value": 55
    }, {
        "day": "Sun",
        "value": 70
    }];
    var chartc = AmCharts.makeChart("Widget-line-chart", {
        "type": "serial",
        "addClassNames": true,
        "defs": {
            "filter": [{
                    "x": "-50%",
                    "y": "-50%",
                    "width": "200%",
                    "height": "200%",
                    "id": "blur",
                    "feGaussianBlur": {
                        "in": "SourceGraphic",
                        "stdDeviation": "30"
                    }
                },
                {
                    "id": "shadow",
                    "x": "-10%",
                    "y": "-10%",
                    "width": "120%",
                    "height": "120%",
                    "feOffset": {
                        "result": "offOut",
                        "in": "SourceAlpha",
                        "dx": "0",
                        "dy": "20"
                    },
                    "feGaussianBlur": {
                        "result": "blurOut",
                        "in": "offOut",
                        "stdDeviation": "10"
                    },
                    "feColorMatrix": {
                        "result": "blurOut",
                        "type": "matrix",
                        "values": "0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 .2 0"
                    },
                    "feBlend": {
                        "in": "SourceGraphic",
                        "in2": "blurOut",
                        "mode": "normal"
                    }
                }
            ]
        },
        "fontSize": 15,
        "dataProvider": chartDatac,
        "autoMarginOffset": 0,
        "marginRight": 0,
        "categoryField": "day",
        "categoryAxis": {
            "color": '#fff',
            "gridAlpha": 0,
            "axisAlpha": 0,
            "lineAlpha": 0,
            "offset": -20,
            "inside": true,
        },
        "valueAxes": [{
            "fontSize": 0,
            "inside": true,
            "gridAlpha": 0,
            "axisAlpha": 0,
            "lineAlpha": 0,
            "minimum": 0,
            "maximum": 100,
        }],
        "chartCursor": {
            "valueLineEnabled": false,
            "valueLineBalloonEnabled": false,
            "cursorAlpha": 0,
            "zoomable": false,
            "valueZoomable": false,
            "cursorColor": "#fff",
            "categoryBalloonColor": "#51b4e6",
            "valueLineAlpha": 0
        },
        "graphs": [{
            "id": "g1",
            "type": "line",
            "valueField": "value",
            "lineColor": "#ffffff",
            "lineAlpha": 1,
            "lineThickness": 3,
            "fillAlphas": 0,
            "showBalloon": true,
            "balloon": {
                "drop": true,
                "adjustBorderColor": false,
                "color": "#ffffff",
                "fillAlphas": 0.2,
                "bullet": "round",
                "bulletBorderAlpha": 1,
                "bulletSize": 5,
                "hideBulletsCount": 50,
                "lineThickness": 2,
                "useLineColorForBulletBorder": true,
                "valueField": "value",
                "balloonText": "<span style='font-size:18px;'>[[value]]</span>"
            }
        }],
    });
    // [ Widget-line-chart ] end

  
  
  let draw = Chart.controllers.line.prototype.draw;
    Chart.controllers.line = Chart.controllers.line.extend({
        draw: function() {
            draw.apply(this, arguments);
            let ctx = this.chart.chart.ctx;
            let _stroke = ctx.stroke;
            ctx.stroke = function() {
                ctx.save();
                ctx.shadowColor = '#E6155E';
                ctx.shadowBlur = 10;
                ctx.shadowOffsetX = 0;
                ctx.shadowOffsetY = 9;
                _stroke.apply(this, arguments)
                ctx.restore();
            }
        }
    });

  
  var ctx = document.getElementById("home-chart");
    // ctx.height = 70;
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            type: 'line',
            defaultFontFamily: 'Montserrat',
            datasets: [{
                label: "This Month",
                data: [0, 10, 20, 10, 25, 15, 150, 46, 43, 65, 39, 61],
                backgroundColor: 'transparent',
                borderColor: '#E6155E',
                borderWidth: 3,
                pointStyle: 'circle',
                pointRadius: 5,
                pointBorderColor: '#E6155E',
                pointBackgroundColor: '#fff'

            }, {
                label: "Pre. Month",
                data: [0, 30, 10, 60, 50, 63, 10, 100, 54, 120, 32, 74],
                backgroundColor: 'transparent',
                borderColor: "#7231F5",
                borderWidth: 3,
                pointStyle: 'circle',
                pointRadius: 5,
                pointBorderColor: '#7231F5',
                pointBackgroundColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, 
            tooltips: {
                mode: 'index',
                titleFontSize: 12,
                titleFontColor: '#000',
                bodyFontColor: '#000',
                backgroundColor: '#fff',
                titleFontFamily: 'Montserrat',
                bodyFontFamily: 'Montserrat',
                cornerRadius: 3,
                intersect: false,
            },
            legend: {
                display: false, 
                labels: {
                    usePointStyle: true,
                    fontFamily: 'Montserrat',
                },
            },
            scales: {
                xAxes: [{
                    display: true,
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    scaleLabel: {
                        display: false,
                        labelString: 'Month'
                    }
                }],
                yAxes: [{
                    display: true,
                    gridLines: {
                        display: true,
                        drawBorder: false, 
                        zeroLineColor: "transparent"
                    },
                    scaleLabel: {
                        display: false,
                        labelString: 'Value'
                    }
                }]
            },
            title: {
                display: false,
                text: 'Normal Legend'
            }
        }
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


  
              


  
$("span.line3").peity("line", {
      fill: ["transparent"],
      stroke: ["#ffffff"],
      height: 150,
      width: 250,
    });
  
