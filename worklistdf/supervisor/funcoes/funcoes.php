<?php
date_default_timezone_set('America/Sao_Paulo');

function formataData($valor) {
    $aux = explode('/', $valor);
    $dia = $aux[0];
    $mes = $aux[1];
    $ano = $aux[2];
    return $ano . "-" . $mes . "-" . $dia;
}

function retornaFormato($valor) {
    $aux = explode('-', $valor);
    $dia = $aux[2];
    $mes = $aux[1];
    $ano = $aux[0];
    return $dia . "/" . $mes . "/" . $ano;
}

function buscarDiasMes($mes) {
    if ($mes == 1) {
        $aux = "01/01/";
        return $aux;
    } else if ($mes == 2) {
        $aux = "01/02/";
        return $aux;
    } else if ($mes == 3) {
        $aux = "01/03/";
        return $aux;
    } else if ($mes == 4) {
        $aux = "01/04/";
        return $aux;
    } else if ($mes == 5) {
        $aux = "01/05/";
        return $aux;
    } else if ($mes == 6) {
        $aux = "01/06/";
        return $aux;
    } else if ($mes == 7) {
        $aux = "01/07/";
        return $aux;
    } else if ($mes == 8) {
        $aux = "01/08/";
        return $aux;
    } else if ($mes == 9) {
        $aux = "01/09/";
        return $aux;
    } else if ($mes == 10) {
        $aux = "01/10/";
        return $aux;
    } else if ($mes == 11) {
        $aux = "01/11/";
        return $aux;
    } else if ($mes == 12) {
        $aux = "01/12/";
        return $aux;
    }
}

function buscarUltimoDia($mes) {
    if ($mes == 1) {
        $aux = "31/01/";
        return $aux;
    } else if ($mes == 2) {
        $aux = "28/02/";
        return $aux;
    } else if ($mes == 3) {
        $aux = "31/03/";
        return $aux;
    } else if ($mes == 4) {
        $aux = "30/04/";
        return $aux;
    } else if ($mes == 5) {
        $aux = "31/05/";
        return $aux;
    } else if ($mes == 6) {
        $aux = "30/06/";
        return $aux;
    } else if ($mes == 7) {
        $aux = "31/07/";
        return $aux;
    } else if ($mes == 8) {
        $aux = "31/08/";
        return $aux;
    } else if ($mes == 9) {
        $aux = "30/09/";
        return $aux;
    } else if ($mes == 10) {
        $aux = "31/10/";
        return $aux;
    } else if ($mes == 11) {
        $aux = "30/11/";
        return $aux;
    } else if ($mes == 12) {
        $aux = "31/12/";
        return $aux;
    }
}

function meses($mes) {
    if ($mes == 1) {
        echo "JAN";
    } else if ($mes == 2) {
        echo "FEV";
    } else if ($mes == 3) {
        echo "MAR";
    } else if ($mes == 4) {
        echo "ABR";
    } else if ($mes == 5) {
        echo "MAI";
    } else if ($mes == 6) {
        echo "JUN";
    } else if ($mes == 7) {
        echo "JUL";
    } else if ($mes == 8) {
        echo "AGO";
    } else if ($mes == 9) {
        echo "SET";
    } else if ($mes == 10) {
        echo "OUT";
    } else if ($mes == 11) {
        echo "NOV";
    } else if ($mes == 12) {
        echo "DEZ";
    }
}

function meses2($mes) {
    if ($mes == 1) {
        echo "'JAN',";
    } else if ($mes == 2) {
        echo "'FEV',";
    } else if ($mes == 3) {
        echo "'MAR',";
    } else if ($mes == 4) {
        echo "'ABR',";
    } else if ($mes == 5) {
        echo "'MAI',";
    } else if ($mes == 6) {
        echo "'JUN',";
    } else if ($mes == 7) {
        echo "'JUL',";
    } else if ($mes == 8) {
        echo "'AGO',";
    } else if ($mes == 9) {
        echo "'SET',";
    } else if ($mes == 10) {
        echo "'OUT',";
    } else if ($mes == 11) {
        echo "'NOV',";
    } else if ($mes == 12) {
        echo "'DEZ',";
    }
}

