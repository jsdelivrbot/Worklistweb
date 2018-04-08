

<html>

    <head>
   

        <script src="js/jquery-3.2.1.js"></script>

        <script>
            $(document).ready(function () {
                $('#botao').click(function(){
                    var nome = $('teste').val();
                   alert(nome); 
                });
                $('.js-example-basic-multiple').select2();
            });
        </script>
    </head>



    <body>

        
        
<select class="js-example-basic-multiple" name="states[]" multiple="multiple" ID="teste">
  <option value="AL">Alabama</option>
  <option value="WY">Wyoming</option>
  <option value="AD">LAZARO</option>
</select>
        
        <input type="button" id="botao" value="buscar">
        
    </body>
</html>

        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script> 
