<?php 
namespace Ademilson\Bolao\Entity;

class BashController
{
	public $applicationRunning;

	function __construct ()
	{
		$this->applicationRunning = true;
		echo "########### BOLAO COPA 2018 #############" . PHP_EOL;
	}

	function showIndex()
	{
		$this->showBoxMessage("ESCOLHA UMA ACAO");

		echo "# 1 - ADICIONAR CAMPANHA ". PHP_EOL;
		echo "# 2 - ADICIONAR JOGO ". PHP_EOL;
		echo "# 3 - ADICIONAR PARTICIPANTES ". PHP_EOL;
		echo "# 4 - ADICIONAR APOSTAS ". PHP_EOL;
		echo "# 5 - INSERIR RESULTADO DE JOGO ". PHP_EOL;
		echo "# 6 - VERIFICAR LIDERANCA ". PHP_EOL;
		echo "# 0 - SAIR ". PHP_EOL;
	}

	function getUserChoice ($message = false) 
	{
		$this->breakLine();
		if ($message) {
			echo "# {$message}";
		}
		return trim(fgets(STDIN));
	}

	function showBoxMessage ($message)
	{
		$lenght = strlen($message);
		
		$boxBorder = '';
		for ($i = 0; $i < $lenght+4; $i++) {
			$boxBorder.= "#";
		}
		$this->breakLine();
		echo $boxBorder . PHP_EOL;
		echo "# $message #" . PHP_EOL;
		echo $boxBorder . PHP_EOL;
		$this->breakLine();
	}

	function showMessage ($message, $emptyLine = true)
	{
		if ($emptyLine){
			$this->breakLine();
		}
		echo "## $message" . PHP_EOL;
		if ($emptyLine){
			$this->breakLine();
		}
	}

	function breakLine()
	{
		echo "# ". PHP_EOL;
	}
}