<?php
// Worklist Goiás Saúde - Pagina ajustada para a nova estrutura de supervisão.
if (!isset($_SESSION)) {
    session_start();
}


if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}

$arquivo = $_FILES['arquivo']['tmp_name'];

//indica o caminho do arquivo no servidor
//$arquivo = 'C:\eclipse\Pasta1.csv';
//cria um array que receber� os dados importados do arquivo txt
$arquivoArr = array();

//aqui � enviado para fun��o fopen o endere�o do arquivo e a instru��o 'r' que indica 'somente leitura' e coloca o ponteiro no come�o do arquivo
$arq = fopen($arquivo, 'r');

//vari�vel armazena o total de linhas importadas
$total_linhas_importadas = 0;

//a fun��o feof retorna true (verdadeiro) se o ponteiro estiver no fim do arquivo aberto
//a nega��o do retorno de feof indicada pelo caracter "!" do lado esquerdo da fun��o faz com 
//que o la�o percorra todas as linhas do arquivo at� fim do arquivo (eof - end of file)
while (!feof($arq)) {
    //retorna a linha do ponteiro do arquivo			
    $conteudo = fgets($arq);
    //transforma a linha do ponteiro em uma matriz de string, cada uma como substring de string formada a partir do caracter ';'
    $linha = explode(';', $conteudo);
    //array recebe as substring contidas na matriz carregada na vari�vel $linha 
    $arquivoArr[$total_linhas_importadas] = $linha;
    //incremente a vari�vel que armazena o total de linhas importadas
    $total_linhas_importadas++;
}
!@($conexao = pg_connect("host=187.72.34.18 dbname=worklistgo port=5432 user=df password=tidf123"));

$representante = $_SESSION['idrepresentante'];
?>
<table border="1" style="width: 100%; text-align: center">
    <thead>
        <tr>
            <th>MÊS</th>
            <th>REPRESENTANTE</th>
            <th>META</th>  
        </tr>
    </thead>
    <tbody>
        <?php foreach ($arquivoArr as $linha): ?>
            <tr>
            <?php
            if (!empty($linha[0])) {
                $query = "select * from sys_obj_cmv where idrca=$linha[1] and mes='$linha[0]'";
                $query = pg_query($query);
                if (pg_num_rows($query) == 0) {
                    $sql = "insert into sys_obj_cmv(mes,idrca,metacmv,usuario) values('$linha[0]',$linha[1],$linha[2],$representante);";
                    $sql = pg_query($sql);
                } else {
                    $sql = "update sys_obj_cmv set metacmv=$linha[2] where idrca=$linha[1] and mes='$linha[0]'";
                    $sql = pg_query($sql);
                }
            }
            foreach ($linha as $campo):
                ?>
                    <td>
                        <?php
                        echo $campo;
                        ?>
                    </td>
                    <?php endforeach; ?>
            </tr>
                <?php endforeach; ?>
    </tbody>
</table>
