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








   var ctx = document.getElementById("chart-1");
    // ctx.height = 135;
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun", "Mon"],
            datasets: [{
                data: [100, 70, 150, 120, 300, 250, 400, 300],
                borderWidth: 3,
                borderColor: "#ffffff",
                pointBackgroundColor: "#FFF",
                pointBorderColor: "#ffffff",
                pointHoverBackgroundColor: "#FFF",
                pointHoverBorderColor: "#ffffff",
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



    var ctx = document.getElementById("chart-2");
    // ctx.height = 135;
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun", "Mon"],
            datasets: [{
                data: [100, 70, 150, 120, 300, 250, 400, 300],
                borderWidth: 3,
                borderColor: "#ffffff",
                pointBackgroundColor: "#FFF",
                pointBorderColor: "#ffffff",
                pointHoverBackgroundColor: "#FFF",
                pointHoverBorderColor: "#ffffff",
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
  
  



   var chart = new ApexCharts(
    document.querySelector("#apexcharts-mixed"), {
      chart: {
        height: 350,
        type: "line",
         toolbar: {
         show: false
     },
        stacked: false,
      },
      stroke: {
        width: [0, 2, 5],
        curve: "smooth"
      },
      plotOptions: {
        bar: {
          columnWidth: "50%"
        }
      },
      colors: ["#4d7cff", "#f2426d", "#fec801"],
      series: [{
        name: "Actual Sales",
        type: "column",
        data: [23, 11, 22, 27, 13, 22, 37, 21, 44, 22, 30]
      }, {
        name: "Target Sales",
        type: "area",
        data: [44, 55, 41, 67, 22, 43, 21, 41, 56, 27, 43]
      }, {
        name: "Market Days",
        type: "line",
        data: [30, 25, 36, 30, 45, 35, 64, 52, 59, 36, 39]
      }],
      fill: {
        opacity: [0.85, 0.25, 1],
        gradient: {
          inverseColors: false,
          shade: "light",
          type: "vertical",
          opacityFrom: 0.85,
          opacityTo: 0.55,
          stops: [0, 100, 100, 100]
        }
      },
      labels: ["01/01/2003", "02/01/2003", "03/01/2003", "04/01/2003", "05/01/2003", "06/01/2003", "07/01/2003", "08/01/2003", "09/01/2003", "10/01/2003",
        "11/01/2003"
      ],
      markers: {
        size: 0
      },
      xaxis: {
        type: "datetime"
      },
      yaxis: {
        title: {
          text: "Points",
        },
        min: 0
      },
      tooltip: {
        shared: true,
        intersect: false,
        y: {
          formatter: function(y) {
            if (typeof y !== "undefined") {
              return y.toFixed(0) + " points";
            }
            return y;
          }
        }
      }
    }
  );
  chart.render();



  $('#circle-progress-1').circleProgress({
        startAngle: -Math.PI / 4 * 2,
        value: 0.5,
        size: 110,
        fill: { color: '#ffffff' },
        thickness: 5,
        emptyFill: "#ff0000"
    });

    $('#circle-progress-2').circleProgress({
        startAngle: -Math.PI / 4 * 2,
        value: 0.6,
        size: 110,
        fill: { color: '#ffffff' },
        thickness: 5,
        emptyFill: "#ff0000"
    });
  
  
    var draw = Chart.controllers.line.prototype.draw;
    Chart.controllers.line = Chart.controllers.line.extend({
        draw: function() {
            draw.apply(this, arguments);
            let ctx = this.chart.chart.ctx;
            let _stroke = ctx.stroke;
            ctx.stroke = function() {
                ctx.save();
                ctx.shadowColor = 'rgba(230, 21, 94, 0.5)';
                ctx.shadowBlur = 10;
                ctx.shadowOffsetX = 0;
                ctx.shadowOffsetY = 9;
                _stroke.apply(this, arguments)
                ctx.restore();
            }
        }
    });


 
        
      
  
  
}); // End of use strict



                


var updatingChart1 = $("span.visitor1").peity("line", {
            fill: "rgba(70, 128, 254,0.2)",
            stroke: "rgb(70, 128, 254)"
        });
        var updatingChart2 = $("span.visitor2").peity("line", {
            fill: "rgba(252, 97, 128,0.2)",
            stroke: "rgb(252, 97, 128)"
        });
        var updatingChart3 = $("span.visitor3").peity("line", {
            fill: "rgba(147, 190, 82,0.2)",
            stroke: "rgb(147, 190, 82)"
        });
        var updatingChart4 = $("span.visitor4").peity("line", {
            fill: "rgba(255, 182, 77,0.2)",
            stroke: "rgb(255, 182, 77)"
        });
        var updatingChart5 = $("span.visitor5").peity("line", {
            fill: "rgba(254, 138, 125,0.2)",
            stroke: "rgb(254, 138, 125)"
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