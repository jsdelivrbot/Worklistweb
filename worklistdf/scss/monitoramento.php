<?php
// chamar criador de sessão de login
if (!isset($_SESSION)) {

    session_start();
}
if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}
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
        <title>Inadimplência - DF Distribuidora</title>
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
                $('.select_indus').change(function () {
                    atualizaSellout();
                    atualizaPos();
                    atualizaPosInd();
                    $('#myBarChart').remove();
                    $('#graficopositivacao').append('<canvas id="myBarChart" width="100" height="50"></canvas>');
                    
                    $('#myBarChart2').remove();
                    $('#graficosellout').append('<canvas id="myBarChart2" width="100" height="50"></canvas>');
                    
                    $('#graficonivel').remove();
                    $('#nivel').append('<canvas id="graficonivel" width="100" height="50"></canvas>');

                });

                function atualizaPos() {
                    var industria = $(".select_indus").val();
                    $.post('classes/sql_positivacao.php', {industriaselecionada: industria}, function (data) {
                        $("#preencher").empty();
                        $("#preencher").html(data);
                    })
                }
                function atualizaPosInd() {
                    var industria = $(".select_indus").val();
                    $.post('classes/sql_positivacao_industria.php', {industriaselecionada: industria}, function (data2) {
                        $("#preencher_positivacao").empty();
                        $("#preencher_positivacao").html(data2);
                    })
                }
                function atualizaSellout() {
                    var industria2 = $(".select_indus").val();
                    $.post('classes/sql_sellout.php', {industriaselecionada2: industria2}, function (data3) {
                         mes11 = 0;
                         mes22 = 0;
                         mes33 = 0;
                         mes44 = 0;
                         mes55 = 0;
                         media1 = 0;
                        $("#selloutatualiza").empty();
                        $("#selloutatualiza").html(data3);
                    })
                }

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
                <!-- Example DataTables Card-->
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fa fa-table"></i> Parâmetros da Análise
                    </div>
                    <div class="card-body">
                        <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">

                            <span class="legenda_indus">Indústria:</span>

                            <select class="select_indus">
                                <option value="1">TODAS</option>
                                <?php
                                require_once 'classes/bd.php';
                                $bd = new bd();
                                $queryindustrias = "SELECT IDINDUSTRIA,
                                FANTASIA AS INDUSTRIA
                                FROM SYS_INDUSTRIAS
                                WHERE FANTASIA <> '' AND
                                LINHA = 'FARMA'";

                                $resultindustrias = pg_query($queryindustrias);

                                while ($rowindustrias = pg_fetch_array($resultindustrias)) {
                                    echo "<option value ='" . $rowindustrias['idindustria'] . "'>" . $rowindustrias['industria'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer small text-muted">Atualizado Hoje

                    </div>
                </div>



                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fa fa-table"></i> Indicador de Positivação Mensal
                    </div>
                    <div class="card-body">
                        <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                            <table class="table table-bordered dataTable no-footer" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr class="cabecalho_indicador" >
                                        <th align="center">Cart.</th>
                                        <th align="center">Julho</th>
                                        <th align="center">Agosto</th>
                                        <th align="center">Setembro</th>
                                        <th align="center">Outubro</th>
                                        <th align="center">Novembro</th>
                                        <th align="center">Período</th>
                                    </tr>
                                </thead>

                                <tbody id="preencher">
                                   
                                </tbody>                               
                            </table>

                        </div>
                    </div>
                    <div class="card-footer small text-muted">Atualizado Hoje</div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <!-- Example Bar Chart Card-->
                        <div class="card mb-3">
                            <div class="card-header">
                                <i class="fa fa-bar-chart"></i> Gráfico de Positivação Mês Atual
                            </div>
                            <div class="card-body" id="graficopositivacao">
                                <canvas id="myBarChart" width="100" height="50"></canvas>
                            </div>
                            <div class="card-footer small text-muted">Atualizado Hoje</div>
                        </div>
                    </div>
                </div> 

                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fa fa-table"></i> Indicador de Sell-Out Mensal
                    </div>
                    <div class="card-body">
                        <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                            <table class="table table-bordered dataTable no-footer" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr class="cabecalho_indicador">
                                        <th>Julho</th>
                                        <th>Agosto</th>
                                        <th>Setembro</th>
                                        <th>Outubro</th>
                                        <th>Novembro</th>
                                        <th>Média</th>
                                    </tr>
                                </thead>
                                <tbody id="selloutatualiza">                    
                                    
                                </tbody>                               
                            </table>
                        </div>
                    </div>
                    <div class="card-footer small text-muted">Atualizado Hoje</div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <!-- Example Bar Chart Card-->
                        <div class="card mb-3">
                            <div class="card-header">
                                <i class="fa fa-bar-chart"></i> Gráfico de Sell-Out Mensal
                            </div>
                            <div class="card-body" id="graficosellout">
                                <canvas id="myBarChart2" width="100" height="50"></canvas>
                            </div>
                            <div class="card-footer small text-muted">Atualizado Hoje</div>
                        </div>
                    </div>
                </div> 

                <!-- Example DataTables Card-->
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fa fa-table"></i> Relatório de Positivação Mensal
                    </div>
                    <div class="card-body">
                        <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                            <table class="table table-bordered dataTable no-footer " id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr class="cabecalho_indicador">
                                        <th>Codigo</th>
                                        <th>Razão Social</th>
                                        <th>FQ&nbsp;</th>
                                        <th>Média</th>
                                        <th>Novembro</th>
                                        <th>&nbspST</th>
                                    </tr>
                                </thead>

                                <tbody id="preencher_positivacao">
                                    
                                </tbody>                               
                            </table>

                        </div>
                    </div>
                    <div class="card-footer small text-muted">Atualizado Hoje</div>
                </div>
                
                <div class="row">
                    <div class="col-lg-6">
                        <!-- Example Bar Chart Card-->
                        <div class="card mb-3">
                            <div class="card-header">
                                <i class="fa fa-bar-chart"></i> CNPJ's por frequência
                            </div>
                            <div class="card-body" id="nivel">
                                <canvas id="graficonivel" width="100" height="50"></canvas>
                            </div>
                            <div class="card-footer small text-muted">Atualizado Hoje</div>
                        </div>
                    </div>
                </div> 
                

            </div>

            <footer class="sticky-footer" id="mainNav">
                <div class="container">
                    <div class="text-center">
                        <small>Copyright © Worknet - 2017</small>
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



        </div>
    </body>

</html>
