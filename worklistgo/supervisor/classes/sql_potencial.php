<?php

// Worklist Goiás Saúde - Pagina ajustada para a nova estrutura de supervisão.

date_default_timezone_set('America/Sao_Paulo');
if (!isset($_SESSION)) {
    session_start();
}

$messelecao = explode('/', $_POST['mes']);
$messelecionado = $messelecao[1];
$posicao = $messelecao[0];
$anoselecionado = $messelecao[2];
$rca = $_POST['rca']; 
$industria = $_POST['industria']; 

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

$query = "select
a.idvendedor, 
c.apelido, 
a.venda, 
b.valormeta 
from 
(select
a.idvendedor, 
SUM(a.mes$posicao) as venda 
from 
sys_vendas a 
where 
a.canalvendas IN ('VENDEDOR','OLL') ";

if($industria<>5933){
    $query.=" and a.idindustria = $industria" ; 
}

$query.=" group by a.idvendedor)a, 
(select cast(b.idrca as integer) as idrca,
 b.valormeta 
 from 
 sys_metas b 
 where 
 b.datameta ='" . $iniciocota . "' and 
 b.idindustria=$industria )b, 
 sys_vendedores c,
 sys_acessos d 
 where 
 a.idvendedor = c.idvendedor and 
 a.idvendedor = b.idrca and
 a.idvendedor = d.idrepresentante and 
 c.idvendedor = d.idrepresentante ";

if($rca==1){
    $query.=" and a.idvendedor in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) "; 
}else{
    $query.=" and a.idvendedor = $rca"; 
}
$result = pg_query($query);

if (pg_affected_rows($result) == 0) {
    echo "<tr>";
    echo '<td>&nbsp;0</td>';
    echo '<td>&nbsp;0</td>';
    echo '<td>&nbsp;0</td>';
    echo '<td>&nbsp;0</td>';
    echo '<td>&nbsp;0</td>';
    echo '<td>&nbsp;0</td>';
    echo "</tr>";
} else {
    while ($row = pg_fetch_array($result)) {
        $nomeVendedor= explode(" ", $row['apelido']);
        $projecao = $row['venda'] / $diasCorridos * $diasUteis;
        if ($diasFalta > 0) {
            $objDia = ($row['valormeta'] - $row['venda']) / $diasFalta;
        } else {
            $objDia = ($row['valormeta'] - $row['venda']);
        }
        if ($objDia < 0) {
            $objDia = 0;
        }

        $perc_proj = $projecao / $row['valormeta'] * 100;
        echo "<tr>&nbsp;";
        echo '<td>&nbsp;' . $nomeVendedor[0] . '</td>';
        echo '<td>&nbsp;' . number_format($row['valormeta'], 0, ',', '.') . '</td>';
        echo '<td>&nbsp;' . number_format($row['venda'], 0, ',', '.') . '</td>';
        echo '<td>&nbsp;' . number_format($objDia, 0, ',', '.') . '</td>';
        echo '<td>&nbsp;' . number_format($projecao, 0, ',', '.') . '</td>';

        defineCor(number_format($perc_proj, 2, ',', '.'));

        echo "</tr>";

        $valormeta += $row['valormeta'];
        $venda += $row['venda'];
    }
    $projecao = $venda / $diasCorridos * $diasUteis;
}

// CALCULA OBJETIVO DIA PARA O SUPERVISOR

        if ($diasFalta > 0) {
            $objDia = ($valormeta - $venda) / $diasFalta;
        } else {
            $objDia = ($valormeta - $venda);
        }
        
        $perc_proj = $projecao / $valormeta * 100;

        echo "<tr>&nbsp;";
        echo '<td class="tdequipe">&nbsp;EQUIPE</td>';
        echo '<td class="tdequipe">&nbsp;' . number_format($valormeta, 0, ',', '.') . '</td>';
        echo '<td class="tdequipe">&nbsp;' . number_format($venda, 0, ',', '.') . '</td>';
        echo '<td class="tdequipe">&nbsp;' . number_format($objDia, 0, ',', '.') . '</td>';
        echo '<td class="tdequipe">&nbsp;' . number_format($projecao, 0, ',', '.') . '</td>';
        defineCorEquipe(number_format($perc_proj, 2, ',', '.'));
        echo "</tr>";



//montando a query para execução no banco
$query = "insert into sys_controleacesso (idusuario,dataacesso,telaacessada) values (" . $_SESSION['idrepresentante'] . ",current_timestamp,'metas')";

//executando a query montada acima
$result = pg_query($query);
if (isset($valormeta)) {
    ?>
    <script>
        var OBJ =<?php echo $valormeta; ?>;
        var VENDA =<?php echo $venda; ?>;
        var PROJ =<?php echo $projecao; ?>;
    </script>
    <?php
} else {
    ?>
    <script>
        var OBJ = 0;
        var VENDA = 0;
        var PROJ = 0;
    </script>

    <?php
}
?>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<!-- Page level plugin JavaScript-->
<script src="vendor/chart.js/Chart.min.js"></script>
<script src="vendor/datatables/jquery.dataTables.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.js"></script>
<!-- Custom scripts for all pages-->
<script src="js/sb-admin.min.js"></script>
<!-- Custom scripts for this page-->
<script src="js/sb-admin-datatables.min.js"></script>
<script src="js/sb-admin-charts.min.js"></script>