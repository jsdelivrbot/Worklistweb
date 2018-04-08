// Chart.js scripts
// -- Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';
// -- Bar Chart Example
var ctx = document.getElementById("myBarChartHistoricoCliente");
var myLineChart = new Chart(ctx, {
   
  type: 'bar',
  data: {
    labels: [MesesSellOutGeral[0],MesesSellOutGeral[1],MesesSellOutGeral[2],MesesSellOutGeral[3],MesesSellOutGeral[4],MesesSellOutGeral[5]],
    datasets: [{
      label: "Valor(R$)",
      backgroundColor: "rgb(0,70,101)",
      borderColor: "rgb(0,70,101)",
      data: [parseInt(mes5),parseInt(mes4),parseInt(mes3),parseInt(mes2),parseInt(mes1),parseInt(media)]
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