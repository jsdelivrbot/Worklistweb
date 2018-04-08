// Chart.js scripts
// -- Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';
// -- Bar Chart Example
var ctx = document.getElementById("graficonivel");
var myLineChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['Ouro','Prata','Bronze','Black'],
    datasets: [{
      label: "Cnpjs",
      backgroundColor: "#246a65",
      borderColor: "#246a65",
      data: [parseInt(nivel3),parseInt(nivel2),parseInt(nivel1),parseInt(nivel0)]
      
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
      display: true
    }
  }
});