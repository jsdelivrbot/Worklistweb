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

if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}

//require_once 'funcoes/funcoes.php';
require_once 'bd.php';
require_once '../funcoes/funcoes.php';
$bd = new bd();

$query ="
SELECT 
A.LINHA as LINHA,
ROUND(SUM(A.T_VENDA),2) AS VENDA, 
ROUND(SUM(A.T_CUSTO),2) AS CUSTO, 
ROUND((SUM(A.T_CUSTO) / SUM(A.T_VENDA_REAL))*100,2) AS CMV
FROM sys_cmv A WHERE MES = '2017/12' AND 
A.DIA <= 15
GROUP BY A.LINHA";
$result = pg_query($query);

while ($row = pg_fetch_array($result)) {
    echo "<tr>";
    echo '<td> ' .$row['linha']. '</td>';
    echo '<td> ' . number_format($row['venda'], 2, ',', '.') . '</td>';
    echo '<td> ' . number_format($row['custo'], 2, ',', '.') . '</td>';
    defineCorCmv($row['cmv']);
    echo "</tr>";
    
    if ($row['linha'] = 'FARMA'){
        $CMVFARMA = $row['cmv'];
    }
    if ($row['linha'] = 'HPC'){
        $CMVHPC = $row['cmv'];
    }
    if ($row['linha'] = 'BABY'){
        $CMVBABY = $row['cmv'];
    }
    if ($row['linha'] = 'FITNESS'){
        $CMVFITNESS = $row['cmv'];
    }
    
}
?>


<script src="../vendor/chart.js/Chart.min.js"></script>
<script src="js/graficomix.js"></script>
