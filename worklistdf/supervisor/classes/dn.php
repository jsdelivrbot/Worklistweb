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
    $mesInformado = 01;
    $aux = 0;
}

$rca = $_POST['rca'];

if ($aux == 1) {
    require_once '../funcoes/funcoes.php';
} else {
    require_once 'funcoes/funcoes.php';
}
!@($conexao = pg_connect("host=187.72.34.18 dbname=worklist port=5432 user=df password=tidf123"));

$inicio_mes = buscarDiasMes($mesInformado);
$final_mes = buscarUltimoDia($mesInformado);
$inicio_mes .=$ano;//date('Y');
$final_mes .= $ano;//date('Y');

$sql_cota_dn="select 
a.grupo,
sum(a.dn) as dn
from 
sys_obj_danone a,
sys_acessos b 
where
a.id_rca=b.idrepresentante";
if ($rca == 1) {
    $sql_cota_dn .= " and a.id_rca in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
} else {
    $sql_cota_dn .= " and a.id_rca = $rca";
}
$sql_cota_dn.=" and mes='$inicio_mes'
group by a.grupo";


$sql_cota_dn = pg_query($sql_cota_dn);

while ($exibe2 = pg_fetch_array($sql_cota_dn)) {
    if (strcasecmp($exibe2['grupo'], 'GUM') == 0) {
        $gum_dn = $exibe2['dn'];
    } else if (strcasecmp($exibe2['grupo'], 'GERAL') == 0) {
        $geral_dn = $exibe2['dn'];
    } elseif (strcasecmp($exibe2['grupo'], 'CEREAL') == 0) {
        $cereal_dn = $exibe2['dn'];
    } else if (strcasecmp($exibe2['grupo'], 'IMF') == 0) {
        $imf_dn = $exibe2['dn'];
    } else if (strcasecmp($exibe2['grupo'], 'SUSTAIN') == 0) {
        $sustain_dn = $exibe2['dn'];
    } else if (strcasecmp($exibe2['grupo'], 'PROFUTURA') == 0) {
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


$sql_geral_dn ="
	select
        COUNT(DISTINCT a.cnpj) AS dn
        from 
        sys_farol_danone a,
        sys_acessos b
        where
        a.id_rca=b.idrepresentante";
if ($rca == 1) {
    $sql_geral_dn .= "  and a.id_rca in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
} else {
    $sql_geral_dn .= " and a.id_rca = $rca";
}
$sql_geral_dn .= " and a.mes_venda >='$inicio_mes'
        and a.mes_venda<='$final_mes' 
        and a.tipo_vendedor='VENDEDOR'"; 

$sql_geral_dn = pg_query($sql_geral_dn);
$array_geral_dn = pg_fetch_assoc($sql_geral_dn);
$dn_realizado = $array_geral_dn['dn'];

$sql_realizado_dn = "select a.divisao, 
       SUM(a.total) AS TOTAL, 
        SUM(a.dn) AS DN 
        from (select a.divisao, SUM(a.total) as total, 
        COUNT(DISTINCT a.cnpj) AS dn
        from
        sys_farol_danone a,
        sys_acessos b 
        where 
        a.id_rca=b.idrepresentante";
if ($rca == 1) {
    $sql_realizado_dn .= "  and a.id_rca in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
} else {
    $sql_realizado_dn .= " and a.id_rca = $rca";
}
$sql_realizado_dn .= " and a.mes_venda >='$inicio_mes'
        and a.mes_venda<='$final_mes' 
        and tipo_vendedor='VENDEDOR'
         group by a.divisao 
         union all 
         select distinct a.divisao, 
         0 as total, 
         0 as dn 
         from 
         sys_farol_danone a)a 
         group by a.divisao 
         ORDER BY a.DIVISAO asc";

$sql_realizado_dn = pg_query($sql_realizado_dn);

// $cereal_realizado=0;
while ($exibe3 = pg_fetch_array($sql_realizado_dn)) {
    if (strcasecmp($exibe3['divisao'], 'CEREAL') == 0) {
        $cereal_realizado_dn = $exibe3['dn'];
    } elseif (strcasecmp($exibe3['divisao'], 'GUM') == 0) {
        $gum_realizado_dn = $exibe3['dn'];
    } else if (strcasecmp($exibe3['divisao'], 'IMF') == 0) {
        $imf_realizado_dn = $exibe3['dn'];
    } else if (strcasecmp($exibe3['divisao'], 'SUSTAIN') == 0) {
        $sustain_realizado_dn = $exibe3['dn'];
    } else if (strcasecmp($exibe3['total'], 'PROFUTURA')) {
        $profutura_realizado_dn = $exibe3['dn'];
    }
}


if (isset($cereal_dn)) {
    $perc_cereal_dn = ($cereal_realizado_dn / $cereal_dn) * 100;
} else {
    $cereal_dn = 0;
    $perc_cereal_dn=0;
}

if (isset($imf_dn)) {
    $perc_imf_dn = ($imf_realizado_dn / $imf_dn) * 100;
} else {
    $imf_dn = 0;
    $perc_imf_dn=0;
}
if (isset($gum_dn)) {
    $perc_gum_dn = ($gum_realizado_dn / $gum_dn) * 100;
} else {
    $gum_dn = 0;
    $perc_gum_dn=0;
}
if (isset($sustain_dn)) {
    $perc_sustain_dn = ($sustain_realizado_dn / $sustain_dn) * 100;
} else {
    $sustain_dn = 0;
    $perc_sustain_dn=0;
}
if (isset($profutura_dn)) {
    $perc_profutura_dn = ($profutura_realizado_dn / $profutura_dn) * 100;
} else {
    $profutura_dn = 0;
    $perc_profutura_dn=0;
}

if(isset($geral_dn)){
    $perc_total_dn = ($dn_realizado / $geral_dn) * 100;
}else{
    $geral_dn=0;
    $perc_total_dn=0;
}

if($geral_dn==0){    
    $perc_total_dn=0;
}else{
    $perc_total_dn = ($dn_realizado/ $geral_dn) * 100;
}


?> 
<tr>
    <td style="background-color: #f7f7f7; font-weight: bold;">GRUPO</td>
    <td style="background-color: #f7f7f7; font-weight: bold;">POTENCIAL</td>
    <td style="background-color: #f7f7f7; font-weight: bold;">POSITIVADO</td>
    <td style="background-color: #f7f7f7; font-weight: bold;">%COB</td>
</tr>
<tr>
    <td width="300">GERAL</td>
    <td width="100"><?php echo '&nbsp'.$geral_dn; ?></td>
    <td width="100"><?php echo '&nbsp'.$dn_realizado; ?></td>
    <?php echo '&nbsp'.defineCor(number_format($perc_total_dn, 2,',','.')); ?>
</tr>
<tr>
    <td>IMF</td>
    <td><?php echo '&nbsp'.$imf_dn; ?></td>
    <td><?php echo '&nbsp'.$imf_realizado_dn; ?></td>
    <?php echo '&nbsp'.defineCor(number_format($perc_imf_dn, 2,',','.')); ?>
</tr>
<tr>
    <td>PROFUTURA</td>
    <td><?php echo '&nbsp'.$profutura_dn; ?></td>
    <td><?php echo '&nbsp'.$profutura_realizado_dn; ?></td>
    <?php echo '&nbsp'.defineCor(number_format($perc_profutura_dn, 2,',','.')); ?>
</tr>
<tr>
    <td>SUSTAIN</td>
    <td><?php echo '&nbsp'.$sustain_dn; ?></td>
    <td><?php echo '&nbsp'.$sustain_realizado_dn; ?></td>
 <?php echo '&nbsp'.defineCor(number_format($perc_sustain_dn, 2,',','.')); ?>
</tr>
<tr>
    <td>CEREAL</td>
    <td><?php echo '&nbsp'.$cereal_dn; ?></td>
    <td><?php echo '&nbsp'.$cereal_realizado_dn; ?></td>
   <?php echo '&nbsp'.defineCor(number_format($perc_cereal_dn, 2,',','.')); ?>
</tr>
<tr>
    <td>GUM</td>
    <td><?php echo '&nbsp'.$gum_dn; ?></td>
    <td><?php echo '&nbsp'.$gum_realizado_dn; ?></td>
    <?php echo '&nbsp'.defineCor(number_format($perc_gum_dn, 2,',','.')); ?>
</tr>

