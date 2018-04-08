<?php
// chamar criador de sessão de login
session_start();
if ($_SESSION['logado'] != "SIM") {
    header('Location: login.php');
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
        <title>Worklist - Goiás Saúde</title>
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
    </head>

    <script>
        $(document).ready(function () {
            $("#btn_atualizar").click(function () {
                var iddocliente = $(".select_cliente").val();

                buscaDias();
                atualizaPainelDanone();
                atualizaCobertura();
                atualizaSellout();
                atualizaposgrupo();

                    $('#graficoselloutdanone').remove();
                    $('#graficoselloutatual').append('<canvas id="graficoselloutdanone" width="100" height="50"></canvas>');

                if (iddocliente == 1) {
                    atualizaDn();

                    $("#dnpositivacli").show();
                    $("#graficodncli").show();
                    $('#graficodndanone').remove();
                    $('#graficodnatual').append('<canvas id="graficodndanone" width="100" height="50"></canvas>');

                    $("#grafcobmulatu").show();
                    $('#graficocoberturadanone').remove();
                    $('#graficocoberturaatual').append('<canvas id="graficocoberturadanone" width="100" height="50"></canvas>');

                } else {
                  //  $("#grafselloutatu").hide();
                    $("#dnpositivacli").hide();
                    $("#graficodncli").hide();
                    $("#grafcobmulatu").hide();
                }
                blacklist();
            })

            $("#btn_cobertura").click(function () {
                filtroCobertura();
            })

            function atualizaPainelDanone() {
                var mes = $("#select_mes").val();
                var iddocliente = $(".select_cliente").val();
                $.post('classes/sql_danone.php', {mes: mes, idcliente: iddocliente}, function (data) {  
                    $("#paineldanone").empty();
                    $("#paineldanone").html(data);
                })
            }

            function atualizaCobertura() {
                var mes = $("#select_mes").val();
                var iddocliente = $(".select_cliente").val();
                $.post('classes/sql_coberturamultipladanone.php', {mes: mes, idcliente: iddocliente}, function (data) {
                    $("#coberturamultipla").empty();
                    $("#coberturamultipla").html(data);
                })

            }
            function atualizaDn() {
                var mes = $("#select_mes").val();
                var iddocliente = $(".select_cliente").val();
                $.post('classes/dn.php', {mes: mes, idcliente: iddocliente}, function (data) {
                    // $("#paineldn").empty();
                    $("#paineldn").html(data);
                })
            }            
            function atualizaposgrupo() {
                var mes = $("#select_mes").val();
                var iddocliente = $(".select_cliente").val();
                $.post('classes/sql_positivacaogrupo.php', {mes: mes, idcliente: iddocliente}, function (data) {
                    $("#positivacaogrupo").html(data);
                })
            }
            
            function blacklist() {
                var mes = $("#select_mes").val();
                var iddocliente = $(".select_cliente").val();
                $.post('classes/sql_blacklist.php', {mes: mes, idcliente: iddocliente}, function (data) {
                    if (data == 0) {
                        $("#blacklistatu").hide();
                    } else {
                        $("#blacklistatu").show();
                        $("#blacklist").empty();
                        $("#blacklist").html(data);
                    }
                })
            }

            $("#pesquisaclienteblack").keyup(function () {
                var index = $(this).parent().index();
                var nth = "#tableblacklist td:nth-child(" + (index + 1).toString() + ")";
                var valor = $(this).val().toUpperCase();
                $("#tableblacklist tbody tr").show();
                $(nth).each(function () {
                    if ($(this).text().toUpperCase().indexOf(valor) < 0) {
                        $(this).parent().hide();
                    }
                });
            });

            $("#pesquisaclientecobmult").keyup(function () {
                var index = $(this).parent().index();
                var nth = "#tablecobertura td:nth-child(" + (index + 1).toString() + ")";
                var valor = $(this).val().toUpperCase();
                $("#tablecobertura tbody tr").show();
                $(nth).each(function () {
                    if ($(this).text().toUpperCase().indexOf(valor) < 0) {
                        $(this).parent().hide();
                    }
                });
            });
            
            $("#pesquisaclientevendagrupo").keyup(function () {
                var index = $(this).parent().index();
                var nth = "#tablevendagrupo td:nth-child(" + (index + 2).toString() + ")";
                var valor = $(this).val().toUpperCase();
                $("#tablevendagrupo tbody tr").show();
                $(nth).each(function () {
                    if ($(this).text().toUpperCase().indexOf(valor) < 0) {
                        $(this).parent().hide();
                    }
                });
            });            
            

            function atualizaSellout() {
                var industria2 = 2009578;
                var iddocliente = $(".select_cliente").val();
                $.post('classes/sql_sellout.php', {industriaselecionada2: industria2, idcliente: iddocliente}, function (data) {
                    $("#selloutatualiza").empty();
                    $("#selloutatualiza").html(data);
                })
                $('#myBarChart2').remove();
                $('#graficosellout').append('<canvas id="myBarChart2" width="100" height="50"></canvas>');
            }

            function buscaDias() {
                var mes = $("#select_mes").val();
                $.post('classes/sql_buscadias.php', {mes: mes}, function (data) {
                    $("#diasDanone").show();
                    $("#dias").empty();
                    $("#dias").html(data);
                })
            }
            
                function salvaPlanilha1() {
                    var htmlPlanilha = "<table>" + document.getElementById("sellout").innerHTML + "</table>";

                    var htmlBase64 = btoa(htmlPlanilha);
                    var link = "data:application/vnd.ms-excel;base64," + htmlBase64;

                    window.open(link);
                }

                $('#exportar1').click(function () {
                    salvaPlanilha1();
                });
                
                function salvaPlanilha2() {
                    var htmlPlanilha = "<table>" + document.getElementById("dn").innerHTML + "</table>";

                    var htmlBase64 = btoa(htmlPlanilha);
                    var link = "data:application/vnd.ms-excel;base64," + htmlBase64;

                    window.open(link);
                }

                $('#exportar2').click(function () {
                    salvaPlanilha2();
                });
                
                function salvaPlanilha3() {
                    var htmlPlanilha = "<table>" + document.getElementById("tablevendagrupo").innerHTML + "</table>";

                    var htmlBase64 = btoa(htmlPlanilha);
                    var link = "data:application/vnd.ms-excel;base64," + htmlBase64;

                    window.open(link);
                }

                $('#exportar3').click(function () {
                    salvaPlanilha3();
                });
                
                function salvaPlanilha4() {
                    var htmlPlanilha = "<table>" + document.getElementById("selloutmes").innerHTML + "</table>";

                    var htmlBase64 = btoa(htmlPlanilha);
                    var link = "data:application/vnd.ms-excel;base64," + htmlBase64;

                    window.open(link);
                }

                $('#exportar4').click(function () {
                    salvaPlanilha4();
                });
                
                function salvaPlanilha5() {
                    var htmlPlanilha = "<table>" + document.getElementById("tablecobertura").innerHTML + "</table>";

                    var htmlBase64 = btoa(htmlPlanilha);
                    var link = "data:application/vnd.ms-excel;base64," + htmlBase64;

                    window.open(link);
                }

                $('#exportar5').click(function () {
                    salvaPlanilha5();
                });
                
                function salvaPlanilha6() {
                    var htmlPlanilha = "<table>" + document.getElementById("tableblacklist").innerHTML + "</table>";

                    var htmlBase64 = btoa(htmlPlanilha);
                    var link = "data:application/vnd.ms-excel;base64," + htmlBase64;

                    window.open(link);
                }

                $('#exportar6').click(function () {
                    salvaPlanilha6();
                });
$('.js-example-responsive').select2();
        })

    </script>

    <body class="fixed-nav sticky-footer bg-dark" id="page-top">
        <!-- Navigation-->
        <?PHP
        include_once 'menu.php';
        include_once './funcoes/funcoes.php';
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

                                        <select class="js-example-responsive" id="select_mes" style="width: 27%; color: #295ba6; font-weight: bold;">
                                             <?php atualizaMeses();?>
                                        </select>                                   
                                        <select class="js-example-responsive select_cliente" style="width: 50%; margin-left: 1%;">
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
                                <div id="diasDanone" style="display: none">
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
                            </div>
                            <!-- Example DataTables Card-->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-table"></i> Sell-Out
                                    <i class="fa fa-file-excel-o" id="exportar1"></i>
                                </div>
                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <table class="table table-bordered dataTable no-footer" id="sellout" width="100%" cellspacing="0" style="text-align: left;">
                                            <tbody id="paineldanone">

                                            </tbody>                               
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer small text-muted">Atualizado Hoje</div>
                            </div>
                            <div class="card mb-3" id="dnpositivacli">
                                <div class="card-header">
                                    <i class="fa fa-table"></i> DN - Positivação
                                    <i class="fa fa-file-excel-o" id="exportar2"></i>
                                </div>
                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <table class="table table-bordered dataTable no-footer" id="dn" width="100%" cellspacing="0" style="text-align: left;">
                                            <tbody id="paineldn">

                                            </tbody>                               
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer small text-muted">Atualizado Hoje</div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-table"></i> Positivação de Cliente por Grupo
                                    <i class="fa fa-file-excel-o" id="exportar3"></i>
                                </div>
                                <div class="card-body">

                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                      
                                            <input type="text" id="pesquisaclientevendagrupo" placeholder="Pesquisar Cliente"style="width: 100%; margin-top: 7px;">
                                        
                                        <span class="legenda_indus"> </span>

                                        <table class="table table-bordered dataTable no-footer" id="tablevendagrupo" width="100%" cellspacing="0">
                                            <thead>
                                                <tr class="cabecalho_indicador" >
                                                    <th align="center">COD.</th>
                                                    <th align="center">CLIENTE</th>
                                                    <th align="center">IMF</th>
                                                    <th align="center">PRO.</th>
                                                    <th align="center">SUST.</th>
                                                    <th align="center">CER.</th>
                                                    <th align="center">GUM</th>
                                                    <th align="center">TOTAL</th>
                                                </tr>
                                            </thead>
                                            <tbody id="positivacaogrupo">

                                            </tbody>                               
                                        </table>

                                    </div>
                                </div>
                                <div class="card-footer small text-muted">Atualizado Hoje</div>
                            </div>

                            <div class="card mb-3" id="grafselloutatu">
                                <div class="card-header">
                                    <i class="fa fa-calendar"></i> Gráfico Venda por Grupo
                                </div>
                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                    </div>
                                    <div class="card-body" id="graficoselloutatual">
                                        <canvas id="graficoselloutdanone" width="100" height="50"></canvas>
                                    </div>
                                </div>
                                <div class="card-footer small text-muted">Atualizado Hoje</div>
                            </div>


                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-calendar"></i> Indicador de Sell-Out Mensal
                                    <i class="fa fa-file-excel-o" id="exportar4"></i>
                                </div>
                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <table class="table table-bordered dataTable no-footer" id="selloutmes" width="100%" cellspacing="0">
                                            <thead>
                                                <tr class="cabecalho_indicador">
                                                    <th align="center"><?php echo $_SESSION['MES5'] ?></th>
                                                    <th align="center"><?php echo $_SESSION['MES4'] ?></th>
                                                    <th align="center"><?php echo $_SESSION['MES3'] ?></th>
                                                    <th align="center"><?php echo $_SESSION['MES2'] ?></th>
                                                    <th align="center"><?php echo $_SESSION['MES1'] ?></th>
                                                    <th>MÉDIA</th>
                                                </tr>
                                            </thead>
                                            <tbody id="selloutatualiza">                    

                                            </tbody>                               
                                        </table>
                                    </div>
                                    <div class="card-body" id="graficosellout">
                                        <canvas id="myBarChart2" width="100" height="50"></canvas>
                                    </div>
                                </div>
                                <div class="card-footer small text-muted">Atualizado Hoje</div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-table"></i> Cobertura Múltipla Danone
                                    <i class="fa fa-file-excel-o" id="exportar5"></i>
                                </div>
                                <div class="card-body">

                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                      
                                            <input type="text" id="pesquisaclientecobmult" placeholder="Pesquisar Cliente"style="width: 100%; margin-top: 7px;">
                                        
                                        <span class="legenda_indus"> </span>

                                        <table class="table table-bordered dataTable no-footer" id="tablecobertura" width="100%" cellspacing="0">
                                            <thead>
                                                <tr class="cabecalho_indicador" >
                                                    <th align="center">CLIENTE</th>
                                                    <th align="center">APT1</th>
                                                    <th align="center">APT2</th>
                                                    <th align="center">APT3</th>
                                                    <th align="center">APTAR</th>
                                                    <th align="center">APTAC</th>
                                                    <th align="center">PRO</th>
                                                    <th align="center">GUM</th>
                                                </tr>
                                            </thead>
                                            <tbody id="coberturamultipla">

                                            </tbody>                               
                                        </table>

                                    </div>
                                </div>
                                <div class="card-footer small text-muted">Atualizado Hoje</div>
                            </div>

                            <div class="card mb-3" id="grafcobmulatu">
                                <div class="card-header">
                                    <i class="fa fa-calendar"></i> Gráfico de Cobertura Múltipla Danone
                                </div>
                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                    </div>
                                    <div class="card-body" id="graficocoberturaatual">
                                        <canvas id="graficocoberturadanone" width="100" height="50"></canvas>
                                    </div>
                                </div>
                                <div class="card-footer small text-muted">Atualizado Hoje</div>
                            </div>


                            <div class="card mb-3" id="blacklistatu">
                                <div class="card-header">
                                    <i class="fa fa-table"></i> Black-List Danone
                                    <i class="fa fa-file-excel-o" id="exportar6"></i>
                                </div>
                                <div class="card-body">

                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                       
                                            <input type="text" id="pesquisaclienteblack" placeholder="Pesquisar Cliente"style="width: 100%;     margin-top: 7px;">
                                        
                                        <span class="legenda_indus"> </span>

                                        <table class="table table-bordered dataTable no-footer" id="tableblacklist" width="100%" cellspacing="0">
                                            <thead>
                                                <tr class="cabecalho_indicador" >
                                                    <th align="center">CLIENTE</th>
                                                    <th align="center">>VENDA</th>
                                                    <th align="center">MÉDIA</th>
                                                    <th align="center"><?php echo $_SESSION['MES1'] ?></th>
                                                </tr>
                                            </thead>
                                            <tbody id="blacklist">

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
            <link href="css/selec2.min.css" rel="stylesheet" />
            <script src="js/select2.min.js"></script>
        </div>
    </body>

</html>
