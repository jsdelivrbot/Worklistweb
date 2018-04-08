<?php

// chamar criador de sessão de login
if (!isset($_SESSION)) {

    session_start();
}

if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}

$industriaselecionada = $_POST['industriaselecionada'];
$rca = $_POST['rca'];

//require_once 'funcoes/funcoes.php';
require_once 'bd.php';
require_once '../funcoes/funcoes.php';
$bd = new bd();


if ($industriaselecionada == 2 || $industriaselecionada == 8306){
$query = " 
select
a.idrca,
c.apelido,
b.metacmv,
case when b.idtipo = 1 then 'CMV-D'
     when b.idtipo = 2 then 'CMV-SD' else 'CMV-G' end as tipo,
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
and b.mes = (select to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-0 MONTH','yyyy/MM')) ";
if ($industriaselecionada == 8306) {
    $query .= " and b.idtipo = 1 and idindus in (8306)";
} else if ($industriaselecionada == 2) {
    $query .= " and b.idtipo = 2 and idindus not in (8306)";
}
if ($rca <> 1) {
    $query .= " and a.idrca= " . $rca;
} else {
    $query .= " and a.idrca in (select idrca from sys_supervisao where idsupervisor = " . $_SESSION['idrepresentante'] . " order by idrca) ";
}

$query .= " group by
a.idrca,
b.idtipo,
c.apelido,
b.metacmv order by idrca,dif asc";

$result = pg_query($query);


while ($row = pg_fetch_array($result)) {
    echo "<tr>";
    echo '<td  style="font-weight:bold;"> ' . $row['tipo'] . '</td>';
    echo '<td align="center" style="font-weight:bold;"> ' . $row['idrca'] . '</td>';
    echo '<td  style="font-weight:bold;"> ' . $row['apelido'] . '</td>';
    echo '<td align="center" style="font-weight:bold;"> ' . number_format($row['metacmv'], 2, ',', '.') . '</td>';
    defineCorCMV(number_format($row['cmvatual'], 2, ',', '.'));
    positivo_e_negativo($row['dif']);
    statuscmv($row['dif']);
    echo "</tr>";
}
} else {
    echo "<tr>";
    echo '<td colspan="7"><div class = "alert alert-danger" role = "alert" align="center">Não existe potencial cadastrado para a indústria selecionada!</div></td>';
    echo "</tr>";

}
?>
