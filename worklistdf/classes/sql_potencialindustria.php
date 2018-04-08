<?php
date_default_timezone_set('America/Sao_Paulo');
if (!isset($_SESSION)) {
    session_start();
}

$messelecao = explode('/', $_POST['mes']);
$messelecionado = $messelecao[1];
$posicao = $messelecao[0];
$anoselecionado = $messelecao[2];

$messelecionado = (int) $messelecionado;

require_once 'bd.php';
include_once '../funcoes/funcoes.php';

$iniciocota = buscarDiasMes($messelecionado) . $anoselecionado;
$fimcota = buscarUltimoDia($messelecionado) . $anoselecionado;

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

$bd = new bd();
$query_industria = "select
                    c.fantasia,
                    b.valormeta,
                    a.venda                                              
                    from
                    (select
                    a.idvendedor,
                    a.idindustria,
                    SUM(a.mes$posicao) as venda
                    from sys_vendas a
                    where
                    a.canalvendas IN ('VENDEDOR','OLL')
                    group by a.idvendedor,
                    a.idindustria)a,
                    (select
                    cast(b.idrca as integer) as idrca,
                    b.idindustria,                                              
                    b.valormeta
                    from sys_metas b
                    where
                    b.datameta ='" . $iniciocota . "')b,
                    sys_industrias c
                    where
                    a.idindustria = b.idindustria and
                    a.idindustria = c.idindustria and
                    a.idvendedor = b.idrca and
                    a.idvendedor =" . $_SESSION['idrepresentante'] . " order by valormeta desc";
$result_industria = pg_query($query_industria);

if (pg_affected_rows($result_industria) == 0) {
    echo "<tr>";
    echo '<td align="center">-</td>';
    echo '<td>0</td>';
    echo '<td>0</td>';
    echo '<td>0</td>';
    echo '<td>0</td>';
    echo '<td>0</td>';
    echo "</tr>";
} else {
    while ($row_industria = pg_fetch_array($result_industria)) {

        $projecao_industria = $row_industria['venda'] / $diasCorridos * $diasUteis;

        if ($diasFalta > 0) {
            $objDiaIndustria = ($row_industria['valormeta'] - $row_industria['venda']) / $diasFalta;
        } else {
            $objDiaIndustria = ($row_industria['valormeta'] - $row_industria['venda']);
        }
        if ($objDiaIndustria < 0) {
            $objDiaIndustria = 0;
        }
        $perc_proj_industria = $projecao_industria / $row_industria['valormeta'] * 100;
        echo "<tr>";
        echo '<td>' . $row_industria['fantasia'] . '</td>';
        echo '<td>' . number_format($row_industria['valormeta'], 0, ',', '.') . '</td>';
        echo '<td>' . number_format($row_industria['venda'], 0, ',', '.') . '</td>';
        echo '<td>' . number_format($objDiaIndustria, 0, ',', '.') . '</td>';
        echo '<td>' . number_format($projecao_industria, 0, ',', '.') . '</td>';

        defineCor(number_format($perc_proj_industria, 2, ',', '.'));

        echo "</tr>";

        $valormeta_industria = $row_industria['valormeta'];
        $venda_industria = $row_industria['venda'];
    }
}
?>

