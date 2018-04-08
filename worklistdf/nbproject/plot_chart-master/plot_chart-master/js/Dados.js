// Chart.js scripts
// -- Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';
// -- Bar Chart Example

$.ajax({
  type: "POST",
  url: "repositorio.php",
  data:{data: []},
  cache: false,

  success: function(result){
    try {
      result = $.parseJSON(result);
      var problemas = result['problema']
      var votos = result['votos']
      var data = {
        labels : problemas,
        datasets : [{
          label: 'Valor',
          data: votos,
          backgroundColor: ["rgb(0,70,101)"],
          borderColor: "rgb(0,70,101)",
          borderWidth: 1,
        }],
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
      }]
    },
    legend: {
      display: true
    }
  }
      },
      

   var ctx = document.getElementById("canvas");
      var myLineChart = new Chart(ctx, {
        type: 'bar',
        data: data,
        responsive : true
      });
    } catch(err) {
      $("#erros").append(err.message);
    }
  }, error: function(result) {
    $("#erros").append(result);
  }
});
