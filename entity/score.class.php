<?php

namespace Ademilson\Bolao\Entity;
//include "baseEntity.class.php";

use Ademilson\Bolao\Entity\BaseEntity;

class Score extends BaseEntity
{
	public $id;
	public $match;
	public $score_team1;
	public $score_team2;
	public $player;

	function __construct($id = null)
	{
		parent::__construct();

		if ($id != null) {
			$res = $this->conn->query(" SELECT * FROM placar WHERE id = $id");
			$s = $res->fetch(\PDO::FETCH_ASSOC);
			$this->id = $s["id"];
			$this->score_team1 = $s["placar_time1"];
			$this->score_team2 = $s["placar_time2"];
			$this->match = new Match($s["id_jogo"]);
			if ($s["id_jogador"] != "") {
				$this->player = new Player($s["id_jogador"]);
			}
		}
	}

	function save()
	{
		$sql = " INSERT INTO placar (id_jogo, placar_time1, placar_time2, id_jogador) VALUES (:id_jogo, :placar_time1, :placar_time2, :id_jogador)";
		$stmt = $this->conn->prepare($sql);

		try{
			$stmt->execute([
				":id_jogo" => $this->match->id,
				":placar_time1" => $this->score_team1,
				":placar_time2" => $this->score_team2,
				":id_jogador" => ($this->player != null ? $this->player->id : null),
			]);
			return true;
		}
		catch(PDOException $e){
			return false;
		}
	}

	function getAllScoresByPlayer($player = null)
	{
		$sql = " SELECT * FROM placar WHERE jogador = $player";

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

		return $m;
	}
}