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
	 * Generate log for action moving
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
	
	/**
	 * Generate log for action kill
	 * @param Player $player
	 * @param Player $playerKill
	 * @param Lobby $lobby
	 */
	public function killLog(Player $player, Player $playerKill, Lobby $lobby, $action = 'kill')
	{
		$key = array_rand($this->logsArray[$action]);
		$strLog = $this->logsArray[$action][$key];
		
		if($action == 'kill')
		{
			$strLog = str_replace('$player$', $player->getName(), $strLog);
			$strLog = str_replace('$player_kill$', $playerKill->getName(), $strLog);
		}
		else if($action == 'self_kill')
		{
			$strLog = str_replace('$player$', $player->getName(), $strLog);
		}
		
		$log = new Log();
		$log->setPlayer($player)->setLobby($lobby)->setLabel($strLog)->setType(1);
		
		$this->doctrine->getManager()->persist($log);
		
		return $strLog;
	}
	
	/**
	 * Generate log for daily statistiques
	 * @param array $stats
	 * @param int $nbDays
	 * @return mixed
	 */
	public function StatDayLog(array $stats, $nbDays)
	{
		$nbKill = 0;
		if(isset($stats['days'][$nbDays]['kill']))
		{
			$nbKill = count($stats['days'][$nbDays]['kill']);
		}
		
		if($nbKill > 0)
		{
			$names = '';
			foreach($stats['days'][$nbDays]['kill'] as $name)
			{
				$names .= $name . ',';
			}
			$names = substr($names, 0, -1);
			
			if($nbKill == 1)
			{
				$strLog = $this->logsArray['log_day']['kill'];
				$strLog = str_replace('$player$', $names, $strLog);
				$strLog = str_replace('$day$', $nbDays, $strLog);
			}
			else
			{
				$strLog = $this->logsArray['log_day']['kills'];
				$strLog = str_replace('$players$', $names, $strLog);
				$strLog = str_replace('$nb_deads$', $nbKill, $strLog);
				$strLog = str_replace('$day$', $nbDays, $strLog);
			}
		}
		else
		{
			$strLog = $this->logsArray['log_day']['no_kill'];
			$strLog = str_replace('$day$', $nbDays, $strLog);
		}
		
		return $strLog;
	}
	
	public function Winnerlog(Player $player, $nbDays)
	{
		$strLog = $this->logsArray['log_day']['winner'];
		$strLog = str_replace('$player$', $player->getName(), $strLog);
		$strLog = str_replace('$day$', $nbDays, $strLog);
		
		return $strLog;
	}
}