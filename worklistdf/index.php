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
        <meta name="Worknet - Daniel Novais" content="Worknet">
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
        <link href="css/index.css" rel="stylesheet">

    </head>

    <body class="fixed-nav sticky-footer bg-dark" id="page-top">
        <!-- Navigation-->

        <?PHP
        include_once 'menu.php';
        ?>


        <div class="content-wrapper" id="div_principal">
            <div class="container"><!-- Inicio do divisão framework -->
                <div class="row"> <!-- Inicio row framework -->
                    <div class="col-xl-7 col-lg-9 col-md-12 col-sm-12 "> <!-- Inicio coluna framework -->
<?php

$pasta = 'img/imagens/'.$_SESSION['equipe'].'/';
$arquivos = glob("$pasta{*.jpg,*.JPG,*.png,*.gif,*.bmp}", GLOB_BRACE);

?>
                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                            <?php
                                 $aux2=0;
                                foreach($arquivos as $img){
                                if($aux2==0){                           
                            ?>                                
                                <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $aux2;?>" class="active"></li>
                                        <?php
                                }else{?>                                
                                <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $aux2;?>"></li>
                                <?php
                                
                                }
                                $aux2++;
                                }
                                
                                ?>
                            </ol>
                            <div class="carousel-inner">
                                <?php
                                $aux=1;
                                foreach($arquivos as $img){
                                if($aux==1){?>
                                <div class="carousel-item active">
                                    <img class="d-block w-100" src="<?php echo $img;?>" alt="First slide">
                                </div>                               
                                
                                        <?php
                                }else{?>
                                    <div class="carousel-item">
                                    <img class="d-block w-100" src="<?php echo $img;?>" alt="First slide">
                                </div>
                                <?php
                                
                                }
                                $aux=2;
                                }
                                
                                ?>


                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div><!-- Inicio coluna framework -->
                </div><!-- Inicio row framework -->
            </div><!-- Inicio divisao framework -->
            <!-- /.content-wrapper-->
            <footer class="sticky-footer" id="mainNav">
                <div class="container">
                    <div class="text-center">
                        <small>Copyright © WorklistWeb 2017 - Versão 2.01</small>
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
            <script src="js/sb-admin-charts.min.js"></script>
        </div>
    </body>

</html>
