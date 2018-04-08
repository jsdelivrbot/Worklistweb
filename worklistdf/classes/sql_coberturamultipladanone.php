<?php
date_default_timezone_set('America/Sao_Paulo');
if (!isset($_SESSION)) {
    session_start();
}

if (isset($_POST['mes'])) {
    $mesInformado = $_POST['mes'];
    $aux = 1;
} else {
    $mesInformado = 1;
    $aux = 0;
}

if ($aux == 1) {
    require_once '../funcoes/funcoes.php';
} else {
    require_once '../funcoes/funcoes.php';
}


$idcliente = $_POST['idcliente'];

!@($conexao = pg_connect("host=187.72.34.18 dbname=worklist port=5432 user=df password=tidf123"));
$sql_data = pg_query("SELECT 
to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-3 MONTH','yyyy-mm-01') AS dtini,
to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '0 MONTH','yyyy-mm-dd') AS dtfim");

$arraydata = pg_fetch_array($sql_data);

$dtini = $arraydata['dtini'];
$dtfim = $arraydata['dtfim'];



$inicio_mes = $dtini;
$final_mes = $dtfim;





    $sql_cm_geral = "SELECT
A.IDCLI, 
A.CLIENTE, 
SUM(A.APT1) AS APT1, 
SUM(A.APT2) AS APT2, 
SUM(A.APT3) AS APT3, 
SUM(A.APTAR) AS APTAR, 
SUM(A.APTAC) AS APTAC, 
SUM(A.PRO1) AS PRO1, 
SUM(A.GUM) AS GUM
FROM

--------------------------- APT-01 ---------------------------
(SELECT 
D.IDCLI, 
D.CLIENTE, 
SUM(D.TOTAL) AS APT1, 0 AS APT2, 0 AS APT3, 0 AS APTAR, 0 AS APTAC, 0 AS PRO1, 0 AS GUM
FROM 
sys_farol_danone D
where
D.GRUPO_COBERTURA = 'APT-01' AND 
D.MES_VENDA >= '$inicio_mes' AND D.MES_VENDA <= '$final_mes' 
GROUP BY D.IDCLI, D.CLIENTE
UNION ALL

--------------------------- APT-02 ---------------------------
SELECT 
D.IDCLI, 
D.CLIENTE, 
0 AS APT1, SUM(D.TOTAL) AS APT2, 0 AS APT3, 0 AS APTAR, 0 AS APTAC, 0 AS PRO1, 0 AS GUM
FROM 
sys_farol_danone D
where
D.GRUPO_COBERTURA = 'APT-02' AND 
D.MES_VENDA >= '$inicio_mes' AND D.MES_VENDA <= '$final_mes' 
GROUP BY D.IDCLI, D.CLIENTE
UNION ALL

--------------------------- APT-03 ---------------------------
SELECT 
D.IDCLI, 
D.CLIENTE, 
0 AS APT1, 0 AS APT2, SUM(D.TOTAL) AS APT3, 0 AS APTAR, 0 AS APTAC, 0 AS PRO1, 0 AS GUM
FROM 
sys_farol_danone D
where
D.GRUPO_COBERTURA = 'APT-03' AND 
D.MES_VENDA >= '$inicio_mes' AND D.MES_VENDA <= '$final_mes' 
GROUP BY D.IDCLI, D.CLIENTE
UNION ALL

--------------------------- APT-AR ---------------------------
SELECT 
D.IDCLI, 
D.CLIENTE, 
0 AS APT1, 0 AS APT2, 0 AS APT3, SUM(D.TOTAL) AS APTAR, 0 AS APTAC, 0 AS PRO1, 0 AS GUM
FROM 
sys_farol_danone D
where
D.GRUPO_COBERTURA = 'APT-AR' AND 
D.MES_VENDA >= '$inicio_mes' AND D.MES_VENDA <= '$final_mes' 
GROUP BY D.IDCLI, D.CLIENTE
UNION ALL

--------------------------- APT-AC ---------------------------
SELECT 
D.IDCLI, 
D.CLIENTE, 
0 AS APT1, 0 AS APT2, 0 AS APT3, 0 AS APTAR, SUM(D.TOTAL) AS APTAC, 0 AS PRO1, 0 AS GUM
FROM 
sys_farol_danone D
where
D.GRUPO_COBERTURA = 'APT-AC' AND 
D.MES_VENDA >= '$inicio_mes' AND D.MES_VENDA <= '$final_mes' 
GROUP BY D.IDCLI, D.CLIENTE
UNION ALL

---------------------------PRO-01---------------------------
SELECT 
D.IDCLI, 
D.CLIENTE, 
0 AS APT1, 0 AS APT2, 0 AS APT3, 0 AS APTAR, 0 AS APTAC, SUM(D.TOTAL) AS PRO1, 0 AS GUM 
FROM 
sys_farol_danone D
where
D.GRUPO_COBERTURA = 'PRO-01' AND 
D.MES_VENDA >= '$inicio_mes' AND D.MES_VENDA <= '$final_mes' 
GROUP BY D.IDCLI, D.CLIENTE
UNION ALL

--------------------------- GUM ---------------------------
SELECT 
D.IDCLI, 
D.CLIENTE, 
0 AS APT1, 0 AS APT2, 0 AS APT3, 0 AS APTAR, 0 AS APTAC, 0 AS PRO1, SUM(D.TOTAL) AS GUM
FROM 
sys_farol_danone D
where
D.GRUPO_COBERTURA = 'GUM' AND 
D.MES_VENDA >= '$inicio_mes' AND D.MES_VENDA <= '$final_mes' 
GROUP BY D.IDCLI, D.CLIENTE) A,
SYS_CARTEIRA B
WHERE
A.IDCLI = B.IDCLIENTE AND
B.IDVENDEDOR = ".$_SESSION['idrepresentante'];
    if($idcliente<>1){
      $sql_cm_geral.= " and B.IDCLIENTE=".$idcliente; 
    }

