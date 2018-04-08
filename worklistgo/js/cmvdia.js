/* global dataset */

// Chart.js scripts
// -- Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';
// -- Bar Chart Example
var ctx = document.getElementById("cmvdia");

var myLineChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: dia,
    datasets: [{
      label: "Valor(%)",
      backgroundColor: "rgba(2, 107, 11, 0.5)",
      borderColor: "#246a65",
      
     data: cmv
    }],
  },
  options: {
    scales: {
      xAxes: [{
        time: {
          unit: 'year'
        },
        gridLines: {
          display: true
        },
        ticks: {
          maxTicksLimit: 23
        }
      }],
      yAxes: [{
        ticks: {
          beginAtZero:true
        },
        gridLines: {
          display: true
        }
      }],
    },
    legend: {
      display: false
    }
  }
});

