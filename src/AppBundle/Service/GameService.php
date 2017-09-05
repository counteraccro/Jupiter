<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\Common\Persistence\ManagerRegistry as Doctrine;
use AppBundle\Entity\Lobby;

class GameService {
	
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
	 * @param Log $log
	 */
	public function __construct(Doctrine $doctrine, Container $container, LogService $logService)
	{
		$this->doctrine = $doctrine;
		$this->container = $container;
		$this->logService = $logService;
		
		$this->logService->init();
	}
	
	
	public function generateGame(Lobby $lobby)
	{
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
			$this->dataReturn['logs'][$this->nbDays][] = $this->logService->movingLog($player, $this->lobby);
		}
		
		if($this->nbDays == 3)
		{
			return true;
		}
		
		$this->nbDays++;
		$this->_generateGame();
	}
}