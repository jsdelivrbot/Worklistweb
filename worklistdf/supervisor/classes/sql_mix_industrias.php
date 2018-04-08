<?php
// chamar criador de sessão de login
if (!isset($_SESSION)) {

    session_start();
}
if (isset($_POST['industriaselecionada'])) {
    $industriaselecionada = $_POST['industriaselecionada'];
} else {
    $industriaselecionada = 1;
}

if (isset($_POST['cliente'])) {
    $cliente = $_POST['cliente'];
} else {
    $cliente = 1;
}

$rca = $_POST['rca'];


if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}

//require_once 'funcoes/funcoes.php';
require_once 'bd.php';
require_once '../funcoes/funcoes.php';
$bd = new bd();

//if ($industriaselecionada <> 1) {
//   $query .= " and idindustria IN($industriaselecionada) ";
//}
//$query .= " and idvendedor = " . $_SESSION['idrepresentante'] . "

$query = "SELECT
A.idindustria,
b.fantasia as industria,
SUM(A.MIX) AS MIX,
SUM(A.MES5) AS MES5,
SUM(A.MES4) AS MES4,
SUM(A.MES3) AS MES3,
SUM(A.MES2) AS MES2,
SUM(A.MES1) AS MES1,
SUM(A.PERIODO) AS PERIODO
FROM
(
--------------- MES 1-----------------
SELECT
idindustria,
0 AS MIX,COUNT(DISTINCT idproduto) AS MES1,0 AS MES2,0 AS MES3,0 AS MES4,0 AS MES5, 0 AS PERIODO
FROM
sys_vendas a,
sys_acessos b
WHERE
a.idvendedor = b.idrepresentante and
MES1 > 0  ";

if($rca==1){
    $query.=" and a.idvendedor in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) "; 
}else{
    $query.=" and a.idvendedor = $rca"; 
}

if ($cliente <> 1) {
    $query .= " and idcliente IN($cliente) ";
}
$query .= " GROUP BY idindustria
UNION ALL
--------------- MES 2-----------------
SELECT
idindustria,
0 AS MIX,0 AS MES1,COUNT(DISTINCT idproduto) AS MES2,0 AS MES3,0 AS MES4,0 AS MES5, 0 AS PERIODO
FROM
sys_vendas a,
sys_acessos b
WHERE
a.idvendedor = b.idrepresentante and
MES2 > 0  ";

if($rca==1){
    $query.=" and a.idvendedor in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) "; 
}else{
    $query.=" and a.idvendedor = $rca"; 
}

if ($cliente <> 1) {
    $query .= " and idcliente IN($cliente) ";
}
$query .= " GROUP BY idindustria
UNION ALL
--------------- MES 3-----------------
SELECT
idindustria,
0 AS MIX,0 AS MES1,0 AS MES2,COUNT(DISTINCT idproduto) AS MES3,0 AS MES4,0 AS MES5, 0 AS PERIODO
FROM
sys_vendas a,
sys_acessos b
WHERE
a.idvendedor = b.idrepresentante and
MES3 > 0  ";

if($rca==1){
    $query.=" and a.idvendedor in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) "; 
}else{
    $query.=" and a.idvendedor = $rca"; 
}
if ($cliente <> 1) {
    $query .= " and idcliente IN($cliente) ";
}
$query .= " GROUP BY idindustria
UNION ALL
--------------- MES 4-----------------
SELECT
idindustria,
0 AS MIX,0 AS MES1,0 AS MES2,0 AS MES3,COUNT(DISTINCT idproduto) AS MES4,0 AS MES5, 0 AS PERIODO
FROM
sys_vendas a,
sys_acessos b
WHERE
a.idvendedor = b.idrepresentante and
MES4 > 0  ";

if($rca==1){
    $query.=" and a.idvendedor in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) "; 
}else{
    $query.=" and a.idvendedor = $rca"; 
}
if ($cliente <> 1) {
    $query .= " and idcliente IN($cliente) ";
}
$query .= " GROUP BY idindustria
UNION ALL
--------------- MES 5-----------------
SELECT
idindustria,
0 AS MIX,0 AS MES1,0 AS MES2,0 AS MES3,0 AS MES4,COUNT(DISTINCT idproduto) AS MES5, 0 AS PERIODO
FROM
sys_vendas a,
sys_acessos b
WHERE
a.idvendedor = b.idrepresentante and
MES5 > 0  ";

if($rca==1){
    $query.=" and a.idvendedor in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) "; 
}else{
    $query.=" and a.idvendedor = $rca"; 
}
if ($cliente <> 1) {
    $query .= " and idcliente IN($cliente) ";
}
$query .= " GROUP BY idindustria
UNION ALL
--------------- PERÍODO-----------------
SELECT
idindustria,
0 AS MIX, 0 AS MES1,0 AS MES2,0 AS MES3,0 AS MES4,0 AS MES5, COUNT(DISTINCT idproduto) AS PERIODO
FROM
sys_vendas a,
sys_acessos b
WHERE
a.idvendedor = b.idrepresentante ";

if($rca==1){
    $query.="  and a.idvendedor in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) "; 
}else{
    $query.=" and a.idvendedor = $rca"; 
}
if ($cliente <> 1) {
    $query .= " and (MES1+MES2+MES3+MES4+MES5) > 0 and idcliente IN($cliente) ";
}
$query .= " GROUP BY idindustria
UNION ALL
--------------- MIX-----------------
SELECT
idindustria,
COUNT(DISTINCT idproduto) as MIX,0 AS MES1,0 AS MES2,0 AS MES3,0 AS MES4,0 AS MES5, 0 AS PERIODO
FROM
sys_produtos
WHERE
estoque > 0
group by idindustria
)a,
sys_industrias b
where
a.idindustria = b.idindustria ";
if ($industriaselecionada == 1) {
  $query .= " and a.idindustria IN(select distinct idindustria from sys_vendas a,sys_acessos b where a.idvendedor =  b.idrepresentante and b.idrepresentante in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ) ";
} else{
  $query .= " and a.idindustria IN($industriaselecionada) ";
} 
$query .= "  
GROUP BY a.idindustria,b.fantasia
ORDER BY PERIODO DESC,MIX DESC ";

