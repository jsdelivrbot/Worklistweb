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
        <title>Worklist - Potenciais</title>
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
                });
                
                $("#btn_pedidos").click(function () {
                    DetectorPedidos();
                });
                
                function salvaPlanilha1() {
                    var htmlPlanilha = "<table>" + document.getElementById("rca").innerHTML + "</table>";
                    var htmlBase64 = btoa(htmlPlanilha);
                    var link = "data:application/vnd.ms-excel;base64," + htmlBase64;

                    window.open(link);
                }

                $('#exportar1').click(function () {
                    salvaPlanilha1();
                });
                
                function salvaPlanilha2() {
                    var htmlPlanilha = "<table>" + document.getElementById("industrias").innerHTML + "</table>";
                    var htmlBase64 = btoa(htmlPlanilha);
                    var link = "data:application/vnd.ms-excel;base64," + htmlBase64;

                    window.open(link);
                }

                $('#exportar2').click(function () {
                    salvaPlanilha2();
                });
                
                function salvaPlanilha3() {
                    var htmlPlanilha = "<table>" + document.getElementById("detector").innerHTML + "</table>";
                    var htmlBase64 = btoa(htmlPlanilha);
                    var link = "data:application/vnd.ms-excel;base64," + htmlBase64;

                    window.open(link);
                }

                $('#exportar3').click(function () {
                    salvaPlanilha3();
                });
                
                
                
                function atualizaPotencial() {//ajustado para a nova estrutura de supervisao
                    var mes = $("#select_mes").val();
                    var rca = $(".select_rca").val();
                    var industria = $(".select_industria").val();
                    $.post('classes/sql_potencial.php', {mes:mes,rca:rca,industria:industria}, function (data) {
                        $("#atualizaPotencialMes").empty();
                        $("#atualizaPotencialMes").html(data);
                    })
                }

                function atualizaPotencialIndus() {//ajustado para a nova estrutura de supervisao
                    var mes = $("#select_mes").val();
                    var rca = $(".select_rca").val();
                    $.post('classes/sql_potencialindustria.php', {mes: mes,rca: rca}, function (data) {
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
                
                function DetectorPedidos() {//ajustado para a nova estrutura de supervisao
                    var dtini = $(".select_dataini").val();
                    var dtfim = $(".select_datafim").val();
                    $.post('classes/sql_pedidos.php', {dtini: dtini, dtfim: dtfim}, function (data) {
                        $("#pedidos").empty();
                        $("#pedidos").html(data);
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
                                        <select class="js-example-responsive" id="select_mes" style="width: 35%; color: #185752; font-weight: bold;">
                                            <?php atualizaMeses(); ?>
                                        </select>                                   
                                        <i class="fa fa-refresh" id="btn_atualizar"></i>
                                    </div>
                                </div>
                                
                                
                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <span class="legenda_indus"></span>
                                        <select class="js-example-responsive select_industria" style="width: 100%;">
                                            <option value="5933">TODAS AS INDUSTRIAS</option>
                                            <?php
                                            require_once 'classes/bd.php';
                                            $bd = new bd();
                                            $queryindustria = " select distinct
                    b.idindustria,  
                    c.razaosocial                                          
                    from 
                    sys_metas b,
                    sys_industrias c,
                    sys_acessos a
                    where
                    a.idrepresentante = b.idrca and
                    b.idindustria = c.idindustria and
                    b.datameta >='01/01/2018' and
                    a.idrepresentante in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
                                            $resultindustria = pg_query($queryindustria);

                                            while ($rowindustria = pg_fetch_array($resultindustria)) {
                                                echo "<option value ='" . $rowindustria['idindustria'] . "'>" . $rowindustria['razaosocial'] . "</option>";
                                            }
                                            ?>
                                        </select>                       
                                        

                                    </div>
                                </div>
                                
                                
                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <span class="legenda_indus"></span>
                                        <select class="js-example-responsive select_rca" style="width: 100%;">
                                          <option value="1">TODOS</option>
                                            <?php
                                            require_once 'classes/bd.php';
                                            $bd = new bd();
                                            $queryvendedor = "SELECT distinct 
a.idvendedor,
a.apelido
FROM 
sys_vendedores a,
sys_supervisao c
where 
a.idvendedor=c.idrca and
c.idsupervisor = '" . $_SESSION['idrepresentante']."'
ORDER BY a.apelido";
                                            $resultvendedor = pg_query($queryvendedor);

                                            while ($rowvendedor = pg_fetch_array($resultvendedor)) {
                                                echo "<option value ='" . $rowvendedor['idvendedor'] . "'>" . $rowvendedor['apelido'] . "</option>";
                                            }
                                            ?>
                                        </select>                       
                                        

                                    </div>
                                </div>
                            </div>
                            <!-- Example DataTables Card-->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-table"></i> Indicador de Venda
                                    <i class="fa fa-file-excel-o" id="exportar1"></i>
                                </div>
                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <table class="table table-bordered dataTable no-footer" width="100%" cellspacing="0" id="rca">
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
                                    <table class="table table-bordered dataTable no-footer" id="diauteis" width="100%" cellspacing="0" style="text-align: center;">
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
                                    <i class="fa fa-file-excel-o" id="exportar2"></i>
                                </div>
                                <div class=" card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <table class="table table-bordered dataTable no-footer" id="industrias" width="100%" cellspacing="0">
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
                            
                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-table"></i> Detector de envio de pedidos
                                    <i class="fa fa-file-excel-o" id="exportar3"></i>
                                </div>
                                <div class=" card-body">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-8" style="border: 1px solid rgba(0,0,0,.125);">
                                                <div class="container" >
                                                    <div class="row">
                                                        <div class="col-12" style="border: 1px solid rgba(0,0,0,.125);">
                                                            <select class="js-example-responsive select_dataini" style="width: 100%;">
                                                                <?php
                                                                require_once 'classes/bd.php';
                                                                $bd = new bd();
                                                                $querydata = "select distinct a.mes from sys_pedidos a order by a.mes asc";
                                                                $resultdata = pg_query($querydata);

                                                                while ($rowdata = pg_fetch_array($resultdata)) {
                                                                    echo "<option value ='" . $rowdata['mes'] . "'>" . $rowdata['mes'] . "</option>";
                                                                }
                                                                ?>
                                                            </select>  
                                                        </div>
                                                        <div class="col-12" style="border: 1px solid rgba(0,0,0,.125);">
                                                            <select class="js-example-responsive select_datafim" style="width: 100%;">
                                                                <?php
                                                                require_once 'classes/bd.php';
                                                                $bd = new bd();
                                                                $querydata = "select distinct a.mes from sys_pedidos a order by a.mes asc";
                                                                $resultdata = pg_query($querydata);

                                                                while ($rowdata = pg_fetch_array($resultdata)) {
                                                                    echo "<option value ='" . $rowdata['mes'] . "'>" . $rowdata['mes'] . "</option>";
                                                                }
                                                                ?>
                                                            </select>  
                                                        </div>
                                                    </div>
                                                </div>     
                                            </div>
                                            <div align="center" class="col-4"  style="border: 1px solid rgba(0,0,0,.125);">

                                                <a  class="btn btn-sm btn-primary" id="btn_pedidos">
                                                    <span class="fa fa-refresh" ></span>
                                                </a>


                                               <!-- <i class="fa fa-refresh " id="btn_pedidos"></i>-->
                                            </div>
                                        </div>
                                    </div>

                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <table class="table table-bordered dataTable no-footer" id="detector" width="100%" cellspacing="0">
                                            <thead>
                                                <tr class="cabecalho_indicador">
                                                    <th>ID </th>
                                                    <th>M.E.I</th>
                                                    <th>PEDIDOS</th>
                                                    <th>CLIENTES</th>
                                                    <th>VALOR</th>
                                                </tr>
                                            </thead>
                                            <tbody id="pedidos">
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
                            <a class="btn btn-primary" href="../login.php">Sair</a>
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
