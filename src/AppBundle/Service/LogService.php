<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\Common\Persistence\ManagerRegistry as Doctrine;
use AppBundle\Entity\Player;
use AppBundle\Entity\Lobby;
use AppBundle\Entity\Log;

class LogService {
	
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
	 *
	 * @param Doctrine $doctrine
	 * @param Container $container
	 */
	public function __construct(Doctrine $doctrine, Container $container)
	{
		$this->doctrine = $doctrine;
		$this->container = $container;
	}
	
	/**
	 * 
	 */
	public function init()
	{
		$this->logsArray = $this->container->getParameter('Logs');
	}
	
	/**
	 * 
	 * @param Player $player
	 * @param Lobby $lobby
	 * @return mixed
	 */
	public function movingLog(Player $player, Lobby $lobby)
	{
		$key = array_rand($this->logsArray['moving']);
		$strLog = $this->logsArray['moving'][$key];
		
		$strLog = str_replace('$player$', $player->getName(), $strLog);
		
		$log = new Log();
		$log->setPlayer($player)->setLobby($lobby)->setLabel($strLog)->setType(1);
		
		$this->doctrine->getManager()->persist($log);
		
		return $strLog;
		
	}
}