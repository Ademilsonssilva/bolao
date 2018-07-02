<?php
	
namespace Ademilson\Bolao;

include "connection.php";

use Ademilson\Bolao\Entity\BashController;
use Ademilson\Bolao\Entity\Campaign;
use Ademilson\Bolao\Entity\Match;
use Ademilson\Bolao\Entity\Player;
use Ademilson\Bolao\Entity\Score;

$bash = new BashController();

while ($bash->applicationRunning)  {
	$bash->showIndex();
	$action = $bash->getUserChoice();	
	if (in_array($action, [0,1,2,3,4,5,6,])){
		
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
					$player = new Player($player_id);

					$matches = new Match();
					$arr_matches = $matches->getAllMatches($campaign_id);

					foreach($arr_matches as $match) {

						$bash->showMessage("JOGO {$match->team1} X {$match->team2}: ", false);

						$score = new Score();
						$score->score_team1 = $bash->getUserChoice("Placar {$match->team1}: ");
						$score->score_team2 = $bash->getUserChoice("Placar {$match->team2}: ");
						$score->match = $match;
						$score->player = $player;

						if ($score->save()) {
							$bash->showMessage("Placar {$match->team1} {$score->score_team1} X {$score->score_team2} {$match->team2} cadastrado com sucesso!");
						}
						else {
							$bash->showMessage("Ocorreu um erro inesperado!");
						}

					}
					$bash->showMessage("## TODOS OS PLACARES DO PARTICIPANTE {$player->name} FORAM CADASTRADOS! ##");

				}
				else {
					$bash->showMessage("## JOGADOR SELECIONADO NAO EXISTE! ##");
				}
			}
			else {
				$bash->showMessage("## CAMPANHA SELECIONADA NAO EXISTE! ##");
			}

		}


		if ($action == 5) {

			$bash->showBoxMessage("INSERIR RESULTADO DA PARTIDA");

			$campaigns = new Campaign();
			
			$campaign_id = $campaigns->campaignSelector($bash);

			if (array_key_exists($campaign_id, $campaigns->getAllCampaigns())) {
				
				$matches = new Match();

				$match_id = $matches->matchSelector($bash);

				if (array_key_exists($match_id, $matches->getAllMatches())) {

					$match = new Match($match_id);
					$bash->showMessage("## PARTIDA SELECIONADA {$match->team1} X {$match->team2}! ##");
					$score = new Score();

					$score->score_team1 = $bash->getUserChoice("Placar {$match->team1}: ");
					$score->score_team2 = $bash->getUserChoice("Placar {$match->team2}: ");
					$score->match = $match;
					$score->player = null;

					if ($score->save()) {
						$bash->showMessage("Placar {$match->team1} {$score->score_team1} X {$score->score_team2} {$match->team2} cadastrado com sucesso!");
					}
					else {
						$bash->showMessage("Ocorreu um erro inesperado!");
					}

				}
				else {
					$bash->showMessage("## PARTIDA SELECIONADA NAO EXISTE! ##");
				}
			}
			else {
				$bash->showMessage("## CAMPANHA SELECIONADA NAO EXISTE! ##");
			}

		}

		if ($action == 6) {
			$bash->showBoxMessage("VERIFICAR LIDERANCA DO BOLAO");

			$campaigns = new Campaign();
			
			$campaign_id = $campaigns->campaignSelector($bash);

			if (array_key_exists($campaign_id, $campaigns->getAllCampaigns())) {
				$campaign = new Campaign($campaign_id);

				$players = new Player();
				$arr_players = $players->getAllPlayers($campaign_id);

				$matches = new Match();
				$campaign->getAllDefinedMatches();
				var_dump($campaign->definedMatches);

				// foreach ($arr_players as $player) {

				// 	$player->getAllScores();

					
				// }
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


