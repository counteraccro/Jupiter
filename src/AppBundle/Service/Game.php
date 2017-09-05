<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\Common\Persistence\ManagerRegistry as Doctrine;
use AppBundle\Entity\Lobby;
use AppBundle\Entity\Player;
use AppBundle\Entity\Log;
use AppBundle\Entity\LobbyPlayer;

class Game {
	
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
	 * Array of logs
	 * @var array
	 */
	private $logsArray;
	
	/**
	 * day counter
	 * @var integer
	 */
	private $nbDays = 1;
	
	/**
	 * 
	 * @var array
	 */
	private $dataReturn = ['logs' => []];
	
	/**
	 * 
	 * @var Lobby
	 */
	private $lobby;
	
	/**
	 *
	 * @param Doctrine $doctrine
	 * @param Container $container
	 */
	public function __construct(Doctrine $doctrine, Container $container)
	{
		$this->doctrine = $doctrine;
		$this->container = $container;
	}
	
	
	public function generateGame(Lobby $lobby)
	{
		// get Logs
		$this->logsArray = $this->container->getParameter('Logs');
		$this->lobby = $lobby;
		
		$this->_generateGame();
		$this->doctrine->getManager()->flush();
		return $this->dataReturn;
	}
	
	private function _generateGame()
	{
		foreach($this->lobby->getLobbyPlayers() as $lobbyPlayers)
		{
			$player = $lobbyPlayers->getPlayer();
			$this->movingLog($player);
		}
		
		if($this->nbDays == 3)
		{
			return true;
		}
		
		$this->nbDays++;
		$this->_generateGame();
	}
	
	private function movingLog(Player $player)
	{
		$key = array_rand($this->logsArray['moving']);
		$strLog = $this->logsArray['moving'][$key];
		
		$strLog = str_replace('$player$', $player->getName(), $strLog);
		$this->dataReturn['logs'][$this->nbDays][] = $strLog;
		
		$log = new Log();
		$log->setPlayer($player)->setLobby($this->lobby)->setLabel($strLog)->setType(1);
		
		$this->doctrine->getManager()->persist($log);
		
	}
}