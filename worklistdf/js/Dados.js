$.ajax({
  type: "POST",
  url: "repositorio_testefinal.php",
  data:{data: []},
  cache: false,

  success: function(result){
    try {
      result = $.parseJSON(result);
      var problemas = result['mes']
      var votos = result['pst']
      var data = {
        labels : problemas,
        datasets : [{
          label: 'Valor',
          data: votos,
          backgroundColor: '#014d65',
          borderColor: '#014d65',
          borderWidth: 1,
        }]
      }

      var ctx = document.getElementById("myBarChart").getContext("2d");
      var myBar = new Chart(ctx, {
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
