<?php

	//indica o caminho do arquivo no servidor
	$arquivo = 'arquivo_de_importacao.txt';

	//cria um array que receberá os dados importados do arquivo txt
	$arquivoArr = array();
	
	//aqui é enviado para função fopen o endereço do arquivo e a instrução 'r' que indica 'somente leitura' e coloca o ponteiro no começo do arquivo
	$arq = fopen($arquivo, 'r');
	
	//variável armazena o total de linhas importadas
	$total_linhas_importadas = 0;
	
	//a função feof retorna true (verdadeiro) se o ponteiro estiver no fim do arquivo aberto
	//a negação do retorno de feof indicada pelo caracter "!" do lado esquerdo da função faz com 
	//que o laço percorra todas as linhas do arquivo até fim do arquivo (eof - end of file)
	while(!feof($arq)){
		
		//retorna a linha do ponteiro do arquivo			
		$conteudo = fgets($arq);

		//transforma a linha do ponteiro em uma matriz de string, cada uma como substring de string formada a partir do caracter ';'
		$linha = explode(';', $conteudo);
		
		//array recebe as substring contidas na matriz carregada na variável $linha 
		$arquivoArr[$total_linhas_importadas] = $linha;

		//incremente a variável que armazena o total de linhas importadas
		$total_linhas_importadas++;
	}
?>	
	
	<!-- Codificação HTML -->
	<table border="1" style="width:100%;">
		<thead>
			<tr>
				<th>Nome</th>
				<th>Profissão</th>
				<th>Estado</th>
			</tr>
		</head>
		
		<tbody>
			<?php foreach($arquivoArr as $linha): ?>
				<tr>
					<?php foreach($linha as $campo): ?>
						<td><?php echo $campo ?></td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	
<?php
	//imprime a quantidade de linhas importadas
	echo "<br/> Quantidade de linhas importadas = ".$total_linhas_importadas;
?>