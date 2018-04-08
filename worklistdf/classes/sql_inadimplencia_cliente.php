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


$query_inadcli = "SELECT
                                                    a.idcli,
                                                    b.razao_social,
                                                    a.cidade,
                                                    SUM(a.valor) AS inadimplencia
                                                    FROM
                                                    sys_inadimplencia a,
                                                    sys_clientes b
                                                    WHERE
                                                    a.idcli = b.idcli and
                                                    a.STATUS = 'VENCIDO' AND
                                                    a.idvendedor = " . $_SESSION['idrepresentante'];
if ($idcli <> 1) {
    $query_inadcli .= " and a.idcli IN($idcli)";
}
$query_inadcli .= " GROUP BY
                                                    A.idcli, b.razao_social, a.cidade
                                                    order by
                                                    inadimplencia desc";

$result_inadcli = pg_query($query_inadcli);
while ($row_inadcli = pg_fetch_array($result_inadcli)) {
    echo "<tr>";
    echo '<td>' . $row_inadcli['idcli'] . '&nbsp;</td>';
    echo '<td>&nbsp;' . $row_inadcli['razao_social'] . '</td>';
    //echo '<td>&nbsp;' . $row_inadcli['cidade'] . '</td>';
    echo '<td width=70 style="color:red;
                                            font-weight:bold;
                                            ">R$ ' . number_format($row_inadcli['inadimplencia'], 0, ',', '.') . '</td>';
    echo "</tr>";
}

?>
