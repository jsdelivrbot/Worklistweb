<?php
// chamar criador de sessão de login
if(!isset($_SESSION))

{

session_start();

}

$industriaselecionada = $_POST['industriaselecionada'];

if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}

//require_once 'funcoes/funcoes.php';
require_once 'bd.php';
require_once '../funcoes/funcoes.php';
$bd = new bd();

$query = "
select
a.idindus,
b.fantasia,
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
idindus,
case when (sum(custo) = 0 and sum(venda) = 0)  then 0 when (sum(custo) > 0 and sum(venda) = 0)  then 100 else (sum(custo)/ sum(venda))*100 end as mes1,0 as mes2,0 as mes3,0 as mes4,0 as mes5
from sys_cmv
where MES = to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-0 MONTH','YYYY/MM')
and idrca = ".$_SESSION['idrepresentante'];

if ($industriaselecionada <> 1 and $industriaselecionada <> 2) {
  $query .= " and idindus = ($industriaselecionada) ";
} 

$query .= " group by idindus
UNION ALL
----------- MES 2 -----------
select
idindus,
0 as mes1,case when (sum(custo) = 0 and sum(venda) = 0)  then 0 when (sum(custo) > 0 and sum(venda) = 0)  then 100 else (sum(custo)/ sum(venda))*100 end as mes2,0 as mes3,0 as mes4,0 as mes5
from sys_cmv
where MES = to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-1 MONTH','YYYY/MM')
and idrca = ".$_SESSION['idrepresentante'];

if ($industriaselecionada <> 1 and $industriaselecionada <> 2) {
  $query .= " and idindus = ($industriaselecionada) ";
} 

$query .= " group by idindus
UNION ALL
----------- MES 3 -----------
select
idindus,
0 as mes1,0 as mes2,case when (sum(custo) = 0 and sum(venda) = 0)  then 0 when (sum(custo) > 0 and sum(venda) = 0)  then 100 else (sum(custo)/ sum(venda))*100 end as mes3,0 as mes4,0 as mes5
from sys_cmv
where MES = to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-2 MONTH','YYYY/MM')
and idrca = ".$_SESSION['idrepresentante'];

if ($industriaselecionada <> 1 and $industriaselecionada <> 2) {
  $query .= " and idindus = ($industriaselecionada) ";
} 

$query .= " group by idindus
UNION ALL
----------- MES 4 -----------
select
idindus,
0 as mes1,0 as mes2,0 as mes3,case when (sum(custo) = 0 and sum(venda) = 0)  then 0 when (sum(custo) > 0 and sum(venda) = 0)  then 100 else (sum(custo)/ sum(venda))*100 end as mes4,0 as mes5
from sys_cmv
where MES = to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-3 MONTH','YYYY/MM')
and idrca = ".$_SESSION['idrepresentante'];

if ($industriaselecionada <> 1 and $industriaselecionada <> 2) {
  $query .= " and idindus = ($industriaselecionada) ";
} 

$query .= " group by idindus
----------- MES 5 -----------
UNION ALL
select
idindus,
0 as mes1,0 as mes2,0 as mes3,0 as mes4,case when (sum(custo) = 0 and sum(venda) = 0)  then 0 when (sum(custo) > 0 and sum(venda) = 0)  then 100 else (sum(custo)/ sum(venda))*100 end as mes5
from sys_cmv
where MES = to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-4 MONTH','YYYY/MM')
and idrca = ".$_SESSION['idrepresentante'];

if ($industriaselecionada <> 1 and $industriaselecionada <> 2) {
  $query .= " and idindus = ($industriaselecionada) ";
} 

$query .= " group by idindus)a, sys_industrias b"
        . " where a.idindus = b.idindustria ";
$query .= " group by a.idindus,b.fantasia order by media desc";


$result = pg_query($query);
while ($row = pg_fetch_array($result)){
        $i = 0;
    $total = 0;
echo "<tr>";
echo '<td  style="font-weight:bold;"> ' . $row['fantasia'] . '</td>';
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
echo "</tr>";
}

?>
