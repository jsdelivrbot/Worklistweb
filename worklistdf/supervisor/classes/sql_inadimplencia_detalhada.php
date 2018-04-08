<?php
if (!isset($_SESSION)) {
    session_start();
}

if (isset($_POST['idcli'])) {
    $idcli = $_POST['idcli'];
} else {
    $idcli = 1;
}

if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}


require_once '../funcoes/funcoes.php';

require_once 'bd.php';

$bd = new bd();

$rca = $_POST['rca'];

$query_inadcli = "
select 
a.idcli,
a.nota,
a.vencimento,
CURRENT_DATE - a.vencimento as atraso,
a.valor
from 
sys_inadimplencia a,
sys_acessos b
where 
a.status ='VENCIDO' and 
a.idvendedor = b.idrepresentante ";

if ($rca == 1) {
    $query_inadcli .= " and a.idvendedor in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
} else {
   $query_inadcli .=" and a.idvendedor = $rca "; 
}

if ($idcli <> 1) {
    $query_inadcli .= " and a.idcli IN($idcli)";
}

$query_inadcli .= " order by atraso desc";

$result_inadcli = pg_query($query_inadcli);
while ($row_inadcli = pg_fetch_array($result_inadcli)) {
    echo "<tr>";
    echo '<td>' . $row_inadcli['idcli'] . '&nbsp;</td>';
    echo '<td>' . $row_inadcli['nota'] . '&nbsp;</td>';
    echo '<td>&nbsp;' . $row_inadcli['vencimento'] . '</td>';
    echo '<td>&nbsp;' . $row_inadcli['atraso'] . '</td>';
    echo '<td width=70 style="color:red;font-weight:bold;">'.$row_inadcli['valor'].'</td>';
    echo "</tr>";
}

?>

<script>
    $(document).ready(function(){
        $('#detalhe_inad').tablesorter();
    });
</script>
