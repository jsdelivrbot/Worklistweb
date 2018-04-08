<?php
date_default_timezone_set('America/Sao_Paulo');
if (!isset($_SESSION)) {
    session_start();
}

$messelecionado = $_POST['mes'];
$aux3 = explode('/', $messelecionado);
$messelecionado=$aux3[1];
$messelecionado = (int) $messelecionado;

require_once 'bd.php';
include_once '../funcoes/funcoes.php';

$iniciocota = buscarDiasMes($messelecionado) . date('Y');
$fimcota = buscarUltimoDia($messelecionado) . date('Y');

$aux = explode('/', $iniciocota);
$dia = $aux[0];
$mes = $aux[1];
$ano = $aux[2];

$mesAtual = (int) date('m');
$feriadosDiasCorridos = buscaFeriados($iniciocota, $fimcota);
$feriadosDiasUteis = buscaFeriadosDiasUteis($iniciocota, $fimcota);

if ($messelecionado == $mesAtual) {
    $diaDataSelecionada = date('d');
    $diasUteis = dias_uteis($mes, $ano, $feriadosDiasUteis);
    $diasCorridos = dias_corridos($mes, $ano, $diaDataSelecionada, $feriadosDiasCorridos);
} else {
    $diasUteis = dias_uteis($mes, $ano, $feriadosDiasUteis);
    $diasCorridos = dias_uteis($mes, $ano, $feriadosDiasUteis);
}

$diasFalta = $diasUteis - $diasCorridos;
?>


<td><?php echo $diasUteis; ?></td>
<td><?php echo $diasCorridos; ?></td>
<td><?php echo $diasFalta; ?></td>
