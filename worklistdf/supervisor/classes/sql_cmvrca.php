<?php
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
b.apelido,
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
idrca,
(sum(custo)/ sum(venda))*100 as mes1,0 as mes2,0 as mes3,0 as mes4,0 as mes5
from sys_cmv
where MES = to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-0 MONTH','YYYY/MM') ";
if ($rca <> 1) {
   $query .= " and idrca= ".$rca; 
}else{
   $query .= " and idrca in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
}
if ($industriaselecionada <> 1) {
  $query .= " and idindus = ($industriaselecionada)  ";
} 

$query .= "
    group by idrca 
UNION ALL
----------- MES 2 -----------
select
idrca,
0 as mes1,(sum(custo)/ sum(venda))*100 as mes2,0 as mes3,0 as mes4,0 as mes5
from sys_cmv
where MES = to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-1 MONTH','YYYY/MM') ";
if ($rca <> 1) {
   $query .= " and idrca= ".$rca; 
}else{
   $query .= " and idrca in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
}
if ($industriaselecionada <> 1) {
  $query .= " and idindus = ($industriaselecionada)";
} 

$query .= "
group by idrca 
UNION ALL
----------- MES 3 -----------
select
idrca,
0 as mes1,0 as mes2,(sum(custo)/ sum(venda))*100 as mes3,0 as mes4,0 as mes5
from sys_cmv
where MES = to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-2 MONTH','YYYY/MM') ";
if ($rca <> 1) {
   $query .= " and idrca= ".$rca; 
}else{
   $query .= " and idrca in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
}
if ($industriaselecionada <> 1) {
  $query .= " and idindus = ($industriaselecionada)  ";
} 
$query .= "
group by idrca 
UNION ALL
----------- MES 4 -----------
select
idrca,
0 as mes1,0 as mes2,0 as mes3,(sum(custo)/ sum(venda))*100 as mes4,0 as mes5
from sys_cmv
where MES = to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-3 MONTH','YYYY/MM') ";
if ($rca <> 1) {
   $query .= " and idrca= ".$rca; 
}else{
   $query .= " and idrca in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
}
if ($industriaselecionada <> 1) {
  $query .= " and idindus = ($industriaselecionada) ";
} 
$query .= "
group by idrca 
UNION ALL
----------- MES 5 -----------
select
idrca,
0 as mes1,0 as mes2,0 as mes3,0 as mes4,(sum(custo)/ sum(venda))*100 as mes5
from sys_cmv
where MES = to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-4 MONTH','YYYY/MM') ";
if ($rca <> 1) {
   $query .= " and idrca= ".$rca; 
}else{
   $query .= " and idrca in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
}
if ($industriaselecionada <> 1) {
  $query .= " and idindus = ($industriaselecionada) ";
} 
$query .= " group by idrca )a, sys_vendedores b where a.idrca = b.idvendedor GROUP BY b.apelido order by media desc";

$result = pg_query($query);

while ($row = pg_fetch_array($result)){
    
    $i = 0;
    $total = 0;
echo "<tr>";
echo '<td style="font-weight:bold;"> ' . $row['apelido'] . '</td>';
echo '<td align="center" > ' . number_format($row['mes5'], 2, ',', '.')  . '</td>';
echo '<td align="center" > ' . number_format($row['mes4'], 2, ',', '.') . '</td>';
echo '<td align="center" > ' . number_format($row['mes3'], 2, ',', '.') . '</td>';
echo '<td align="center" > ' . number_format($row['mes2'], 2, ',', '.') . '</td>';
echo '<td align="center" > ' . number_format($row['mes1'], 2, ',', '.') . '</td>';
 if ($row['mes5'] > 0) {
        $total = $total + $row['mes5'];
        $i++;
    }

    if ($row['mes4'] > 0) {
        $total = $total + $row['mes4'];
        $i++;
    }

    if ($row['mes3'] > 0) {
        $total = $total + $row['mes3'];
        $i++;
    }

    if ($row['mes2'] > 0) {
        $total = $total + $row['mes2'];
        $i++;
    }
defineCorCMV(number_format($total/$i, 2, ',', '.'));
//defineCorInadimplencia($row['inad']);
echo "</tr>";
} 

?>

