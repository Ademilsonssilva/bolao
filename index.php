<?php
	
namespace Ademilson\Bolao;

include "connection.php";

use Ademilson\Bolao\Entity\BashController;
use Ademilson\Bolao\Entity\Campaign;
use Ademilson\Bolao\Entity\Match;
use Ademilson\Bolao\Entity\Player;

$bash = new BashController();

while ($bash->applicationRunning)  {
	$bash->showIndex();
	$action = $bash->getUserChoice();	
	if (in_array($action, [0,1,2,3,4,5,])){
		
		if ($action == "0") {
			$bash->applicationRunning = false;
		}


		else if ($action == 1){ 
			$bash->showBoxMessage("NOVA CAMPANHA");
			$campaign = new Campaign();

			$campaign->name = $bash->getUserChoice("Nome da campanha: ");

			if ($campaign->Save()) {
				$bash->showMessage("Salvo com sucesso!");
			}
			else {
				$bash->showMessage("Ocorreu um erro inesperado!");
			}
		}


		else if ($action == 2) {
			$bash->showBoxMessage("NOVO JOGO");

			$campaigns = new Campaign();
			
			$campaign_id = $campaigns->campaignSelector($bash);

			if (array_key_exists($campaign_id, $campaigns->getAllCampaigns())) {
				$match = new Match();

				$match->team1 = $bash->getUserChoice("Nome do primeiro time: ");
				$match->team2 = $bash->getUserChoice("Nome do segundo time: ");
				$match->campaign = new Campaign($campaign_id);

				if($match->Save()) {
					$bash->showMessage("Jogo {$match->team1} X {$match->team2} cadastrado com sucesso!");
				}
				else {
					$bash->showMessage("Ocorreu um erro inesperado!");
				}
			}
			else {
				$bash->showMessage("## CAMPANHA SELECIONADA NAO EXISTE! ##");
			}
		}

		else if ($action == 3) {
			$bash->showBoxMessage("NOVO JOGADOR");			
			$player = new Player();

			$campaigns = new Campaign();
			
			$campaign_id = $campaigns->campaignSelector($bash);

			if (array_key_exists($campaign_id, $campaigns->getAllCampaigns())) {

				$player->name = $bash->getUserChoice("Nome do participante: ");
				$player->campaign = new Campaign($campaign_id);

				if ($player->Save()) {
					$bash->showMessage("Participante {$player->name} salvo com sucesso!");
				}
				else {
					$bash->showMessage("Ocorreu um erro inesperado!");
				}
			}
			else {
				$bash->showMessage("## CAMPANHA SELECIONADA NAO EXISTE! ##");
			}
		}

		else if ($action == 4) {
			$bash->showBoxMessage("ADICIONAR APOSTAS");			

			$campaigns = new Campaign();
			
			$campaign_id = $campaigns->campaignSelector($bash);

			if (array_key_exists($campaign_id, $campaigns->getAllCampaigns())) {

				$players = new Player();

				$player_id = $players->playerSelector($bash, $campaign_id);

				if (array_key_exists($player_id, $players->getAllPlayers())) {

				}
				else {
					$bash->showMessage("## JOGADOR SELECIONADO NAO EXISTE! ##");
				}
			}
			else {
				$bash->showMessage("## CAMPANHA SELECIONADA NAO EXISTE! ##");
			}

		}

	}
	else {
		$bash->showMessage("Comando inválido");
	}
}

$bash->showBoxMessage("Aplicação encerrada");


