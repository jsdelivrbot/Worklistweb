<?php
// chamar criador de sessão de login DF
if (!isset($_SESSION)) {

    session_start();
}
if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}

echo '<script>var mes_1= "' . $_SESSION['MES1'] . '";</script>';
echo '<script>var mes_2= "' . $_SESSION['MES2'] . '";</script>';
echo '<script>var mes_3= "' . $_SESSION['MES3'] . '";</script>';
echo '<script>var mes_4= "' . $_SESSION['MES4'] . '";</script>';
echo '<script>var mes_5= "' . $_SESSION['MES5'] . '";</script>';
?>

<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="O Worklist é um sistema de monitoramento de indicadores de vendas." content="">
        <meta name="Worknet" content="Worknet">
        <link rel="icon" href="img/favicon.png"> <!--Coloca Icone da aba do navegador-->
        <title>Mix de Produtos</title>
        <!-- Bootstrap core CSS-->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!-- Custom fonts for this template-->
        <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <!-- Page level plugin CSS-->
        <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
        <!-- Custom styles for this template-->

        <link href="css/sb-admin.css" rel="stylesheet">
        <link href="css/carteira.css" rel="stylesheet">
        <script src="js/jquery-3.2.1.js"></script>
        <script type="text/javascript">

            $(document).ready(function () {
                $('#btn_atualizar').click(function () {


         //excluir tabela mix por industria e recriar para funcionar a ordenação de dados                  
         $('#mixindustrias').remove();
                    $('#mixIndustriasAtual').append(
                            '<table class="table table-bordered dataTable no-footer" id="mixindustrias" width="100%" cellspacing="0">' +
                            '<thead>' +
                            '<tr class="cabecalho_indicador" >' +
                            '<th align="center">INDUSTRIA</th>' +
                            '<th align="center">MIX</th>' +
                            '<th>' + mes_5 + '</th>' +
                            '<th>' + mes_4 + '</th>' +
                            '<th>' + mes_3 + '</th>' +
                            '<th>' + mes_2 + '</th>' +
                            '<th>' + mes_1 + '</th>' +
                            '<th align="center">PER.</th>' +
                            '</tr>' +
                            '</thead>' +
                            '<tbody id="preenchermix_industrias">' +
                            '</tbody>   ' +
                            '</table>');
                    $('#graficomix').remove();
                    $('#mix').append('<canvas id="graficomix" width="100" height="50"></canvas>'); 
                    
         //excluir tabela carteira de clientes e recriar para funcionar a ordenação de dados
         
                    $('#mixclientes').remove();
                    $('#mixClienteAtualiza').append(
                            '<table class="table table-bordered dataTable no-footer" id="mixclientes" width="100%" cellspacing="0">' +
                            '<thead>' +
                            '<tr class="cabecalho_indicador" >' +
                            '<th align="center">ID</th>' +
                            '<th align="center">CLIENTE</th>' +
                            '<th>' + mes_5 + '</th>' +
                            '<th>' + mes_4 + '</th>' +
                            '<th>' + mes_3 + '</th>' +
                            '<th>' + mes_2 + '</th>' +
                            '<th>' + mes_1 + '</th>' +
                            '<th align="center">PER.</th>' +
                            '</tr>' +
                            '</thead>' +
                            '<tbody id="preenchermix_clientes">' +
                            '</tbody>   ' +
                            '</table>');
                    
                    $('#graficomix').remove();
                    $('#mix').append('<canvas id="graficomix" width="100" height="50"></canvas>');
                    
                    atualizamix();
                    atualizaMixIndustrias();
                    atualizaMixClientes();

                });
                
                function salvaPlanilha1() {
                    var htmlPlanilha = "<table>" + document.getElementById("mixclientes").innerHTML + "</table>";

                    var htmlBase64 = btoa(htmlPlanilha);
                    var link = "data:application/vnd.ms-excel;base64," + htmlBase64;

                    window.open(link);
                }
                
                function salvaPlanilha2() {
                    var htmlPlanilha = "<table>" + document.getElementById("mixindustrias").innerHTML + "</table>";

                    var htmlBase64 = btoa(htmlPlanilha);
                    var link = "data:application/vnd.ms-excel;base64," + htmlBase64;

                    window.open(link);
                }
                
                $('#exportar1').click(function () {
                    salvaPlanilha1();
                });
                
                $('#exportar2').click(function () {
                    salvaPlanilha2();
                });

                function atualizamix() {
                    var industria = $(".select_indus").val();
                    var cliente = $(".select_cliente").val();
                    $.post('classes/sql_mix.php', {industriaselecionada: industria, cliente: cliente}, function (data) {
                        $("#preenchermix").empty();
                        $("#preenchermix").html(data);
                    })
                }


                function atualizaMixClientes() {
                    var industria = $(".select_indus").val();
                    var cliente = $(".select_cliente").val();
                    if (cliente != 1) {
                        $("#mixcli").hide();
                    } else {
                        $("#mixcli").show();
                        $.post('classes/sql_mix_clientes.php', {industriaselecionada: industria, cliente: cliente}, function (data) {
                            $("#preenchermix_clientes").empty();
                            $("#preenchermix_clientes").html(data);
                        })
                    }
                }

                function atualizaMixIndustrias() {
                    var industria = $(".select_indus").val();
                    var cliente = $(".select_cliente").val();
                    if (industria != 1) {
                        $("#mixindus").hide();
                    } else {
                        $("#mixindus").show();
                        $.post('classes/sql_mix_industrias.php', {industriaselecionada: industria, cliente: cliente}, function (data) {
                            $("#preenchermix_industrias").empty();
                            $("#preenchermix_industrias").html(data);
                        })
                    }
                }

                $("#pesquisacli").keyup(function () {
                    var index = $(this).parent().index();
                    var nth = "#mixclientes td:nth-child(" + (index + 1).toString() + ")";
                    var valor = $(this).val().toUpperCase();
                    $("#mixclientes tbody tr").show();
                    $(nth).each(function () {
                        if ($(this).text().toUpperCase().indexOf(valor) < 0) {
                            $(this).parent().hide();
                        }
                    });
                });

                $("#pesquisacli").blur(function () {
                    $(this).val("");
                });

                $("#pesquisaindus").keyup(function () {
                    var index = $(this).parent().index();
                    var nth = "#mixindustrias td:nth-child(" + (index + 0).toString() + ")";
                    var valor = $(this).val().toUpperCase();
                    $("#mixindustrias tbody tr").show();
                    $(nth).each(function () {
                        if ($(this).text().toUpperCase().indexOf(valor) < 0) {
                            $(this).parent().hide();
                        }
                    });
                });

                $("#pesquisaindus").blur(function () {
                    $(this).val("");
                });

$('.js-example-responsive').select2();

            })
        </script>
    </head>

    <body class="fixed-nav sticky-footer bg-dark" id="page-top">
        <!-- Navigation-->
        <?PHP
        include_once 'menu.php';
        ?>
        <div class="content-wrapper">
            <div class="container-fluid"> 
                <div class="container"><!-- Inicio do divisão framework -->
                    <div class="row"> <!-- Inicio row framework -->
                        <div class="col-xl-7 col-lg-9 col-md-12 col-sm-12 "> <!-- Inicio coluna framework -->


                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-filter"></i> Filtro de Clientes
                                </div>
                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">

                                        <span class="legenda_indus"> </span>

                                        <select class="js-example-responsive select_cliente">
                                            <option value="1">TODOS</option>
                                            <?php
                                            require_once 'classes/bd.php';
                                            $bd = new bd();
                                            $queryindustrias = "select
                                                    idcli,
                                                    razao_social
                                                    from 
                                                    sys_clientes a,
                                                    sys_carteira b
                                                    where
                                                    a.idcli = b.idcliente and
                                                    b.idvendedor = " . $_SESSION['idrepresentante'] .
                                                    " ORDER BY razao_social";

                                            $resultindustrias = pg_query($queryindustrias);

                                            while ($rowindustrias = pg_fetch_array($resultindustrias)) {
                                                echo "<option value ='" . $rowindustrias['idcli'] . "'>" . $rowindustrias['razao_social'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                        <i class="fa fa-refresh" id="btn_atualizar"></i>



                                    </div>
                                </div>
                                <div class="card-footer small text-muted">

                                </div>
                            </div>


                            <!-- Example DataTables Card-->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-filter"></i> Filtro de Indústria
                                </div>
                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <span class="legenda_indus"></span>
                                        <select class="js-example-responsive select_indus" >
                                            <option value="1">TODAS</option>
                                            <?php
                                            require_once 'classes/bd.php';
                                            $bd = new bd();
                                            $queryindustrias = "SELECT IDINDUSTRIA,
                                FANTASIA AS INDUSTRIA
                                FROM SYS_INDUSTRIAS
                                ORDER BY FANTASIA";

                                            $resultindustrias = pg_query($queryindustrias);

                                            while ($rowindustrias = pg_fetch_array($resultindustrias)) {
                                                echo "<option value ='" . $rowindustrias['idindustria'] . "'>" . $rowindustrias['industria'] . "</option>";
                                            }
                                            ?>
                                        </select>


                                    </div>
                                </div>
                                <div class="card-footer small text-muted">

                                </div>
                            </div>






                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-calendar"></i> Indicador de Mix de Produtos
                                    
                                </div>
                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">

                                        <table class="table table-bordered dataTable no-footer" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr class="cabecalho_indicador" >
                                                    <th align="center">MIX</th>
                                                    <th align="center"><?php echo $_SESSION['MES5'] ?></th>
                                                    <th align="center"><?php echo $_SESSION['MES4'] ?></th>
                                                    <th align="center"><?php echo $_SESSION['MES3'] ?></th>
                                                    <th align="center"><?php echo $_SESSION['MES2'] ?></th>
                                                    <th align="center"><?php echo $_SESSION['MES1'] ?></th>
                                                    <th align="center">PER.</th>
                                                </tr>
                                            </thead>

                                            <tbody id="preenchermix">

                                            </tbody>                               
                                        </table>

                                    </div>
                                    <div class="card-body" id="mix">
                                        <canvas id="graficomix" width="100" height="50"></canvas>
                                    </div>
                                </div>
                                <div class="card-footer small text-muted">Atualizado Hoje</div>
                            </div>


                            <div class="card mb-3" id="mixindus">
                                <div class="card-header">
                                    <i class="fa fa-calendar"></i> Indicador Mix por Industria
                                    <i class="fa fa-file-excel-o" id="exportar2"></i>
                                </div>

                                <div class="card-body">
                                    <input type="text" id="pesquisaindus" placeholder="Pesquisar Cliente" style="width: 100%;height: 25px;">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer" id="mixIndustriasAtual">

                                        <table class="table table-bordered dataTable no-footer" id="mixindustrias" width="100%" cellspacing="0">
                                            <thead>
                                                <tr class="cabecalho_indicador" >
                                                    <th align="center">INDUSTRIA</th>
                                                    <th align="center">MIX</th>
                                                    <th align="center"><?php echo $_SESSION['MES5'] ?></th>
                                                    <th align="center"><?php echo $_SESSION['MES4'] ?></th>
                                                    <th align="center"><?php echo $_SESSION['MES3'] ?></th>
                                                    <th align="center"><?php echo $_SESSION['MES2'] ?></th>
                                                    <th align="center"><?php echo $_SESSION['MES1'] ?></th>
                                                    <th align="center">PER.</th>
                                                </tr>
                                            </thead>
                                            <tbody id="preenchermix_industrias">
                                            </tbody>                               
                                        </table>

                                    </div>
                                </div>
                                <div class="card-footer small text-muted">Atualizado Hoje</div>
                            </div>

                            <div class="card mb-3" id="mixcli">
                                <div class="card-header">
                                    <i class="fa fa-calendar"></i> Indicador Mix por cliente
                                    <i class="fa fa-file-excel-o" id="exportar1"></i>
                                </div>

                                <div class="card-body">
                                    <input type="text" id="pesquisacli" placeholder="Pesquisar Cliente" style="width: 100%;height: 25px;">
                                    
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer" id="mixClienteAtualiza">
                                        <table class="table table-bordered dataTable no-footer" id="mixclientes" width="100%" cellspacing="0">
                                            <thead>
                                                <tr class="cabecalho_indicador" >
                                                    <th align="center">ID</th>
                                                    <th align="center">CLIENTE</th>
                                                    <th align="center"><?php echo $_SESSION['MES5'] ?></th>
                                                    <th align="center"><?php echo $_SESSION['MES4'] ?></th>
                                                    <th align="center"><?php echo $_SESSION['MES3'] ?></th>
                                                    <th align="center"><?php echo $_SESSION['MES2'] ?></th>
                                                    <th align="center"><?php echo $_SESSION['MES1'] ?></th>
                                                    <th align="center">PER.</th>
                                                </tr>
                                            </thead>

                                            <tbody id="preenchermix_clientes">

                                            </tbody>                               
                                        </table>

                                    </div>
                                </div>
                                <div class="card-footer small text-muted">Atualizado Hoje</div>
                            </div>


                        </div><!-- Inicio coluna framework -->
                    </div><!-- Inicio row framework -->
                </div><!-- Inicio divisao framework -->
            </div>
            <footer class="sticky-footer" id="mainNav"> 
                <div class="container">
                    <div class="text-center">
                        <small>Copyright © WorklistWeb - 2017</small>
                    </div>
                </div>
            </footer>
            <!-- Scroll to Top Button-->
            <a class="scroll-to-top rounded" href="#page-top">
                <i class="fa fa-angle-up"></i>
            </a>
            <!-- Logout Modal-->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Deseja Sair?</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">Selecione "Sair" se você estiver pronto para terminar sua sessão atual.</div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                            <a class="btn btn-primary" href="login.php">Sair</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bootstrap core JavaScript-->
            <script src="vendor/jquery/jquery.min.js"></script>
            <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
            <!-- Core plugin JavaScript-->
            <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
            <!-- Page level plugin JavaScript-->
            <script src="vendor/chart.js/Chart.min.js"></script>
            <script src="vendor/datatables/jquery.dataTables.js"></script>
            <script src="vendor/datatables/dataTables.bootstrap4.js"></script>
            <!-- Custom scripts for all pages-->
            <script src="js/sb-admin.min.js"></script>
            <!-- Custom scripts for this page-->
            <script src="js/sbs-admin-datatables.min.js"></script>
            <script src="js/jquery.tablesorter.min.js"></script>
            <link href="css/selec2.min.css" rel="stylesheet" />
            <script src="js/select2.min.js"></script>

        </div>
    </body>

</html>
