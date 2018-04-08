<?php

try {

    require_once '../../classes/bd.php';
    $bd = new bd();
    $query = "SELECT 
IDINDUSTRIA as idindustria,
FANTASIA AS industria
from  SYS_INDUSTRIAS
WHERE FANTASIA <> '' AND
LINHA = 'FARMA' and idindustria in (2000529,2006181,1000005,2006083,2006105)
";
    $data = array('idindustria' => [], 'industria' => []);

    $result = pg_query($query);
    while ($row = pg_fetch_array($result)) {
        array_push($data['industria'], utf8_encode($row['industria']));
        array_push($data['idindustria'], $row['idindustria']);
    }    
    echo json_encode($data);
} catch (\Exception $e) {
    var_dump($e);
    die();
}
?>