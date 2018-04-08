// Chart.js scripts
// -- Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';
// -- Bar Chart Example
var ctx = document.getElementById("graficomix");
var myLineChart = new Chart(ctx, {
   
  type: 'bar',
  data: {
    labels: [MesesMix[0],MesesMix[1],MesesMix[2],MesesMix[3],MesesMix[4],MesesMix[5],MesesMix[6]],
    datasets: [{
      label: "Valor(R$)",
      backgroundColor: "rgb(0,70,101)",
      borderColor: "rgb(0,70,101)",
      data: [parseInt(mix),parseInt(mes5),parseInt(mes4),parseInt(mes3),parseInt(mes2),parseInt(mes1),parseInt(periodo)]
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