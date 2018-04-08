'   <?php
// chamar criador de sessão de login
if (!isset($_SESSION)) {
    session_start();
}
if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}

echo '<script>var mesatual = "' . $_SESSION['MES1'] . '";</script>';
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
        <title>CMV - Goiás Saúde</title>
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

                $('#btnatualizar').click(function () {
                    $('#ChartCMV').remove();
                    $('#graficoCMV').append('<canvas id="ChartCMV" width="100" height="50"></canvas>');

                    $('#cmvdia').remove();
                    $('#graficoCMV_Dia').append('<canvas id="cmvdia" width="100" height="50">11</canvas>');

                    atualizaCMV();
                    AtulizaCMVIndus();
                    atualizaCMV_Dia();
                    ObjCMV();

                })

                function atualizaCMV() {
                    var industria = $(".select_indus").val();
                    $.post('classes/sql_cmv.php', {industriaselecionada: industria}, function (data) {
                        $("#cmv").empty();
                        $("#cmv").html(data);
                    })
                }

                function AtulizaCMVIndus() {
                    var industria = $(".select_indus").val();
                    $.post('classes/sql_cmv_industria.php', {industriaselecionada: industria}, function (data) {
                        $("#cmvindus").empty();
                        $("#cmvindus").html(data);
                    })

                }

                function atualizaCMV_Dia() {
                    var industria = $(".select_indus").val();
                    $.post('classes/sql_cmv_dia.php', {industriaselecionada: industria}, function (data) {
                        $("#cmv_dia").empty();
                        $("#cmv_dia").html(data);
                    })
                }

                function ObjCMV() {
                    var industria = $(".select_indus").val();
                    $.post('classes/sql_cmv_atual.php', {industriaselecionada: industria}, function (data) {
                        $("#objcmv").empty();
                        $("#objcmv").html(data);
                    })
                }

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

                            <!-- Example DataTables Card-->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-filter"></i> Filtro de Indústria
                                </div>
                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">

                                        <div class="container">
                                            <div class="row">
                                                <div class="col-9" >
                                                    <select class="js-example-responsive select_indus">
                                                        <option value="1">TODAS</option>
                                                        <option value="2">TODAS SEM DANONE</option>
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
                                                <div class="col-3" align="center">
                                                    <a  class="btn btn-sm formatbtn" id="btnatualizar">
                                                        <span class="fa fa-refresh" ></span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-calendar"></i> Objetivo CMV
                                </div>
                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <table class="table table-bordered dataTable no-footer" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr class="cabecalho_indicador">
                                                    <th align="center">TIPO</th>
                                                    <th align="center">ID</th>
                                                    <th align="center">M.E.I</th>
                                                    <th align="center">OBJ</th>
                                                    <th align="center">CMV <?php echo $_SESSION['MES1'] ?>%</th>
                                                    <th align="center">DIF</th>
                                                    <th align="center">ST</th>
                                                </tr>
                                            </thead>
                                            <tbody id="objcmv">                    

                                            </tbody>  
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer small text-muted">Atualizado Hoje</div>
                            </div>


                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-calendar"></i> Indicador de CMV Geral
                                </div>
                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <table class="table table-bordered dataTable no-footer" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr class="cabecalho_indicador">
                                                    <th align="center"><?php echo $_SESSION['MES5'] ?>%</th>
                                                    <th align="center"><?php echo $_SESSION['MES4'] ?>%</th>
                                                    <th align="center"><?php echo $_SESSION['MES3'] ?>%</th>
                                                    <th align="center"><?php echo $_SESSION['MES2'] ?>%</th>
                                                    <th align="center"><?php echo $_SESSION['MES1'] ?>%</th>
                                                    <th>MÉDIA%</th>
                                                </tr>
                                            </thead>
                                            <tbody id="cmv">                    

                                            </tbody>  
                                        </table>
                                        <div class="card-body" id="graficoCMV">
                                            <canvas id="ChartCMV" width="100" height="50"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer small text-muted">Atualizado Hoje</div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-calendar"></i> Indicador de CMV por Indústria
                                </div>
                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <table class="table table-bordered dataTable no-footer" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr class="cabecalho_indicador">
                                                    <th align="center">INDUSTRIA</th>
                                                    <th align="center"><?php echo $_SESSION['MES5'] ?></th>
                                                    <th align="center"><?php echo $_SESSION['MES4'] ?></th>
                                                    <th align="center"><?php echo $_SESSION['MES3'] ?></th>
                                                    <th align="center"><?php echo $_SESSION['MES2'] ?></th>
                                                    <th align="center"><?php echo $_SESSION['MES1'] ?></th>
                                                    <th align="center">MÉDIA</th>
                                                </tr>
                                            </thead>
                                            <tbody id="cmvindus">                    

                                            </tbody>  
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer small text-muted">Atualizado Hoje</div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-calendar"></i> Indicador de CMV Dia - <strong><?php echo $_SESSION['MES1'] ?></strong>
                                </div>
                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <table class="table table-bordered dataTable no-footer" id="dataTable" width="100%" cellspacing="0">
                                            <thead>

                                            </thead>
                                            <tbody id="cmv_dia">                    

                                            </tbody>  
                                        </table>
                                        <div class="card-body" id="graficoCMV_Dia">
                                            <canvas id="cmvdia" width="100" height="50"></canvas>
                                        </div>
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
            <script src="js/jquery.tablesorter.min.js"></script>
            <link href="css/selec2.min.css" rel="stylesheet" />
            <script src="js/select2.min.js"></script>
        </div>
    </body>

</html>
