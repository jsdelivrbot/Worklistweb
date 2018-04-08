<?php
// Worklist Goiás Saúde - Página ajustada para a nova estrutura de supevisão.
// chamar criador de sessão de login
if(!isset($_SESSION))

{

session_start();

}

$industriaselecionada = $_POST['industriaselecionada'];

$rca = $_POST['rca'];

if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}

//require_once 'funcoes/funcoes.php';
require_once 'bd.php';
require_once '../funcoes/funcoes.php';
$bd = new bd();

$query = "
select
sum(mes5) as mes5,
sum(mes4) as mes4,
sum(mes3) as mes3,
sum(mes2) as mes2,
sum(mes1) as mes1,
(sum(mes5)+sum(mes4)+sum(mes3)+sum(mes2))/4 as media
from
(
----------- MES 1 -----------
select
(sum(custo)/ sum(venda))*100 as mes1,0 as mes2,0 as mes3,0 as mes4,0 as mes5
from sys_cmv
where MES = to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-0 MONTH','YYYY/MM') ";
if ($rca <> 1) {
   $query .= " and idrca= ".$rca; 
}else{
   $query .= " and idrca in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
}
if ($industriaselecionada <> 1 and $industriaselecionada <> 2) {
  $query .= " and idindus = ($industriaselecionada) ";
} 
if ($industriaselecionada == 2) {
  $query .= " and idindus not in (8306)";
} 
$query .= "
UNION ALL
----------- MES 2 -----------
select
0 as mes1,(sum(custo)/ sum(venda))*100 as mes2,0 as mes3,0 as mes4,0 as mes5
from sys_cmv
where MES = to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-1 MONTH','YYYY/MM') ";
if ($rca <> 1) {
   $query .= " and idrca= ".$rca; 
}else{
   $query .= " and idrca in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
}
if ($industriaselecionada <> 1 and $industriaselecionada <> 2) {
  $query .= " and idindus = ($industriaselecionada) ";
} 
if ($industriaselecionada == 2) {
  $query .= " and idindus not in (8306)";
} 
$query .= "
UNION ALL
----------- MES 3 -----------
select
0 as mes1,0 as mes2,(sum(custo)/ sum(venda))*100 as mes3,0 as mes4,0 as mes5
from sys_cmv
where MES = to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-2 MONTH','YYYY/MM') ";
if ($rca <> 1) {
   $query .= " and idrca= ".$rca; 
}else{
   $query .= " and idrca in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
}
if ($industriaselecionada <> 1 and $industriaselecionada <> 2) {
  $query .= " and idindus = ($industriaselecionada) ";
} 
if ($industriaselecionada == 2) {
  $query .= " and idindus not in (8306)";
} 
$query .= "
UNION ALL
----------- MES 4 -----------
select
0 as mes1,0 as mes2,0 as mes3,(sum(custo)/ sum(venda))*100 as mes4,0 as mes5
from sys_cmv
where MES = to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-3 MONTH','YYYY/MM') ";
if ($rca <> 1) {
   $query .= " and idrca= ".$rca; 
}else{
   $query .= " and idrca in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
}
if ($industriaselecionada <> 1 and $industriaselecionada <> 2) {
  $query .= " and idindus = ($industriaselecionada) ";
} 
if ($industriaselecionada == 2) {
  $query .= " and idindus not in (8306)";
} 
$query .= "
----------- MES 5 -----------
UNION ALL
select
0 as mes1,0 as mes2,0 as mes3,0 as mes4,(sum(custo)/ sum(venda))*100 as mes5
from sys_cmv
where MES = to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-4 MONTH','YYYY/MM') ";
if ($rca <> 1) {
   $query .= " and idrca= ".$rca; 
}else{
   $query .= " and idrca in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
}
if ($industriaselecionada <> 1 and $industriaselecionada <> 2) {
  $query .= " and idindus = ($industriaselecionada) ";
}
if ($industriaselecionada == 2) {
  $query .= " and idindus not in (8306)";
} 
$query .= ")a ";

echo $query;

$result = pg_query($query);

$row = pg_fetch_array($result);
    

echo "<tr>";
echo '<td align="center" style="font-weight:bold;"> ' . number_format($row['mes5'], 2, ',', '.')  . '</td>';
echo '<td align="center" style="font-weight:bold;"> ' . number_format($row['mes4'], 2, ',', '.') . '</td>';
echo '<td align="center" style="font-weight:bold;"> ' . number_format($row['mes3'], 2, ',', '.') . '</td>';
echo '<td align="center" style="font-weight:bold;"> ' . number_format($row['mes2'], 2, ',', '.') . '</td>';
echo '<td align="center" style="font-weight:bold;"> ' . number_format($row['mes1'], 2, ',', '.') . '</td>';
defineCorCMV(number_format($row['media'], 2, ',', '.')) ;
echo "</tr>";


?>

<script>
    var MesesCMV = ['<?php echo $_SESSION['MES5'] ?>','<?php echo $_SESSION['MES4'] ?>','<?php echo $_SESSION['MES3'] ?>','<?php echo $_SESSION['MES2'] ?>','<?php echo $_SESSION['MES1'] ?>','Média'];
    var mes1 =<?php echo $row['mes1'];?>;
    var mes2 =<?php echo $row['mes2'];?>;
    var mes3 =<?php echo $row['mes3'];?>;
    var mes4 =<?php echo $row['mes4'];?>;
    var mes5 =<?php echo $row['mes5'];?>;
    var media =<?php echo $row['media'];?>;
</script>



            <script src="../vendor/chart.js/Chart.min.js"></script>
  
            <script src="js/graficoCMV.js"></script>
            