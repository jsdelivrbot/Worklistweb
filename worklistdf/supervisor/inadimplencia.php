<?php
// chamar criador de sessão de login
session_start();
if ($_SESSION['logado'] != "SIM") {
    header('Location: login.php');
}

require_once 'funcoes/funcoes.php';

require_once 'classes/bd.php';
$bd = new bd();
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
        <title>Inadimplência - Goías Saúde</title>
        <!-- Bootstrap core CSS-->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom fonts for this template-->
        <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <!-- Page level plugin CSS-->
        <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
        <!-- Custom styles for this template-->

        <link href="css/sb-admin.css" rel="stylesheet">
        <link href="css/metas.css" rel="stylesheet">
        <script src="js/jquery-3.2.1.js"></script>

        <script type="text/javascript">

            $(document).ready(function () {
                $('#btn_atualizar').click(function () {
                    var idcli = $("#selectCliente").val();
                    $('#detalhe_inad').remove();
                    $('#div_inad').append(
                            ' <table class="table table-bordered dataTable no-footer" id="detalhe_inad" width="100%" cellspacing="0">' +
                            ' <thead>' +
                            ' <tr class = "cabecalho_indicador">' +
                            ' <th> Cliente </th>' +
                            ' <th> Nota </th>' +
                            ' <th> Vencimento </th>' +
                            ' <th> Atraso </th>' +
                            ' <th> Valor </th>' +
                            ' </tr>' +
                            ' </thead>' +
                            ' <tbody id = "inad_detalhada">' +
                            ' </tbody>    ' +
                            ' </table>');
                    if (idcli == 1) {
                        atualizaInadCidade();
                        $("#inad_cidade").show();
                        $("#inadimplenciagrafico2").show();
                    } else {
                        $("#inad_cidade").hide();
                        $("#inadimplenciagrafico2").hide();

                    }

                    atualizaInadClientes();
                    atualizaInadDetalhada();
                });
                function atualizaInadCidade() {
                    var idcli = $("#selectCliente").val();
                    var rca = $(".select_rca").val();
                    if (rca == 1) {
                        $.post('classes/sql_inadimplencia_cidade.php', {idcli: idcli, rca: rca}, function (data) {
                            $("#preencherinadcidade").empty();
                            $("#preencherinadcidade").html(data);
                        })
                    } else {
                        $.post('classes/sql_inadimplencia_cidade.php', {idcli: idcli, rca: rca}, function (data) {
                            $("#preencherinadcidade").empty();
                            $("#preencherinadcidade").html(data);
                        })
                        $('#graficoinad').remove();
                        $('#inadimplenciagrafico').append('<canvas id="graficoinad" width="100" height="50"></canvas>');
                    }
                }

                function atualizaInadClientes() {
                    var idcli = $("#selectCliente").val();
                    var rca = $(".select_rca").val();
                    $.post('classes/sql_inadimplencia_cliente.php', {idcli: idcli, rca: rca}, function (data) {
                        $("#inad_clientes").empty();
                        $("#inad_clientes").html(data);
                    })
                }
                function atualizaInadDetalhada() {
                    var idcli = $("#selectCliente").val();
                    var rca = $(".select_rca").val();
                    $.post('classes/sql_inadimplencia_detalhada.php', {idcli: idcli, rca: rca}, function (data) {
                        $("#inad_detalhada").empty();
                        $("#inad_detalhada").html(data);
                    })
                }

                $("#pesquisacodcli").keyup(function () {
                    var index = $(this).parent().index();
                    var nth = "#div_inad td:nth-child(" + (index + 0).toString() + ")";
                    var valor = $(this).val().toUpperCase();
                    $("#div_inad tbody tr").show();
                    $(nth).each(function () {
                        if ($(this).text().toUpperCase().indexOf(valor) < 0) {
                            $(this).parent().hide();
                        }
                    });
                });

                $("#pesquisacodcli").blur(function () {
                    $(this).val("");
                });

                $('.js-example-responsive').select2();

                function salvaPlanilha1() {
                    var htmlPlanilha = "<table>" + document.getElementById("inadcidade").innerHTML + "</table>";
                    var htmlBase64 = btoa(htmlPlanilha);
                    var link = "data:application/vnd.ms-excel;base64," + htmlBase64;

                    window.open(link);
                }

                $('#exportar1').click(function () {
                    salvaPlanilha1();
                });
                
                function salvaPlanilha2() {
                    var htmlPlanilha = "<table>" + document.getElementById("inadcli").innerHTML + "</table>";
                    var htmlBase64 = btoa(htmlPlanilha);
                    var link = "data:application/vnd.ms-excel;base64," + htmlBase64;

                    window.open(link);
                }

                $('#exportar2').click(function () {
                    salvaPlanilha2();
                });
                
                function salvaPlanilha3() {
                    var htmlPlanilha = "<table>" + document.getElementById("detalhe_inad").innerHTML + "</table>";
                    var htmlBase64 = btoa(htmlPlanilha);
                    var link = "data:application/vnd.ms-excel;base64," + htmlBase64;

                    window.open(link);
                }

                $('#exportar3').click(function () {
                    salvaPlanilha3();
                });
                
            });
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
                            <!-- Example DataTables Card-->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-filter"></i> Filtro de Clientes
                                </div>

                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <span class="legenda_indus"></span>
                                        <select class="js-example-responsive select_rca">
                                            <option value="1">TODOS OS RCAS</option>
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
c.idsupervisor = '" . $_SESSION['idrepresentante'] . "'
ORDER BY a.apelido";
                                            $resultvendedor = pg_query($queryvendedor);

                                            while ($rowvendedor = pg_fetch_array($resultvendedor)) {
                                                echo "<option value ='" . $rowvendedor['idvendedor'] . "'>" . $rowvendedor['apelido'] . "</option>";
                                            }
                                            ?>
                                        </select>                       
                                        <i class="fa fa-refresh" id="btn_atualizar"></i>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">

                                        <span class="legenda_indus"> </span>

                                        <select class="js-example-responsive" id="selectCliente">
                                            <option value="1">TODOS OS CLIENTES</option>
                                            <?php
                                            require_once 'classes/bd.php';
                                            $bd = new bd();
                                            $queryindustrias = " SELECT distinct
    c.idcli,
    c.razao_social
    FROM 
    sys_inadimplencia a,
    sys_acessos b,
    sys_clientes c
    WHERE
    a.idvendedor=b.idrepresentante and
    a.idcli = c.idcli and
    a.STATUS = 'VENCIDO' and
    b.idrepresentante in (select idrca from sys_supervisao where idsupervisor = " . $_SESSION['idrepresentante'] . " order by idrca) ";

                                            $resultindustrias = pg_query($queryindustrias);

                                            while ($rowindustrias = pg_fetch_array($resultindustrias)) {
                                                echo "<option value ='" . $rowindustrias['idcli'] . "'>" . $rowindustrias['razao_social'] . "</option>";
                                            }
                                            ?>
                                        </select>




                                    </div>
                                </div>

                                <div class="card-footer small text-muted">

                                </div>
                            </div>



                            <!-- Example DataTables Card-->
                            <div class="card mb-3" id="inad_cidade">
                                <div class="card-header">
                                    <i class="fa fa-table"></i> Indicador de Inadimplência Geral
                                    <i class="fa fa-file-excel-o" id="exportar1"></i>
                                </div>
                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <table class="table table-bordered dataTable no-footer" id="inadcidade" width="100%" cellspacing="0">
                                            <thead>
                                                <tr class="cabecalho_indicador">
                                                    <th style="width: 40%;
                                                        ">Cidade</th>
                                                    <th style="width: 25%;
                                                        ">Carteira</th>
                                                    <th style="width: 25%;
                                                        ">Vencido</th>
                                                    <th style="width: 10%;
                                                        ">%Inad</th>
                                                </tr>
                                            </thead>

                                            <tbody id="preencherinadcidade">

                                            </tbody>                               
                                        </table>

                                    </div>
                                </div>
                                <div class="card-footer small text-muted">Atualizado Hoje</div>
                            </div>


                            <div class="card mb-3" id="inadimplenciagrafico2">
                                <div class="card-header">
                                    <i class="fa fa-calendar"></i> Gráfico de Inadimplência Geral
                                </div>
                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                    </div>
                                    <div class="card-body" id="inadimplenciagrafico">
                                        <canvas id="graficoinad" width="100" height="50"></canvas>
                                    </div>
                                </div>
                                <div class="card-footer small text-muted">Atualizado Hoje</div>
                            </div>

                            <!-- Example DataTables Card-->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-table"></i> Inadimplência por cliente
                                    <i class="fa fa-file-excel-o" id="exportar2"></i>
                                </div>
                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <table class="table table-bordered dataTable no-footer" id="inadcli" width="100%" cellspacing="0">
                                            <thead>
                                                <tr class="cabecalho_indicador">
                                                    <th>ID</th>
                                                    <th>Cliente</th>
                                                    <th>Vencido</th>
                                                </tr>
                                            </thead>

                                            <tbody id="inad_clientes">

                                            </tbody>                               
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer small text-muted">Atualizado Hoje</div>
                            </div>

                            <!-- Example DataTables Card-->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-table"></i> Detalhamento da Inadimplência
                                    <i class="fa fa-file-excel-o" id="exportar3"></i>
                                </div>
                                <div class="card-body">

                                    <input type="text" id="pesquisacodcli" placeholder="Pesquisar código do cliente" style="width: 100%;height: 25px;">

                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer" id="div_inad">
                                        <table class="table table-bordered dataTable no-footer" id="detalhe_inad" width="100%" cellspacing="0">
                                            <thead>
                                                <tr class="cabecalho_indicador">
                                                    <th>Cliente</th>
                                                    <th>Nota</th>
                                                    <th>Vencimento</th>
                                                    <th>Atraso</th>
                                                    <th>Valor</th>
                                                </tr>
                                            </thead>

                                            <tbody id="inad_detalhada">

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
                <i class = "fa fa-angle-up"></i>
            </a>
            <!--Logout Modal-->
            <div class = "modal fade" id = "exampleModal" tabindex = "-1" role = "dialog" aria-labelledby = "exampleModalLabel" aria-hidden = "true">
                <div class = "modal-dialog" role = "document">
                    <div class = "modal-content">
                        <div class = "modal-header">
                            <h5 class = "modal-title" id = "exampleModalLabel">Deseja Sair?</h5>
                            <button class = "close" type = "button" data-dismiss = "modal" aria-label = "Close">
                                <span aria-hidden = "true">×</span>
                            </button>
                        </div>
                        <div class = "modal-body">Selecione "Sair" se você estiver pronto para terminar sua sessão atual.</div>
                        <div class = "modal-footer">
                            <button class = "btn btn-secondary" type = "button" data-dismiss = "modal">Cancelar</button>
                            <a class = "btn btn-primary" href = "../login.php">Sair</a>
                        </div>
                    </div>
                </div>
            </div>






            <!--Bootstrap core JavaScript-->
            <script src = "vendor/jquery/jquery.min.js"></script>
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
            <script src="js/graficoInadimplencia.js"></script>
            <script src="js/jquery.tablesorter.min.js"></script>
            <link href="css/selec2.min.css" rel="stylesheet" />
            <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script> 
        </div>
    </body>

</html>
