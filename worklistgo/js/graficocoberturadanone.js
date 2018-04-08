// Chart.js scripts
// -- Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';
// -- Bar Chart Example
var ctx = document.getElementById("graficocoberturadanone");
var myLineChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['Clientes','Potencial','Realizado'],
    datasets: [{
      label: "Cnpjs",
      backgroundColor: "#246a65",
      borderColor: "#246a65",
      data: [clientescobertura,potencialcobertura,realizadocobertura]
      
    }],
  },
  options: {
    scales: {
      xAxes: [{
        time: {
          unit: 'year'
        },
        gridLines: {
          display: false
        },
        ticks: {
          maxTicksLimit: 6
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