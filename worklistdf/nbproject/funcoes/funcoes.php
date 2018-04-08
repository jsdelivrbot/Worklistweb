<?php
date_default_timezone_set('America/Sao_Paulo');

function DiasUteis($yDataInicial, $yDataFinal) {

    $diaFDS = 0; //dias não úteis(Sábado=6 Domingo=0)
    $calculoDias = CalculaDias($yDataInicial, $yDataFinal); //número de dias entre a data inicial e a final
    $diasUteis = 0;

    while ($yDataInicial != $yDataFinal) {
        $diaSemana = date("w", dataToTimestamp($yDataInicial));
        if ($diaSemana == 0 || $diaSemana == 6) {
            //se SABADO OU DOMINGO, SOMA 01
            $diaFDS++;
        } else {
            //senão vemos se este dia é FERIADO
            for ($i = 0; $i <= 12; $i++) {
                if ($yDataInicial == Feriados(date("Y"), $i)) {
                    $diaFDS++;
                }
            }
        }
        $yDataInicial = Soma1dia($yDataInicial); //dia + 1
    }

    return $calculoDias - $diaFDS;
}

function CalculaDias($xDataInicial, $xDataFinal) {
    $time1 = dataToTimestamp($xDataInicial);
    $time2 = dataToTimestamp($xDataFinal);

    $tMaior = $time1 > $time2 ? $time1 : $time2;
    $tMenor = $time1 < $time2 ? $time1 : $time2;

    $diff = $tMaior - $tMenor;
    $numDias = $diff / 86400;
    //86400 é o número de segundos que 1 dia possui  
    return $numDias;
}

function Feriados($ano, $posicao) {
    $dia = 86400;
    $datas = array();
    //$datas['pascoa'] = easter_date($ano);
    //$datas['sexta_santa'] = $datas['pascoa'] - (3 * $dia);
    //$datas['carnaval'] = $datas['pascoa'] - (48 * $dia);
    // $datas['corpus_cristi'] = $datas['pascoa'] + (60 * $dia);
    $feriados = array(
        '01/01',
        '02/02', // Navegantes
        //  date('d/m', $datas['carnaval']),
        //  date('d/m', $datas['sexta_santa']),
        //  date('d/m', $datas['pascoa']),
        '28/02',
        '01/03',
        '14/04',
        '16/04',
        '21/04',
        '01/05',
        '15/06',
        //  date('d/m', $datas['corpus_cristi']),
        '20/09', // Revolução Farroupilha \m/
        '12/10',
        '02/11',
        '15/11',
        '25/12'
    );

    return $feriados[$posicao] . "/" . $ano;
}

//FORMATA COMO TIMESTAMP
/* Esta função é bem simples, e foi criada somente para nos ajudar a formatar a data já em formato  TimeStamp facilitando nossa soma de dias para uma data qualquer. */
function dataToTimestamp($data) {
    $ano = substr($data, 6, 4);
    $mes = substr($data, 3, 2);
    $dia = substr($data, 0, 2);
    return mktime(0, 0, 0, $mes, $dia, $ano);
}

//SOMA 01 DIA   
function Soma1dia($data) {
    $ano = substr($data, 6, 4);
    $mes = substr($data, 3, 2);
    $dia = substr($data, 0, 2);
    return date("d/m/Y", mktime(0, 0, 0, $mes, $dia + 1, $ano));
}

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

function defineCorInadimplencia($valor) {
    if ($valor > 4) {
        echo "<td style='color:red; font-weight: bold;'>$valor</td>";
    } else {
        echo "<td style='color:#0042ff; font-weight: bold;'>$valor</td>";
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

function defineIconePositivado($valor) {
    if ($valor > 0) {
        echo "<td style='color:#000066; vertical-align:middle; text-align:center; font-weight: bold;'><i class='fa fa-fw fa-thumbs-o-up'></i></td>";
    } else {
        echo "<td style='color:#FF0000; vertical-align:middle; text-align:center; font-weight: bold;'><i class='fa fa-fw fa-thumbs-o-down'></i></td>";
    }
}

function atualizaMeses($qtdMeses) {
    $mesAtual = (int) date('m');
    $aux = $mesAtual - $qtdMeses;
    while ($aux <= $mesAtual) {
        ?>
        <option value=<?php echo $aux; ?> selected=<?php echo $mesAtual; ?>><?php meses($aux); ?></option>
        <?php
        $aux++;
    }
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

