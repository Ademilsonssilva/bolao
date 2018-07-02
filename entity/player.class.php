<?php

namespace Ademilson\Bolao\Entity;

use Ademilson\Bolao\Entity\BaseEntity;

class Player extends BaseEntity
{
	public $id;
	public $name;
	public $campaign;

	function __construct($id = null)
	{
		parent::__construct();

		if ($id != null) {
			$res = $this->conn->query(" SELECT * FROM jogador WHERE id = $id");
			$j = $res->fetch(\PDO::FETCH_ASSOC);
			$this->id = $j["id"];
			$this->name = $j["nome"];
			$this->campaign = new Campaign($j["campanha"]);
		}
	}

	function save()
	{
		$stmt = $this->conn->prepare(" INSERT INTO jogador (nome, campanha) VALUES (:name, :campaign)");

		try{
			$stmt->execute([
				":name" => $this->name,
				":campaign" => $this->campaign->id,
			]);
			return true;
		}
		catch(PDOException $e){
			return false;
		}
	}

	function getAllPlayers($campaign = null)
	{
		$sql = " SELECT * FROM jogador ";
		if ($campaign != null) {
			$sql .= " WHERE campanha = $campaign";
		}

		$res = $this->conn->query($sql);

		$players = [];
		while ($linha = $res->fetch(\PDO::FETCH_ASSOC)) {
			$j = new Player;
			$j->id = $linha["id"];
			$j->name = $linha["nome"];
			$j->campaign = new Campaign($linha["campanha"]);
			$players[$j->id] = $j;
		}		

		return $players;
	}

	function playerSelector($bash, $campaign = null)
	{
		$arr_players = $this->getAllPlayers($campaign);

		$bash->showMessage("JOGADORES:");

		foreach($arr_players as $player) {
			$bash->showMessage("{$player->id} - {$player->name} ", false);
		}

		$player_id = $bash->getUserChoice("Escolha o participante desejado: ");
		return $player_id;
	}
}