<?php
date_default_timezone_set('America/Sao_Paulo');
if (!isset($_SESSION)) {
    session_start();
}

if (isset($_POST['mes'])) {
    $messelecao = explode('/', $_POST['mes']);
    $mesInformado = $messelecao[1];
    $ano = $messelecao[2];
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
$rca=$_POST['rca'];

!@($conexao = pg_connect("host=187.72.34.18 dbname=worklist port=5432 user=df password=tidf123"));

$inicio_mes = buscarDiasMes($mesInformado);
$final_mes = buscarUltimoDia($mesInformado);
$inicio_mes .= $ano;
$final_mes .= $ano;

if ($mesInformado == date('m')) {
    $final_mes = date('d/m/Y');
}

$aux = explode('/', $inicio_mes);
$dia = $aux[0];
$mes = $aux[1];
$ano = $aux[2];


$mesAtual = (int) date('m');
$feriadosDiasCorridos = buscaFeriados($inicio_mes, $final_mes);
$feriadosDiasUteis = buscaFeriadosDiasUteis($inicio_mes, $final_mes);

if ($mesInformado == $mesAtual) {
    $diaDataSelecionada = date('d');
    $diasUteis = dias_uteis($mes, $ano, $feriadosDiasUteis);
    $diasCorridos = dias_corridos($mes, $ano, $diaDataSelecionada, $feriadosDiasCorridos);
} else {
    $diasUteis = dias_uteis($mes, $ano, $feriadosDiasUteis);
    $diasCorridos = dias_uteis($mes, $ano, $feriadosDiasUteis);
}


$sql_cm_geral = "SELECT
A.IDCLI,
A.CLIENTE, 
SUM(A.IMF) AS IMF, 
SUM(A.GUM) AS GUM, 
SUM(A.PROFUTURA) AS PROFUTURA, 
SUM(A.CEREAL) AS CEREAL, 
SUM(A.SUSTAIN) AS SUSTAIN,
SUM(A.IMF) + SUM(A.GUM) + SUM(A.PROFUTURA) + SUM(A.CEREAL) + SUM(A.SUSTAIN) AS TOTAL 

FROM 
--------------------------- IMF ---------------------------
(SELECT 
D.IDCLI, 
D.CLIENTE, 
SUM(D.TOTAL) AS IMF, 0 AS GUM, 0 AS PROFUTURA, 0 AS CEREAL, 0 AS SUSTAIN
FROM sys_farol_danone D,
sys_acessos E 
where D.DIVISAO = 'IMF' AND
D.ID_RCA=E.IDREPRESENTANTE AND
D.TIPO_VENDEDOR='VENDEDOR' AND
D.MES_VENDA >= '$inicio_mes' AND 
D.MES_VENDA <= '$final_mes' AND";
if ($rca == 1) {
    $sql_cm_geral .= " D.id_rca in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
} else {
    $sql_cm_geral .= " D.id_rca = $rca";
}
$sql_cm_geral .= " GROUP BY D.IDCLI, D.CLIENTE 
UNION ALL
--------------------------- GUM --------------------------- 
SELECT 
D.IDCLI, 
D.CLIENTE, 0 AS IMF, SUM(D.TOTAL) AS GUM, 0 AS PROFUTURA, 0 AS CEREAL, 0 AS SUSTAIN
FROM sys_farol_danone D,
sys_acessos E  
where D.DIVISAO = 'GUM' AND 
D.ID_RCA=E.IDREPRESENTANTE AND
D.TIPO_VENDEDOR='VENDEDOR' AND
D.MES_VENDA >= '$inicio_mes' AND 
D.MES_VENDA <= '$final_mes' AND";
if ($rca == 1) {
    $sql_cm_geral .= " D.id_rca in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
} else {
    $sql_cm_geral .= " D.id_rca = $rca";
}
$sql_cm_geral .= " GROUP BY D.IDCLI, D.CLIENTE 
UNION ALL
--------------------------- PROFUTURA ---------------------------
 SELECT 
 D.IDCLI, 
 D.CLIENTE, 
 0 AS IMF, 0 AS GUM, SUM(D.TOTAL) AS PROFUTURA, 0 AS CEREAL, 0 AS SUSTAIN
 FROM sys_farol_danone D,
 sys_acessos E   
 where D.DIVISAO = 'PRO-01' AND 
 D.ID_RCA=E.IDREPRESENTANTE AND
 D.TIPO_VENDEDOR='VENDEDOR' AND
D.MES_VENDA >= '$inicio_mes' AND 
D.MES_VENDA <= '$final_mes' AND";
if ($rca == 1) {
    $sql_cm_geral .= " D.id_rca in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
} else {
    $sql_cm_geral .= " D.id_rca = $rca";
}
$sql_cm_geral .= " GROUP BY D.IDCLI, D.CLIENTE 
 UNION ALL 
 --------------------------- CEREAL ---------------------------
 SELECT 
 D.IDCLI, 
 D.CLIENTE, 
 0 AS IMF, 0 AS GUM, 0 AS PROFUTURA, SUM(D.TOTAL) AS CEREAL, 0 AS SUSTAIN
 FROM sys_farol_danone D,
 sys_acessos E 
 where D.DIVISAO = 'CEREAL' AND
 D.ID_RCA=E.IDREPRESENTANTE AND 
 D.TIPO_VENDEDOR='VENDEDOR' AND
D.MES_VENDA >= '$inicio_mes' AND 
D.MES_VENDA <= '$final_mes' AND";
if ($rca == 1) {
    $sql_cm_geral .= " D.id_rca in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
} else {
    $sql_cm_geral .= " D.id_rca = $rca";
}
$sql_cm_geral .= " GROUP BY D.IDCLI, D.CLIENTE 
 UNION ALL 
 --------------------------- SUSTAIN ---------------------------
  SELECT 
  D.IDCLI, 
  D.CLIENTE, 
  0 AS IMF, 0 AS GUM, 0 AS PROFUTURA, 0 AS CEREAL, SUM(D.TOTAL) AS SUSTAIN
  FROM sys_farol_danone D,
  sys_acessos E  
  where D.DIVISAO = 'SUSTAIN' AND
  D.ID_RCA=E.IDREPRESENTANTE AND 
  D.TIPO_VENDEDOR='VENDEDOR' AND 
D.MES_VENDA >= '$inicio_mes' AND 
D.MES_VENDA <= '$final_mes' AND";
if ($rca == 1) {
    $sql_cm_geral .= " D.id_rca in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
} else {
    $sql_cm_geral .= " D.id_rca = $rca";
}
$sql_cm_geral .= " GROUP BY D.IDCLI, D.CLIENTE
  UNION ALL 
 --------------------------- CARTEIRA ---------------------------
  SELECT 
  B.IDCLI, 
  B.razao_social as CLIENTE, 
  0 AS IMF, 0 AS GUM, 0 AS PROFUTURA, 0 AS CEREAL, 0 AS SUSTAIN
  FROM sys_carteira A, sys_clientes B 
  where 
  A.IDCLIENTE = B.idcli 
  ) A, SYS_CARTEIRA B,sys_acessos F 
    WHERE A.IDCLI = B.IDCLIENTE AND 
    B.IDVENDEDOR=F.IDREPRESENTANTE";
if ($rca == 1) {
    $sql_cm_geral .= " AND B.IDVENDEDOR in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
} else {
    $sql_cm_geral .= " AND B.IDVENDEDOR = $rca ";
}

if ($idcliente <> 1) {
    $sql_cm_geral .= " AND B.IDCLIENTE=" . $idcliente;
}

$sql_cm_geral .= " GROUP BY A.IDCLI, A.CLIENTE
    ORDER BY TOTAL DESC
";


$auxsql = $sql_cm_geral;

$sql_cm_geral = pg_query($sql_cm_geral);
$qtd_cli = pg_num_rows($sql_cm_geral);

$i2 = 0;

if (pg_affected_rows($sql_cm_geral) == 0) {
    $query_cliente = pg_query("SELECT razao_social FROM sys_clientes WHERE idcli=" . $idcliente . ";");
    $cliente = pg_fetch_array($query_cliente);
    echo "<tr>";
    echo "<td id='cliente_desc'>" . $cliente['razao_social'] .$sql_cm_geral. "</td>";
    echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-down' style='color:#FF0000;'></i></td>";
    echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-down' style='color:#FF0000;'></i></td>";
    echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-down' style='color:#FF0000;'></i></td>";
    echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-down' style='color:#FF0000;'></i></td>";
    echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-down' style='color:#FF0000;'></i></td>";
    echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-down' style='color:#FF0000;'></i></td>";
    echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-down' style='color:#FF0000;'></i></td>";
    echo "</tr>";
} else {
    $somaimf = 0;
    $somagum = 0;
    $somaprofutura = 0;
    $somacereal = 0;
    $somasustain = 0;
    while ($valores = pg_fetch_array($sql_cm_geral)) {
        $somaimf = $somaimf + $valores['imf'];
        $somagum = $somagum + $valores['gum'];
        $somaprofutura = $somaprofutura + $valores['profutura'];
        $somacereal = $somacereal + $valores['cereal'];
        $somasustain = $somasustain + $valores['sustain'];
        $cm = 0;

        echo "<tr>"
        . "<td id='cliente_desc'>" . $valores['idcli'] ."</td>"
        . "<td id='cliente_desc'>" . $valores['cliente'] . "</td>";

        if (round($valores['imf'], 2) <= 0) {
            echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-down' style='color:#FF0000;' title='R$ " . round($valores['imf'], 2) . "'></i></td>";
        } else {
            echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-up' style='color:#000066;' title='R$ " . round($valores['imf'], 2) . "'></i></td>";
            $cm++;
        }
        if (round($valores['gum'], 2) <= 0) {
            echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-down' style='color:#FF0000;' title='R$ " . round($valores['gum'], 2) . "'></i></td>";
        } else {
            echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-up' style='color:#000066;' title='R$ " . round($valores['gum'], 2) . "'></i></td>";
            $cm++;
        }
        if (round($valores['profutura'], 2) <= 0) {
            echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-down' style='color:#FF0000;' title='R$ " . round($valores['profutura'], 2) . "'></i></td>";
        } else {
            echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-up' style='color:#000066;' title='R$ " . round($valores['profutura'], 2) . "'></i></td>";
            $cm++;
        }
        if (round($valores['cereal'], 2) <= 0) {
            echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-down' style='color:#FF0000;' title='R$ " . round($valores['cereal'], 2) . "'></i></td>";
        } else {
            echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-up' style='color:#000066;' title='R$ " . round($valores['cereal'], 2) . "'></i></td>";
            $cm++;
        }
        if (round($valores['sustain'], 2) <= 0) {
            echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-down' style='color:#FF0000;' title='R$ " . round($valores['sustain'], 2) . "'></i></td>";
        } else {
            echo "<td style='vertical-align:middle; text-align:center;'><i class='fa fa-fw fa-thumbs-o-up' style='color:#000066;' title='R$ " . round($valores['sustain'], 2) . "'></i></td>";
            $cm++;
        }
        defineCorCliPositivadoDAN(number_format($valores['total'], 0, ',', '.'));

        echo "</tr>";

        if ($cm == 7) {
            $i++;
            $i2++;
        }
    }
}
?>    
<script>
    var imf =<?php echo $somaimf; ?>;
    var gum =<?php echo $somagum; ?>;
    var profutura =<?php echo $somaprofutura; ?>;
    var cereal =<?php echo $somacereal; ?>;
    var sustain =<?php echo $somasustain; ?>;
</script>





<script src="vendor/chart.js/Chart.min.js"></script>
<script src="js/graficoselloutdanone.js"></script>

