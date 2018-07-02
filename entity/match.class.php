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
			$this->team1 = $c["time1"];
			$this->team2 = $c["time2"];
			$this->campaign = new Campaign($c["campaign"]);
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

	function getAllMatches()
	{
		$res = $this->conn->query(" SELECT * FROM jogo ");

		$matches = [];
		while ($linha = $res->fetch(\PDO::FETCH_ASSOC)) {
			$m = new Match;
			$m->id = $linha["id"];
			$m->team1 = $linha["time1"];
			$m->team2 = $linha["time2"];
			$m->campaign = new Campaign($linha["campanha"]);
			$matches[$m->id] = $m;
		}		

		return $m;
	}
}