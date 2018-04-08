<?php
if (!isset($_SESSION)) {
    session_start();
}
if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}


require_once '../funcoes/funcoes.php';

require_once 'bd.php';

$bd = new bd();


$query = "SELECT
                                                    A.idvendedor,
                                                    a.cidade,
                                                    ROUND(SUM(A.VENCIDO), 0) AS VENCIDO,
                                                    ROUND(SUM(A.EM_ABERTO) + SUM(A.VENCIDO), 0) AS CARTEIRA,
                                                    CASE WHEN SUM(A.EM_ABERTO) = 0 AND SUM(A.VENCIDO) > 0 THEN 100
                                                    WHEN SUM(A.EM_ABERTO) = 0 AND SUM(A.VENCIDO) = 0 THEN 0
                                                    WHEN SUM(A.EM_ABERTO) > 0 AND SUM(A.VENCIDO) = 0 THEN 0
                                                    WHEN SUM(A.EM_ABERTO) > 0 AND SUM(A.VENCIDO) > 0 THEN
                                                    ROUND((SUM(A.VENCIDO) / (SUM(A.VENCIDO) + SUM(A.EM_ABERTO)))*100, 1) END AS INAD

                                                    FROM
                                                    (SELECT
                                                    a.idvendedor,
                                                    a.cidade,
                                                    SUM(a.valor) AS VENCIDO,
                                                    0 AS EM_ABERTO
                                                    FROM
                                                    sys_inadimplencia a
                                                    WHERE
                                                    a.STATUS = 'VENCIDO' AND
                                                    a.idvendedor = " . $_SESSION['idrepresentante']." GROUP BY
                                                    A.idvendedor, a.cidade

                                                    UNION ALL

                                                    SELECT
                                                    a.idvendedor,
                                                    a.cidade,
                                                    0 AS VENCIDO,
                                                    SUM(a.valor) AS EM_ABERTO
                                                    FROM
                                                    sys_inadimplencia a
                                                    WHERE
                                                    a.STATUS = 'EM ABERTO' AND
                                                    a.idvendedor = " . $_SESSION['idrepresentante']." GROUP BY
                                                    A.idvendedor, a.cidade
                                                    ) A
                                                    WHERE
                                                    A.idvendedor IN (" . $_SESSION['idrepresentante'] . ")
                                                    GROUP BY
                                                    A.idvendedor, a.cidade
                                                    ORDER BY INAD DESC";



$result = pg_query($query);



while ($row = pg_fetch_array($result)) {
    echo "<tr>";
    echo '<td>' . $row['cidade'] . '</td>';
    echo '<td>R$ ' . number_format($row['carteira'], 2, ',', '.') . '</td>';
    echo '<td>R$ ' . number_format($row['vencido'], 2, ',', '.') . '</td>';
    defineCorInadimplencia($row['inad']);
    echo "</tr>";
    $carteira = $row['carteira'];
    $vencido = $row['vencido'];
    $inad = $row['inad'];
}

$result = pg_query($query);

$carteira_total = 0;
$vencido_total = 0;

while ($row = pg_fetch_array($result)) {
    $carteira_total = $carteira_total + $row['carteira'];
    $vencido_total = $vencido_total + $row['vencido'];
}

$inad_geral = number_format(($vencido_total / $carteira_total) * 100, 1, ',', '.');

echo "<tr class = 'inadgeral'>";
echo '<td>GERAL</td>';
echo '<td>R$ ' . number_format($carteira_total, 2, ',', '.') . '</td>';
echo '<td>R$ ' . number_format($vencido_total, 2, ',', '.') . '</td>';
defineCorInadimplencia($inad_geral);
echo "</tr>";


//montando a query para execução no banco
$query = "insert into sys_controleacesso (idusuario, dataacesso, telaacessada) values (" . $_SESSION['idrepresentante'] . ", current_timestamp, 'inadimplencia')";

//executando a query montada acima
$result = pg_query($query);
?>

<script>
    var carteira =<?php echo $carteira_total; ?>;
    var vencido = <?php echo $vencido_total; ?>;
</script>

<script src="../vendor/chart.js/Chart.min.js"></script>
<script src="js/graficoInadimplencia.js"></script>
