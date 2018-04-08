<?php
if (!isset($_SESSION)) {
    session_start();
}
if (isset($_POST['industriaselecionada'])) {
    $industriaselecionada = $_POST['industriaselecionada'];
} else {
    $industriaselecionada = 1;
}


if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}


require_once 'funcoes/funcoes.php';
?>

<!-- Example DataTables Card-->
<div class="card mb-3">
    <div class="card-header">
        <i class="fa fa-table"></i> Indicador de Sell-Out Mensal
    </div>
    <div class="card-body">
        <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
            <table class="table table-bordered dataTable no-footer" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr class="cabecalho_indicador">
                        <th>Julho</th>
                        <th>Agosto</th>
                        <th>Setembro</th>
                        <th>Outubro</th>
                        <th>Novembro</th>
                        <th>Média</th>
                    </tr>
                </thead>

                <tbody>
<?php
require_once 'classes/bd.php';
$bd = new bd();
$query = "select
SUM(mes5) as MES5,
SUM(mes4) as MES4,
SUM(mes3) as MES3,
SUM(mes2) as MES2,
SUM(mes1) as MES1
from
sys_vendas
where
idvendedor=" . $_SESSION['idrepresentante'];
if ($industriaselecionada <> 1) {
    $query .= " and idindustria IN(0,$industriaselecionada)";
}
$query .=" and canalvendas in('VENDEDOR','OLL')";

$result = pg_query($query);
while ($row = pg_fetch_array($result)) {
    echo "<tr>";
    echo '<td> ' . number_format($row['mes5'], 0, ',', '.') . '</td>';
    echo '<td> ' . number_format($row['mes4'], 0, ',', '.') . '</td>';
    echo '<td> ' . number_format($row['mes3'], 0, ',', '.') . '</td>';
    echo '<td> ' . number_format($row['mes2'], 0, ',', '.') . '</td>';
    echo '<td> ' . number_format($row['mes1'], 0, ',', '.') . '</td>';
    echo '<td>' . number_format($media = ($row['mes5'] + $row['mes4'] + $row['mes3'] + $row['mes2']) / 4, 0, ',', '.') . '</td>';
    //defineCorInadimplencia($row['inad']);
    echo "</tr>";

    $mes5 = $row['mes5'];
    $mes4 = $row['mes4'];
    $mes3 = $row['mes3'];
    $mes2 = $row['mes2'];
    $mes1 = $row['mes1'];
}
?>
                </tbody>                               
            </table>
            <script>
                var mes11 =<?php echo $mes1; ?>;
                var mes22 =<?php echo $mes2; ?>;
                var mes33 =<?php echo $mes3; ?>;
                var mes44 =<?php echo $mes4; ?>;
                var mes55 =<?php echo $mes5; ?>;
                var media1 =<?php echo $media; ?>;

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
                <i class="fa fa-bar-chart"></i> Gráfico de Sell-Out Mensal
            </div>
            <div class="card-body">
                <canvas id="myBarChart2" width="100" height="50"></canvas>
            </div>
            <div class="card-footer small text-muted">Atualizado Hoje</div>
        </div>
    </div>
</div> 





<script src="vendor/chart.js/Chart.min.js"></script>
<script src="js/graficoSellout.js"></script>



