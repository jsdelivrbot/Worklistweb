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

$query = "delete from sys_supervisao where idrca= $rca and idsupervisor =$supervisor";

$result = pg_query($query);

if ($result) {

//Montando Query
    $queryequipe = "
select 
idrepresentante,
nome,
sobrenome,
usuario,
senha 
from 
sys_acessos a,
sys_supervisao b 
where 
a.idrepresentante = b.idrca and
idsupervisor = $supervisor";


    $resultequipe = pg_query($queryequipe);

    while ($row = pg_fetch_array($resultequipe)) {
        echo "<tr>";
        echo "<td>" . $row['idrepresentante'] . "</td>";
        echo "<td>" . $row['nome'] . "</td>";
        echo "<td>" . $row['sobrenome'] . "</td>";
        echo "<td>" . $row['usuario'] . "</td>";
        echo "<td>" . $row['senha'] . "</td>";
        echo '<td width=40 align="center"><button type="button" name="btn_excluir" class="btn_excluir" data-id_rca="' . $row['idrepresentante'] . '"><i class="fa fa-trash " style="color:red;" aria-hidden="true"></i></button></td>';
        echo "</tr>";
    }
    echo "<tr>";
    echo "<td colspan='6'>" . "<div class='alert alert-success'><strong>Mensagem: </strong>Excluido com Sucesso</div>" . "</td>";
    echo "</tr>";
}
?>

<script>
    $(document).ready(function () {
        $('.btn_excluir').click(function () {
            var rca = $(this).data('id_rca');
            var supervisor = <?php echo $supervisor ?>;
            $.post('classes/sql_excluir_representante.php', {rca:rca,supervisor:supervisor}, function (data) {
                $("#equipe").empty();
                $("#equipe").html(data);
            });
        });

    });
</script>