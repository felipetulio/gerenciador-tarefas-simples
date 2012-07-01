<?php
include("classes/connection.php");

//verificação do estado do input CHECKBOX
if(isset($_GET['check']))
{
	if($_GET['check'] == 'on' || $_GET['check'] == 1) {
			$_GET['check'] = 1;
			$var = "checked=\"checked\"";
	}
	else {
			$_GET['check'] = 0;
			$var = '';
	}
}
else {
	$_GET['check'] = 0;
	$var = '';
}

//função para editar a tarefa caso já exista a tarefa
function editartarefa(){
		$conexao = new Connection;	
		//aux é uma variavel que vai receber o resultado da chamada da funcao conectar da classe
		$aux = $conexao->conectar();
				
		if($aux == TRUE)
		{
			$conexao->editar($_GET['idtarefa'], $_GET['desc'], $_GET['check']);
		}
		else
		{
			die("O banco nao pode ser aberto.");
		}
		
		//fechar conexao do banco
		$conexao->desconectar();
}

//verificação do estado das variáveis GET 
if(isset($_GET['desc']) && isset($_GET['salvar']) && isset($_GET['idtarefa'])){

	//verifica se o botão do FORM é o salvar
	if($_GET['salvar'] == 'salvar'){
		
			//verifica estado da CHECKBOX novamente para o caso da pessoa tentar trocar o estado da tarefa de concluida para não concluida
			if($_GET['check'] == "checked")
			{
					if($_GET['check'] == 'on') {	
						$_GET['check'] = 0;
						$var = '';
					}	
			}
			else {	
					$_GET['check'] = 1;
					$var = "checked=\"checked\"";
			}
			//verifica se existe um id da tarefa no campo escondido. Se existir, se deduz que a pessoas deseja editar alguma tarefa
			if($_GET['idtarefa'] != ""){
					editartarefa();
			}
			//caso não exista um id, então a tarefa é nova e deve ser salva.
			else{
				date_default_timezone_set('America/Sao_Paulo');			
				$data = date('d/m/Y');
				$hora = date('H:i');
				
				//conexao é uma variavel instanciada para ser do tipo de objeto da classe Connection
				$conexao = new Connection;
				
				//aux é uma variavel que vai receber o resultado da chamada da funcao conectar da classe
				$aux = $conexao->conectar();
				
				if($aux == TRUE)
				{
					$conexao->inserir($_GET['desc'], $_GET['check'], $data, $hora);
				}
				else
				{
					die("O banco nao pode ser aberto.");
				}
				
				//fechar conexao do banco
				$conexao->desconectar();
			}
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="pt-BR">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="css/960.css" />
		<link rel="stylesheet" type="text/css" href="css/reset.css" />
		<link rel="stylesheet" type="text/css" href="css/text.css" />
		<link rel="stylesheet" type="text/css" href="css/estilo.css" />
		<title> Gerenciador de Tarefas </title>
	</head>
	<body>
		<p>SISTEMA GERENCIADOR DE TAREFAS</p>
		<form method="get" action="index.php">
			<fieldset>
				<legend>
					TAREFAS
				</legend>
				<input  type="hidden" id="txt" name="idtarefa" value="<?php if (isset($_GET['idtarefa'])) echo $_GET['idtarefa']; ?>" />
				Descrição:
				<input type="text" id="desc" name="desc" placeholder="Digite aqui a descrição" maxlength="150" value="<?php if (isset($_GET['desc'])) echo $_GET['desc']; ?>">
				<br />
				<br />
				Concluída:
				<input type="checkbox" id="check" name="check"  <?php if (isset($_GET['check']) && $_GET['check'] == 1) { echo @$var; } ?> />
				<button name="salvar" value="salvar"> Salvar </button>
			</fieldset>
		</form>
		<br />
		<br />
		<!-- DIV que recebe as listagens das tarefas -->
		<div id="lista">
			<?php

			echo '<a href="index.php?tipopesq=listartodas">Listar todas</a> | ';
			echo '<a href="index.php?tipopesq=concluidas">Concluídas</a> | ';
			echo '<a href="index.php?tipopesq=naoconcluidas">Não concluídas</a> | ';
			echo '<a href="index.php?tipopesq=maisrecentes">Mais recentes</a> | ';
			echo '<a href="index.php?tipopesq=maisantigas">Mais antigas</a>';
			echo "<br />";
			echo "<br />";
			echo "<br />";

			if (isset($_GET['tipopesq'])) {
				$id = '';
				if ($_GET['tipopesq'] == 'listartodas') {
					$conn = new Connection;
					$conn -> conectar();
					$resultado = $conn -> pesquisar();
					echo "<ul>";
					while ($linha = mysql_fetch_assoc($resultado)) {
						if (isset($linha["id_tarefa"])) {
							$id = $linha["id_tarefa"];
						}
						$desc = $linha["descricao"];
						$status = $linha["status"];
						$data = $linha["data"];
						$hora = $linha["hora"];
						if ($status == 1) {
							echo '<li style="text-decoration: line-through; color: #CCCCCC;"><a style="text-decoration: line-through; color: #CCCCCC;" href="index.php?idtarefa=' . $id . '&desc=' . $desc . '&check=' . $status . '">' . $desc . '</a> - ' . $data . ' - ' . $hora . '</li>';
						} else {
							echo '<li><a href="index.php?idtarefa=' . $id . '&desc=' . $desc . '&check=' . $status . '">' . $desc . '</a> - ' . $data . ' - ' . $hora . '</li>';
						}
					}
					echo "</ul>";

				} else if ($_GET['tipopesq'] == 'concluidas') {
					$conn = new Connection;
					$conn -> conectar();
					$resultado = $conn -> pesquisarConcluidas();
					echo "<ul>";
					while ($linha = mysql_fetch_assoc($resultado)) {
						if (isset($linha["id_tarefa"])) {
							$id = $linha["id_tarefa"];
						}
						$desc = $linha["descricao"];
						$status = $linha["status"];
						$data = $linha["data"];
						$hora = $linha["hora"];
						if ($status == 1) {
							echo '<li style="text-decoration: line-through; color: #CCCCCC;"><a style="text-decoration: line-through; color: #CCCCCC;" href="index.php?idtarefa=' . $id . '&desc=' . $desc . '&check=' . $status . '">' . $desc . '</a> - ' . $data . ' - ' . $hora . '</li>';
						} else {
							echo '<li><a href="index.php?idtarefa=' . $id . '&desc=' . $desc . '&check=' . $status . '">' . $desc . '</a> - ' . $data . ' - ' . $hora . '</li>';
						}
					}
					echo "</ul>";
				} else if ($_GET['tipopesq'] == 'naoconcluidas') {
					$conn = new Connection;
					$conn -> conectar();
					$resultado = $conn -> pesquisarNaoconcluidas();

					echo "<ul>";
					while ($linha = mysql_fetch_assoc($resultado)) {
						if (isset($linha["id_tarefa"])) {
							$id = $linha["id_tarefa"];
						}
						$desc = $linha["descricao"];
						$status = $linha["status"];
						$data = $linha["data"];
						$hora = $linha["hora"];
						if ($status == 1) {
							echo '<li style="text-decoration: line-through; color: #CCCCCC;"><a style="text-decoration: line-through; color: #CCCCCC;" href="index.php?idtarefa=' . $id . '&desc=' . $desc . '&check=' . $status . '">' . $desc . '</a> - ' . $data . ' - ' . $hora . '</li>';
						} else {
							echo '<li><a href="index.php?idtarefa=' . $id . '&desc=' . $desc . '&check=' . $status . '">' . $desc . '</a> - ' . $data . ' - ' . $hora . '</li>';
						}
					}
					echo "</ul>";
				} else if ($_GET['tipopesq'] == 'maisrecentes') {
					$conn = new Connection;
					$conn -> conectar();
					$resultado = $conn -> pesquisarRecentes();

					echo "<ul>";
					while ($linha = mysql_fetch_assoc($resultado)) {
						if (isset($linha["id_tarefa"])) {
							$id = $linha["id_tarefa"];
						}
						$desc = $linha["descricao"];
						$status = $linha["status"];
						$data = $linha["data"];
						$hora = $linha["hora"];
						if ($status == 1) {
							echo '<li style="text-decoration: line-through; color: #CCCCCC;"><a style="text-decoration: line-through; color: #CCCCCC;" href="index.php?idtarefa=' . $id . '&desc=' . $desc . '&check=' . $status . '">' . $desc . '</a> - ' . $data . ' - ' . $hora . '</li>';
						} else {
							echo '<li><a href="index.php?idtarefa=' . $id . '&desc=' . $desc . '&check=' . $status . '">' . $desc . '</a> - ' . $data . ' - ' . $hora . '</li>';
						}
					}
					echo "</ul>";
				} else if ($_GET['tipopesq'] == 'maisantigas') {
					$conn = new Connection;
					$conn -> conectar();
					$resultado = $conn -> pesquisarAntigas();

					echo "<ul>";
					while ($linha = mysql_fetch_assoc($resultado)) {
						if (isset($linha["id_tarefa"])) {
							$id = $linha["id_tarefa"];
						}
						$desc = $linha["descricao"];
						$status = $linha["status"];
						$data = $linha["data"];
						$hora = $linha["hora"];
						if ($status == 1) {
							echo '<li style="text-decoration: line-through; color: #CCCCCC;"><a style="text-decoration: line-through; color: #CCCCCC;" href="index.php?idtarefa=' . $id . '&desc=' . $desc . '&check=' . $status . '">' . $desc . '</a> - ' . $data . ' - ' . $hora . '</li>';
						} else {
							echo '<li><a href="index.php?idtarefa=' . $id . '&desc=' . $desc . '&check=' . $status . '">' . $desc . '</a> - ' . $data . ' - ' . $hora . '</li>';
						}
					}
					echo "</ul>";
				}
			}
			?>
		</div>
	</body>
</html>