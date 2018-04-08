<?php
if (!isset($_SESSION)) {
    session_start();
}
if (isset($_POST['industriaselecionada2'])) {
    $industriaselecionada2 = $_POST['industriaselecionada2'];
} else {
    $industriaselecionada2 = 1;
}

if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}

require_once 'bd.php';
require_once '../funcoes/funcoes.php';

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
if ($industriaselecionada2 <> 1) {
    $query .= " and idindustria IN(0,$industriaselecionada2)";
}
if (isset($_POST['idcliente'])) {
    if ($_POST['idcliente'] <> 1) {

        $query .= " and idcliente=" . $_POST['idcliente'];
    }
}
$query .= " and canalvendas in('VENDEDOR','OLL')";


$result = pg_query($query);

while ($row = pg_fetch_array($result)) {
    echo "<tr>";
    echo '<td> ' . number_format($row['mes5'], 0, ',', '.') . '</td>';
    echo '<td> ' . number_format($row['mes4'], 0, ',', '.') . '</td>';
    echo '<td> ' . number_format($row['mes3'], 0, ',', '.') . '</td>';
    echo '<td> ' . number_format($row['mes2'], 0, ',', '.') . '</td>';
    echo '<td> ' . number_format($row['mes1'], 0, ',', '.') . '</td>';
    echo '<td>' . number_format($media2 = ($row['mes5'] + $row['mes4'] + $row['mes3'] + $row['mes2']) / 4, 0, ',', '.') . '</td>';
    //defineCorInadimplencia($row['inad']);
    echo "</tr>";

    $mes55 = $row['mes5'];
    $mes44 = $row['mes4'];
    $mes33 = $row['mes3'];
    $mes22 = $row['mes2'];
    $mes11 = $row['mes1'];
}

?>

<script> 
    var arrayMes2=[<?php atualizaMeses2(4)?>'MÉDIA'];
    var mes11 =<?php echo $mes11; ?>;
    var mes22 =<?php echo $mes22; ?>;
    var mes33 =<?php echo $mes33; ?>;
    var mes44 =<?php echo $mes44; ?>;
    var mes55 =<?php echo $mes55; ?>;
    var media1 =<?php echo $media2; ?>;
</script>



<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Core plugin JavaScript-->
<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
<!-- Page level plugin JavaScript-->
<script src="../vendor/chart.js/Chart.min.js"></script>
<script src="../vendor/datatables/jquery.dataTables.js"></script>
<script src="../vendor/datatables/dataTables.bootstrap4.js"></script>
<!-- Custom scripts for all pages-->
<script src="../js/sb-admin.min.js"></script>
<!-- Custom scripts for this page-->
<script src="../js/sbs-admin-datatables.min.js"></script>
<script src="js/graficoSellout.js"></script>


