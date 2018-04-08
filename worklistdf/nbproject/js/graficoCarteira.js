// Chart.js scripts
// -- Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';
// -- Bar Chart Example
var ctx = document.getElementById("myBarChart");
var myLineChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: arrayMes,
    datasets: [{
      label: "Cnpjs",
      backgroundColor: ["#00778c","rgb(0,70,101)","rgb(0,70,101)","rgb(0,70,101)","rgb(0,70,101)","rgb(0,70,101)","rgb(0,70,101)",],
      borderColor: ["004665","rgb(0,70,101)","rgb(0,70,101)","rgb(0,70,101)","rgb(0,70,101)","rgb(0,70,101)","rgb(0,70,101)",],
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