<?php

 class Connection
 {
	private $servidor = 'localhost';
	private $usuario = 'root';
	private $senha = '';
	private $bd = 'gerentarefas';
	
	public $conn;
	
	public function conectar()
	{
		$this->conn = mysql_connect($this->servidor, $this->usuario, $this->senha);
		if (!$this->conn)
		{
			die("Não foi possivel conectar." . mysql_error())	;			
		}
		else 
		{
			$select = mysql_select_db($this->bd);
			if(!$select)
			{
				die("Não foi possivel acessar o banco." . mysql_error());
			}
			else
			{
				return true;				
			}
		}
		
	}
	
	public function desconectar()
	{
		mysql_close($this->conn);
		return true;
	}
	
	public function inserir($desc, $status, $data, $hora)
	{
		$sql = "INSERT INTO tarefas(id_tarefa, descricao, status, data, hora) VALUES ('','$desc', '$status', '$data', '$hora')";
		mysql_query($sql) or die(mysql_error());
	}

	public function excluir($nome)
	{
		$sql = "DELETE FROM tarefas WHERE nome like '$nome'";
		mysql_query($sql) or die(mysql_error());	
	}
	
	public function pesquisar()
	{
		$sql = "SELECT * FROM tarefas";
		$res = mysql_query($sql) or die(mysql_error());
		return $res;
		
	}
	
	public function pesquisarRecentes()
	{
		$sql = "SELECT * FROM tarefas order by data,hora desc";
		$res = mysql_query($sql) or die(mysql_error());
		return $res;
		
	}
	
	public function pesquisarAntigas()
	{
		$sql = "SELECT * FROM tarefas order by data, hora asc";
		$res = mysql_query($sql) or die(mysql_error());
		return $res;
		
	}
	
	public function pesquisarNaoconcluidas()
	{
		$sql = "SELECT * FROM tarefas where status=0";
		$res = mysql_query($sql) or die(mysql_error());
		return $res;
		
	}
	
	public function pesquisarConcluidas()
	{
		$sql = "SELECT * FROM tarefas where status=1";
		$res = mysql_query($sql) or die(mysql_error());
		return $res;
		
	}
	
		
	public function editar($id_tarefa, $tarefa, $status)
	{
		$sql = "UPDATE tarefas SET descricao=\"$tarefa\", status=$status where id_tarefa=$id_tarefa;";
		$res = mysql_query($sql) or die(mysql_error());
		
	}
 }

?>

