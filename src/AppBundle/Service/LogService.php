<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\Common\Persistence\ManagerRegistry as Doctrine;
use AppBundle\Entity\Player;
use AppBundle\Entity\Lobby;
use AppBundle\Entity\Log;
use Symfony\Component\HttpFoundation\Session\Session;

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
	 * Path
	 * @var string
	 */
	private $folder_path;
	
	/**
	 *
	 * @var Session
	 */
	private $session;

	/**
	 *
	 * @param Doctrine $doctrine
	 * @param Container $container
	 */
	public function __construct(Doctrine $doctrine, Container $container, Session $session)
	{
		$this->doctrine = $doctrine;
		$this->container = $container;
		$this->session = $session;
		
		$this->logsArray = $this->container->getParameter('Logs');
		$this->folder_path = dirname(__DIR__) . '/Resources/public/battles/';
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
		
		return $this->writeLog($strLog);
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
		
		return $this->writeLog($strLog);
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
			foreach( $stats['days'][$nbDays]['kill'] as $name )
			{
				$names .= $name . ', ';
			}
			$names = substr($names, 0, - 2);
			
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
		
		return $this->writeLog($strLog);
	}

	/**
	 * Write log when we have a winner
	 * @param Player $player
	 * @param int $nbDays
	 * @return mixed
	 */
	public function Winnerlog(Player $player, $nbDays)
	{
		$strLog = $this->logsArray['log_day']['winner'];
		$strLog = str_replace('$player$', $player->getName(), $strLog);
		$strLog = str_replace('$day$', $nbDays, $strLog);
		
		return $this->writeLog($strLog);
	}

	/**
	 * Write presentation log
	 * @param Lobby $lobby
	 * @return string
	 */
	public function Presentationlog(Lobby $lobby)
	{
		$action = 'presentation';
		$key = array_rand($this->logsArray[$action]);
		$strLog = $this->logsArray[$action][$key];
		
		$players = '';
		foreach( $lobby->getLobbyPlayers() as $lobbyPLayer )
		{
			$players .= $lobbyPLayer->getPlayer()->getName() . ', ';
		}
		$players = substr($players, 0, - 2);
		
		$strLog = str_replace('$players$', $players, $strLog);
		$strLog = str_replace('$nb$', $lobby->getLobbyPlayers()->count(), $strLog);
		
		return $this->writeLog($strLog);
	}

	/**
	 * Write error log
	 * @param string $str
	 */
	public function errorLog($str)
	{
		$this->writeLog($str);
	}

	/**
	 * Write log in a file
	 * @param string $strLog
	 * @return string
	 */
	private function writeLog($strLog)
	{
		$fileLog = fopen($this->folder_path . $this->getFileName(), 'a+');
		fputs($fileLog, $strLog . "\n");
		fclose($fileLog);
		
		return $strLog;
	}

	/**
	 * Get the name of the log file
	 * @param integer $lobby_id
	 * @return string
	 */
	private function getFileName($lobby_id = null)
	{
		if($lobby_id == null)
		{
			$fileName = 'battle-' . $this->session->get('lobby_id') . '.txt';
		}
		else
		{
			$fileName = 'battle-' . $lobby_id . '.txt';
		}
		
		// For débug
		$fileName = 'battle-demo.txt';
		return $fileName;
	}

	/**
	 * Read log file
	 * @param Lobby $lobby_id
	 * @return array
	 */
	public function readLog($lobby_id)
	{
		$file = $this->folder_path . $this->getFileName();
		
		if(file_exists($file))
		{
			$logs = file($file);
			return $this->formatTabLog($logs);
		}
		else
		{
			return [ ];
		}
	}

	/**
	 * Format the logs for display
	 * @param array $logs
	 * @return array
	 */
	private function formatTabLog($logs)
	{
		$result = [ ];
		$jour = 1;
		foreach( $logs as $log )
		{
			if(preg_match('/Jour/', $log, $matchs))
			{
				$result['logs'][$jour][] = $log;
				$jour ++;
			}
			else
			{
				$result['logs'][$jour][] = $log;
			}
		}
		
		return $result;
	}
}