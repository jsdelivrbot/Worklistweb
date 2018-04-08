<?php
// EMPRESA: GOIÁS SÁUDE
// chamar criador de sessão de login (PAINEL GOIAS LOGISTICA)
session_start();

// Requerendo Classe de conexão com o banco
require_once '../classes/bd.php';

// Instanciando a classe em uma variavel bd
$bd = new bd();


// definindo meses para preenchimento das TH dos formularios
//montando a query para execução no banco
$queryMes = " SELECT 
to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-0 MONTH','MM') AS MES1,
to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-1 MONTH','MM') AS MES2,
to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-2 MONTH','MM') AS MES3,
to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-3 MONTH','MM') AS MES4,
to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-4 MONTH','MM') AS MES5 ";

//executando a query montada acima
$resultMes = pg_query($queryMes);


while ($mes = pg_fetch_array($resultMes)) {

switch ($mes['mes1']) {
        case "01":    $mes1 = JAN;     break;
        case "02":    $mes1 = FEV;   break;
        case "03":    $mes1 = MAR;       break;
        case "04":    $mes1 = ABR;       break;
        case "05":    $mes1 = MAI;        break;
        case "06":    $mes1 = JUN;       break;
        case "07":    $mes1 = JUL;       break;
        case "08":    $mes1 = AGO;      break;
        case "09":    $mes1 = SET;    break;
        case "10":    $mes1 = OUT;     break;
        case "11":    $mes1 = NOV;    break;
        case "12":    $mes1 = DEZ;    break; 
 }
switch ($mes['mes2']) {
        case "01":    $mes2 = JAN;     break;
        case "02":    $mes2 = FEV;   break;
        case "03":    $mes2 = MAR;       break;
        case "04":    $mes2 = ABR;       break;
        case "05":    $mes2 = MAI;        break;
        case "06":    $mes2 = JUN;       break;
        case "07":    $mes2 = JUL;       break;
        case "08":    $mes2 = AGO;      break;
        case "09":    $mes2 = SET;    break;
        case "10":    $mes2 = OUT;     break;
        case "11":    $mes2 = NOV;    break;
        case "12":    $mes2 = DEZ;    break; 
 }
switch ($mes['mes3']) {
        case "01":    $mes3 = JAN;     break;
        case "02":    $mes3 = FEV;   break;
        case "03":    $mes3 = MAR;       break;
        case "04":    $mes3 = ABR;       break;
        case "05":    $mes3 = MAI;        break;
        case "06":    $mes3 = JUN;       break;
        case "07":    $mes3 = JUL;       break;
        case "08":    $mes3 = AGO;      break;
        case "09":    $mes3 = SET;    break;
        case "10":    $mes3 = OUT;     break;
        case "11":    $mes3 = NOV;    break;
        case "12":    $mes3 = DEZ;    break; 
 }
 switch ($mes['mes4']) {
        case "01":    $mes4 = JAN;     break;
        case "02":    $mes4 = FEV;   break;
        case "03":    $mes4 = MAR;       break;
        case "04":    $mes4 = ABR;       break;
        case "05":    $mes4 = MAI;        break;
        case "06":    $mes4 = JUN;       break;
        case "07":    $mes4 = JUL;       break;
        case "08":    $mes4 = AGO;      break;
        case "09":    $mes4 = SET;    break;
        case "10":    $mes4 = OUT;     break;
        case "11":    $mes4 = NOV;    break;
        case "12":    $mes4 = DEZ;    break; 
 }
switch ($mes['mes5']) {
        case "01":    $mes5 = JAN;     break;
        case "02":    $mes5 = FEV;   break;
        case "03":    $mes5 = MAR;       break;
        case "04":    $mes5 = ABR;       break;
        case "05":    $mes5 = MAI;        break;
        case "06":    $mes5 = JUN;       break;
        case "07":    $mes5 = JUL;       break;
        case "08":    $mes5 = AGO;      break;
        case "09":    $mes5 = SET;    break;
        case "10":    $mes5 = OUT;     break;
        case "11":    $mes5 = NOV;    break;
        case "12":    $mes5 = DEZ;    break; 
 }
        
}


$_SESSION['MES1'] = $mes1;
$_SESSION['MES2'] = $mes2;
$_SESSION['MES3'] = $mes3;
$_SESSION['MES4'] = $mes4;
$_SESSION['MES5'] = $mes5;





//carregando campos do formulario para as variaveis
$usuario = $_POST['usuario'];
$senha = $_POST['senha'];
$conectado = $_POST['conectado'];

// criação do cookie de conexão para manter conectado.
if ($conectado == 1) {
    setcookie('usuarioworklist', $usuario, (time() + (30 * 24 * 360000)));
    setcookie('senhaworklist', $senha, (time() + (30 * 24 * 360000)));
    setcookie('checked', 'checked', (time() + (30 * 24 * 360000)));
} else {
    //deletar cooki de manter conectao
    setcookie('usuarioworklist');
    setcookie('senhaworklist');
    setcookie('checked');
}

//montando a query para execução no banco
$query = "select idusuario,nome,sobrenome,usuario,senha,idrepresentante,tipoacesso,linha from sys_acessos where usuario = '$usuario'";

//executando a query montada acima
$result = pg_query($query);

//verificando se houve resultado no processamento da query
if (pg_num_rows($result) == 0) {
    header('Location: ../login.php?erro=1');
} else {
    $row = pg_fetch_array($result);
    $usuario2 = $row['usuario'];
    $senha2 = $row['senha'];
    $idusuario = $row['idrepresentante'];
}

$input['lembrar'] = true;

//comparando dos dados da query com os dados do formulario e retornando true ou false
if ($usuario == $usuario2 && $senha == $senha2) {

    $_SESSION['logado'] = "SIM";
    $_SESSION['idrepresentante'] = $row['idrepresentante'];
    $_SESSION['usuario'] = $row['nome'] . "&nbsp" . $row['sobrenome'];
    // carrega session com dados de usuario caso o cookie exista no dispositivo 
    $_SESSION['usuarioworklist'] = $_COOKIE['usuarioworklist'];
    $_SESSION['senhaworklist'] = $_COOKIE['senhaworklist'];
    $_SESSION['checked'] = $_COOKIE['checked'];
    $_SESSION['equipe'] = $row['linha'];
    $_SESSION['idusuario'] = $row['idusuario'];
//montando a query para execução no banco
    if ($idusuario == ''){
       $idusuario = 0;
    }
    $query = "insert into sys_controleacesso (idusuario,dataacesso,telaacessada) values (" . $idusuario . ",current_timestamp,'login')";
//executando a query montada acima
    $result = pg_query($query);

//abri index após execução do controle de acesso.
    if ($row['senha'] == 'go123') {
        if ($row['tipoacesso'] == 'supervisor') {
           header('Location: ../supervisor/mudar-senha.php');
        } else {
            header('Location: ../mudar-senha.php');
        }
    } else {
        if ($row['tipoacesso'] == 'supervisor') {
           header('Location: ../supervisor/index.php'); 
        } else {
            header('Location: ../index.php');
        }
    }
} else {
    header('Location: ../login.php?erro=1');
    session_destroy();
}
