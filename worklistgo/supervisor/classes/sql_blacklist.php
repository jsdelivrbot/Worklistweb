<?php
// Worklist Goiás Saúde - Pagina ajustada para a nova estrutura de supervisão.
if (!isset($_SESSION)) {
    session_start();
}

$idcliente = $_POST['idcliente'];
$rca = $_POST['rca'];

if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}
?>


<?php

require_once 'bd.php';
$bd = new bd();
$query = "SELECT
b.razao_social,
a.mes1,
a.mes2,
a.mes3,
a.mes4,
a.mes5,
a.mes6,
a.mes7,
a.mes8,
a.mes9,
a.total
FROM sys_blacklist a,sys_clientes b, sys_carteira c,sys_acessos d
WHERE
a.idcliente = b.idcli and
a.idcliente = c.idcliente and
c.idvendedor=d.idrepresentante AND
a.bkl='SIM'";
if ($rca == 1) {
    $query .= " AND c.idvendedor in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca ) ";
} else {
    $query .= " AND c.IDVENDEDOR = $rca";
}
if ($idcliente <> 1) {
    $query .= " AND a.idcliente=$idcliente";
}
$result = pg_query($query);

if (pg_affected_rows($result) == 0) {
    echo "0";
} else {
    $aux=0;
    while ($row = pg_fetch_array($result)) {
        $valorMaximo=  max($row['mes9'],$row['mes8'],$row['mes7'],$row['mes6'],$row['mes5'],$row['mes4']);
        $media=($row['mes9']+$row['mes8']+$row['mes7']+$row['mes6']+$row['mes5']+$row['mes4'])/6;        
        echo "<tr>";
        echo '<td> ' . $row['razao_social'] . '</td>';
        echo '<td>&nbsp;' . number_format($valorMaximo, 0, ',', '.') . '</td>';
        echo '<td>&nbsp;' . number_format($media, 0, ',', '.') . '</td>';
        if($row['mes1']<=0){
            echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-down' style='color:#FF0000;'></i></td>";
        }else{
            echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-up' style='color:#185752;'></i></td>";
        }
        
        echo "</tr>";
    }
}
?>


