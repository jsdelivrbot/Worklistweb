<?php

// Worklist Goiás Saúde - Pagina ajustada para a nova estrutura de supervisão.
// chamar criador de sessão de login
if (!isset($_SESSION)) {

    session_start();
}

if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}

$rca = $_POST['rca'];
$supervisor = $_POST['supervisor'];

//require_once 'funcoes/funcoes.php';
require_once 'bd.php';

$bd = new bd();

$query = "insert into sys_supervisao (idrca,idsupervisor) values ($rca,$supervisor)";

$result = pg_query($query);

if ($result) {
    echo '<table class="table table-bordered dataTable no-footer" id="dataTable" width="100%" cellspacing="0">';
    echo "<tr>";
    echo "<td>" . "<div class='alert alert-success'><strong>Mensagem: </strong>Representante <strong>$rca</strong> associado com Sucesso!</div>" . "</td>";
    echo "</tr>";
    echo "</table>";
}
