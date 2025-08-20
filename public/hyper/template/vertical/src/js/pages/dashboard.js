//[Dashboard Javascript]

//Project:  Hyper Admin - Responsive Admin Template
//Primary use:   Used only for the main dashboard (index.html)


$(function () {

  'use strict';
  
  var options = {
      chart: {
        height: 279,
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
        height: 360,
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


                

