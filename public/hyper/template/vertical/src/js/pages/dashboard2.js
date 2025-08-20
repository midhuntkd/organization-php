//[Dashboard Javascript]

//Project:  Hyper Admin - Responsive Admin Template
//Primary use:   Used only for the main dashboard (index.html)


$(function () {

  'use strict';
  var options = {
      chart: {
        height: 350,
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
          name: 'Unique Visitor',
          data: generateDayWiseTimeSeries(new Date('11 Feb 2017 GMT').getTime(), 20, {
            min: 10,
            max: 60
          })
        },
        {
          name: 'Total Visits',
          data: generateDayWiseTimeSeries(new Date('11 Feb 2017 GMT').getTime(), 20, {
            min: 10,
            max: 20
          })
        },
        
        {
          name: 'Page Views',
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

    
    var plot1 = $.plot('#flotChart', [{
            data: flotSampleData5,
            color: '#E6155E'
          },{
            data: flotSampleData3,
            color: '#7231F5'
          }], {
          series: {
            shadowSize: 0,
            lines: {
              show: true,
              lineWidth: 2,
              fill: true,
              fillColor: { colors: [ { opacity: 0 }, { opacity: 0.2 } ] }
            }
          },
          grid: {
            borderWidth: 0,
            borderColor: '#969dab',
            labelMargin: 5,
            markings: [{
              xaxis: { from: 10, to: 20 },
              color: '#f7f7f7'
            }]
          },
          yaxis: {
            show: false,
            color: '#ced4da',
            tickLength: 10,
            min: 0,
            max: 110,
            font: {
              size: 11,
              color: '#969dab'
            },
            tickFormatter: function formatter(val, axis) {
              return val + 'k';
            }
          },
          xaxis: {
            show: false,
            position: 'top',
            color: 'rgba(0,0,0,0.1)'
          }
        });

        var mqSM = window.matchMedia('(min-width: 576px)');
        var mqSMMD = window.matchMedia('(min-width: 576px) and (max-width: 991px)');
        var mqLG = window.matchMedia('(min-width: 992px)');

        function screenCheck() {
          if (mqSM.matches) {
            plot1.getAxes().yaxis.options.show = true;
            plot1.getAxes().xaxis.options.show = true;
          } else {
            plot1.getAxes().yaxis.options.show = false;
            plot1.getAxes().xaxis.options.show = false;
          }

          if (mqSMMD.matches) {
            var tick = [
              [0, '<span>Oct</span><span>10</span>'],
              [20, '<span>Oct</span><span>12</span>'],
              [40, '<span>Oct</span><span>14</span>'],
              [60, '<span>Oct</span><span>16</span>'],
              [80, '<span>Oct</span><span>18</span>'],
              [100, '<span>Oct</span><span>19</span>'],
              [120, '<span>Oct</span><span>20</span>'],
              [140, '<span>Oct</span><span>23</span>']
            ];

            plot1.getAxes().xaxis.options.ticks = tick;
          }

          if (mqLG.matches) {
            var tick = [
              [10, '<span>Oct</span><span>10</span>'],
              [20, '<span>Oct</span><span>11</span>'],
              [30, '<span>Oct</span><span>12</span>'],
              [40, '<span>Oct</span><span>13</span>'],
              [50, '<span>Oct</span><span>14</span>'],
              [60, '<span>Oct</span><span>15</span>'],
              [70, '<span>Oct</span><span>16</span>'],
              [80, '<span>Oct</span><span>17</span>'],
              [90, '<span>Oct</span><span>18</span>'],
              [100, '<span>Oct</span><span>19</span>'],
              [110, '<span>Oct</span><span>20</span>'],
              [120, '<span>Oct</span><span>21</span>'],
              [130, '<span>Oct</span><span>22</span>'],
              [140, '<span>Oct</span><span>23</span>']
            ];

            plot1.getAxes().xaxis.options.ticks = tick;
          }
        }

        screenCheck();
        mqSM.addListener(screenCheck);
        mqSMMD.addListener(screenCheck);
        mqLG.addListener(screenCheck);

        plot1.setupGrid();
        plot1.draw();

    
        
  
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

  
  
  
  // visitor - traffic resources
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
  
    


                

 