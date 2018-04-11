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

$idcliente = $_POST['idcliente'];



if ($aux == 1) {
    require_once '../funcoes/funcoes.php';
} else {
    require_once 'funcoes/funcoes.php';
}
!@($conexao = pg_connect("host=187.72.34.18 dbname=worklist port=5432 user=df password=tidf123"));

$inicio_mes = buscarDiasMes($mesInformado);
$final_mes = buscarUltimoDia($mesInformado);
$inicio_mes .= $ano;
$final_mes .= $ano;

if ($mesInformado == date('m')) {
    $final_mes = date('d/m/Y');
}

$sql2 = "select grupo,dn,cota from sys_obj_danone where id_rca=" . $_SESSION['idrepresentante'] . " and mes='$inicio_mes'";

$sql2 = pg_query($sql2);

while ($exibe2 = pg_fetch_array($sql2)) {
    if (strcasecmp($exibe2['grupo'], 'GUM') == 0) {
        $gum_cota = $exibe2['cota'];
        $gum_dn = $exibe2['dn'];
    } else if (strcasecmp($exibe2['grupo'], 'GERAL') == 0) {
        $geral_cota = $exibe2['cota'];
        $geral_dn = $exibe2['dn'];
    } elseif (strcasecmp($exibe2['grupo'], 'CEREAL') == 0) {
        $cereal_cota = $exibe2['cota'];
        $cereal_dn = $exibe2['dn'];
    } else if (strcasecmp($exibe2['grupo'], 'IMF') == 0) {
        $imf_cota = $exibe2['cota'];
        $imf_dn = $exibe2['dn'];
    } else if (strcasecmp($exibe2['grupo'], 'SUSTAIN') == 0) {
        $sustain_cota = $exibe2['cota'];
        $sustain_dn = $exibe2['dn'];
    } else if (strcasecmp($exibe2['grupo'], 'PROFUTURA') == 0) {
        $profutura_cota = $exibe2['cota'];
        $profutura_dn = $exibe2['dn'];
    }
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


$sql_geral_dn = "select ROUND(SUM(a.total),2) as total,COUNT(DISTINCT a.cnpj) AS dn"
        . " from sys_farol_danone a where a.mes_venda >='$inicio_mes' and a.mes_venda<='$final_mes' "
        . "and a.id_rca=" . $_SESSION['idrepresentante'] . " and a.tipo_vendedor='VENDEDOR'";

$sql_geral_dn = pg_query($sql_geral_dn);
$array_geral_dn = pg_fetch_assoc($sql_geral_dn);
$dn_realizado = $array_geral_dn['dn'];
$total_realizado = $array_geral_dn['total'];


$sql3 = "select a.divisao, SUM(a.total) AS TOTAL, SUM(a.dn) AS DN from (select a.divisao, SUM(a.total) as total, COUNT(DISTINCT a.cnpj) AS dn
        from sys_farol_danone a where id_rca=" . $_SESSION['idrepresentante'] . " and a.mes_venda >= '$inicio_mes'
        and a.mes_venda <='$final_mes' and tipo_vendedor='VENDEDOR'";

$sql3 .= " group by a.divisao union all select distinct a.divisao, 0 as total, 0 as dn from sys_farol_danone a)a group by a.divisao ORDER BY a.DIVISAO asc";

$sql3 = pg_query($sql3);

// $cereal_realizado=0;
while ($exibe3 = pg_fetch_array($sql3)) {
    if (strcasecmp($exibe3['divisao'], 'CEREAL') == 0) {
        $cereal_realizado = $exibe3['total'];
        $cereal_realizado_dn = $exibe3['dn'];
    } elseif (strcasecmp($exibe3['divisao'], 'GUM') == 0) {
        $gum_realizado = $exibe3['total'];
        $gum_realizado_dn = $exibe3['dn'];
    } else if (strcasecmp($exibe3['divisao'], 'IMF') == 0) {
        $imf_realizado = $exibe3['total'];
        $imf_realizado_dn = $exibe3['dn'];
    } else if (strcasecmp($exibe3['divisao'], 'SUSTAIN') == 0) {
        $sustain_realizado = $exibe3['total'];
        $sustain_realizado_dn = $exibe3['dn'];
    } else if (strcasecmp($exibe3['total'], 'PROFUTURA')) {
        $profutura_realizado = $exibe3['total'];
        $profutura_realizado_dn = $exibe3['dn'];
    }
}
//calculo percentual do sell-out
if (isset($cereal_cota)) {
    $perc_cereal = ($cereal_realizado / $cereal_cota) * 100;
} else {
    $perc_cereal = 0;
    $cereal_cota = 0;
}

if (isset($imf_cota)) {
    $perc_imf = ($imf_realizado / $imf_cota) * 100;
} else {
    $perc_imf = 0;
    $imf_cota = 0;
}

if (isset($gum_cota)) {
    $perc_gum = ($gum_realizado / $gum_cota) * 100;
} else {
    $perc_gum = 0;
    $gum_cota = 0;
}

if (isset($sustain_cota)) {
    $perc_sustain = ($sustain_realizado / $sustain_cota) * 100;
} else {
    $perc_sustain = 0;
    $sustain_cota = 0;
}

if (isset($profutura_cota)) {
    $perc_profutura = ($profutura_realizado / $profutura_cota) * 100;
} else {
    $perc_profutura = 0;
    $profutura_cota = 0;
}

if (isset($cereal_dn)) {
    $perc_cereal_dn = ($cereal_realizado_dn / $cereal_dn) * 100;
} else {
    $cereal_dn = 0;
    $perc_cereal_dn = 0;
}

if (isset($imf_dn)) {
    $perc_imf_dn = ($imf_realizado_dn / $imf_dn) * 100;
} else {
    $imf_dn = 0;
    $perc_imf_dn = 0;
}
if (isset($gum_dn)) {
    $perc_gum_dn = ($gum_realizado_dn / $gum_dn) * 100;
} else {
    $gum_dn = 0;
    $perc_gum_dn = 0;
}
if (isset($sustain_dn)) {
    $perc_sustain_dn = ($sustain_realizado_dn / $sustain_dn) * 100;
} else {
    $sustain_dn = 0;
    $perc_sustain_dn = 0;
}
if (isset($profutura_dn)) {
    $perc_profutura_dn = ($profutura_realizado_dn / $profutura_dn) * 100;
} else {
    $profutura_dn = 0;
    $perc_profutura_dn = 0;
}


if (isset($geral_cota)) {
    $perc_total_realizado = ($total_realizado / $geral_cota) * 100;
} else {
    $geral_cota = 0;
    $perc_total_realizado = 0;
}

if (isset($geral_dn)) {
    $perc_total_dn = ($dn_realizado / $geral_dn) * 100;
} else {
    $perc_total_dn = 0;
}


$projecao = ($total_realizado / $diasCorridos) * $diasUteis;
$proj_cereal = ($cereal_realizado / $diasCorridos) * $diasUteis;
$proj_imf = ($imf_realizado / $diasCorridos) * $diasUteis;
$proj_gum = ($gum_realizado / $diasCorridos) * $diasUteis;
$proj_sustain = ($sustain_realizado / $diasCorridos) * $diasUteis;
$proj_profutura = ($profutura_realizado / $diasCorridos) * $diasUteis;

//percentual projecao
if ($geral_cota == 0) {
    $perc_projecao = 0;
} else {
    $perc_projecao = ($projecao / $geral_cota) * 100;
}

if ($cereal_cota == 0) {
    $perc_proj_cereal = 0;
} else {
    $perc_proj_cereal = ($proj_cereal / $cereal_cota) * 100;
}

if ($imf_cota == 0) {

    $perc_proj_imf = 0;
} else {
    $perc_proj_imf = ($proj_imf / $imf_cota) * 100;
}

if ($gum_cota == 0) {
    $perc_proj_gum = 0;
} else {
    $perc_proj_gum = ($proj_gum / $gum_cota) * 100;
}

if ($sustain_cota == 0) {
    $perc_proj_sustain = 0;
} else {
    $perc_proj_sustain = ($proj_sustain / $sustain_cota) * 100;
}

if ($profutura_cota == 0) {
    $perc_proj_profutura = 0;
} else {
    $perc_proj_profutura = ($proj_profutura / $profutura_cota) * 100;
}
?> 

<tr>
    <td style="background-color: #f7f7f7; font-weight: bold;">GRUPO</td>
    <td style="background-color: #f7f7f7; font-weight: bold;">POTENCIAL</td>
    <td style="background-color: #f7f7f7; font-weight: bold;">VENDA</td>
    <td style="background-color: #f7f7f7; font-weight: bold;">PROJEÇÃO</td>
    <td style="background-color: #f7f7f7; font-weight: bold;">%COB</td>
</tr>
<tr>
    <td width="300">GERAL</td>
    <td width="100"><?php echo '&nbsp' . number_format($geral_cota, 0, ',', '.'); ?></td>
    <td width="100"><?php echo '&nbsp' . number_format($total_realizado, 0, ',', '.'); ?></td>
    <td width="100"> <?php echo '&nbsp' . number_format($projecao, 0, ',', '.'); ?></td>
    <?php echo '&nbsp' . defineCor(number_format($perc_projecao, 2, ',', '.')); ?>
</tr>
<tr>
    <td>IMF</td>
    <td> <?php echo '&nbsp' . number_format($imf_cota, 0, ',', '.'); ?></td>
    <td> <?php echo '&nbsp' . number_format($imf_realizado, 0, ',', '.'); ?></td>
    <td> <?php echo '&nbsp' . number_format($proj_imf, 0, ',', '.'); ?></td>
    <?php echo '&nbsp' . defineCor(number_format($perc_proj_imf, 2, ',', '.')); ?>
</tr>
<tr>
    <td>PROFUTURA</td>
    <td> <?php echo '&nbsp' . number_format($profutura_cota, 0, ',', '.'); ?></td>
    <td> <?php echo '&nbsp' . number_format($profutura_realizado, 0, ',', '.'); ?></td>
    <td> <?php echo '&nbsp' . number_format($proj_profutura, 0, ',', '.'); ?></td>
    <?php echo '&nbsp' . defineCor(number_format($perc_proj_profutura, 2, ',', '.')); ?>
</tr>
<tr>
    <td>SUSTAIN</td>
    <td> <?php echo '&nbsp' . number_format($sustain_cota, 0, ',', '.'); ?></td>
    <td> <?php echo '&nbsp' . number_format($sustain_realizado, 0, ',', '.'); ?></td>
    <td> <?php echo '&nbsp' . number_format($proj_sustain, 0, ',', '.'); ?></td>
    <?php echo '&nbsp' . defineCor(number_format($perc_proj_sustain, 2, ',', '.')); ?>
</tr>
<tr>
    <td>CEREAL</td>
    <td> <?php echo '&nbsp' . number_format($cereal_cota, 0, ',', '.'); ?></td>
    <td> <?php echo '&nbsp' . number_format($cereal_realizado, 0, ',', '.'); ?></td>
    <td> <?php echo '&nbsp' . number_format($proj_cereal, 0, ',', '.'); ?></td>
    <?php echo '&nbsp' . defineCor(number_format($perc_proj_cereal, 2, ',', '.')); ?></td>
</tr>
<tr>
    <td>GUM</td>
    <td> <?php echo '&nbsp' . number_format($gum_cota, 0, ',', '.'); ?></td>
    <td> <?php echo '&nbsp' . number_format($gum_realizado, 0, ',', '.'); ?></td>
    <td> <?php echo '&nbsp' . number_format($proj_gum, 0, ',', '.'); ?></td>
    <?php echo '&nbsp' . defineCor(number_format($perc_proj_gum, 2, ',', '.')); ?>
</tr>
<?php
//montando a query para execução no banco
$query = "insert into sys_controleacesso (idusuario,dataacesso,telaacessada) values (".$_SESSION['idrepresentante'].",current_timestamp,'visaoegarra')";

//executando a query montada acima
$result = pg_query($query);
?>
<script>
    var geralcota =<?php echo $geral_cota; ?>;
    var totalrealizado =<?php echo round($total_realizado, 0); ?>;
    var projecao =<?php echo round($projecao, 0); ?>;

    var dnpotencial =<?php echo $geral_dn; ?>;
    var dnrealizado =<?php echo $dn_realizado; ?>
</script>





<script src="vendor/chart.js/Chart.min.js"></script>
<script src="js/graficodndanone.js"></script>
