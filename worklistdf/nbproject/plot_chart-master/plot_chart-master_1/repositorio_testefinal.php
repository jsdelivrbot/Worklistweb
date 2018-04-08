<?php

session_start();

$industriaselecionada = $_POST['industriaselecionada'];

try {

    require_once '../../classes/bd.php';
    $bd = new bd();

//query para pegar total da carteira do vendedor
    $query = "select count(idcliente) as carteira
    from sys_carteira where idvendedor =" . $_SESSION['idrepresentante'];
    $result = pg_query($query);
    $carteira = pg_fetch_array($result);
    

    //query para contar a positivação do vendedor

    $query = "select
idcliente,
SUM(mes5) as MES5,
SUM(mes4) as MES4,
SUM(mes3) as MES3,
SUM(mes2) as MES2,
SUM(mes1) as MES1
from
sys_vendas
where
idvendedor=" . $_SESSION['idrepresentante'];
if ($industriaselecionada <> 1) {
  $query .= " and idindustria =".$industriaselecionada;  
}
$query .= "and canalvendas in('VENDEDOR','OLL') group by idcliente";

    //declarando Variáveis
    $data = array('pst' => [], 'mes' => []);
    $mes1 = 0;
    $mes2 = 0;
    $mes3 = 0;
    $mes4 = 0;
    $mes5 = 0;
    $positivacao = 0;
    //executando a query
    $result = pg_query($query);
    //leitura dos dados da query
    while ($row = pg_fetch_array($result)) {
        if ($row['mes5'] > 0) {
            $mes5++;
        }
        if ($row['mes4'] > 0) {
            $mes4++;
        }
        if ($row['mes3'] > 0) {
            $mes3++;
        }
        if ($row['mes2'] > 0) {
            $mes2++;
        }
        if ($row['mes1'] > 0) {
            $mes1++;
        }
        if (($row['mes5'] + $row['mes4'] + $row['mes3'] + $row['mes2']) > 0) {
            $positivacao++;
        }
    }

    array_push($data['mes'], utf8_encode('Cart'));
    array_push($data['mes'], utf8_encode('Jul'));
    array_push($data['mes'], utf8_encode('Ago'));
    array_push($data['mes'], utf8_encode('Set'));
    array_push($data['mes'], utf8_encode('Out'));
    array_push($data['mes'], utf8_encode('Nov'));
    array_push($data['mes'], utf8_encode('Per.'));

    array_push($data['pst'], $carteira['carteira']);
    array_push($data['pst'], $mes5);
    array_push($data['pst'], $mes4);
    array_push($data['pst'], $mes3);
    array_push($data['pst'], $mes2);
    array_push($data['pst'], $mes1);
    array_push($data['pst'], $positivacao);
    // array_push($data['mes'], 'mes');
    // array_push($data['pst'],$mes5 );

    echo json_encode($data);
} catch (\Exception $e) {
    var_dump($e);
    die();
}
?>