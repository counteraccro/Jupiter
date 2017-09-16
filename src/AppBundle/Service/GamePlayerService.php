<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\Common\Persistence\ManagerRegistry as Doctrine;
use AppBundle\Entity\LobbyPlayer;
use AppBundle\Entity\Lobby;

class GamePlayerService {
	
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
		
	}
	
	/**
	 * Kill Player Action
	 * @param LobbyPlayer $lobbyPlayer
	 * @param Lobby $lobby
	 * @param array $gameStatistiques
	 * @param int $nbDay
	 * @param string $action_kill
	 * @param string $action_self_kill
	 * @return array
	 */
	public function killAction(LobbyPlayer $lobbyPlayer, Lobby $lobby, array $gameStatistiques, $nbDay, $action_kill, $action_self_kill)
	{
		$tabLobbyPlayerTmp = [ ];
		foreach( $lobby->getLobbyPlayers() as $lPlayer )
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
		
		$gameStatistiques['days'][$nbDay]['kill'][] = $lobbyPlayerKill->getPlayer()->getName();
		$gameStatistiques['total_kill'] = $gameStatistiques['total_kill'] + 1;
		
		if($lobbyPlayer->getId() == $lobbyPlayerKill->getId())
		{
			$this->logService->killLog($lobbyPlayer->getPlayer(), $lobbyPlayerKill->getPlayer(), $lobby, $action_self_kill);
		}
		else
		{
			$this->logService->killLog($lobbyPlayer->getPlayer(), $lobbyPlayerKill->getPlayer(), $lobby, $action_kill);
		}
		
		return $gameStatistiques;
	}
}