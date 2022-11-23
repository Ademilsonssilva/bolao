<?php

namespace Ademilson\Bolao\Entity;
include "baseEntity.class.php";

use Ademilson\Bolao\Entity\BaseEntity;

class Campaign extends BaseEntity
{
	public $id;
	public $name;

	function __construct($id = null)
	{
		parent::__construct();

		if ($id != null) {
			$res = $this->conn->query(" SELECT * FROM campanha WHERE id = $id");
			$c = $res->fetch(\PDO::FETCH_ASSOC);
			$this->id = $c["id"];
			$this->name = $c["descricao"];
		}
	}

	function save()
	{
		$stmt = $this->conn->prepare(" INSERT INTO campanha (descricao) VALUES (:descricao)");

		try{
			$stmt->execute([":descricao" => $this->name]);
			return true;
		}
		catch(PDOException $e){
			return false;
		}
	}

	function getAllCampaigns()
	{
		$res = $this->conn->query(" SELECT * FROM campanha ");

		$campaigns = [];
		while ($linha = $res->fetch(\PDO::FETCH_ASSOC)) {
			$c = new Campaign;
			$c->id = $linha["id"];
			$c->name = $linha["descricao"];
			$campaigns[$linha["id"]] = $c;
		}		

		return $campaigns;
	}

	function campaignSelector($bash)
	{
		$arr_campaigns = $this->getAllCampaigns();

		$bash->showMessage("CAMPANHAS:");

		foreach($arr_campaigns as $campaign) {
			$bash->showMessage("{$campaign->id} - {$campaign->name} ", false);
		}

		$campaign_id = $bash->getUserChoice("Escolha a campanha desejada: ");
		return $campaign_id;
	}

	function getAllDefinedMatches()
	{
		$sql  = " SELECT * FROM jogo j ";
		$sql .= " INNER JOIN placar p ON j.id = p.id_jogo AND p.id_jogador IS NULL ";
		$sql .= " WHERE j.campanha = {$this->id}";

		$res = $this->conn->query($sql);

		$matches = [];
		while ($linha = $res->fetch(\PDO::FETCH_ASSOC)) {
			$match["match"] = $linha["id_jogo"];
			$match["team1"] = $linha["time1"];
			$match["team2"] = $linha["time2"];
			$match["score_team1"] = $linha["placar_time1"];
			$match["score_team2"] = $linha["placar_time2"];

			$match["winner"] = ($match["score_team1"] > $match["score_team2"] ? "team1" : ($match["score_team1"] == $match["score_team2"] ? "tie" : "team2"));
		
			$matches[$linha["id_jogo"]] = $match;
		}		

		$this->definedMatches =  $matches;
	}
}