<?php
// Worklist Goiás Saúde - Pagina ajustada para a nova estrutura de supevisão.
if (!isset($_SESSION)) {
    session_start();
}
if (isset($_POST['idcliente'])) {
    $idcliente = $_POST['idcliente'];
} else {
    $idcliente = 1;
}

if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}

$rca = $_POST['rca'];
?>

<!-- Example DataTables Card-->

<?php
require_once 'bd.php';
require_once '../funcoes/funcoes.php';
$bd = new bd();
$query = "select
b.apelido,
SUM(a.mes5) as MES5,
SUM(a.mes4) as MES4,
SUM(a.mes3) as MES3,
SUM(a.mes2) as MES2,
SUM(a.mes1) as MES1
from
sys_vendas a,
sys_vendedores b,
sys_acessos c
where
a.idvendedor=b.idvendedor and
a.idvendedor=c.idrepresentante and
b.idvendedor=c.idrepresentante ";


if($rca==1){
    $query.="  and a.idvendedor in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) "; 
}else{
    $query.=" and a.idvendedor = $rca"; 
}

$query .= " and a.canalvendas in('VENDEDOR','OLL')";
if ($idcliente <> 1) {
    $query .= " and a.idcliente IN($idcliente)";
}
$query.=" group by b.apelido";


$result = pg_query($query);

while ($row = pg_fetch_array($result)) {
    echo "<tr>";
    echo '<td> ' . $row['apelido'] . '</td>';
    echo '<td> ' . number_format($row['mes5'], 0, ',', '.') . '</td>';
    echo '<td> ' . number_format($row['mes4'], 0, ',', '.') . '</td>';
    echo '<td> ' . number_format($row['mes3'], 0, ',', '.') . '</td>';
    echo '<td> ' . number_format($row['mes2'], 0, ',', '.') . '</td>';
    echo '<td> ' . number_format($row['mes1'], 0, ',', '.') . '</td>';
    echo '<td>' . number_format($media = ($row['mes5'] + $row['mes4'] + $row['mes3'] + $row['mes2']) / 4, 0, ',', '.') . '</td>';

    echo "</tr>";

    $mes5 += $row['mes5'];
    $mes4 += $row['mes4'];
    $mes3 += $row['mes3'];
    $mes2 += $row['mes2'];
    $mes1 += $row['mes1'];
}
$media2 = ($mes5+$mes4+$mes3+$mes2) / 4;
?>
<script>
    var MesesSellOutGeral = ['<?php echo $_SESSION['MES5'] ?>', '<?php echo $_SESSION['MES4'] ?>', '<?php echo $_SESSION['MES3'] ?>', '<?php echo $_SESSION['MES2'] ?>', '<?php echo $_SESSION['MES1'] ?>', 'MÉDIA'];
    var mes1 =<?php echo $mes1; ?>;
    var mes2 =<?php echo $mes2; ?>;
    var mes3 =<?php echo $mes3; ?>;
    var mes4 =<?php echo $mes4; ?>;
    var mes5 =<?php echo $mes5; ?>;
    var media =<?php echo $media2; ?>;
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
<script src="js/graficoHistorico_Sellout_cliente.js"></script>


