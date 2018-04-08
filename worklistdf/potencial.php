<?php
// chamar criador de sessão de login
session_start();
if ($_SESSION['logado'] != "SIM") {
    header('Location: login.php');
}

require_once 'funcoes/funcoes.php';
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
        <title>Worklist - DF Distribuidora</title>
        <!-- Bootstrap core CSS-->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom fonts for this template-->
        <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <!-- Page level plugin CSS-->
        <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
        <!-- Custom styles for this template-->

        <link href="css/sb-admin.css" rel="stylesheet">
        <link href="css/metas.css" rel="stylesheet">
        <link href="css/carteira.css" rel="stylesheet">
        <link href="css/visaoegarra.css" rel="stylesheet">

        <script src="js/jquery-3.2.1.js"></script>

        <script>
            $(document).ready(function () {
                $("#btn_atualizar").click(function () {
                    atualizaPotencial();
                    buscaDias();
                    atualizaPotencialIndus();

                    $('#myBarChart').remove();
                    $('#grafpotencialatualiza').append('<canvas id="myBarChart" width="100" height="50"></canvas>');
                })

                function atualizaPotencial() {
                    var mes = $("#select_mes").val();
                    $.post('classes/sql_potencial.php', {mes: mes}, function (data) {
                        $("#atualizaPotencialMes").empty();
                        $("#atualizaPotencialMes").html(data);
                    })
                }

                function atualizaPotencialIndus() {
                    var mes = $("#select_mes").val();
                    $.post('classes/sql_potencialindustria.php', {mes: mes}, function (data) {
                        $("#atualizaPotencialIndustria").empty();
                        $("#atualizaPotencialIndustria").html(data);
                    })
                }

                function buscaDias() {
                    var mes = $("#select_mes").val();
                    $.post('classes/sql_buscadias.php', {mes: mes}, function (data) {
                        $("#dias").empty();
                        $("#dias").html(data);
                    })
                }
                
                $('.js-example-responsive').select2();
                
            })
        </script>

    </head>

    <body class="fixed-nav sticky-footer bg-dark" id="page-top">
        <!-- Navigation-->
        <?PHP
        require_once 'menu.php';
        require_once './funcoes/funcoes.php';
        ?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="container"><!-- Inicio do divisão framework -->
                    <div class="row"> <!-- Inicio row framework -->
                        <div class="col-xl-7 col-lg-9 col-md-12 col-sm-12 "> <!-- Inicio coluna framework -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-table"></i> Parâmetros da Análise
                                </div>
                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <select class="js-example-responsive" id="select_mes" style="width: 35%; color: #295ba6; font-weight: bold;">
                                            <?php atualizaMeses(); ?>
                                        </select>                                   
                                        <i class="fa fa-refresh" id="btn_atualizar"></i>
                                    </div>
                                </div>
                            </div>
                            <!-- Example DataTables Card-->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-table"></i> Indicador de Venda
                                </div>
                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <table class="table table-bordered dataTable no-footer" width="100%" cellspacing="0">
                                            <thead>
                                                <tr class="cabecalho_indicador">
                                                    <th>&nbsp;Nome</th>
                                                    <th>&nbsp;Potencial</th>
                                                    <th>&nbsp;Venda</th>
                                                    <th>&nbsp;Obj. Dia</th>
                                                    <th>&nbsp;Projeção</th>
                                                    <th>&nbsp;%Cob</th>
                                                </tr>
                                            </thead>
                                            <tbody id="atualizaPotencialMes">
                                            </tbody>                        
                                        </table>
                                    </div>
                                    <div class="card-body" id="grafpotencialatualiza">
                                        <canvas id="myBarChart" width="100" height="50"></canvas>
                                    </div>
                                </div>
                                <div>
                                    <table class="table table-bordered dataTable no-footer" id="dataTable" width="100%" cellspacing="0" style="text-align: center;">
                                        <tr class="cabecalho_indicador">
                                            <th style="width:33%;">Dias Úteis</th>
                                            <th style="width:33%;">Dias Corridos</th>
                                            <th style="width:33%;">Falta</th>
                                        </tr>
                                        <tr id="dias">
                                        </tr>
                                    </table>
                                </div>
                                <div class="card-footer small text-muted">Atualizado Hoje</div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-table"></i> Indicadores Indústrias
                                </div>
                                <div class=" card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <table class="table table-bordered dataTable no-footer" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr class="cabecalho_indicador">
                                                    <th>Indústria </th>
                                                    <th>Potencial</th>
                                                    <th>Venda</th>
                                                    <th>Obj. Dia</th>
                                                    <th>Projeção</th>
                                                    <th>%Cob</th>
                                                </tr>
                                            </thead>
                                            <tbody id="atualizaPotencialIndustria">
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
            <script src="js/sb-admin-datatables.min.js"></script>
            <link href="css/selec2.min.css" rel="stylesheet" />
            <script src="js/select2.min.js"></script>
        </div>
    </body>

</html>
