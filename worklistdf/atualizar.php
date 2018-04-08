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
        <title>Atualizar</title>
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

                    enviarrequisicao();
                    $("#msgrequisicao").show();

                });
                
                $('#btn_retorno').click(function () {

                    retornorequisicao();

                });
                

                function enviarrequisicao() {
                    $.post('classes/sql_atualizar.php', {}, function (data) {
                        $("#preencherrequisicao").empty();
                        $("#preencherrequisicao").html(data);
                    })
                }
                
                function retornorequisicao() {
                    $.post('classes/sql_atualizar_retorno.php', {}, function (data) {
                        $("#preencherretorno").empty();
                        $("#preencherretorno").html(data);
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
                                    <i class="fa fa-filter"></i> Módulos
                                </div>
                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <span class="legenda_indus"></span>
                                        <select class="js-example-responsive select_indus" >
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

                                        <i class="fa fa-refresh" id="btn_atualizar"></i>
                                    </div>
                                </div>
                                <div class="card-footer small text-muted">

                                </div>
                            </div>






                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-calendar"></i> Requisição para atualização
                                </div>
                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">

                                        <table class="table table-bordered dataTable no-footer" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr class="cabecalho_indicador" >
                                                    <th align="center">ID          </th>
                                                    <th align="center">Solicitação </th>
                                                    <th align="center">Resposta    </th>
                                                    <th align="center">Tempo       </th>
                                                    <th align="center">Status      </th>
                                                </tr>
                                            </thead>

                                            <tbody id="preencherrequisicao">

                                            </tbody>                               
                                        </table>
                                       <!-- <div id="msgrequisicao" class="alert alert-success">Sua requisição foi enviada com sucesso, aguarde alguns minutos e verifique o retorno!</div>-->
                                    </div>
                                </div>
                                <div class="card-footer small text-muted"></div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-calendar"></i> Retorno da requisição
                                </div>
                                <div class="card-body">
                                    Verificar Retorno <i class="fa fa-refresh" id="btn_retorno"></i>
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">

                                        <table class="table table-bordered dataTable no-footer" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr class="cabecalho_indicador" >
                                                    <th align="center">ID          </th>
                                                    <th align="center">Solicitação </th>
                                                    <th align="center">Resposta    </th>
                                                    <th align="center">Tempo       </th>
                                                    <th align="center">Status      </th>
                                                </tr>
                                            </thead>

                                            <tbody id="preencherretorno">

                                            </tbody>                               
                                        </table>

                                    </div>
                                </div>
                                <div class="card-footer small text-muted"></div>
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
