// Chart.js scripts
// -- Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';
// -- Bar Chart Example
var ctx = document.getElementById("myBarChart2");
var myLineChart = new Chart(ctx, {
   
  type: 'bar',
  data: {
    labels: [MesesSellOut[0],MesesSellOut[1],MesesSellOut[2],MesesSellOut[3],MesesSellOut[4],MesesSellOut[5]],
    datasets: [{
      label: "Valor(R$)",
      backgroundColor: "#246a65",
      borderColor: "#246a65",
      data: [parseInt(mes55),parseInt(mes44),parseInt(mes33),parseInt(mes22),parseInt(mes11),parseInt(media1)]
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