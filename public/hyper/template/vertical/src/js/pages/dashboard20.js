//[Dashboard Javascript]

//Project:  Hyper Admin - Responsive Admin Template
//Primary use:   Used only for the main dashboard (index.html)


$(function () {

  'use strict';


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
          height: 185

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
          categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan'],
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
    
    var options = {
      chart: {
        height: 348,
        type: 'area',
        toolbar: {
                    show: false
                },
        stacked: true,
        events: {
          selection: function(chart, e) {
            console.log(new Date(e.xaxis.min) )
          }
        },

      },
      colors: ['#0bb2d4', '#faa700', '#E6155E'],
      dataLabels: {
          enabled: false
      },
      stroke: {
        curve: 'smooth'
      },

      series: [{
          name: 'Electronics',
          data: generateDayWiseTimeSeries(new Date('11 Feb 2017 GMT').getTime(), 20, {
            min: 10,
            max: 60
          })
        },
        {
          name: 'Apparel',
          data: generateDayWiseTimeSeries(new Date('11 Feb 2017 GMT').getTime(), 20, {
            min: 10,
            max: 20
          })
        },
        
        {
          name: 'Decor',
          data: generateDayWiseTimeSeries(new Date('11 Feb 2017 GMT').getTime(), 20, {
            min: 10,
            max: 15
          })
        }
      ],
      fill: {
        gradient: {
          enabled: true,
          opacityFrom: 0.6,
          opacityTo: 0.8,
        }
      },
      legend: {
        position: 'top',
        horizontalAlign: 'left'
      },
      xaxis: {
        type: 'datetime'
      },
    }

    var chart = new ApexCharts(
      document.querySelector("#chart-s1"),
      options
    );

    chart.render();

    /*
      // this function will generate output in this format
      // data = [
          [timestamp, 23],
          [timestamp, 33],
          [timestamp, 12]
          ...
      ]
      */
    function generateDayWiseTimeSeries(baseval, count, yrange) {
      var i = 0;
      var series = [];
      while (i < count) {
        var x = baseval;
        var y = Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;

        series.push([x, y]);
        baseval += 86400000;
        i++;
      }
      return series;
    }

    
    
    
    
     window.Apex = {
      stroke: {
        width: 3
      },
      markers: {
        size: 0
      },
      tooltip: {
        fixed: {
          enabled: true,
        }
      }
    };
    
    var randomizeArray = function (arg) {
      var array = arg.slice();
      var currentIndex = array.length,
        temporaryValue, randomIndex;

      while (0 !== currentIndex) {

        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex -= 1;

        temporaryValue = array[currentIndex];
        array[currentIndex] = array[randomIndex];
        array[randomIndex] = temporaryValue;
      }

      return array;
    }

    // data for the sparklines that appear below header area
    var sparklineData = [47, 45, 54, 38, 56, 24, 65, 31, 37, 39, 62, 51, 35, 41, 35, 27, 93, 53, 61, 27, 54, 43, 19, 46];

    var spark3 = {
      chart: {
        type: 'area',
        height: 430,

        sparkline: {
          enabled: true
        },
      },
      stroke: {
        curve: 'straight'
      },
      fill: {
        opacity: 0.3,
        gradient: {
          enabled: false
        }
      },
      series: [{
        data: randomizeArray(sparklineData)
      }],
      xaxis: {
        crosshairs: {
          width: 1
        },
      },
      yaxis: {
        min: 0
      },
      title: {
        text: '$135,965',
        offsetX: 0,
        style: {
          fontSize: '24px',
          cssClass: 'apexcharts-yaxis-title'
        }
      },
      subtitle: {
        text: 'Profits',
        offsetX: 0,
        style: {
          fontSize: '14px',
          cssClass: 'apexcharts-yaxis-title'
        }
      }
    }
    
    var spark3 = new ApexCharts(document.querySelector("#spark3"), spark3);
    spark3.render();
    
    /*
        // this function will generate output in this format
        // data = [
            [timestamp, 23],
            [timestamp, 33],
            [timestamp, 12]
            ...
        ]
        */

        var lastDate = 0;
        var data = []
        function getDayWiseTimeSeries(baseval, count, yrange) {
            var i = 0;
            while (i < count) {
                var x = baseval;
                var y = Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;

                data.push({
                    x, y
                });
                lastDate = baseval
                baseval += 86400000;
                i++;
            }
        }

        getDayWiseTimeSeries(new Date('11 Feb 2017 GMT').getTime(), 10, {
            min: 10,
            max: 90
        })

        function getNewSeries(baseval, yrange) {
            var newDate = baseval + 86400000;
            lastDate = newDate
            data.push({
                x: newDate,
                y: Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min
            })
        }

        function resetData(){
            data = data.slice(data.length - 10, data.length);
        }

var ctx5 = document.getElementById('chartBar5').getContext('2d');
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
    



        var options = {
            chart: {
                height: 421,
                type: 'line',
                animations: {
                    enabled: true,
                    easing: 'linear',
                    dynamicAnimation: {
                        speed: 2000
                    }
                },
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            series: [{
                data: data
            }],
            markers: {
                size: 0
            },
            xaxis: {
                type: 'datetime',
                range: 777600000,
            },
            yaxis: {
                max: 100
            },
            legend: {
                show: false
            },
        }

        var chart = new ApexCharts(
            document.querySelector("#chart-s5"),
            options
        );

        chart.render();

        var dataPointsLength = 10;

        window.setInterval(function () {
            getNewSeries(lastDate, {
                min: 10,
                max: 90
            })

            chart.updateSeries([{
                data: data
            }])
        }, 2000)
        
        // every 60 seconds, we reset the data 
        window.setInterval(function() {
            resetData()
            chart.updateSeries([{
                data
            }], false, true)
        }, 60000)
    
    
    

    var plot = $.plot('#flotChart', [{
          data: flotSampleData3,
          color: '#E6155E',
          lines: {
            fillColor: { colors: [{ opacity: 0 }, { opacity: 0.0 }]}
          }
        },{
          data: flotSampleData4,
          color: '#7231F5',
          lines: {
            fillColor: { colors: [{ opacity: 0 }, { opacity: 0.0 }]}
          }
        }], {
                series: {
                    shadowSize: 0,
            lines: {
              show: true,
              lineWidth: 2,
              fill: true
            }
                },
          grid: {
            borderWidth: 0,
            labelMargin: 8
          },
                yaxis: {
                        show: true,
                        min: 0,
                        max: 100,
                        ticks: [[0,''],[20,'20K'],[40,'40K'],[60,'60K'],[80,'80K']],
                        tickColor: 'rgba(0, 0, 0, 0.10)',
                        font: {
                            color: '#333333'
                          }
                },
                xaxis: {
                        show: true,
                        color: 'rgba(0, 0, 0, 0.10)',
                        ticks: [[25,'OCT 21'],[75,'OCT 22'],[100,'OCT 23'],[125,'OCT 24']],
                        font: {
                            color: '#333333'
                          }
          }
        });

        $.plot('#flotChart1', [{
          data: dashData2,
          color: '#faa700'
        }], {
                series: {
                    shadowSize: 0,
            lines: {
              show: true,
              lineWidth: 2,
              fill: true,
              fillColor: { colors: [ { opacity: 0.2 }, { opacity: 0.2 } ] }
            }
                },
          grid: {
            borderWidth: 0,
            labelMargin: 0
          },
                yaxis: {
            show: false,
            min: 0,
            max: 35
          },
                xaxis: {
            show: false,
            max: 50
          }
            });

        $.plot('#flotChart2', [{
          data: dashData2,
          color: '#0bb2d4'
        }], {
                series: {
                    shadowSize: 0,
            bars: {
              show: true,
              lineWidth: 0,
              fill: 1,
              barWidth: .5
            }
                },
          grid: {
            borderWidth: 0,
            labelMargin: 0
          },
                yaxis: {
            show: false,
            min: 0,
            max: 35
          },
                xaxis: {
            show: false,
            max: 20
          }
            });


        //-------------------------------------------------------------//
    
    
    
    
         
    //map   
    

    
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