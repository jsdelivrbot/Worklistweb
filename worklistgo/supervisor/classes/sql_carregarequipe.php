<?php
// Worklist DF Distribuidora - Pagina ajustada para a nova estrutura de supervisão.
// chamar criador de sessão de login
if (!isset($_SESSION)) {

    session_start();
}

if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}

//require_once 'funcoes/funcoes.php';
require_once 'bd.php';

$supervisor = $_POST['supervisor'];

$bd = new bd();

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