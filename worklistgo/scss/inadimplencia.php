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
        <title>Inadimplência - DF Distribuidora</title>
        <!-- Bootstrap core CSS-->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom fonts for this template-->
        <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <!-- Page level plugin CSS-->
        <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
        <!-- Custom styles for this template-->

        <link href="css/sb-admin.css" rel="stylesheet">
        <link href="css/metas.css" rel="stylesheet">
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
                        <i class="fa fa-table"></i> Indicador de Inadimplência
                    </div>
                    <div class="card-body">
                        <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                            <table class="table table-bordered dataTable no-footer" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr class="cabecalho_indicador">
                                        <th>Carteira  </th>
                                        <th>Vencido</th>
                                        <th>%Inad</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    require_once 'classes/bd.php';
                                    $bd = new bd();
                                    $query = "SELECT 
    A.IDRCA,  
    ROUND(SUM(A.VENCIDO),0) AS VENCIDO, 
    ROUND(SUM(A.EM_ABERTO) + SUM(A.VENCIDO),0)   AS CARTEIRA, 
    CASE WHEN SUM(A.EM_ABERTO) = 0 AND SUM(A.VENCIDO) > 0 THEN 100 
         WHEN SUM(A.EM_ABERTO) = 0 AND SUM(A.VENCIDO) = 0 THEN 0 
         WHEN SUM(A.EM_ABERTO) > 0 AND SUM(A.VENCIDO) = 0 THEN 0  
         WHEN SUM(A.EM_ABERTO) > 0 AND SUM(A.VENCIDO) > 0 THEN  
         ROUND((SUM(A.VENCIDO) / (SUM(A.VENCIDO) + SUM(A.EM_ABERTO)))*100,1) END AS INAD 
     
    FROM 
    (
    SELECT 
    a.idrca, 
    SUM(a.valor) AS VENCIDO, 
    0 AS EM_ABERTO 
    FROM 
    sys_inadimplencia a 
    WHERE 
    a.STATUS = 'VENCIDO' AND a.tipo NOT IN ('CR') AND a.portador NOT IN ('0998') and
    a.idrca = " . $_SESSION['idrepresentante'] .
                                            "GROUP BY
    A.idrca
     
    UNION ALL 
     
    SELECT 
    a.idrca, 
    0 AS VENCIDO, 
    SUM(a.valor) AS EM_ABERTO 
    FROM 
    sys_inadimplencia a 
    WHERE 
    a.STATUS = 'EM ABERTO' AND a.tipo NOT IN ('CR') AND a.portador NOT IN ('0998') and
    a.idrca = " . $_SESSION['idrepresentante'] .
                                            "GROUP BY
    A.idrca
    ) A 
    WHERE A.IDRCA IN (" . $_SESSION['idrepresentante'] .
                                            ")GROUP BY 
    A.IDRCA 
    ORDER BY INAD DESC";

                                    $result = pg_query($query);
                                    while ($row = pg_fetch_array($result)) {
                                        echo "<tr>";
                                        echo '<td>R$ ' . number_format($row['carteira'], 2, ',', '.') . '</td>';
                                        echo '<td>R$ ' . number_format($row['vencido'], 2, ',', '.') . '</td>';
                                        defineCorInadimplencia($row['inad']);
                                        echo "</tr>";
                                        $carteira = $row['carteira'];
                                        $vencido = $row['vencido'];
                                        $inad = $row['inad'];
                                    }
                                    
//montando a query para execução no banco
$query = "insert into sys_controleacesso (idusuario,dataacesso,telaacessada) values (".$_SESSION['idrepresentante'].",current_timestamp,'inadimplencia')";

//executando a query montada acima
$result = pg_query($query);
                                    
                                    
                                    ?>
                                </tbody>                               
                            </table>
                            <script>
                                var carteira =<?php echo $carteira; ?>;
                                var vencido = <?php echo $vencido; ?>;
                            </script>
                        </div>
                    </div>
                    <div class="card-footer small text-muted">Atualizado Hoje</div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <!-- Example Bar Chart Card-->
                        <div class="card mb-3">
                            <div class="card-header">
                                <i class="fa fa-bar-chart"></i> Gráfico de Inadimplência
                            </div>
                            <div class="card-body">
                                <canvas id="myBarChart" width="100" height="50"></canvas>
                            </div>
                            <div class="card-footer small text-muted">Atualizado Hoje</div>
                        </div>
                    </div>
                </div> 


                <!-- Example DataTables Card-->
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fa fa-table"></i> Relatório de inadimplência por cliente
                    </div>
                    <div class="card-body">
                        <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                            <table class="table table-bordered dataTable no-footer" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr class="cabecalho_indicador">
                                        <th>Código  </th>
                                        <th>Razão Social</th>
                                        <th>Vencido</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    require_once 'classes/bd.php';
                                    $bd = new bd();
                                    $query_inadcli = "SELECT 
        a.idcli, 
        b.razao_social,
        SUM(a.valor) AS inadimplencia
        FROM 
        sys_inadimplencia a,
        sys_clientes b 
        WHERE  
        a.idcli = b.idcli and
        a.STATUS = 'VENCIDO' AND a.tipo NOT IN ('CR') AND a.portador NOT IN ('0998') and
        a.idrca = " . $_SESSION['idrepresentante'] .
                                            "GROUP BY
        A.idcli,b.razao_social
        order by 
        inadimplencia desc";

                                    $result_inadcli = pg_query($query_inadcli);
                                    while ($row_inadcli = pg_fetch_array($result_inadcli)) {
                                        echo "<tr>";
                                        echo '<td>' . $row_inadcli['idcli'] . '&nbsp;</td>';
                                        echo '<td>&nbsp;' . $row_inadcli['razao_social'] . '</td>';
                                        echo '<td width=60 style="color:red;font-weight:bold;">R$ ' . number_format($row_inadcli['inadimplencia'], 0, ',', '.') . '</td>';
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>                               
                            </table>
                        </div>
                    </div>
                    <div class="card-footer small text-muted">Atualizado Hoje</div>
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
            <script src="js/graficoInadimplencia.js"></script>
        </div>
    </body>

</html>
