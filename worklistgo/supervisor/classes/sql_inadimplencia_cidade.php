<?php
// Worklist Goiás Saúde - Pagina ajustada para a nova estrutura de supevisão.
if (!isset($_SESSION)) {
    session_start();
}
if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}


require_once '../funcoes/funcoes.php';

require_once 'bd.php';

$rca = $_POST['rca'];

$bd = new bd();



$query = "
SELECT                                    
c.apelido as vendedor,
ROUND(SUM(A.EM_ABERTO) + SUM(A.VENCIDO), 0) AS CARTEIRA,
ROUND(SUM(A.VENCIDO), 0) AS VENCIDO,
CASE WHEN SUM(A.EM_ABERTO) = 0 AND SUM(A.VENCIDO) > 0 THEN 100
WHEN SUM(A.EM_ABERTO) = 0 AND SUM(A.VENCIDO) = 0 THEN 0
WHEN SUM(A.EM_ABERTO) > 0 AND SUM(A.VENCIDO) = 0 THEN 0
WHEN SUM(A.EM_ABERTO) > 0 AND SUM(A.VENCIDO) > 0 THEN
ROUND((SUM(A.VENCIDO) / (SUM(A.VENCIDO) + SUM(A.EM_ABERTO)))*100, 1) END AS INAD

FROM
(SELECT
a.idvendedor,
SUM(a.valor) AS VENCIDO,
0 AS EM_ABERTO
FROM
sys_inadimplencia a,
sys_acessos b
WHERE
a.idvendedor = b.idrepresentante and
a.status = 'VENCIDO'";
if ($rca == 1) {
    $query .= " and a.idvendedor in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
} else {
    $query .= " and a.idvendedor = $rca"; // . $_SESSION['idrepresentante'];    
}

$query .= "
GROUP BY A.idvendedor

UNION ALL

SELECT
a.idvendedor,
0 AS VENCIDO,
SUM(a.valor) AS EM_ABERTO
FROM
sys_inadimplencia a,
sys_acessos b
WHERE
a.idvendedor = b.idrepresentante and
a.STATUS = 'EM ABERTO'";
if ($rca == 1) {
    $query .= " and a.idvendedor in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
} else {
    $query .= " and a.idvendedor = $rca"; // . $_SESSION['idrepresentante'];    
}

$query .= "
GROUP BY A.idvendedor
) A,
sys_acessos b,
sys_vendedores c
where 
a.idvendedor = b.idrepresentante  and
a.idvendedor = c.idvendedor ";

if ($rca == 1) {
    $query .= " and a.idvendedor in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
} else {
    $query .= " and a.idvendedor = $rca"; // . $_SESSION['idrepresentante'];    
}
$query .= "
GROUP BY
c.apelido
ORDER BY INAD DESC
";

$result = pg_query($query);

echo $query;

while ($row = pg_fetch_array($result)) {
    echo "<tr>";
    echo '<td>' . $row['vendedor'] . '</td>';
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