$result = pg_query($query);

while ($row = pg_fetch_array($result)) {
   // if ($row['periodo'] > 0) {
        echo "<tr>";
        echo '<td> ' . $row['industria'] . '</td>';
        if ($row['mix'] == 0) {
            echo '<td style="color:#FF0000; vertical-align:middle; text-align:center; font-weight: bold;"> ' . $row['mix'] . '</td>';
        } else {
            echo '<td align="center" style="color:#295ba6; vertical-align:middle; text-align:center; font-weight: bold;" > ' . $row['mix'] . '</td>';
        }
        if ($row['mes5'] == 0) {
            echo '<td style="color:#FF0000; vertical-align:middle; text-align:center; font-weight: bold;"> ' . $row['mes5'] . '</td>';
        } else {
            echo '<td align="center" > ' . $row['mes5'] . '</td>';
        }
        if ($row['mes4'] == 0) {
            echo '<td style="color:#FF0000; vertical-align:middle; text-align:center; font-weight: bold;"> ' . $row['mes4'] . '</td>';
        } else {
            echo '<td align="center" > ' . $row['mes4'] . '</td>';
        }
        if ($row['mes3'] == 0) {
            echo '<td style="color:#FF0000; vertical-align:middle; text-align:center; font-weight: bold;"> ' . $row['mes3'] . '</td>';
        } else {
            echo '<td align="center" > ' . $row['mes3'] . '</td>';
        }
        if ($row['mes2'] == 0) {
            echo '<td style="color:#FF0000; vertical-align:middle; text-align:center; font-weight: bold;"> ' . $row['mes2'] . '</td>';
        } else {
            echo '<td align="center" > ' . $row['mes2'] . '</td>';
        }
        if ($row['mes1'] == 0) {
            echo '<td style="color:#FF0000; vertical-align:middle; text-align:center; font-weight: bold;"> ' . $row['mes1'] . '</td>';
        } else {
            echo '<td align="center" > ' . $row['mes1'] . '</td>';
        }
        echo '<td align="center" style="color:#295ba6; vertical-align:middle; text-align:center; font-weight: bold;"> ' . $row['periodo'] . '</td>';
//defineCorInadimplencia($row['inad']);
        echo "</tr>";
    }
//}

//montando a query para execução no banco
$query = "insert into sys_controleacesso (idusuario,dataacesso,telaacessada) values (" . $_SESSION['idrepresentante'] . ",current_timestamp,'mix')";

//executando a query montada acima
$result = pg_query($query);
?>

<script>
    $(document).ready(function () {
        $('#mixindustrias').tablesorter();
    });
</script>

