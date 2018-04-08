<?php
// chamar criador de sessão de login
if (!isset($_SESSION)) {

    session_start();
}

if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}

$rca=$_POST['rca'];
$industriaselecionada = $_POST['industriaselecionada'];

//require_once 'funcoes/funcoes.php';
require_once 'bd.php';
require_once '../funcoes/funcoes.php';
$bd = new bd();

$query = " select dia,(sum(custo)/ sum(venda))*100 as cmv 
from sys_cmv_dia 
where mes = (select to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-0 MONTH','yyyy/MM')) ";

if ($rca <> 1) {
   $query .= " and idrca= ".$rca; 
}else{
   $query .= " and idrca in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
}

if ($industriaselecionada <> 1 and $industriaselecionada <> 2) {
  $query .= " and idindus = ($industriaselecionada) ";
} 
if ($industriaselecionada == 2) {
  $query .= " and idindus not in (8306) ";
} 

//if($rca!=1){
 //$query .= " and idrca = " . $rca;   
//}

$query .= " group by dia order by dia asc ";
$result = pg_query($query);

$cmv = '';
$dia = '';

$colunas = '';
$linhas = '';


$query_vendedor=pg_query('select apelido from sys_vendedores where idvendedor='.$rca);
$array_vendedor= pg_fetch_array($query_vendedor);

while ($row = pg_fetch_array($result)) {
     $dia .= $row["dia"] . ',';
     $cmv .= $row["cmv"] . ',';
     $colunas .= '<th align="center">'.str_pad($row["dia"], 2, "0", STR_PAD_LEFT).'</th>';
     $linhas  .= '<td align="center">'.number_format($row["cmv"], 0, ',', '.').'</td>';
}

echo '<strong>'.$array_vendedor['apelido'].'</strong>';
echo '<table class="table table-bordered dataTable no-footer" id="dataTable" width="100%" cellspacing="0">';
echo '<thead>';
echo '<tr class="cabecalho_indicador">';
echo '<th align="center" style="background:#c7e8a0;">DIA</th>';
echo $colunas;
echo '</tr>';
echo '</thead>';
echo '<tbody>';
echo '<tr>';
echo '<th align="center" style="background:#fff497;">CMV</th>';
echo $linhas;
echo '</tr>';
echo '</tbody>';
echo '</table>';




?>


<script>
    var dia = [<?php echo str_pad($dia, 2, "0", STR_PAD_LEFT); ?>];
    var cmv = [<?php echo $cmv; ?>];
</script>

<script src="../vendor/chart.js/Chart.min.js"></script>
<script src="js/cmvdia.js"></script>

