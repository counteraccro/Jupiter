<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\Common\Persistence\ManagerRegistry as Doctrine;
use AppBundle\Entity\Lobby;
use AppBundle\Entity\LobbyPlayer;

class GameService {
	const ACTION_MOVING = 'moving';
	const ACTION_KILL = 'kill';
	const ACTION_SELF_KILL = 'self_kill';
	
	/**
	 *
	 * @var Doctrine
	 */
	private $doctrine;
	
	/**
	 *
	 * @var Container
	 */
	private $container;
	
	/**
	 *
	 * @var LogService
	 */
	private $logService;
	
	/**
	 * day counter
	 * @var integer
	 */
	private $nbDays = 1;
	
	/**
	 * Array containing the trigger values of the conditions of the actions
	 * @var array
	 */
	private $randomActionsConditions = [ ];
	
	/**
	 *
	 * @var Lobby
	 */
	private $lobby;
	
	/**
	 *
	 * @var array
	 */
	private $statistiques = [ 
			'total_kill' => 0 
	];

	/**
	 *
	 * @param Doctrine $doctrine
	 * @param Container $container
	 * @param LogService $log
	 */
	public function __construct(Doctrine $doctrine, Container $container, LogService $logService)
	{
		$this->doctrine = $doctrine;
		$this->container = $container;
		$this->logService = $logService;
		
		$this->randomActionsConditions = $this->container->getParameter('RandomActionsConditions');
	}

	/**
	 * Entry of the service
	 * @param Lobby $lobby
	 * @return array
	 */
	public function generateGame(Lobby $lobby)
	{
		$this->lobby = $lobby;
		$this->logService->Presentationlog($this->lobby);
		$this->_generateGame();
		$this->doctrine->getManager()->flush();
	}

	/**
	 * loop of the game
	 * @return boolean
	 */
	private function _generateGame()
	{
		$endLoop = false;
		foreach( $this->lobby->getLobbyPlayers() as $lobbyPlayer )
		{
			if($this->checkEndGame())
			{
				$endLoop = true;
				break;
			}
			
			if($lobbyPlayer->getIsDead())
			{
				continue;
			}
			
			$this->choiceAction($lobbyPlayer);
		}
		
		if(!$endLoop && $this->checkEndGame())
		{
			$endLoop = true;
		}
		
		if($endLoop)
		{
			return true;
		}
		
		$this->logService->StatDayLog($this->statistiques, $this->nbDays);
		
		$this->nbDays ++;
		$this->_generateGame();
	}

	/**
	 * Check if the game is end, Max 100 days
	 */
	private function checkEndGame()
	{
		$endLoop = false;
		
		$nbPlayers = $this->lobby->getLobbyPlayers()->count();
		if($this->statistiques['total_kill'] == ($nbPlayers - 1) || $this->nbDays == 100)
		{
			foreach( $this->lobby->getLobbyPlayers() as $lobbyPlayerWinner )
			{
				if(! $lobbyPlayerWinner->getIsDead())
				{
					$this->logService->Winnerlog($lobbyPlayerWinner->getPlayer(), $this->nbDays);
					$endLoop = true;
					break;
				}
			}
			// Security
			$endLoop = true;
		}
		return $endLoop;
	}

	/**
	 * *
	 * Define what action to take
	 * @param LobbyPlayer $lobbyPlayer
	 */
	private function choiceAction(LobbyPlayer $lobbyPlayer)
	{
		$rand = rand(1, 100) * $this->nbDays;
		
		$r1 = range(1, 10);
		$r2 = range(10, 20);
		$r3 = range(20, 30);
		$r4 = range(30, 40);
		$r5 = range(40, 50);
		$r6 = range(50, 60);
		$r7 = range(60, 70);
		$r8 = range(70, 80);
		$r9 = range(80, 90);
		$r10 = range(90, 100);
		
		$action_number = 1;
		
		switch(true) {
			case in_array($rand, $r1):
				$action_number = 1;
			break;
			case in_array($rand, $r2):
				$action_number = 2;
			break;
			case in_array($rand, $r3):
				$action_number = 3;
			break;
			case in_array($rand, $r4):
				$action_number = 4;
			break;
			case in_array($rand, $r5):
				$action_number = 5;
			break;
			case in_array($rand, $r6):
				$action_number = 6;
			break;
			case in_array($rand, $r7):
				$action_number = 7;
			break;
			case in_array($rand, $r8):
				$action_number = 8;
			break;
			case in_array($rand, $r9):
				$action_number = 9;
			break;
			case in_array($rand, $r10):
				$action_number = 10;
			break;
			default:
				$action_number = 10;
			break;
		}
		
		$key = array_rand($this->randomActionsConditions[$action_number]);
		$action = $this->randomActionsConditions[$action_number][$key];
		
		switch($action) {
			case self::ACTION_MOVING:
				$this->logService->movingLog($lobbyPlayer->getPlayer(), $this->lobby);
			break;
			case self::ACTION_KILL:
				$this->killAction($lobbyPlayer);
			break;
			default:
				$log = 'Action ' . $action . ' inconnu';
			break;
		}
	}

	/**
	 * Defined who kills who
	 * @param LobbyPlayer $lobbyPlayer
	 * @return mixed
	 */
	private function killAction(LobbyPlayer $lobbyPlayer)
	{
		$tabLobbyPlayerTmp = [ ];
		foreach( $this->lobby->getLobbyPlayers() as $lPlayer )
		{
			if($lPlayer->getIsDead())
			{
				continue;
			}
			$tabLobbyPlayerTmp[] = $lPlayer;
		}
		
		$key = array_rand($tabLobbyPlayerTmp);
		$lobbyPlayerKill = $tabLobbyPlayerTmp[$key];
		
		$lobbyPlayerKill->setIsDead(true);
		$lobbyPlayer->setNbKill($lobbyPlayer->getNbKill() + 1);
		$this->doctrine->getManager()->persist($lobbyPlayerKill);
		$this->doctrine->getManager()->persist($lobbyPlayer);
		
		$this->statistiques['days'][$this->nbDays]['kill'][] = $lobbyPlayerKill->getPlayer()->getName();
		$this->statistiques['total_kill'] = $this->statistiques['total_kill'] + 1;
		
		if($lobbyPlayer->getId() == $lobbyPlayerKill->getId())
		{
			$this->logService->killLog($lobbyPlayer->getPlayer(), $lobbyPlayerKill->getPlayer(), $this->lobby, self::ACTION_SELF_KILL);
		}
		else
		{
			$this->logService->killLog($lobbyPlayer->getPlayer(), $lobbyPlayerKill->getPlayer(), $this->lobby, self::ACTION_KILL);
		}
	}
}