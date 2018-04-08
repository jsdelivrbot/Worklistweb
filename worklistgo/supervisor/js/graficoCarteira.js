// Chart.js scripts
// -- Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';
// -- Bar Chart Example
var ctx = document.getElementById("myBarChart");
var myLineChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: [Meses[0],Meses[1],Meses[2],Meses[3],Meses[4],Meses[5],Meses[6]],
    datasets: [{
      label: "Cnpjs",
      backgroundColor: ["#246a65","#246a65","#246a65","#246a65","#246a65","#246a65","#246a65",],
      borderColor: ["004665","#246a65","#246a65","#246a65","#246a65","#246a65","#246a65",],
      data: [parseInt(cart),parseInt(mes5),parseInt(mes4),parseInt(mes3),parseInt(mes2),parseInt(mes1),parseInt(positivacao)]
      
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