function defineCor($valor) {
    if ($valor < 90) {
        echo "<td style='color:red; font-weight: bold;'>$valor</td>";
    } else if ($valor >= 90 && $valor < 100) {
        echo "<td style='color:#cdd213; font-weight: bold;'>$valor</td>";
    } else if ($valor >= 100) {
        echo "<td style='color:#0042ff; font-weight: bold;'>$valor</td>";
    }
}

function defineCorEquipe($valor) {
    if ($valor < 90) {
        echo "<td class='tdequipe' style='color:red; font-weight: bold;'>$valor</td>";
    } else if ($valor >= 90 && $valor < 100) {
        echo "<td class='tdequipe' style='color:#cdd213; font-weight: bold;'>$valor</td>";
    } else if ($valor >= 100) {
        echo "<td class='tdequipe' style='color:#0042ff; font-weight: bold;'>$valor</td>";
    }
}

function defineCorInadimplencia($valor) {
    if ($valor > 4) {
        echo "<td style='color:red; font-weight: bold;'>$valor</td>";
    } else {
        echo "<td style='color:#0042ff; font-weight: bold;'>$valor</td>";
    }
}

function defineCorCMV($valor) {
    if ($valor >=67) {
        echo "<td style='color:red; vertical-align:middle; text-align:center;  font-weight: bold;'>$valor</td>";
    } else if ($valor >= 66 && $valor < 66.99) {
        echo "<td style='color:#FF9800;; vertical-align:middle; text-align:center;  font-weight: bold;'>$valor</td>";
    } else if ($valor < 66) {
        echo "<td style='color:#1a8600;; vertical-align:middle; text-align:center;  font-weight: bold;'>$valor</td>";
    }
}

function positivo_e_negativo($valor){
    if ($valor < 0) {
        echo "<td style='color:#185752; vertical-align:middle; text-align:center; font-weight: bold;'>".number_format($valor, 2, ',', '.')."</td>";
    } else {
        echo "<td style='color:#FF0000; vertical-align:middle; text-align:center; font-weight: bold;'>".number_format($valor, 2, ',', '.')."</td>";
    }
}

function statuscmv($valor) {
    if ($valor < 0) {
        echo "<td style='color:#185752; vertical-align:middle; text-align:center; font-weight: bold;'><i class='fa fa-fw fa-thumbs-o-up'></i></td>";
    } else {
        echo "<td style='color:#FF0000; vertical-align:middle; text-align:center; font-weight: bold;'><i class='fa fa-fw fa-thumbs-o-down'></i></td>";
    }
}



function defineCorCarteira($valor) {
    if ($valor == 3) {
        echo "<td style='color:#fff; vertical-align:middle; text-align:center; background-color:#009933; font-weight: bold;'>$valor</td>";
    } else if ($valor == 2) {
        echo "<td style='color:#000; vertical-align:middle; text-align:center; background-color:#FFFF00; font-weight: bold;'>$valor</td>";
    } else if ($valor == 1) {
        echo "<td style='color:#fff; vertical-align:middle; text-align:center; background-color:#FF0000; font-weight: bold;'>$valor</td>";
    } else {
        echo "<td style='color:#fff; vertical-align:middle; text-align:center; background-color:#000000; font-weight: bold;'>$valor</td>";
    }
}

function defineCorCliPositivado($valor) {
    if ($valor <= 0) {
        echo "<td style='color:#FF0000; vertical-align:middle; text-align:center; font-weight: bold;'>$valor</td>";
    } else {
        echo "<td style='color:#000066; vertical-align:middle; text-align:center; font-weight: bold;'>$valor</td>";
    }
}

function CorPedidos($valor) {
    if ($valor <= 0) {
        echo "<td style='color:#FF0000; vertical-align:middle; text-align:center; font-weight: bold;'>R$ " . number_format($valor, 2, ',', '.') . "</td>";
    } else {
        echo "<td style='color:#000066; vertical-align:middle; text-align:center; font-weight: bold;'>R$ " . number_format($valor, 2, ',', '.') . "</td>";
    }
}

function defineCorCliPositivadoDAN($valor) {
    if ($valor <= 0) {
        echo "<td style='color:#FF0000; vertical-align:middle; font-weight: bold;'>$valor</td>";
    } else {
        echo "<td style='color:#000066; vertical-align:middle; font-weight: bold;'>$valor</td>";
    }
}

