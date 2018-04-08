<?php

// chamar criador de sessão de login
if (!isset($_SESSION)) {

    session_start();
}

if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}

$industriaselecionada = $_POST['industriaselecionada'];

//require_once 'funcoes/funcoes.php';
require_once 'bd.php';
require_once '../funcoes/funcoes.php';
$bd = new bd();

$query = " 
select
a.idrca,
c.apelido,
b.metacmv,
(sum(a.custo)/ sum(a.venda))*100 as cmvatual,
((sum(a.custo)/ sum(a.venda))*100) - b.metacmv as dif
from 
sys_cmv_dia a,
sys_obj_cmv b,
sys_vendedores c
where 
a.idrca = b.idrca 
and c.idvendedor = b.idrca
and a.mes = (select to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-0 MONTH','yyyy/MM'))
and b.mes = (select to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-0 MONTH','yyyy/MM')) 
and b.idtipo = 1 and idindus in (8306) and a.idrca = " . $_SESSION['idrepresentante'] .
        " group by
a.idrca,
c.apelido,
b.metacmv ";



$result = pg_query($query);

$cmv = '';
$dia = '';

$colunas = '';
$linhas = '';

$row = pg_fetch_array($result);

echo "<tr>";
echo '<td align="center" style="font-weight:bold;"><strong>CMV-D</strong></td>';
echo '<td align="center" style="font-weight:bold;"> ' . $row['idrca'] . '</td>';
echo '<td align="center" style="font-weight:bold;"> ' . $row['apelido'] . '</td>';
echo '<td align="center" style="font-weight:bold;"> ' . number_format($row['metacmv'], 2, ',', '.') . '</td>';
defineCorCMV(number_format($row['cmvatual'], 2, ',', '.'));
positivo_e_negativo($row['dif']);
statuscmv($row['dif']);
echo "</tr>";




$query = " 
select
a.idrca,
c.apelido,
b.metacmv,
(sum(a.custo)/ sum(a.venda))*100 as cmvatual,
((sum(a.custo)/ sum(a.venda))*100) - b.metacmv as dif
from 
sys_cmv_dia a,
sys_obj_cmv b,
sys_vendedores c
where 
a.idrca = b.idrca 
and c.idvendedor = b.idrca
and a.mes = (select to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-0 MONTH','yyyy/MM'))
and b.mes = (select to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-0 MONTH','yyyy/MM')) 
and b.idtipo = 2 and idindus not in (8306) and a.idrca = " . $_SESSION['idrepresentante'] .
        " group by
a.idrca,
c.apelido,
b.metacmv ";



$result = pg_query($query);

$cmv = '';
$dia = '';

$colunas = '';
$linhas = '';

$row = pg_fetch_array($result);

echo "<tr>";
echo '<td align="center" style="font-weight:bold;"><strong>CMV-SD</strong></td>';
echo '<td align="center" style="font-weight:bold;"> ' . $row['idrca'] . '</td>';
echo '<td align="center" style="font-weight:bold;"> ' . $row['apelido'] . '</td>';
echo '<td align="center" style="font-weight:bold;"> ' . number_format($row['metacmv'], 2, ',', '.') . '</td>';
defineCorCMV(number_format($row['cmvatual'], 2, ',', '.'));
positivo_e_negativo($row['dif']);
statuscmv($row['dif']);
echo "</tr>";
?>
