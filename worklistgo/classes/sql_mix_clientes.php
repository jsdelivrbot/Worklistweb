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

if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}

//require_once 'funcoes/funcoes.php';
require_once 'bd.php';
require_once '../funcoes/funcoes.php';
$bd = new bd();

$query = "SELECT
COUNT(IDPRODUTO) AS MIX
FROM
SYS_PRODUTOS A
WHERE
estoque > 0 ";
if ($industriaselecionada <> 1) {
    $query .= " and idindustria IN($industriaselecionada) ";
}

$result = pg_query($query);
$row = pg_fetch_array($result);

$mix = $row['mix'];


$query = "
SELECT
idcliente,
b.razao_social as cliente,
SUM(MES5) AS MES5,
SUM(MES4) AS MES4,
SUM(MES3) AS MES3,
SUM(MES2) AS MES2,
SUM(MES1) AS MES1,
SUM(PERIODO) AS PERIODO
FROM
(
--------------- MES 1-----------------
SELECT
idcliente,
COUNT(DISTINCT idproduto) AS MES1,0 AS MES2,0 AS MES3,0 AS MES4,0 AS MES5, 0 AS PERIODO
FROM
sys_vendas
WHERE
MES1 > 0";
if ($industriaselecionada <> 1) {
    $query .= " and idindustria IN($industriaselecionada) ";
}
if ($cliente <> 1) {
    $query .= " and idcliente IN($cliente) ";
}
$query .= " and idvendedor = " . $_SESSION['idrepresentante'] . "
GROUP BY idcliente
UNION ALL
--------------- MES 2-----------------
SELECT
idcliente,
0 AS MES1,COUNT(DISTINCT idproduto) AS MES2,0 AS MES3,0 AS MES4,0 AS MES5, 0 AS PERIODO
FROM
sys_vendas
WHERE
MES2 >0";
if ($industriaselecionada <> 1) {
    $query .= " and idindustria IN($industriaselecionada) ";
}
if ($cliente <> 1) {
    $query .= " and idcliente IN($cliente) ";
}
$query .= " and idvendedor = " . $_SESSION['idrepresentante'] . "
GROUP BY idcliente
UNION ALL
--------------- MES 3-----------------
SELECT
idcliente,
0 AS MES1,0 AS MES2,COUNT(DISTINCT idproduto) AS MES3,0 AS MES4,0 AS MES5, 0 AS PERIODO
FROM
sys_vendas
WHERE
MES3 >0";
if ($industriaselecionada <> 1) {
    $query .= " and idindustria IN($industriaselecionada) ";
}
if ($cliente <> 1) {
    $query .= " and idcliente IN($cliente) ";
}
$query .= " and idvendedor = " . $_SESSION['idrepresentante'] . "
GROUP BY idcliente
UNION ALL
--------------- MES 4-----------------
SELECT
idcliente,
0 AS MES1,0 AS MES2,0 AS MES3,COUNT(DISTINCT idproduto) AS MES4,0 AS MES5, 0 AS PERIODO
FROM
sys_vendas
WHERE
MES4 >0";
if ($industriaselecionada <> 1) {
    $query .= " and idindustria IN($industriaselecionada) ";
}
if ($cliente <> 1) {
    $query .= " and idcliente IN($cliente) ";
}
$query .= " and idvendedor = " . $_SESSION['idrepresentante'] . "
GROUP BY idcliente
UNION ALL
--------------- MES 5-----------------
SELECT
idcliente,
0 AS MES1,0 AS MES2,0 AS MES3,0 AS MES4,COUNT(DISTINCT idproduto) AS MES5, 0 AS PERIODO
FROM
sys_vendas
WHERE
MES5 >0";
if ($industriaselecionada <> 1) {
    $query .= " and idindustria IN($industriaselecionada) ";
}
if ($cliente <> 1) {
    $query .= " and idcliente IN($cliente) ";
}
$query .= " and idvendedor = " . $_SESSION['idrepresentante'] . "
GROUP BY idcliente
UNION ALL
--------------- PERÍODO-----------------
SELECT
idcliente,
0 AS MES1,0 AS MES2,0 AS MES3,0 AS MES4,0 AS MES5, COUNT(DISTINCT idproduto) AS PERIODO
FROM
sys_vendas
WHERE idproduto > 0 and idvendedor = " . $_SESSION['idrepresentante'];
if ($industriaselecionada <> 1) {
    $query .= " and idindustria IN($industriaselecionada) ";
}
if ($cliente <> 1) {
    $query .= " and idcliente IN($cliente) ";
}
$query .= " and (MES1+MES2+MES3+MES4+MES5) > 0 GROUP BY idcliente
UNION ALL
--------------- CARTEIRA-----------------
SELECT
idcliente,
0 AS MES1,0 AS MES2,0 AS MES3,0 AS MES4,0 AS MES5, 0 AS PERIODO
FROM
sys_carteira
WHERE
idvendedor = " . $_SESSION['idrepresentante'];
if ($cliente <> 1) {
    $query .= " and idcliente IN($cliente) ";
}
$query .= " )a,
sys_clientes b
where
a.idcliente = b.idcli 
GROUP BY idcliente,b.razao_social order by periodo desc";

$result = pg_query($query);

while ($row = pg_fetch_array($result)) {
    echo "<tr>";
    echo '<td> ' . $row['idcliente'] . '</td>';
    echo '<td> ' . $row['cliente'] . '</td>';
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
    echo '<td align="center" > ' . $row['periodo'] . '</td>';
//defineCorInadimplencia($row['inad']);
    echo "</tr>";
}

?>


<script>
    $(document).ready(function () {
        $('#mixclientes').tablesorter();
    });
</script>