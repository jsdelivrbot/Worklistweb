// Chart.js scripts
// -- Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';
// -- Bar Chart Example
var ctx = document.getElementById("graficoinad");
var myLineChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['Carteira','Inadimplência'],
    datasets: [{
      label: "Valor(R$)",
      backgroundColor: "rgb(0,70,101)",
      borderColor: "rgb(0,70,101)",
      data: [parseFloat(carteira),parseFloat(vencido)]
    }],
  },
  options: {
    scales: {
      xAxes: [{
        time: {
          unit: 'month'
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