function defineIconePositivado($valor) {
    if ($valor > 0) {
        echo "<td style='color:#000066; vertical-align:middle; text-align:center; font-weight: bold;'><i class='fa fa-fw fa-thumbs-o-up'></i></td>";
    } else {
        echo "<td style='color:#FF0000; vertical-align:middle; text-align:center; font-weight: bold;'><i class='fa fa-fw fa-thumbs-o-down'></i></td>";
    }
}

function atualizaMeses() {
    !@($conexao = pg_connect("host=187.72.34.18 dbname=worklistgo port=5432 user=df password=tidf123"));
    $queryMes = "SELECT 
to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-0 MONTH','MM/YYYY') AS MES1,
to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-1 MONTH','MM/YYYY') AS MES2,
to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-2 MONTH','MM/YYYY') AS MES3,
to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-3 MONTH','MM/YYYY') AS MES4";

    $resultMes = pg_query($queryMes);
    $mes = pg_fetch_array($resultMes);
    ?>
    <option value="<?php echo "1/" . $mes['mes1']; ?>"><?php echo $mes['mes1']; ?></option>
    <option value="<?php echo "2/" . $mes['mes2']; ?>"><?php echo $mes['mes2']; ?></option>
    <option value="<?php echo "3/" . $mes['mes3']; ?>"><?php echo $mes['mes3']; ?></option>
    <option value="<?php echo "4/" . $mes['mes4']; ?>"><?php echo $mes['mes4']; ?></option>
    <?php
}

function atualizaMeses2($qtdMeses) {
    $mesAtual = (int) date('m');
    $aux = $mesAtual - $qtdMeses;
    while ($aux <= $mesAtual) {
        $array = [meses2($aux)];
        $aux++;
    }
}

function atualizaMesesTitulo($qtdMeses) {
    $mesAtual = (int) date('m');
    $aux = $mesAtual - $qtdMeses;
    while ($aux <= $mesAtual) {
        ?>
        <th align="center"><?php echo meses($aux); ?></th> 
        <?php
        $aux++;
    }
}

function dias_uteis($mes, $ano, $feriados) {
    $uteis = 0;
    // Obtém o número de dias no mês 
    // (http://php.net/manual/en/function.cal-days-in-month.php)
    $dias_no_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
    for ($dia = 1; $dia <= $dias_no_mes; $dia++) {

        // Aqui você pode verifica se tem feriado
        // ----------------------------------------
        // Obtém o timestamp
        // (http://php.net/manual/pt_BR/function.mktime.php)
        $timestamp = mktime(0, 0, 0, $mes, $dia, $ano);
        $semana = date("N", $timestamp);

        if ($semana < 6)
            $uteis++;
    }
    return $uteis - $feriados;
}

function dias_corridos($mes, $ano, $dias, $feriados) {
    $uteis = 0;
    for ($dia = 1; $dia <= $dias; $dia++) {

        // Aqui você pode verifica se tem feriado
        // ----------------------------------------
        // Obtém o timestamp
        // (http://php.net/manual/pt_BR/function.mktime.php)
        $timestamp = mktime(0, 0, 0, $mes, $dia, $ano);
        $semana = date("N", $timestamp);

        if ($semana < 6)
            $uteis++;
    }
    return $uteis - $feriados;
}

function buscaFeriados($datainicial, $datafinal) {
    include_once '../classes/bd.php';
    $bd = new bd();
    $query = pg_query("select count(data) as feriados from sys_feriados where data>='$datainicial' and data<='" . date('d/m/Y') . "'");
    $arrayFeriado = pg_fetch_array($query);
    return $arrayFeriado['feriados'];
}

function buscaFeriadosDiasUteis($datainicial, $datafinal) {
    include_once '../classes/bd.php';
    $bd = new bd();
    $query = pg_query("select count(data) as feriados from sys_feriados where data>='$datainicial' and data<='$datafinal'");
    $arrayFeriado = pg_fetch_array($query);
    return $arrayFeriado['feriados'];
}

function valorZerado($valor) {
    if ($valor == 0) {
        echo "<td style='color:#FF0000; vertical-align:middle; text-align:center; font-weight: bold;'>$valor</td>";
    }
}