$sql_cm_geral.= " GROUP BY
A.IDCLI, 
A.CLIENTE
ORDER BY A.CLIENTE";
    
    
    
$sql_cm_geral = pg_query($sql_cm_geral);
$qtd_cli = pg_num_rows($sql_cm_geral);

$i2 = 0;
if (pg_affected_rows($sql_cm_geral) == 0) {
    $query_cliente = pg_query("SELECT razao_social FROM sys_clientes WHERE idcli=" . $idcliente . ";");
    $cliente = pg_fetch_array($query_cliente);    
        echo "<tr>";
        echo "<td id='cliente_desc'>" . $cliente['razao_social'] . "</td>";
        echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-down' style='color:#FF0000;'></i></td>";
        echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-down' style='color:#FF0000;'></i></td>";
        echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-down' style='color:#FF0000;'></i></td>";
        echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-down' style='color:#FF0000;'></i></td>";
        echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-down' style='color:#FF0000;'></i></td>";
        echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-down' style='color:#FF0000;'></i></td>";
        echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-down' style='color:#FF0000;'></i></td>";
        echo "</tr>";

} else {
    while ($valores = pg_fetch_array($sql_cm_geral)) {
        $cm = 0;
        echo "<tr>"
        . "<td id='cliente_desc'>" . $valores['cliente'] . "</td>";
        if (round($valores['apt1'], 2) <= 0) {
            echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-down' style='color:#FF0000;' title='R$ " . round($valores['apt1'], 2) . "'></i></td>";
        } else {
            echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-up' style='color:#000066;' title='R$ " . round($valores['apt1'], 2) . "'></i></td>";
            $cm++;
        }
        if (round($valores['apt2'], 2) <= 0) {
            echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-down' style='color:#FF0000;' title='R$ " . round($valores['apt2'], 2) . "'></i></td>";
        } else {
            echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-up' style='color:#000066;' title='R$ " . round($valores['apt2'], 2) . "'></i></td>";
            $cm++;
        }
        if (round($valores['apt3'], 2) <= 0) {
            echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-down' style='color:#FF0000;' title='R$ " . round($valores['apt3'], 2) . "'></i></td>";
        } else {
            echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-up' style='color:#000066;' title='R$ " . round($valores['apt3'], 2) . "'></i></td>";
            $cm++;
        }
        if (round($valores['aptar'], 2) <= 0) {
            echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-down' style='color:#FF0000;' title='R$ " . round($valores['aptar'], 2) . "'></i></td>";
        } else {
            echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-up' style='color:#000066;' title='R$ " . round($valores['aptar'], 2) . "'></i></td>";
            $cm++;
        }
        if (round($valores['aptac'], 2) <= 0) {
            echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-down' style='color:#FF0000;' title='R$ " . round($valores['aptac'], 2) . "'></i></td>";
        } else {
            echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-up' style='color:#000066;' title='R$ " . round($valores['aptac'], 2) . "'></i></td>";
            $cm++;
        }
        if (round($valores['pro1'], 2) <= 0) {
            echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-down' style='color:#FF0000;' title='R$ " . round($valores['pro1'], 2) . "'></i></td>";
        } else {
            echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-up' style='color:#000066;' title='R$ " . round($valores['pro1'], 2) . "'></i></td>";
            $cm++;
        }
        if (round($valores['gum'], 2) <= 0) {
            echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-down' style='color:#FF0000;' title='R$ " . round($valores['gum'], 2) . "'></i></td>";
        } else {
            echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-up' style='color:#000066;' title='R$ " . round($valores['gum'], 2) . "'></i></td>";
            $cm++;
        }
        /*
          if ($cm == 7) {
          echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-up' style='color:#000066;' title='R$ " . round($valores['total'], 2) . "'></i></td>";
          } else {
          echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-down' style='color:#FF0000;' title='R$ " . round($valores['total'], 2) . "'></i></td>";
          } */
        echo "</tr>";

        if ($cm == 7) {
            $i++;
            $i2++;
        }
    }
}
?>    
<script>
    var clientescobertura =<?php echo $qtd_cli; ?>;
    var potencialcobertura =<?php echo round(($qtd_cli * 22) / 100, 0); ?>;
    var realizadocobertura =<?php echo $i2; ?>;
</script>





<script src="vendor/chart.js/Chart.min.js"></script>

<script src="js/graficocoberturadanone.js"></script>
