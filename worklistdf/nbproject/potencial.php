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
        
        <script src="js/jquery-3.2.1.js"></script>
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

                                            <tbody>
                                                <?php
                                                require_once 'classes/bd.php';
                                                $iniciocota=buscarDiasMes(date('m')).date('Y');
                                                $bd = new bd();
                                                $query = "select
                                              a.idvendedor,
                                              c.apelido,
                                              a.venda,
                                              b.valormeta
                                              from
                                              (select
                                              a.idvendedor,
                                              SUM(a.mes1) as venda
                                              from sys_vendas a
                                              where
                                              a.canalvendas IN ('VENDEDOR','OLL')
                                              group by a.idvendedor)a,
                                              (select
                                              cast(b.idrca as integer) as idrca,
                                              b.valormeta
                                              from sys_metas b
                                              where
                                              b.datameta >='".$iniciocota."' and
                                              b.idindustria=1)b,
                                              sys_vendedores c
                                              where
                                              a.idvendedor = c.idvendedor and
                                              a.idvendedor = b.idrca and
                                              a.idvendedor =" . $_SESSION['idrepresentante'];
                                                $result = pg_query($query);

                                                $diasUteis = DiasUteis('01/12/2017', '31/12/2017')-1;
                                                $diasCorridos = DiasUteis('01/12/2017', date('d/m/Y'))+1;
                                                
                                                                                                
                                                $diasFalta = $diasUteis - $diasCorridos;

                                                while ($row = pg_fetch_array($result)) {

                                                    $projecao = $row['venda'] / $diasCorridos * $diasUteis;
                                                    if($diasFalta>0){
                                                        $objDia = ($row['valormeta'] - $row['venda']) / $diasFalta;
                                                    }else{
                                                        $objDia = ($row['valormeta'] - $row['venda']);
                                                    }
                                                    if(isset($perc_proj)){
                                                        $perc_proj = $projecao / $row['valormeta'] * 100;
                                                    }else{
                                                        $perc_proj=0;
                                                    }
                                                    
                                                    echo "<tr>";
                                                    echo '<td>' . $row['apelido'] . '</td>';
                                                    echo '<td>' . number_format($row['valormeta'], 0, ',', '.') . '</td>';
                                                    echo '<td>' . number_format($row['venda'], 0, ',', '.') . '</td>';
                                                    echo '<td>' . number_format($objDia, 0, ',', '.') . '</td>';
                                                    echo '<td>' . number_format($projecao, 0, ',', '.') . '</td>';

                                                    defineCor(number_format($perc_proj, 2, ',', '.'));

                                                    echo "</tr>";

                                                    $valormeta = $row['valormeta'];
                                                    $venda = $row['venda'];
                                                }


//montando a query para execução no banco
                                                $query = "insert into sys_controleacesso (idusuario,dataacesso,telaacessada) values (" . $_SESSION['idrepresentante'] . ",current_timestamp,'metas')";

//executando a query montada acima
                                                $result = pg_query($query);
                                                ?>


                                            </tbody>                               

                                        </table>
                                        <script>
                                            var OBJ =<?php echo $valormeta; ?>;
                                            var VENDA =<?php echo $venda; ?>;
                                              var PROJ =<?php echo $projecao; ?>;
                                        </script>
                                    </div>
                                    <div class="card-body">
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
                                        <tr>
                                            <td><?php echo $diasUteis; ?></td>
                                            <td><?php echo $diasCorridos; ?></td>
                                            <td><?php echo $diasFalta; ?></td>
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

                                            <tbody>
                                                <?php
                                                require_once 'classes/bd.php';
                                                $bd = new bd();
                                                $query_industria = "select
                                              c.fantasia,
                                              b.valormeta,
                                              a.venda                                              
                                              from
                                              (select
                                              a.idvendedor,
                                              a.idindustria,
                                              SUM(a.mes1) as venda
                                              from sys_vendas a
                                              where
                                              a.canalvendas IN ('VENDEDOR','OLL')
                                              group by a.idvendedor,
                                              a.idindustria)a,
                                              (select
                                              cast(b.idrca as integer) as idrca,
                                              b.idindustria,                                              
                                              b.valormeta
                                              from sys_metas b
                                              where
                                              b.datameta >='".$iniciocota."')b,
                                              sys_industrias c
                                              where
                                              a.idindustria = b.idindustria and
                                              a.idindustria = c.idindustria and
                                              a.idvendedor = b.idrca and
                                              a.idvendedor =" . $_SESSION['idrepresentante']." order by valormeta desc";
                                                $result_industria = pg_query($query_industria);

                                                if (pg_affected_rows($result_industria) == 0) {
                                                    echo "<tr>";
                                                    echo '<td align="center">-</td>';
                                                    echo '<td>0</td>';
                                                    echo '<td>0</td>';
                                                    echo '<td>0</td>';
                                                    echo '<td>0</td>';
                                                    echo '<td>0</td>';
                                                    echo "</tr>";
                                                } else {
                                                    while ($row_industria = pg_fetch_array($result_industria)) {
                                                        
                                                        $projecao_industria = $row_industria['venda'] / $diasCorridos * $diasUteis;
                                                        
                                                        if($diasFalta>0){
                                                            $objDiaIndustria = ($row_industria['valormeta'] - $row_industria['venda']) / $diasFalta;
                                                        }else{
                                                            $objDiaIndustria=($row_industria['valormeta'] - $row_industria['venda']);
                                                        }
                                                        
                                                        $perc_proj_industria = $projecao_industria / $row_industria['valormeta'] * 100;
                                                        echo "<tr>";
                                                        echo '<td>' . $row_industria['fantasia'] . '</td>';
                                                        echo '<td>' . number_format($row_industria['valormeta'], 0, ',', '.') . '</td>';
                                                        echo '<td>' . number_format($row_industria['venda'], 0, ',', '.') . '</td>';
                                                        echo '<td>' . number_format($objDiaIndustria, 0, ',', '.') . '</td>';
                                                        echo '<td>' . number_format($projecao_industria, 0, ',', '.') . '</td>';

                                                        defineCor(number_format($perc_proj_industria, 2, ',', '.'));

                                                        echo "</tr>";

                                                        $valormeta_industria = $row_industria['valormeta'];
                                                        $venda_industria = $row_industria['venda'];
                                                    }
                                                }
                                                ?>


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
            <script src="js/sb-admin-charts.min.js"></script>
        </div>
    </body>

</html>
