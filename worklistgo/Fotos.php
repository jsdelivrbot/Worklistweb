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
                    atualizamix();
                    atualizaMixClientes();
                    $('#graficomix').remove();
                    $('#mix').append('<canvas id="graficomix" width="100" height="50"></canvas>');
                });

                function atualizamix() {
                    var industria = $(".select_indus").val();
                    $.post('classes/sql_mix.php', {industriaselecionada: industria}, function (data) {
                        $("#preenchermix").empty();
                        $("#preenchermix").html(data);
                    })
                }

                function atualizaMixClientes() {
                    var industria = $(".select_indus").val();
                    $.post('classes/sql_mix_clientes.php', {industriaselecionada: industria}, function (data) {
                        $("#preenchermix_clientes").empty();
                        $("#preenchermix_clientes").html(data);
                    })
                }

                $("#pesquisacli").keyup(function () {
                    var index = $(this).parent().index();
                    var nth = "#dataTable td:nth-child(" + (index + 1).toString() + ")";
                    var valor = $(this).val().toUpperCase();
                    $("#dataTable tbody tr").show();
                    $(nth).each(function () {
                        if ($(this).text().toUpperCase().indexOf(valor) < 0) {
                            $(this).parent().hide();
                        }
                    });
                });

                $("#pesquisacli").blur(function () {
                    $(this).val("");
                });


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
                                    <i class="fa fa-file-image-o"></i> Importador de Fotos
                                </div>
                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-6">
                                                    <form method="post" enctype="multipart/form-data" action="uploader/recebeUpload.php">

                                                        <label class="custom-file-upload">
                                                            <input name="arquivo" type="file" />
                                                            <i class="fa fa-cloud-upload"></i> Escolher Imagem
                                                        </label>
                                                        <label class="custom-file-upload">
                                                            <input name="arquivo" type="submit" />
                                                            <i class="fa fa-cloud-upload"></i> Enviar Imagem
                                                        </label>

                                                    </form>

                                                </div>
                                                <div class="col-6">

                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="card-footer small text-muted">Importe Sua Imagem

                                </div>
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



        </div>
    </body>

</html>
