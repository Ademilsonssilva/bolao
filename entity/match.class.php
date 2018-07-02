<?php

namespace Ademilson\Bolao\Entity;
//include "baseEntity.class.php";

use Ademilson\Bolao\Entity\BaseEntity;

class Match extends BaseEntity
{
	public $id;
	public $campaign;
	public $team1;
	public $team2;

	function __construct($id = null)
	{
		parent::__construct();

		if ($id != null) {
			$res = $this->conn->query(" SELECT * FROM jogo WHERE id = $id");
			$m = $res->fetch(\PDO::FETCH_ASSOC);
			$this->id = $m["id"];
			$this->team1 = $m["time1"];
			$this->team2 = $m["time2"];
			$this->campaign = new Campaign($m["campanha"]);
		}
	}

	function save()
	{
		$stmt = $this->conn->prepare(" INSERT INTO jogo (time1, time2, campanha) VALUES (:time1, :time2, :campanha)");

		try{
			$stmt->execute([
				":time1" => $this->team1,
				":time2" => $this->team2,
				":campanha" => $this->campaign->id,
			]);
			return true;
		}
		catch(PDOException $e){
			return false;
		}
	}

	function getAllMatches($campaign = null)
	{
		$sql = " SELECT * FROM jogo ";
		if ($campaign != null) {
			$sql .= " WHERE campanha = {$campaign} ";
		}

		$res = $this->conn->query($sql);

		$matches = [];
		while ($linha = $res->fetch(\PDO::FETCH_ASSOC)) {
			$m = new Match;
			$m->id = $linha["id"];
			$m->team1 = $linha["time1"];
			$m->team2 = $linha["time2"];
			$m->campaign = new Campaign($linha["campanha"]);
			$matches[$m->id] = $m;
		}		

		return $matches;
	}

	function matchSelector($bash, $campaign = null)
	{
		$arr_matches = $this->getAllMatches($campaign);

		$bash->showMessage("JOGOS:");

		foreach($arr_matches as $match) {
			$bash->showMessage("{$match->id} - {$match->team1} X {$match->team2} ", false);
		}

		$match_id = $bash->getUserChoice("Escolha o jogo desejado: ");
		return $match_id;
	}

}